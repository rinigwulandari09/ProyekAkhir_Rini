<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class NotifikasiController extends Controller
{
    private function produksiQuery($user)
    {
        $query = DB::table('produksi')
            ->join('petani', 'produksi.petani_id', '=', 'petani.petani_id');

        if ($user->user_role === 'admin') {
            $query->where('petani.desa_id', $user->desa_id);
        }

        return $query;
    }

    private function profileUpdateQuery($user)
    {
        // Karena petani tidak memiliki updated_at, return query kosong agar tidak error
        if (! Schema::hasColumn('petani', 'updated_at')) {
            return DB::table('produksi')
                ->selectRaw("0 as id, '' as judul, '' as pesan, now() as created_at, 'profil' as tipe, NULL as jumlah_tbs, '' as lokasi, '' as nama")
                ->whereRaw('0 = 1');
        }

        $query = DB::table('petani')
            ->leftJoin('desa', 'petani.desa_id', '=', 'desa.desa_id')
            ->select(
                'petani.petani_id as id',
                DB::raw("'Update Data Diri' as judul"),
                DB::raw("CONCAT(petani.petani_nama, ' memperbarui data diri') as pesan"),
                'petani.updated_at as created_at',
                DB::raw("'profil' as tipe"),
                DB::raw('NULL as jumlah_tbs'),
                DB::raw("COALESCE(desa.desa_nama, '-') as lokasi"),
                'petani.petani_nama as nama'
            )
            ->whereNotNull('petani.updated_at');

        if ($user->user_role === 'admin') {
            $query->where('petani.desa_id', $user->desa_id);
        }

        return $query;
    }

    private function notificationsQuery($user, $search = null, $tab = 'all')
    {
        $userLastRead = $user->updated_at ? Carbon::parse($user->updated_at) : now();

        $produksi = $this->produksiQuery($user)
            ->leftJoin('lahan', 'produksi.lahan_id', '=', 'lahan.lahan_id')
            ->leftJoin('desa', 'petani.desa_id', '=', 'desa.desa_id')
            ->select(
                'produksi.id',
                DB::raw("'Produksi Baru' as judul"),
                DB::raw("CONCAT(petani.petani_nama, ' menambahkan data produksi') as pesan"),
                // Gunakan produksi_tanggal sebagai created_at pengganti
                'produksi.produksi_tanggal as created_at',
                DB::raw("'produksi' as tipe"),
                'produksi.jumlah_tbs',
                DB::raw("COALESCE(lahan.lahan_nama, desa.desa_nama, '-') as lokasi"),
                'petani.petani_nama as nama'
            );

        $profile = $this->profileUpdateQuery($user);

        if ($search) {
            $produksi->where(function ($q) use ($search) {
                $q->where('petani.petani_nama', 'like', "%{$search}%")
                    ->orWhere('lahan.lahan_nama', 'like', "%{$search}%")
                    ->orWhere('desa.desa_nama', 'like', "%{$search}%");
            });
        }

        if ($tab === 'produksi') {
            $baseQuery = DB::query()->fromSub($produksi->orderByDesc('id'), 'notifications');
        } elseif ($tab === 'profil') {
            $baseQuery = DB::query()->fromSub($profile->orderByDesc('created_at'), 'notifications');
        } else {
            $union = $profile ? $produksi->unionAll($profile) : $produksi;
            $baseQuery = DB::query()->fromSub($union, 'notifications');
        }

        // Penentuan is_read: jika tanggal produksi sebelum hari ini, ATAU hari ini tapi admin sudah klik tandai dibaca hari ini
        return $baseQuery->select('*', DB::raw("
            CASE 
                WHEN created_at < '" . today()->toDateString() . "' THEN 1 
                WHEN created_at = '" . today()->toDateString() . "' AND DATE('{$userLastRead}') >= '" . today()->toDateString() . "' THEN 1
                ELSE 0 
            END as is_read
        "))
        ->orderByDesc('id');
    }

    public function getPopup()
    {
        $user = Auth::user();
        if (! in_array($user->user_role, ['super_admin', 'admin'])) { 
            return response()->json(['data' => []], 403); 
        }

        $userLastRead = $user->updated_at ? Carbon::parse($user->updated_at) : now();
        $userIdColumn = Schema::hasColumn('users', 'user_id') ? 'user_id' : 'id';

        $produksi = $this->produksiQuery($user)
            ->leftJoin('lahan', 'produksi.lahan_id', '=', 'lahan.lahan_id')
            ->leftJoin('desa', 'petani.desa_id', '=', 'desa.desa_id')
            ->select(
                'produksi.id',
                DB::raw("'Produksi Baru' as judul"),
                DB::raw("CONCAT(petani.petani_nama, ' menambahkan data produksi') as pesan"),
                'produksi.produksi_tanggal as created_at',
                DB::raw("
                    CASE 
                        WHEN produksi.produksi_tanggal < '" . today()->toDateString() . "' THEN 1 
                        WHEN produksi.produksi_tanggal = '" . today()->toDateString() . "' AND DATE('{$userLastRead}') >= '" . today()->toDateString() . "' THEN 1
                        ELSE 0 
                    END as is_read
                "),
                'petani.petani_nama as nama',
                DB::raw("'produksi' as tipe")
            )
            ->orderByDesc('produksi.id')
            ->take(20)
            ->get();

        // Tampilkan semua tugas (termasuk yang sudah masuk sebelumnya) agar popup tidak menghapus riwayat.
        // Batasi untuk performa: ambil 50 tugas terbaru.
        $custom = DB::table('tugas')
            ->where(function ($q) use ($user, $userIdColumn) {
                $q->whereNull('user_id')->orWhere('user_id', $user->{$userIdColumn});
            })
            ->select('id', 'judul', 'pesan', 'created_at', 'is_read', DB::raw("NULL as nama"), DB::raw("'custom' as tipe"))
            ->orderByDesc('created_at')
            ->take(50)
            ->get();

        $merged = $custom->concat($produksi)->sortByDesc(function ($item) {
            return $item->tipe === 'produksi' ? $item->id : strtotime($item->created_at ?? now());
        })->values()->take(10)->map(function ($row) {
            $row->notif_id = $row->tipe . '_' . $row->id;
            $row->is_read = (bool) $row->is_read;
            return $row;
        });

        return response()->json(['data' => $merged]);
    }

    public function count()
    {
        $user = Auth::user();
        if (! in_array($user->user_role, ['super_admin', 'admin'])) { abort(403); }

        $userLastRead = $user->updated_at ? Carbon::parse($user->updated_at) : null;
        $userIdColumn = Schema::hasColumn('users', 'user_id') ? 'user_id' : 'id';

        $retentionDays = 7;
        $retentionDate = now()->subDays($retentionDays)->toDateString();

        // Hitung produksi baru hanya yang diinput HARI INI
        $produksiQuery = $this->produksiQuery($user)
            ->whereDate('produksi.produksi_tanggal', today());

        // Jika admin sudah pernah mengklik "tandai dibaca" HARI INI, maka count produksi hari ini menjadi 0
        if ($userLastRead && $userLastRead->isToday()) {
            $produksiCount = 0;
        } else {
            $produksiCount = $produksiQuery->count('produksi.id');
        }

        $customCount = DB::table('tugas')
            ->where(function ($q) use ($user, $userIdColumn, $retentionDate) {
                $q->whereNull('user_id')->orWhere('user_id', $user->{$userIdColumn});
            })
            ->where(function ($q) use ($retentionDate) {
                $q->where(function ($q2) {
                    $q2->where('is_done', false);
                })->orWhere(function ($q3) use ($retentionDate) {
                    $q3->where('is_done', true)
                       ->where('created_at', '>=', $retentionDate);
                });
            })
            ->where(function ($q) {
                $q->where('is_read', false)->orWhereNull('is_read');
            })
            ->count();

        return response()->json([
            'count' => $produksiCount + $customCount,
            'produksiCount' => $produksiCount,
            'customCount' => $customCount,
        ]);
    }

    public function markAsRead($id)
    {
        $user = Auth::user();
        if (! in_array($user->user_role, ['super_admin', 'admin'])) { abort(403); }

        if (str_starts_with($id, 'custom_')) {
            $realId = str_replace('custom_', '', $id);
            DB::table('tugas')->where('id', $realId)->update(['is_read' => true, 'read_at' => now()]);
        } else {
            $userIdColumn = Schema::hasColumn('users', 'user_id') ? 'user_id' : 'id';
            DB::table('users')->where($userIdColumn, $user->{$userIdColumn})->update(['updated_at' => now()]);
        }

        return response()->json(['success' => true]);
    }

    public function markAllAsRead()
    {
        $user = Auth::user();
        if (! in_array($user->user_role, ['super_admin', 'admin'])) { abort(403); }

        $userIdColumn = Schema::hasColumn('users', 'user_id') ? 'user_id' : 'id';

        $retentionDays = 7;
        $retentionDate = now()->subDays($retentionDays)->toDateString();

        DB::table('tugas')
            ->where(function ($q) use ($user, $userIdColumn, $retentionDate) {
                $q->whereNull('user_id')->orWhere('user_id', $user->{$userIdColumn});
            })
            ->where(function ($q) use ($retentionDate) {
                $q->where('is_done', false)
                  ->orWhere('created_at', '>=', $retentionDate);
            })->update(['is_read' => true, 'read_at' => now()]);

        // Update updated_at milik user menjadi detik ini
        DB::table('users')->where($userIdColumn, $user->{$userIdColumn})->update(['updated_at' => now()]);

        return response()->json(['success' => true]);
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        if (! in_array($user->user_role, ['super_admin', 'admin'])) { abort(403); }

        $search = $request->input('search');
        $tab = in_array($request->input('tab'), ['all', 'produksi', 'profil']) ? $request->input('tab') : 'all';
        $page = max(1, (int) $request->input('page', 1));
        $perPage = 10;

        $notifsQuery = $this->notificationsQuery($user, $search, $tab);
        $total = $notifsQuery->count();

        $notifs = $notifsQuery->forPage($page, $perPage)->get()
            ->map(function ($row) {
                $row->notif_id = $row->tipe . '_' . $row->id;
                $row->hasil = $row->tipe === 'produksi' ? number_format($row->jumlah_tbs ?? 0, 2, ',', '.') . ' Ton Kelapa Sawit (TBS)' : null;
                $row->waktu = $row->created_at ? Carbon::parse($row->created_at)->translatedFormat('d F Y') : '-';
                return $row;
            });

        $counts = $this->count()->getData();

        return view('super_admin.notifikasi.index', [
            'notifs' => $notifs,
            'unreadCount' => $counts->count,
            'dailyProductionCount' => $counts->produksiCount,
            'total' => $total,
            'page' => $page,
            'perPage' => $perPage,
            'search' => $search,
            'tab' => $tab
        ]);
    }
}
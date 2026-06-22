<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notifikasi;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class NotifikasiController extends Controller
{
    /*
    |-----------------------------------------
    | GET NOTIFIKASI (UNTUK POPUP / AJAX)
    |-----------------------------------------
    */
    public function getPopup()
    {
        $user = Auth::user();

        $query = Notifikasi::query();

        if ($user->user_role === 'super_admin') {
            $query->where('target', 'superadmin');
        } else {
            $query->where('target', 'admin')
                ->where('user_id', $user->user_id);
        }

        $notifikasi = $query->latest()->take(10)->get();

        return response()->json([
            'data' => $notifikasi
        ]);
    }

    /*
    |-----------------------------------------
    | COUNT UNREAD (BADGE)
    |-----------------------------------------
    */
    public function count()
    {
        $user = Auth::user();

        $query = Notifikasi::where('is_read', false);

        if ($user->user_role === 'super_admin') {
            $query->where('target', 'superadmin');
        } else {
            $query->where('target', 'admin')
                  ->where('user_id', $user->user_id);
        }

        return response()->json([
            'count' => $query->count()
        ]);
    }

    /*
    |-----------------------------------------
    | MARK AS READ
    |-----------------------------------------
    */
    public function markAsRead($id)
    {
        $user = Auth::user();

        $notif = Notifikasi::findOrFail($id);

        if ($user->user_role !== 'super_admin') {
            if ($notif->user_id != $user->user_id) {
                abort(403);
            }
        }

        $notif->update([
            'is_read' => true,
            'read_at' => now()
        ]);

        return redirect()->back()
            ->with('success', 'Notifikasi berhasil dibaca');
    }

    /*
    |-----------------------------------------
    | MARK ALL AS READ
    |-----------------------------------------
    */
    public function markAllAsRead()
    {
        $user = Auth::user();

        $query = Notifikasi::where('is_read', false);

        if ($user->user_role === 'super_admin') {
            $query->where('target', 'superadmin');
        } else {
            $query->where('target', 'admin')
                  ->where('user_id', $user->user_id);
        }

        $query->update([
            'is_read' => true,
            'read_at' => now()
        ]);

        return response()->json(['success' => true]);
    }

    /*
    |-----------------------------------------
    | HALAMAN LIST NOTIFIKASI
    |-----------------------------------------
    */
    public function index()
    {
        $user = Auth::user();

        $query = Notifikasi::query();

        if ($user->user_role === 'super_admin') {
            $query->where('target', 'superadmin');
        } else {
            $query->where('target', 'admin')
                  ->where('user_id', $user->user_id);
        }

        $notifikasi = $query->latest()->paginate(20);

        return view('notifikasi.index', compact('notifikasi'));
    }

    /*
    |-----------------------------------------
    | KIRIM TUGAS (SUPER ADMIN → ADMIN)
    |-----------------------------------------
    */
    public function kirimTugas(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,user_id',
            'judul' => 'required|string|max:255',
            'pesan' => 'required|string'
        ]);

        Notifikasi::create([
            'target' => 'admin',
            'user_id' => $request->user_id,
            'judul' => $request->judul,
            'pesan' => $request->pesan,
            'jenis' => 'tugas',
            'is_read' => false,
            'read_at' => null
        ]);

        return back()->with('success', 'Tugas berhasil dikirim');
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Petani;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\PengingatMail;
use Illuminate\Support\Facades\DB;

class PengingatController extends Controller
{
    public function create()
    {
        $petanis = Petani::orderBy('petani_nama')->get();
        $admins = User::whereIn('user_role', ['admin', 'super_admin'])->orderBy('user_nama')->get();

        return view('super_admin.pengingat.create', compact('petanis', 'admins'));
    }

    public function send(Request $request)
    {
        $data = $request->validate([
            'recipient_category' => 'required|in:petani,admin',
            'recipient_scope' => 'required|in:single,all',
            'recipient_id' => 'nullable|integer',
            'judul' => 'required|string',
            'message' => 'required|string',
            'deadline' => 'nullable|date',
            'priority' => 'nullable|in:normal,urgent'
        ]);

        $recipients = [];

        if ($data['recipient_category'] === 'petani') {
            if ($data['recipient_scope'] === 'all') {
                $recipients = Petani::whereNotNull('petani_email')->pluck('petani_email')->filter()->unique()->toArray();
            } else {
                $p = Petani::find($data['recipient_id']);
                if ($p && $p->petani_email) $recipients[] = $p->petani_email;
            }
        } else {
            if ($data['recipient_scope'] === 'all') {
                $recipients = User::whereIn('user_role', ['admin','super_admin'])->whereNotNull('user_email')->pluck('user_email')->filter()->unique()->toArray();
            } else {
                $u = User::find($data['recipient_id']);
                if ($u && $u->user_email) $recipients[] = $u->user_email;
            }
        }

        // Jika tidak ada penerima yang ditemukan
        if (empty($recipients)) {
            return redirect()->back()->with('error', 'Gagal mengirim pengingat: Tidak ada data email penerima yang ditemukan.');
        }

        $successCount = 0;
        $failCount = 0;

        // send emails
        foreach ($recipients as $email) {
            try {
                logger("Mengirim email ke : ".$email);

                Mail::to($email)->send(new PengingatMail([
                    'judul' => $data['judul'],
                    'pesan' => $data['message'],
                    'deadline' => $data['deadline'] ?? null,
                ]));

                logger("BERHASIL ".$email);
                $successCount++;
            } catch (\Exception $e){
                logger("GAGAL : ".$e->getMessage());
                $failCount++;
            }
        }

        // juga simpan di tabel tugas jika kategori admin
        if ($data['recipient_category'] === 'admin') {
            $now = now();
            $taskData = [
                'judul' => $data['judul'],
                'pesan' => $data['message'],
                'deadline' => $data['deadline'] ?? null,
                'is_read' => false,
                'read_at' => null,
                'is_done' => false,
                'created_at' => $now,
                'updated_at' => $now,
            ];

            if ($data['recipient_scope'] === 'all') {
                $taskData['user_id'] = null;
            } else {
                $taskData['user_id'] = $data['recipient_id'];
            }

            DB::table('tugas')->insert($taskData);
        }

        // Atur pesan alert berdasarkan status pengiriman log email
        if ($successCount > 0 && $failCount == 0) {
            return redirect()->back()->with('success', "Pengingat berhasil dikirim ke seluruh ($successCount) penerima.");
        } elseif ($successCount > 0 && $failCount > 0) {
            return redirect()->back()->with('warning', "Pengingat terkirim ke $successCount penerima, tetapi gagal dikirim ke $failCount penerima. Periksa log sistem.");
        } else {
            return redirect()->back()->with('error', "Gagal mengirim pengingat ke semua ($failCount) penerima. Silakan periksa jaringan atau konfigurasi email.");
        }
    }
}
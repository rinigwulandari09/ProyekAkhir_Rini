<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Petani;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\PengingatMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

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
        $fcmTokens = [];

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
                if (Schema::hasColumn('users', 'fcm_token')) {
                    $fcmTokens = User::whereIn('user_role', ['admin','super_admin'])->whereNotNull('fcm_token')->pluck('fcm_token')->filter()->unique()->toArray();
                }
            } else {
                $u = User::find($data['recipient_id']);
                if ($u && $u->user_email) $recipients[] = $u->user_email;
                if ($u && Schema::hasColumn('users', 'fcm_token') && !empty($u->fcm_token)) {
                    $fcmTokens[] = $u->fcm_token;
                }
            }
        }

        // Jika tidak ada penerima email yang ditemukan
        if (empty($recipients)) {
            return redirect()->back()->with('error', 'Gagal mengirim pengingat: Tidak ada data email penerima yang ditemukan.');
        }

        $successCount = 0;
        $failCount = 0;

        // 1. Send Email Notification
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

        // 2. Simpan di tabel tugas jika kategori admin
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

            // 3. Send Mobile Push Notification (FCM)
            $this->sendPushNotification($fcmTokens, "Tugas Baru: " . $data['judul'], $data['message']);
        }

        if ($successCount > 0 && $failCount == 0) {
            return redirect()->back()->with('success', "Pengingat dan tugas berhasil dikirim ke seluruh ($successCount) penerima.");
        } elseif ($successCount > 0 && $failCount > 0) {
            return redirect()->back()->with('warning', "Pengingat terkirim ke $successCount penerima, tetapi gagal dikirim ke $failCount penerima. Periksa log sistem.");
        } else {
            return redirect()->back()->with('error', "Gagal mengirim pengingat ke semua ($failCount) penerima. Silakan periksa jaringan atau konfigurasi email.");
        }
    }

    protected function sendPushNotification(array $tokens, string $title, string $body)
    {
        $serverKey = env('FCM_SERVER_KEY');
        if (empty($serverKey) || empty($tokens)) {
            return;
        }

        $tokens = array_values(array_filter($tokens));
        if (empty($tokens)) return;

        $url = 'https://fcm.googleapis.com/fcm/send';
        $payload = [
            'registration_ids' => $tokens,
            'notification' => [
                'title' => $title,
                'body' => $body,
                'sound' => 'default',
            ],
            'data' => [
                'type' => 'tugas',
                'title' => $title,
                'body' => $body,
            ]
        ];

        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: key=' . $serverKey,
                'Content-Type: application/json',
            ]);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
            $result = curl_exec($ch);
            curl_close($ch);
            logger("FCM Result: " . $result);
        } catch (\Exception $e) {
            logger("FCM Error: " . $e->getMessage());
        }
    }
}
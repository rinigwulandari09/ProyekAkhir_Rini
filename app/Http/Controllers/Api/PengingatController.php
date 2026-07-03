<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Petani;
use Illuminate\Support\Facades\Mail;
use App\Mail\PengingatMail;

class PengingatController extends Controller
{
    /**
     * Send pengingat to petani (single or all) without storing to DB.
     */
    public function send(Request $request)
    {
        $data = $request->validate([
            'recipient_scope' => 'required|in:single,all',
            'recipient_id' => 'nullable|integer',
            'message' => 'required|string',
            'deadline' => 'nullable|date',
        ]);

        $recipients = [];

        if ($data['recipient_scope'] === 'all') {
            $recipients = Petani::whereNotNull('petani_email')->pluck('petani_email')->filter()->unique()->toArray();
        } else {
            $p = Petani::find($data['recipient_id']);
            if ($p && $p->petani_email) $recipients[] = $p->petani_email;
        }

        if (empty($recipients)) {
            return response()->json(["status" => "error", "message" => "Tidak ada email penerima yang ditemukan."], 422);
        }

        $success = 0;
        $failed = 0;
        $failed_list = [];

        foreach ($recipients as $email) {
            try {
                Mail::to($email)->send(new PengingatMail([
                    'pesan' => $data['message'],
                    'deadline' => $data['deadline'] ?? null,
                ]));
                $success++;
            } catch (\Exception $e) {
                $failed++;
                $failed_list[] = ['email' => $email, 'error' => $e->getMessage()];
            }
        }

        $resp = [
            'status' => 'ok',
            'sent' => $success,
            'failed' => $failed,
        ];

        if (!empty($failed_list)) $resp['failed_list'] = $failed_list;

        return response()->json($resp);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\User;

class TugasApiController extends Controller
{
    /**
     * Get list of tasks for mobile app.
     */
    public function index(Request $request)
    {
        $userId = $request->query('user_id');

        $query = DB::table('tugas');

        if ($userId) {
            $query->where(function ($q) use ($userId) {
                $q->whereNull('user_id')
                  ->orWhere('user_id', $userId);
            });
        }

        $tasks = $query->orderByRaw("CASE WHEN is_done = false THEN 0 ELSE 1 END")
                      ->orderByRaw("CASE WHEN deadline IS NULL THEN 1 ELSE 0 END ASC")
                      ->orderBy('deadline', 'asc')
                      ->orderBy('created_at', 'desc')
                      ->get();

        return response()->json([
            'status' => 'success',
            'data' => $tasks
        ]);
    }

    /**
     * Mark task as complete from mobile app.
     */
    public function complete($id)
    {
        $updated = DB::table('tugas')
            ->where('id', $id)
            ->update([
                'is_done' => true,
                'updated_at' => now()
            ]);

        if (!$updated) {
            return response()->json([
                'status' => 'error',
                'message' => 'Tugas tidak ditemukan atau sudah selesai.'
            ], 444);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Tugas berhasil ditandai selesai.'
        ]);
    }

    /**
     * Save FCM Token for Push Notification.
     */
    public function updateFcmToken(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'fcm_token' => 'required|string'
        ]);

        $userId = $request->input('user_id');
        $token = $request->input('fcm_token');

        $idCol = Schema::hasColumn('users', 'user_id') ? 'user_id' : 'id';

        $user = User::where($idCol, $userId)->first();
        if ($user) {
            $user->fcm_token = $token;
            $user->save();

            return response()->json([
                'status' => 'success',
                'message' => 'FCM Token berhasil diperbarui.'
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'User tidak ditemukan.'
        ], 404);
    }
}
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// return new class extends Migration
// {
//     public function up(): void
//     {
//         Schema::create('notifikasi', function (Blueprint $table) {

//             $table->id();

//             $table->unsignedBigInteger('user_id')->nullable();

//             $table->enum('target', [
//                 'superadmin',
//                 'admin'
//             ]);

//             $table->string('judul');

//             $table->text('pesan');

//             $table->string('jenis');

//             $table->boolean('is_read')
//                   ->default(false);

//             $table->timestamp('read_at')
//                   ->nullable();

//             $table->timestamps();
//         });
//     }

//     public function down(): void
//     {
//         Schema::dropIfExists('notifikasi');
//     }
// };
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('harga_tbs', function (Blueprint $table) {
            $table->id('harga_tbs_id');
            $table->decimal('harga_dinas', 12, 2)->default(0);
            $table->decimal('harga_pt_sar', 12, 2)->default(0);
            $table->date('tanggal_berlaku');
            $table->foreignId('created_by_user_id')->nullable()->constrained('users', 'user_id')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('harga_tbs');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('petani')) {
            Schema::create('petani', function (Blueprint $table) {
                $table->id('petani_id');
                $table->string('petani_nama');
                $table->string('petani_alamat')->nullable();
                $table->string('petani_no_hp')->nullable();
                $table->string('petani_status')->nullable();
                $table->string('petani_email')->nullable()->unique();
                $table->text('petani_pin')->nullable();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->text('petani_desa')->nullable();
                $table->string('petani_jenis_kelamin')->nullable();
                $table->string('petani_tanggal_lahir')->nullable();
                $table->string('petani_username')->nullable();
                $table->unsignedInteger('desa_id')->nullable();
                $table->string('petani_profil')->nullable();
                $table->string('petani_path')->nullable();

                $table->foreign('user_id')
                    ->references('user_id')
                    ->on('users')
                    ->nullOnDelete();

                $table->foreign('desa_id')
                    ->references('desa_id')
                    ->on('desa')
                    ->nullOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('petani');
    }
};

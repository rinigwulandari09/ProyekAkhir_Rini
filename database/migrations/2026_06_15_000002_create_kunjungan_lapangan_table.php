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
        if (!Schema::hasTable('kunjungan_lapangan')) {
            Schema::create('kunjungan_lapangan', function (Blueprint $table) {
                $table->id('id_kunjungan');
                $table->string('tanggal_kunjungan');
                $table->string('desa_kebun');
                $table->string('desa_kepengurusan');
                $table->string('nama_auditor');
                $table->string('nama_petani')->nullable();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->unsignedBigInteger('petani_id')->nullable();
                $table->text('path_file_kunjungan')->nullable();
                $table->string('status')->nullable();
                $table->text('keterangan')->nullable();
                $table->string('periode')->nullable();
                $table->integer('visit_attempt')->default(1);
                $table->timestamps();

                $table->foreign('user_id')
                    ->references('user_id')
                    ->on('users')
                    ->nullOnDelete();

                $table->foreign('petani_id')
                    ->references('petani_id')
                    ->on('petani')
                    ->nullOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kunjungan_lapangan');
    }
};

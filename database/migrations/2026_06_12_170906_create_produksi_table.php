<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('produksi')) {
            Schema::create('produksi', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->date('produksi_tanggal')->nullable();
                $table->double('jumlah_tbs')->nullable();
                $table->double('harga_tbs')->nullable();
                $table->double('total_pendapatan')->nullable();
                $table->text('produksi_ket')->nullable();
                $table->string('produksi_bukti')->nullable();
                $table->string('status_validasi')->nullable()->default('Pending');
                $table->unsignedBigInteger('petani_id')->nullable();
                $table->unsignedBigInteger('lahan_id')->nullable();
                $table->boolean('is_read')->default(false);

                $table->foreign('petani_id')
                    ->references('petani_id')
                    ->on('petani')
                    ->onDelete('set null');

                $table->foreign('lahan_id')
                    ->references('lahan_id')
                    ->on('lahan')
                    ->onDelete('set null');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('produksi');
    }
};
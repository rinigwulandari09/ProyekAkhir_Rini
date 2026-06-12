<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('produksi', function (Blueprint $table) {

            $table->bigIncrements('id');

            $table->date('produksi_tanggal')->nullable();

            $table->double('jumlah_tbs')->nullable();

            $table->double('harga_tbs')->nullable();

            $table->double('total_pendapatan')->nullable();

            $table->string('status_validasi')->nullable();

            $table->unsignedBigInteger('petani_id')->nullable();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('produksi');
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('biaya_operasional', function (Blueprint $table) {

            $table->bigIncrements('id');

            $table->date('biaya_tanggal')->nullable();

            $table->string('biaya_jenis')->nullable();

            $table->double('biaya_jumlah')->nullable();

            $table->text('biaya_ket')->nullable();

            $table->unsignedBigInteger('petani_id')->nullable();

            $table->foreign('petani_id')
                ->references('petani_id')
                ->on('petani')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('biaya_operasional');
    }
};
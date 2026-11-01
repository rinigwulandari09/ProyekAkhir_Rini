<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('biaya_operasional')) {
            Schema::create('biaya_operasional', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->date('biaya_tanggal')->nullable();
                $table->string('biaya_jenis')->nullable();
                $table->string('biaya_nama')->nullable();
                $table->double('biaya_jumlah')->nullable();
                $table->double('biaya_total')->nullable();
                $table->text('biaya_ket')->nullable();
                $table->text('biaya_bukti')->nullable();
                $table->unsignedBigInteger('petani_id')->nullable();
                $table->unsignedInteger('desa_id')->nullable();
                $table->unsignedBigInteger('lahan_id')->nullable();
                $table->boolean('is_read')->default(false);

                $table->foreign('petani_id')
                    ->references('petani_id')
                    ->on('petani')
                    ->onDelete('set null');

                $table->foreign('desa_id')
                    ->references('desa_id')
                    ->on('desa')
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
        Schema::dropIfExists('biaya_operasional');
    }
};
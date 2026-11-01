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
        if (!Schema::hasTable('lahan')) {
            Schema::create('lahan', function (Blueprint $table) {
                $table->id('lahan_id');
                $table->unsignedBigInteger('petani_id');
                $table->string('lahan_lokasi');
                $table->double('lahan_luas')->nullable();
                $table->json('area_lahan')->nullable();
                $table->string('lahan_nama')->nullable();
                $table->integer('tahun_tanam')->nullable();
                $table->string('lahan_no_surat')->nullable();

                $table->foreign('petani_id')
                    ->references('petani_id')
                    ->on('petani')
                    ->cascadeOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lahan');
    }
};

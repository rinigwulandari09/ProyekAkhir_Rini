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
        if (!Schema::hasTable('detail_kegiatan')) {
            Schema::create('detail_kegiatan', function (Blueprint $table) {
                $table->increments('detail_kegiatan_id');
                $table->unsignedInteger('kegiatan_id');
                $table->unsignedBigInteger('lahan_id');

                $table->foreign('kegiatan_id')
                    ->references('kegiatan_id')
                    ->on('kegiatan')
                    ->cascadeOnDelete();

                $table->foreign('lahan_id')
                    ->references('lahan_id')
                    ->on('lahan')
                    ->cascadeOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_kegiatan');
    }
};

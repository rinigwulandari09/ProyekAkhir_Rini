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
        if (!Schema::hasTable('kegiatan')) {
            Schema::create('kegiatan', function (Blueprint $table) {
                $table->increments('kegiatan_id');
                $table->unsignedBigInteger('petani_id');
                $table->unsignedInteger('jenis_kegiatan_id');
                $table->date('kegiatan_tanggal');
                $table->decimal('kegiatan_jumlah', 12, 2);
                $table->string('kegiatan_satuan');
                $table->text('kegiatan_ket')->nullable();

                $table->foreign('petani_id')
                    ->references('petani_id')
                    ->on('petani')
                    ->cascadeOnDelete();

                $table->foreign('jenis_kegiatan_id')
                    ->references('id_jenis')
                    ->on('jenis_kegiatan')
                    ->cascadeOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kegiatan');
    }
};

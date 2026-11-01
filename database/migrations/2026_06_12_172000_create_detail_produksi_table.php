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
        if (!Schema::hasTable('detail_produksi')) {
            Schema::create('detail_produksi', function (Blueprint $table) {
                $table->increments('detail_produksi_id');
                $table->unsignedBigInteger('produksi_id');
                $table->unsignedBigInteger('lahan_id');
                $table->decimal('jumlah_tbs', 12, 2)->default(0);
                $table->decimal('subtotal_pendapatan', 12, 2)->default(0);

                $table->foreign('produksi_id')
                    ->references('id')
                    ->on('produksi')
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
        Schema::dropIfExists('detail_produksi');
    }
};

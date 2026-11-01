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
        if (!Schema::hasTable('detail_biaya_operasional')) {
            Schema::create('detail_biaya_operasional', function (Blueprint $table) {
                $table->increments('detail_biaya_operasional_id');
                $table->unsignedBigInteger('biaya_operasional_id');
                $table->unsignedBigInteger('lahan_id');
                $table->decimal('subtotal', 12, 2)->default(0);

                $table->foreign('biaya_operasional_id')
                    ->references('id')
                    ->on('biaya_operasional')
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
        Schema::dropIfExists('detail_biaya_operasional');
    }
};

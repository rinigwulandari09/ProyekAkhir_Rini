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
        if (!Schema::hasTable('audit_internal')) {
            Schema::create('audit_internal', function (Blueprint $table) {
                $table->string('id_audit')->primary();
                $table->unsignedBigInteger('user_id');
                $table->string('tanggal');
                $table->string('desa');
                $table->string('nama_auditor');
                $table->string('nama_petani');
                $table->unsignedBigInteger('petani_id')->nullable();
                $table->text('path_file_kunjungan')->nullable();
                $table->string('status_audit')->nullable();
                $table->text('keterangan')->nullable();
                $table->string('periode')->nullable();
                $table->integer('audit_attempt')->default(1);
                $table->boolean('is_read')->default(false);

                $table->foreign('user_id')
                    ->references('user_id')
                    ->on('users')
                    ->cascadeOnDelete();

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
        Schema::dropIfExists('audit_internal');
    }
};

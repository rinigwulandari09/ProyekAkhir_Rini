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
        if (!Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->id('user_id');
                $table->string('user_nama');
                $table->string('user_username')->nullable();
                $table->string('user_password')->nullable();
                $table->string('user_role')->nullable();
                $table->text('user_desa')->nullable();
                $table->string('user_email')->nullable()->unique();
                $table->unsignedInteger('desa_id')->nullable();
                $table->string('user_profil')->nullable();
                $table->timestamps();

                $table->foreign('desa_id')
                    ->references('desa_id')
                    ->on('desa')
                    ->nullOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};

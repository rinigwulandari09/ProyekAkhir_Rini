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
        Schema::table('petani', function (Blueprint $table) {
            if (!Schema::hasColumn('petani', 'id_petani_asosiasi')) {
                $table->string('id_petani_asosiasi', 100)->nullable()->unique();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('petani', function (Blueprint $table) {
            if (Schema::hasColumn('petani', 'id_petani_asosiasi')) {
                $table->dropColumn('id_petani_asosiasi');
            }
        });
    }
};


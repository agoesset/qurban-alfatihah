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
        Schema::table('list_hewans', function (Blueprint $table) {
            $table->timestamp('penyembelihan_updated_at')->nullable();
            $table->timestamp('pengulitan_updated_at')->nullable();
            $table->timestamp('penimbangan_updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('list_hewans', function (Blueprint $table) {
            $table->dropColumn('penyembelihan_updated_at');
            $table->dropColumn('pengulitan_updated_at');
            $table->dropColumn('penimbangan_updated_at');
        });
    }
};

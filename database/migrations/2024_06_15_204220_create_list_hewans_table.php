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
        Schema::create('list_hewans', function (Blueprint $table) {
            $table->id();
            $table->string('kode_hewan')->unique();
            $table->decimal('bobot', 5, 2);
            $table->foreignId('kategori_id')->constrained('kategoris')->cascadeOnDelete();
            $table->boolean('penyembelihan')->default(false);
            $table->boolean('pengulitan')->default(false);
            $table->boolean('penimbangan')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('list_hewans');
    }
};

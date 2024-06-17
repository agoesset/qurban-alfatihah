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
        Schema::create('list_distribusis', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->boolean('shohibul_qurban')->default(false);
            $table->integer('jumlah');
            $table->json('request');
            $table->string('alamat');
            $table->boolean('terbungkus')->default(false);
            $table->boolean('terdistribusi')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('list_distribusis');
    }
};

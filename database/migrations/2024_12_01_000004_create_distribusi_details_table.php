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
        Schema::create('distribusi_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('list_distribusi_id')->constrained('list_distribusis')->cascadeOnDelete();
            $table->foreignId('hewan_meat_part_id')->constrained('hewan_meat_parts')->restrictOnDelete();
            $table->decimal('berat', 8, 2)->comment('Weight allocated in kg');
            $table->timestamps();

            $table->index('list_distribusi_id');
            $table->index('hewan_meat_part_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('distribusi_details');
    }
};

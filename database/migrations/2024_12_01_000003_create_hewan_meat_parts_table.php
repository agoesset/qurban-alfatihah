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
        Schema::create('hewan_meat_parts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('list_hewan_id')->constrained('list_hewans')->cascadeOnDelete();
            $table->enum('jenis_bagian', ['Daging', 'Jeroan', 'Kepala & Kaki', 'Buntut']);
            $table->decimal('berat_total', 8, 2)->comment('Total weight in kg');
            $table->decimal('berat_tersedia', 8, 2)->comment('Available weight in kg');
            $table->timestamps();

            $table->index(['list_hewan_id', 'jenis_bagian']);
            $table->index('berat_tersedia');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hewan_meat_parts');
    }
};

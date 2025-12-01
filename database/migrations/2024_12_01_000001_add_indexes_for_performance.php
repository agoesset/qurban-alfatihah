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
        // Add indexes to list_hewans table
        Schema::table('list_hewans', function (Blueprint $table) {
            // Index for kategori_id (used in all Helper methods for filtering by jenis)
            $table->index('kategori_id', 'idx_list_hewans_kategori_id');

            // Composite indexes for filtering by status
            $table->index(['kategori_id', 'penyembelihan'], 'idx_list_hewans_kategori_penyembelihan');
            $table->index(['kategori_id', 'pengulitan'], 'idx_list_hewans_kategori_pengulitan');
            $table->index(['kategori_id', 'penimbangan'], 'idx_list_hewans_kategori_penimbangan');

            // Indexes for timestamp queries
            $table->index(['penyembelihan', 'penyembelihan_updated_at'], 'idx_list_hewans_penyembelihan_timestamp');
            $table->index(['pengulitan', 'pengulitan_updated_at'], 'idx_list_hewans_pengulitan_timestamp');
            $table->index(['penimbangan', 'penimbangan_updated_at'], 'idx_list_hewans_penimbangan_timestamp');

            // Index for kode_hewan search
            $table->index('kode_hewan', 'idx_list_hewans_kode_hewan');
        });

        // Add indexes to list_distribusis table
        Schema::table('list_distribusis', function (Blueprint $table) {
            // Index for shohibul_qurban filter
            $table->index('shohibul_qurban', 'idx_list_distribusis_shohibul_qurban');

            // Composite indexes for filtering by status
            $table->index(['terbungkus', 'updated_at'], 'idx_list_distribusis_terbungkus_updated');
            $table->index(['terdistribusi', 'updated_at'], 'idx_list_distribusis_terdistribusi_updated');

            // Composite indexes for combined filters
            $table->index(['shohibul_qurban', 'terdistribusi'], 'idx_list_distribusis_shohibul_terdistribusi');

            // Index for nama search
            $table->index('nama', 'idx_list_distribusis_nama');
        });

        // Add indexes to kategoris table
        Schema::table('kategoris', function (Blueprint $table) {
            // Index for nama_kategori LIKE queries
            $table->index('nama_kategori', 'idx_kategoris_nama_kategori');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop indexes from list_hewans table
        Schema::table('list_hewans', function (Blueprint $table) {
            $table->dropIndex('idx_list_hewans_kategori_id');
            $table->dropIndex('idx_list_hewans_kategori_penyembelihan');
            $table->dropIndex('idx_list_hewans_kategori_pengulitan');
            $table->dropIndex('idx_list_hewans_kategori_penimbangan');
            $table->dropIndex('idx_list_hewans_penyembelihan_timestamp');
            $table->dropIndex('idx_list_hewans_pengulitan_timestamp');
            $table->dropIndex('idx_list_hewans_penimbangan_timestamp');
            $table->dropIndex('idx_list_hewans_kode_hewan');
        });

        // Drop indexes from list_distribusis table
        Schema::table('list_distribusis', function (Blueprint $table) {
            $table->dropIndex('idx_list_distribusis_shohibul_qurban');
            $table->dropIndex('idx_list_distribusis_terbungkus_updated');
            $table->dropIndex('idx_list_distribusis_terdistribusi_updated');
            $table->dropIndex('idx_list_distribusis_shohibul_terdistribusi');
            $table->dropIndex('idx_list_distribusis_nama');
        });

        // Drop indexes from kategoris table
        Schema::table('kategoris', function (Blueprint $table) {
            $table->dropIndex('idx_kategoris_nama_kategori');
        });
    }
};

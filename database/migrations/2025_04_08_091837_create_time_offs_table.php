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
        Schema::create('time_offs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->enum('type', [
                'cuti_tahunan',
                'izin_jam_kerja',
                'izin_sebelum_atau_sesudah_istirahat',
                'cuti_umroh',
                'cuti_haji',
                'dinas_dalam_kota',
                'dinas_luar_kota',
                'izin_tidak_masuk',
                'sakit_berkepanjangan_12_bulan_pertama',
                'sakit_berkepanjangan_4_bulan_pertama',
                'sakit_berkepanjangan_8_bulan_pertama',
                'sakit_berkepanjangan_diatas_12_bulan_pertama',
                'sakit_dengan_surat_dokter',
                'sakit_tanpa_surat_dokter',
                'cuti_menikah',
                'cuti_menikahkan_anak',
                'cuti_khitanan_anak',
                'cuti_istri_melahirkan_atau_keguguran',
                'cuti_keluarga_meninggal',
                'cuti_anggota_keluarga_dalam_satu_rumah_meninggal',
            ]);
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('days');
            $table->text('reason');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->string('document_url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('time_offs');
    }
};

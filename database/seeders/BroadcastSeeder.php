<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Broadcast;

class BroadcastSeeder extends Seeder
{
    public function run()
    {
        $broadcasts = [
            [
                'title' => 'Undangan Rapat Triwulan',
                'message' => 'Diinformasikan kepada seluruh karyawan bahwa rapat triwulan akan dilaksanakan pada Jumat, 28 Maret 2025 pukul 10.00 WIB via Zoom.',
                'priority' => 'high',
                'send_to_all' => true,
                'status' => 'sent',
                'sender_id' => 1,
            ],
            [
                'title' => 'Perubahan Kebijakan Cuti',
                'message' => 'Mulai bulan April 2025, pengajuan cuti harus dilakukan minimal 3 hari kerja sebelum tanggal cuti. Harap perhatikan perubahan ini.',
                'priority' => 'medium',
                'send_to_all' => true,
                'status' => 'sent',
                'sender_id' => 1,
            ],
            [
                'title' => 'Pelatihan Leadership',
                'message' => 'Pelatihan leadership untuk seluruh manager akan dilaksanakan pada tanggal 10-12 April 2025. Pendaftaran dibuka hingga 30 Maret 2025.',
                'priority' => 'low',
                'send_to_all' => true,
                'status' => 'sent',
                'sender_id' => 1,
            ],
            [
                'title' => 'Pengumuman Kenaikan Gaji',
                'message' => 'Kenaikan gaji tahunan akan diproses pada akhir bulan Maret. Silakan cek slip gaji digital Anda per tanggal 1 April 2025.',
                'priority' => 'medium',
                'send_to_all' => true,
                'status' => 'sent',
                'sender_id' => 1,
            ],
        ];

        foreach ($broadcasts as $broadcast) {
            Broadcast::create($broadcast);
        }
    }
}

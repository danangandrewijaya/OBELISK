<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Kurikulum;
use App\Models\MataKuliahKurikulum;

class MataKuliahKurikulum2017Seeder extends Seeder
{
    public function run()
    {
        // Ambil ID kurikulum 2017
        $kurikulum = Kurikulum::where('nama', 'Kurikulum 2017')->first();
        if (!$kurikulum) {
            echo "Seeder gagal: Kurikulum 2017 tidak ditemukan!";
            return;
        }

        $mataKuliah = [
            // SEMESTER 1
            ['kode' => 'PTEL5110', 'nama' => 'Pengantar Ilmu Rekayasa', 'semester' => 1, 'kategori' => 'Wajib', 'sks' => 2],
            ['kode' => 'PTEL5111', 'nama' => 'Fisika Dasar', 'semester' => 1, 'kategori' => 'Wajib', 'sks' => 3],
            ['kode' => 'PTEL5112', 'nama' => 'Kalkulus I', 'semester' => 1, 'kategori' => 'Wajib', 'sks' => 3],
            ['kode' => 'UUW00001', 'nama' => 'Pendidikan Pancasila', 'semester' => 1, 'kategori' => 'Wajib', 'sks' => 2],
            ['kode' => 'UUW00002', 'nama' => 'Bahasa Indonesia', 'semester' => 1, 'kategori' => 'Wajib', 'sks' => 2],
            ['kode' => 'UUW00003', 'nama' => 'Pengantar Komputer', 'semester' => 1, 'kategori' => 'Wajib', 'sks' => 2],
            ['kode' => 'UUW00004', 'nama' => 'Bahasa Inggris I', 'semester' => 1, 'kategori' => 'Wajib', 'sks' => 2],
            ['kode' => 'UUW00005', 'nama' => 'Pendidikan Agama', 'semester' => 1, 'kategori' => 'Wajib', 'sks' => 2],

            // SEMESTER 2
            ['kode' => 'PTEL5120', 'nama' => 'Rangkaian Listrik I', 'semester' => 2, 'kategori' => 'Wajib', 'sks' => 3],
            ['kode' => 'PTEL5121', 'nama' => 'Algoritma dan Pemrograman', 'semester' => 2, 'kategori' => 'Wajib', 'sks' => 3],
            ['kode' => 'PTEL5122', 'nama' => 'Praktikum Algoritma dan Pemrograman', 'semester' => 2, 'kategori' => 'Wajib', 'sks' => 1],
            ['kode' => 'PTEL5123', 'nama' => 'Kalkulus II', 'semester' => 2, 'kategori' => 'Wajib', 'sks' => 3],
            ['kode' => 'PTEL5124', 'nama' => 'Fisika Elektronika', 'semester' => 2, 'kategori' => 'Wajib', 'sks' => 3],
            ['kode' => 'UUW00006', 'nama' => 'Pendidikan Kewarganegaraan', 'semester' => 2, 'kategori' => 'Wajib', 'sks' => 2],
            ['kode' => 'UUW00007', 'nama' => 'Bahasa Inggris II', 'semester' => 2, 'kategori' => 'Wajib', 'sks' => 2],

            // SEMESTER 3
            ['kode' => 'PTEL5210', 'nama' => 'Rangkaian Listrik II', 'semester' => 3, 'kategori' => 'Wajib', 'sks' => 3],
            ['kode' => 'PTEL5211', 'nama' => 'Elektronika Analog I', 'semester' => 3, 'kategori' => 'Wajib', 'sks' => 3],
            ['kode' => 'PTEL5212', 'nama' => 'Matematika Teknik I', 'semester' => 3, 'kategori' => 'Wajib', 'sks' => 3],

            // SEMESTER LANJUTAN
            ['kode' => 'PTEL5300', 'nama' => 'Elektronika Digital', 'semester' => 4, 'kategori' => 'Wajib', 'sks' => 3],
            ['kode' => 'PTEL5301', 'nama' => 'Pemrograman Lanjut', 'semester' => 4, 'kategori' => 'Wajib', 'sks' => 3],
            ['kode' => 'PTEL5302', 'nama' => 'Sinyal dan Sistem', 'semester' => 5, 'kategori' => 'Wajib', 'sks' => 3],
            ['kode' => 'PTEL5303', 'nama' => 'Sistem Tenaga Listrik', 'semester' => 5, 'kategori' => 'Wajib Konsentrasi TTL', 'sks' => 3],
            ['kode' => 'PTEL5304', 'nama' => 'Dasar Telekomunikasi', 'semester' => 5, 'kategori' => 'Wajib Konsentrasi Telekomunikasi', 'sks' => 3],
            ['kode' => 'PTEL5305', 'nama' => 'Mikroprosesor', 'semester' => 5, 'kategori' => 'Wajib Konsentrasi Elektronika', 'sks' => 3],

            // TUGAS AKHIR DAN KKN
            ['kode' => 'PTEL5400', 'nama' => 'Proposal Tugas Akhir', 'semester' => 7, 'kategori' => 'Wajib', 'sks' => 2],
            ['kode' => 'PTEL5500', 'nama' => 'Tugas Akhir', 'semester' => 8, 'kategori' => 'Wajib', 'sks' => 4],
            ['kode' => 'UUW00008', 'nama' => 'Kuliah Kerja Nyata (KKN)', 'semester' => 8, 'kategori' => 'Wajib', 'sks' => 3],
        ];

        // Tambahkan 'kurikulum_id' ke setiap data
        $batchInsert = array_map(function ($mk) use ($kurikulum) {
            return array_merge($mk, [
                'kurikulum_id' => $kurikulum->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }, $mataKuliah);

        // Insert batch
        MataKuliahKurikulum::insert($batchInsert);
    }
}

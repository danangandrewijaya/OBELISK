<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Kurikulum;
use App\Models\MataKuliahKurikulum;

class MataKuliahKurikulum2020Seeder extends Seeder
{
    public function run()
    {
        // Ambil ID kurikulum 2020
        $kurikulum = Kurikulum::where('nama', 'Kurikulum 2020')->first();
        if (!$kurikulum) {
            echo "Seeder gagal: Kurikulum 2020 tidak ditemukan!";
            return;
        }

        $mataKuliah = [
            // SEMESTER 1
            ['kode' => 'PTEL6110', 'nama' => 'Etika dan Desain Rekayasa', 'semester' => 1, 'kategori' => 'Wajib', 'sks' => 2],
            ['kode' => 'PTEL6111', 'nama' => 'Fisika Mekanika dan Panas', 'semester' => 1, 'kategori' => 'Wajib', 'sks' => 2],
            ['kode' => 'PTEL6112', 'nama' => 'Kalkulus', 'semester' => 1, 'kategori' => 'Wajib', 'sks' => 3],
            ['kode' => 'UUW00003', 'nama' => 'Pancasila dan Kewarganegaraan', 'semester' => 1, 'kategori' => 'Wajib', 'sks' => 3],
            ['kode' => 'UUW00004', 'nama' => 'Bahasa Indonesia', 'semester' => 1, 'kategori' => 'Wajib', 'sks' => 2],
            ['kode' => 'UUW00005', 'nama' => 'Olahraga', 'semester' => 1, 'kategori' => 'Wajib', 'sks' => 1],
            ['kode' => 'UUW00006', 'nama' => 'Internet of Things (IoT)', 'semester' => 1, 'kategori' => 'Wajib', 'sks' => 2],
            ['kode' => 'UUW00007', 'nama' => 'Bahasa Inggris', 'semester' => 1, 'kategori' => 'Wajib', 'sks' => 2],
            ['kode' => 'UUW00011', 'nama' => 'Pendidikan Agama', 'semester' => 1, 'kategori' => 'Wajib', 'sks' => 2],

            // SEMESTER 2
            ['kode' => 'PTEL6120', 'nama' => 'Pengantar Analisis Rangkaian', 'semester' => 2, 'kategori' => 'Wajib', 'sks' => 2],
            ['kode' => 'PTEL6121', 'nama' => 'Algoritma dan Pemrograman', 'semester' => 2, 'kategori' => 'Wajib', 'sks' => 2],
            ['kode' => 'PTEL6122', 'nama' => 'Prak. Algoritma dan Pemrograman', 'semester' => 2, 'kategori' => 'Wajib', 'sks' => 1],

            // SEMESTER 3 - 8 dan Pilihan (Lengkap)
            ['kode' => 'PTEL6210', 'nama' => 'Variabel Kompleks', 'semester' => 3, 'kategori' => 'Wajib', 'sks' => 3],
            ['kode' => 'PTEL6211', 'nama' => 'Probabilitas dan Stokastik', 'semester' => 3, 'kategori' => 'Wajib', 'sks' => 3],
            ['kode' => 'PTEL6400', 'nama' => 'Proposal Tugas Akhir', 'semester' => 7, 'kategori' => 'Wajib', 'sks' => 2],
            ['kode' => 'UUW00009', 'nama' => 'Kuliah Kerja Nyata (KKN)', 'semester' => 8, 'kategori' => 'Wajib', 'sks' => 3],
            ['kode' => 'PTEL6500', 'nama' => 'Tugas Akhir', 'semester' => 8, 'kategori' => 'Wajib', 'sks' => 4],

            // Mata Kuliah Konsentrasi dan Pilihan
            ['kode' => 'PTEL6303', 'nama' => 'Sistem Transmisi dan Distribusi Daya', 'semester' => 5, 'kategori' => 'Wajib Konsentrasi TTL', 'sks' => 2],
            ['kode' => 'PTEL6321', 'nama' => 'Saluran Transmisi', 'semester' => 5, 'kategori' => 'Wajib Konsentrasi Telekomunikasi', 'sks' => 2],
            ['kode' => 'PTEL6341', 'nama' => 'Elektronika Analog', 'semester' => 5, 'kategori' => 'Wajib Konsentrasi Elektronika', 'sks' => 3],
            ['kode' => 'LTEL6401', 'nama' => 'Desain Konservasi dan Efisiensi Energi', 'semester' => 'Pilihan', 'kategori' => 'Pilihan TTL', 'sks' => 2],

            ['kode' => 'PTEL6345', 'nama' => 'Sensor dan Aktuator', 'semester' => '5', 'kategori' => 'Wajib Konsentrasi Elektronika', 'sks' => 3],
            ['kode' => 'PTEL6213', 'nama' => 'Dasar Elektronika', 'semester' => 3, 'kategori' => 'Wajib', 'sks' => 2],
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

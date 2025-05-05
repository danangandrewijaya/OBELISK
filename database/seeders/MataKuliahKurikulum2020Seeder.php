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
            ['kode' => 'PTEL6123', 'nama' => 'Fisika Listrik, Gelombang dan Cahaya', 'semester' => 2, 'kategori' => 'Wajib', 'sks' => 2],
            ['kode' => 'PTEL6124', 'nama' => 'Prak. Fisika Listrik', 'semester' => 2, 'kategori' => 'Wajib', 'sks' => 1],
            ['kode' => 'PTEL6125', 'nama' => 'Persamaan Differensial', 'semester' => 2, 'kategori' => 'Wajib', 'sks' => 3],
            ['kode' => 'PTEL6126', 'nama' => 'Analisis Vektor', 'semester' => 2, 'kategori' => 'Wajib', 'sks' => 3],
            ['kode' => 'PTEL6127', 'nama' => 'Aljabar Linear', 'semester' => 2, 'kategori' => 'Wajib', 'sks' => 3],
            ['kode' => 'PTEL6128', 'nama' => 'Kimia Dasar', 'semester' => 2, 'kategori' => 'Wajib', 'sks' => 2],

            // SEMESTER 3
            ['kode' => 'PTEL6210', 'nama' => 'Variabel Kompleks', 'semester' => 3, 'kategori' => 'Wajib', 'sks' => 3],
            ['kode' => 'PTEL6211', 'nama' => 'Probabilitas dan Stokastik', 'semester' => 3, 'kategori' => 'Wajib', 'sks' => 3],
            ['kode' => 'PTEL6212', 'nama' => 'Sinyal dan Sistem', 'semester' => 3, 'kategori' => 'Wajib', 'sks' => 3],
            ['kode' => 'PTEL6213', 'nama' => 'Dasar Elektronika', 'semester' => 3, 'kategori' => 'Wajib', 'sks' => 2],
            ['kode' => 'PTEL6214', 'nama' => 'Matematika Diskret', 'semester' => 3, 'kategori' => 'Wajib', 'sks' => 3],
            ['kode' => 'PTEL6215', 'nama' => 'Sistem Digital', 'semester' => 3, 'kategori' => 'Wajib', 'sks' => 2],
            ['kode' => 'PTEL6216', 'nama' => 'Rangkaian Listrik', 'semester' => 3, 'kategori' => 'Wajib', 'sks' => 2],
            ['kode' => 'PTEL6217', 'nama' => 'Prak. Rangkaian Listrik', 'semester' => 3, 'kategori' => 'Wajib', 'sks' => 1],

            // SEMESTER 4
            ['kode' => 'PTEL6220', 'nama' => 'Fisika Material', 'semester' => 4, 'kategori' => 'Wajib', 'sks' => 2],
            ['kode' => 'PTEL6221', 'nama' => 'Metode Numerik', 'semester' => 4, 'kategori' => 'Wajib', 'sks' => 2],
            ['kode' => 'PTEL6222', 'nama' => 'Medan Elektromagnetik', 'semester' => 4, 'kategori' => 'Wajib', 'sks' => 3],
            ['kode' => 'PTEL6223', 'nama' => 'Sistem Pengukuran dan Instrumentasi', 'semester' => 4, 'kategori' => 'Wajib', 'sks' => 2],
            ['kode' => 'PTEL6224', 'nama' => 'Prak. Dasar Elektronika', 'semester' => 4, 'kategori' => 'Wajib', 'sks' => 1],
            ['kode' => 'PTEL6225', 'nama' => 'Dasar Sistem Kontrol', 'semester' => 4, 'kategori' => 'Wajib', 'sks' => 1],
            ['kode' => 'PTEL6226', 'nama' => 'Prak. Dasar Sistem Kontrol', 'semester' => 4, 'kategori' => 'Wajib', 'sks' => 2],
            ['kode' => 'PTEL6227', 'nama' => 'Dasar Sistem Telekomunikasi', 'semester' => 4, 'kategori' => 'Wajib', 'sks' => 2],
            ['kode' => 'PTEL6228', 'nama' => 'Prak. Dasar Sistem Telekomunikasi', 'semester' => 4, 'kategori' => 'Wajib', 'sks' => 1],
            ['kode' => 'PTEL6229', 'nama' => 'Dasar Tenaga Listrik', 'semester' => 4, 'kategori' => 'Wajib', 'sks' => 2],

            // SEMESTER 5 (WAJIB)
            ['kode' => 'PTEL6300', 'nama' => 'Desain Sistem Mikroprosesor', 'semester' => 5, 'kategori' => 'Wajib', 'sks' => 3],
            ['kode' => 'PTEL6301', 'nama' => 'Prak. Desain Sistem Mikroprosesor', 'semester' => 5, 'kategori' => 'Wajib', 'sks' => 1],
            ['kode' => 'PTEL6302', 'nama' => 'Prak. Dasar Tenaga Listrik', 'semester' => 5, 'kategori' => 'Wajib', 'sks' => 1],
            ['kode' => 'UUW00008', 'nama' => 'Kewirausahaan', 'semester' => 5, 'kategori' => 'Wajib', 'sks' => 2],

            // SEMESTER 7 & 8 (WAJIB)
            ['kode' => 'PTEL6400', 'nama' => 'Proposal Tugas Akhir', 'semester' => 7, 'kategori' => 'Wajib', 'sks' => 2],
            ['kode' => 'UUW00009', 'nama' => 'Kuliah Kerja Nyata (KKN)', 'semester' => 8, 'kategori' => 'Wajib', 'sks' => 3],
            ['kode' => 'PTEL6497', 'nama' => 'Manajemen dan Ekonomi Teknik (MET)', 'semester' => 8, 'kategori' => 'Wajib', 'sks' => 2],
            ['kode' => 'PTEL6498', 'nama' => 'Etika Profesi', 'semester' => 8, 'kategori' => 'Wajib', 'sks' => 1],
            ['kode' => 'PTEL6499', 'nama' => 'Kerja Praktek (KP)', 'semester' => 8, 'kategori' => 'Wajib', 'sks' => 2],
            ['kode' => 'PTEL6500', 'nama' => 'Tugas Akhir', 'semester' => 8, 'kategori' => 'Wajib', 'sks' => 4],

            // KONSENTRASI TTL - SEMESTER 5
            ['kode' => 'PTEL6303', 'nama' => 'Sistem Transmisi dan Distribusi Daya Arus Bolak Balik', 'semester' => 5, 'kategori' => 'Wajib Konsentrasi TTL', 'sks' => 2],
            ['kode' => 'PTEL6304', 'nama' => 'Mesin Listrik Arus Searah dan Transformator', 'semester' => 5, 'kategori' => 'Wajib Konsentrasi TTL', 'sks' => 2],
            ['kode' => 'PTEL6305', 'nama' => 'Mesin Listrik Asinkron dan Sinkron', 'semester' => 5, 'kategori' => 'Wajib Konsentrasi TTL', 'sks' => 2],
            ['kode' => 'PTEL6306', 'nama' => 'Teknik dan Peralatan Tegangan Tinggi', 'semester' => 5, 'kategori' => 'Wajib Konsentrasi TTL', 'sks' => 2],
            ['kode' => 'PTEL6307', 'nama' => 'Prakt. Teknik dan Peralatan Tegangan Tinggi', 'semester' => 5, 'kategori' => 'Wajib Konsentrasi TTL', 'sks' => 1],
            ['kode' => 'PTEL6308', 'nama' => 'Instalasi Pemanfaatan Tenaga Listrik dan Iluminasi', 'semester' => 5, 'kategori' => 'Wajib Konsentrasi TTL', 'sks' => 2],
            ['kode' => 'PTEL6309', 'nama' => 'Prakt. Instalasi Pemanfaatan Tenaga Listrik dan Iluminasi', 'semester' => 5, 'kategori' => 'Wajib Konsentrasi TTL', 'sks' => 1],

            // KONSENTRASI TTL - SEMESTER 6
            ['kode' => 'PTEL6311', 'nama' => 'Prakt. Mesin-Mesin Listrik', 'semester' => 6, 'kategori' => 'Wajib Konsentrasi TTL', 'sks' => 1],
            ['kode' => 'PTEL6312', 'nama' => 'Konverter dan Pengemudian Elektronika Daya', 'semester' => 6, 'kategori' => 'Wajib Konsentrasi TTL', 'sks' => 2],
            ['kode' => 'PTEL6313', 'nama' => 'Prakt. Konverter dan Pengemudian Elektronika Daya', 'semester' => 6, 'kategori' => 'Wajib Konsentrasi TTL', 'sks' => 1],
            ['kode' => 'PTEL6314', 'nama' => 'Analisis Sistem Tenaga, Proteksi dan Pembumian', 'semester' => 6, 'kategori' => 'Wajib Konsentrasi TTL', 'sks' => 2],
            ['kode' => 'PTEL6315', 'nama' => 'Prakt. Sistem, Proteksi dan Pembumian Sistem Tenaga', 'semester' => 6, 'kategori' => 'Wajib Konsentrasi TTL', 'sks' => 1],
            ['kode' => 'PTEL6316', 'nama' => 'Keselamatan Kerja Listrik', 'semester' => 6, 'kategori' => 'Wajib Konsentrasi TTL', 'sks' => 2],
            ['kode' => 'PTEL6317', 'nama' => 'Stabilitas dan Keandalan Tenaga Listrik', 'semester' => 6, 'kategori' => 'Wajib Konsentrasi TTL', 'sks' => 2],
            ['kode' => 'PTEL6318', 'nama' => 'Termodinamika dan Pembangkit Tenaga Listrik', 'semester' => 6, 'kategori' => 'Wajib Konsentrasi TTL', 'sks' => 2],

            // PILIHAN TTL
            ['kode' => 'LTEL6401', 'nama' => 'Desain Konservasi dan Efisiensi Energi Listrik', 'semester' => 7, 'kategori' => 'Pilihan TTL', 'sks' => 2],
            ['kode' => 'LTEL6402', 'nama' => 'Rancangan Pemrograman Sistem Tenaga', 'semester' => 7, 'kategori' => 'Pilihan TTL', 'sks' => 2],
            ['kode' => 'LTEL6403', 'nama' => 'Desain Sistem Distribusi Tenaga Listrik', 'semester' => 7, 'kategori' => 'Pilihan TTL', 'sks' => 2],
            ['kode' => 'LTEL6404', 'nama' => 'Perancangan Pembangkit Tenaga Listrik', 'semester' => 7, 'kategori' => 'Pilihan TTL', 'sks' => 2],
            ['kode' => 'LTEL6405', 'nama' => 'Perancangan Pembangkit Energi Baru & Terbaharukan', 'semester' => 7, 'kategori' => 'Pilihan TTL', 'sks' => 2],
            ['kode' => 'LTEL6406', 'nama' => 'Rancangan Prakiraan Beban dan Tarif Listrik', 'semester' => 7, 'kategori' => 'Pilihan TTL', 'sks' => 2],
            ['kode' => 'LTEL6407', 'nama' => 'Desain Optimasi Pembangkitan dan Operasi Tenaga Listrik', 'semester' => 7, 'kategori' => 'Pilihan TTL', 'sks' => 2],
            ['kode' => 'LTEL6408', 'nama' => 'Desain Aplikasi Kecerdasan Buatan dalam Tenaga Listrik', 'semester' => 7, 'kategori' => 'Pilihan TTL', 'sks' => 2],
            ['kode' => 'LTEL6409', 'nama' => 'Perancangan Saluran dan Gardu Induk', 'semester' => 7, 'kategori' => 'Pilihan TTL', 'sks' => 2],
            ['kode' => 'LTEL6410', 'nama' => 'Desain Sistem Proteksi Petir', 'semester' => 7, 'kategori' => 'Pilihan TTL', 'sks' => 2],
            ['kode' => 'LTEL6411', 'nama' => 'Perancangan Mesin Listrik', 'semester' => 7, 'kategori' => 'Pilihan TTL', 'sks' => 2],
            ['kode' => 'LTEL6412', 'nama' => 'Perancangan Traksi dan Sistem Transportasi Listrik', 'semester' => 7, 'kategori' => 'Pilihan TTL', 'sks' => 2],
            ['kode' => 'LTEL6413', 'nama' => 'Perancangan Sistem Transmisi Daya Arus Searah', 'semester' => 7, 'kategori' => 'Pilihan TTL', 'sks' => 2],
            ['kode' => 'LTEL6414', 'nama' => 'Desain Isolator Tenaga', 'semester' => 7, 'kategori' => 'Pilihan TTL', 'sks' => 2],
            ['kode' => 'LTEL6415', 'nama' => 'Perancangan Kabel Tenaga Listrik', 'semester' => 7, 'kategori' => 'Pilihan TTL', 'sks' => 2],
            ['kode' => 'LTEL6416', 'nama' => 'Perancangan Aplikasi Tegangan Tinggi', 'semester' => 7, 'kategori' => 'Pilihan TTL', 'sks' => 2],
            ['kode' => 'LTEL6417', 'nama' => 'Praktek Kualitas Daya Listrik', 'semester' => 7, 'kategori' => 'Pilihan TTL', 'sks' => 1],

            // KONSENTRASI TELEKOMUNIKASI - SEMESTER 5
            ['kode' => 'PTEL6321', 'nama' => 'Saluran Transmisi', 'semester' => 5, 'kategori' => 'Wajib Konsentrasi Telekomunikasi', 'sks' => 2],
            ['kode' => 'PTEL6322', 'nama' => 'Jaringan Telekomunikasi', 'semester' => 5, 'kategori' => 'Wajib Konsentrasi Telekomunikasi', 'sks' => 2],
            ['kode' => 'PTEL6323', 'nama' => 'Pengenalan Pola', 'semester' => 5, 'kategori' => 'Wajib Konsentrasi Telekomunikasi', 'sks' => 2],
            ['kode' => 'PTEL6324', 'nama' => 'Elektronika Telekomunikasi', 'semester' => 5, 'kategori' => 'Wajib Konsentrasi Telekomunikasi', 'sks' => 2],
            ['kode' => 'PTEL6325', 'nama' => 'Praktikum Modulasi Pulsa', 'semester' => 5, 'kategori' => 'Wajib Konsentrasi Telekomunikasi', 'sks' => 1],
            ['kode' => 'PTEL6326', 'nama' => 'Teori Informasi dan Pengkodean', 'semester' => 5, 'kategori' => 'Wajib Konsentrasi Telekomunikasi', 'sks' => 2],
            ['kode' => 'PTEL6327', 'nama' => 'Praktikum Modulasi Digital', 'semester' => 5, 'kategori' => 'Wajib Konsentrasi Telekomunikasi', 'sks' => 1],

            // KONSENTRASI TELEKOMUNIKASI - SEMESTER 6
            ['kode' => 'PTEL6331', 'nama' => 'Teori & Perancangan Antena', 'semester' => 6, 'kategori' => 'Wajib Konsentrasi Telekomunikasi', 'sks' => 2],
            ['kode' => 'PTEL6332', 'nama' => 'Sistem Komunikasi Digital', 'semester' => 6, 'kategori' => 'Wajib Konsentrasi Telekomunikasi', 'sks' => 2],
            ['kode' => 'PTEL6333', 'nama' => 'Trafik & Kinerja Jaringan Telekomunikasi', 'semester' => 6, 'kategori' => 'Wajib Konsentrasi Telekomunikasi', 'sks' => 2],
            ['kode' => 'PTEL6334', 'nama' => 'Komunikasi Nirkawat', 'semester' => 6, 'kategori' => 'Wajib Konsentrasi Telekomunikasi', 'sks' => 2],
            ['kode' => 'PTEL6335', 'nama' => 'Komunikasi Data', 'semester' => 6, 'kategori' => 'Wajib Konsentrasi Telekomunikasi', 'sks' => 2],
            ['kode' => 'PTEL6336', 'nama' => 'Sistem Komunikasi Serat Optik', 'semester' => 6, 'kategori' => 'Wajib Konsentrasi Telekomunikasi', 'sks' => 2],
            ['kode' => 'PTEL6337', 'nama' => 'Praktikum Kinerja Jaringan Telekomunikasi', 'semester' => 6, 'kategori' => 'Wajib Konsentrasi Telekomunikasi', 'sks' => 1],

            // PILIHAN TELEKOMUNIKASI
            ['kode' => 'LTEL6421', 'nama' => 'Sistem Terestrial & Satelit', 'semester' => 7, 'kategori' => 'Pilihan Telekomunikasi', 'sks' => 2],
            ['kode' => 'LTEL6422', 'nama' => 'Telekomunikasi Multimedia', 'semester' => 7, 'kategori' => 'Pilihan Telekomunikasi', 'sks' => 2],
            ['kode' => 'LTEL6423', 'nama' => 'Pembelajaran Mesin', 'semester' => 7, 'kategori' => 'Pilihan Telekomunikasi', 'sks' => 2],
            ['kode' => 'LTEL6424', 'nama' => 'Pengolahan dan Analisis Sinyal', 'semester' => 7, 'kategori' => 'Pilihan Telekomunikasi', 'sks' => 3],
            ['kode' => 'LTEL6425', 'nama' => 'Jaringan Akses Nirkawat', 'semester' => 7, 'kategori' => 'Pilihan Telekomunikasi', 'sks' => 2],
            ['kode' => 'LTEL6426', 'nama' => 'Sistem Komunikasi Bergerak', 'semester' => 7, 'kategori' => 'Pilihan Telekomunikasi', 'sks' => 2],
            ['kode' => 'LTEL6427', 'nama' => 'Perancangan Sistem Komunikasi', 'semester' => 7, 'kategori' => 'Pilihan Telekomunikasi', 'sks' => 2],
            ['kode' => 'LTEL6428', 'nama' => 'Perencanaan Jaringan Telekomunikasi', 'semester' => 7, 'kategori' => 'Pilihan Telekomunikasi', 'sks' => 2],
            ['kode' => 'LTEL6429', 'nama' => 'Manajemen Jaringan Telekomunikasi', 'semester' => 7, 'kategori' => 'Pilihan Telekomunikasi', 'sks' => 2],
            ['kode' => 'LTEL6430', 'nama' => 'Perbaikan Kinerja Jaringan', 'semester' => 7, 'kategori' => 'Pilihan Telekomunikasi', 'sks' => 2],
            ['kode' => 'LTEL6431', 'nama' => 'Pengolahan Sinyal Digital', 'semester' => 7, 'kategori' => 'Pilihan Telekomunikasi', 'sks' => 2],
            ['kode' => 'LTEL6432', 'nama' => 'Pengolahan Suara Digital', 'semester' => 7, 'kategori' => 'Pilihan Telekomunikasi', 'sks' => 2],
            ['kode' => 'LTEL6433', 'nama' => 'Pengolahan Citra Digital', 'semester' => 7, 'kategori' => 'Pilihan Telekomunikasi', 'sks' => 2],
            ['kode' => 'LTEL6434', 'nama' => 'Jaringan Saraf Tiruan', 'semester' => 7, 'kategori' => 'Pilihan Telekomunikasi', 'sks' => 2],
            ['kode' => 'LTEL6435', 'nama' => 'Aplikasi Python', 'semester' => 7, 'kategori' => 'Pilihan Telekomunikasi', 'sks' => 2],
            ['kode' => 'LTEL6436', 'nama' => 'Analisis Spektral', 'semester' => 7, 'kategori' => 'Pilihan Telekomunikasi', 'sks' => 2],
            ['kode' => 'LTEL6437', 'nama' => 'Pemrograman Matlab', 'semester' => 7, 'kategori' => 'Pilihan Telekomunikasi', 'sks' => 2],

            // KONSENTRASI ELEKTRONIKA - SEMESTER 5
            ['kode' => 'PTEL6341', 'nama' => 'Elektronika Analog', 'semester' => 5, 'kategori' => 'Wajib Konsentrasi Elektronika', 'sks' => 3],
            ['kode' => 'PTEL6342', 'nama' => 'Prak. Elektronika Analog', 'semester' => 5, 'kategori' => 'Wajib Konsentrasi Elektronika', 'sks' => 1],
            ['kode' => 'PTEL6343', 'nama' => 'Divais Semikonduktor', 'semester' => 5, 'kategori' => 'Wajib Konsentrasi Elektronika', 'sks' => 3],
            ['kode' => 'PTEL6344', 'nama' => 'Optoelektronika', 'semester' => 5, 'kategori' => 'Wajib Konsentrasi Elektronika', 'sks' => 2],
            ['kode' => 'PTEL6345', 'nama' => 'Sensor dan Aktuator', 'semester' => 5, 'kategori' => 'Wajib Konsentrasi Elektronika', 'sks' => 3],

            // KONSENTRASI ELEKTRONIKA - SEMESTER 6
            ['kode' => 'PTEL6351', 'nama' => 'Pengolahan Sinyal Elektronika', 'semester' => 6, 'kategori' => 'Wajib Konsentrasi Elektronika', 'sks' => 2],
            ['kode' => 'PTEL6352', 'nama' => 'Prak. Pengolahan Sinyal Elektronika', 'semester' => 6, 'kategori' => 'Wajib Konsentrasi Elektronika', 'sks' => 1],
            ['kode' => 'PTEL6353', 'nama' => 'Perancangan Sistem Digital', 'semester' => 6, 'kategori' => 'Wajib Konsentrasi Elektronika', 'sks' => 2],
            ['kode' => 'PTEL6354', 'nama' => 'Prak. Perancangan Sistem Digital', 'semester' => 6, 'kategori' => 'Wajib Konsentrasi Elektronika', 'sks' => 1],
            ['kode' => 'PTEL6355', 'nama' => 'Teknologi Rangkaian Terintegrasi', 'semester' => 6, 'kategori' => 'Wajib Konsentrasi Elektronika', 'sks' => 3],
            ['kode' => 'PTEL6356', 'nama' => 'Elektronika RF', 'semester' => 6, 'kategori' => 'Wajib Konsentrasi Elektronika', 'sks' => 2],
            ['kode' => 'PTEL6357', 'nama' => 'Derau dalam Sistem Elektronika', 'semester' => 6, 'kategori' => 'Wajib Konsentrasi Elektronika', 'sks' => 2],

            // PILIHAN ELEKTRONIKA
            ['kode' => 'LTEL6441', 'nama' => 'Elektronika Industri dan Otomasi', 'semester' => 7, 'kategori' => 'Pilihan Elektronika', 'sks' => 3],
            ['kode' => 'LTEL6442', 'nama' => 'Elektronika Kedokteran', 'semester' => 7, 'kategori' => 'Pilihan Elektronika', 'sks' => 3],
            ['kode' => 'LTEL6443', 'nama' => 'Sistem Tertanam', 'semester' => 7, 'kategori' => 'Pilihan Elektronika', 'sks' => 3],
            ['kode' => 'LTEL6444', 'nama' => 'Perancangan sistem VLSI', 'semester' => 7, 'kategori' => 'Pilihan Elektronika', 'sks' => 3],
            ['kode' => 'LTEL6445', 'nama' => 'Perancangan berbasis FPGA', 'semester' => 7, 'kategori' => 'Pilihan Elektronika', 'sks' => 3],
            ['kode' => 'LTEL6446', 'nama' => 'Teknologi Nano', 'semester' => 7, 'kategori' => 'Pilihan Elektronika', 'sks' => 2],
            ['kode' => 'LTEL6447', 'nama' => 'Pengolahan Citra Medis', 'semester' => 7, 'kategori' => 'Pilihan Elektronika', 'sks' => 2],
            ['kode' => 'LTEL6448', 'nama' => 'Perancangan IC Analog/ Mixed Signal', 'semester' => 7, 'kategori' => 'Pilihan Elektronika', 'sks' => 2],
            ['kode' => 'LTEL6449', 'nama' => 'Sistem Cerdas', 'semester' => 7, 'kategori' => 'Pilihan Elektronika', 'sks' => 2],
            ['kode' => 'LTEL6450', 'nama' => 'Teknologi Display dan Memory', 'semester' => 7, 'kategori' => 'Pilihan Elektronika', 'sks' => 2],
            ['kode' => 'LTEL6451', 'nama' => 'Pengolahan Sinyal Digital', 'semester' => 7, 'kategori' => 'Pilihan Elektronika', 'sks' => 2],
            ['kode' => 'LTEL6452', 'nama' => 'Teknologi Lapisan tipis', 'semester' => 7, 'kategori' => 'Pilihan Elektronika', 'sks' => 2],
            ['kode' => 'LTEL6453', 'nama' => 'Mekatronika dan Robot', 'semester' => 7, 'kategori' => 'Pilihan Elektronika', 'sks' => 2],
            ['kode' => 'LTEL6454', 'nama' => 'Teknik Akuisisi Data', 'semester' => 7, 'kategori' => 'Pilihan Elektronika', 'sks' => 2],
            ['kode' => 'LTEL6455', 'nama' => 'Teknologi Mobil Listrik', 'semester' => 7, 'kategori' => 'Pilihan Elektronika', 'sks' => 2],
            ['kode' => 'LTEL6456', 'nama' => 'Teknologi Fuel Cell', 'semester' => 7, 'kategori' => 'Pilihan Elektronika', 'sks' => 2],

            // KONSENTRASI KONTROL - SEMESTER 5
            ['kode' => 'PTEL6361', 'nama' => 'Praktikum Kontrol Analog', 'semester' => 5, 'kategori' => 'Wajib Konsentrasi Kontrol', 'sks' => 1],
            ['kode' => 'PTEL6362', 'nama' => 'Sistem Kontrol Analog', 'semester' => 5, 'kategori' => 'Wajib Konsentrasi Kontrol', 'sks' => 2],
            ['kode' => 'PTEL6363', 'nama' => 'Sistem Kontrol Multivariabel', 'semester' => 5, 'kategori' => 'Wajib Konsentrasi Kontrol', 'sks' => 3],
            ['kode' => 'PTEL6364', 'nama' => 'Teknik Optimasi', 'semester' => 5, 'kategori' => 'Wajib Konsentrasi Kontrol', 'sks' => 3],
            ['kode' => 'PTEL6365', 'nama' => 'Sistem Kontrol Tertanam', 'semester' => 5, 'kategori' => 'Wajib Konsentrasi Kontrol', 'sks' => 3],

            // KONSENTRASI KONTROL - SEMESTER 6
            ['kode' => 'PTEL6371', 'nama' => 'Pemodelan dan Identifikasi Sistem', 'semester' => 6, 'kategori' => 'Wajib Konsentrasi Kontrol', 'sks' => 2],
            ['kode' => 'PTEL6372', 'nama' => 'Sistem Kontrol Digital', 'semester' => 6, 'kategori' => 'Wajib Konsentrasi Kontrol', 'sks' => 2],
            ['kode' => 'PTEL6373', 'nama' => 'Kontrol Proses Manufakturing', 'semester' => 6, 'kategori' => 'Wajib Konsentrasi Kontrol', 'sks' => 2],
            ['kode' => 'PTEL6374', 'nama' => 'Komponen Sistem Kontrol', 'semester' => 6, 'kategori' => 'Wajib Konsentrasi Kontrol', 'sks' => 3],
            ['kode' => 'PTEL6375', 'nama' => 'Menggambar Instrumentasi', 'semester' => 6, 'kategori' => 'Wajib Konsentrasi Kontrol', 'sks' => 1],
            ['kode' => 'PTEL6376', 'nama' => 'P. Pemodelan dan Identifikasi Sistem', 'semester' => 6, 'kategori' => 'Wajib Konsentrasi Kontrol', 'sks' => 1],
            ['kode' => 'PTEL6377', 'nama' => 'P. Kontrol Proses Manufakturing', 'semester' => 6, 'kategori' => 'Wajib Konsentrasi Kontrol', 'sks' => 1],
            ['kode' => 'PTEL6378', 'nama' => 'P. Kontrol Digital', 'semester' => 6, 'kategori' => 'Wajib Konsentrasi Kontrol', 'sks' => 1],

            // PILIHAN KONTROL
            ['kode' => 'LTEL6461', 'nama' => 'Teknik Kontrol Optimal', 'semester' => 7, 'kategori' => 'Pilihan Kontrol', 'sks' => 3],
            ['kode' => 'LTEL6462', 'nama' => 'Teknik Kontrol Adaptif', 'semester' => 7, 'kategori' => 'Pilihan Kontrol', 'sks' => 3],
            ['kode' => 'LTEL6463', 'nama' => 'Sistem Kontrol Cerdas', 'semester' => 7, 'kategori' => 'Pilihan Kontrol', 'sks' => 3],
            ['kode' => 'LTEL6464', 'nama' => 'Sistem Skala Besar', 'semester' => 7, 'kategori' => 'Pilihan Kontrol', 'sks' => 3],
            ['kode' => 'LTEL6465', 'nama' => 'Sistem Navigasi Inersia', 'semester' => 7, 'kategori' => 'Pilihan Kontrol', 'sks' => 2],
            ['kode' => 'LTEL6466', 'nama' => 'Mekatronika', 'semester' => 7, 'kategori' => 'Pilihan Kontrol', 'sks' => 3],
            ['kode' => 'LTEL6467', 'nama' => 'Robotika', 'semester' => 7, 'kategori' => 'Pilihan Kontrol', 'sks' => 2],
            ['kode' => 'LTEL6468', 'nama' => 'Pembelajaran Mesin', 'semester' => 7, 'kategori' => 'Pilihan Kontrol', 'sks' => 2],
            ['kode' => 'LTEL6469', 'nama' => 'Kontrol Remote dan Telemetri', 'semester' => 7, 'kategori' => 'Pilihan Kontrol', 'sks' => 3],
            ['kode' => 'LTEL6470', 'nama' => 'Kontrol Otomotif', 'semester' => 7, 'kategori' => 'Pilihan Kontrol', 'sks' => 2],
            ['kode' => 'LTEL6471', 'nama' => 'Kontrol Energi Listrik', 'semester' => 7, 'kategori' => 'Pilihan Kontrol', 'sks' => 2],
            ['kode' => 'LTEL6472', 'nama' => 'Pemrograman & Simulasi Sistem', 'semester' => 7, 'kategori' => 'Pilihan Kontrol', 'sks' => 3],
            ['kode' => 'LTEL6473', 'nama' => 'Kontrol Berbasis Model', 'semester' => 7, 'kategori' => 'Pilihan Kontrol', 'sks' => 2],
            ['kode' => 'LTEL6474', 'nama' => 'Identifikasi Sistem Lanjut', 'semester' => 7, 'kategori' => 'Pilihan Kontrol', 'sks' => 2],

            // KONSENTRASI TI - SEMESTER 5
            ['kode' => 'PTEL6381', 'nama' => 'Struktur Data', 'semester' => 5, 'kategori' => 'Wajib Konsentrasi TI', 'sks' => 3],
            ['kode' => 'PTEL6382', 'nama' => 'Organisasi dan Arsitektur Komputer', 'semester' => 5, 'kategori' => 'Wajib Konsentrasi TI', 'sks' => 3],
            ['kode' => 'PTEL6383', 'nama' => 'Basis Data', 'semester' => 5, 'kategori' => 'Wajib Konsentrasi TI', 'sks' => 2],
            ['kode' => 'PTEL6384', 'nama' => 'Prak. Basis Data', 'semester' => 5, 'kategori' => 'Wajib Konsentrasi TI', 'sks' => 1],
            ['kode' => 'PTEL6385', 'nama' => 'Jaringan dan Komunikasi Data', 'semester' => 5, 'kategori' => 'Wajib Konsentrasi TI', 'sks' => 3],

            // KONSENTRASI TI - SEMESTER 6
            ['kode' => 'PTEL6391', 'nama' => 'Jaringan Komputer', 'semester' => 6, 'kategori' => 'Wajib Konsentrasi TI', 'sks' => 2],
            ['kode' => 'PTEL6392', 'nama' => 'Prak. Jaringan Komputer', 'semester' => 6, 'kategori' => 'Wajib Konsentrasi TI', 'sks' => 1],
            ['kode' => 'PTEL6393', 'nama' => 'Rekayasa Perangkat Lunak', 'semester' => 6, 'kategori' => 'Wajib Konsentrasi TI', 'sks' => 2],
            ['kode' => 'PTEL6394', 'nama' => 'Prak. Rekayasa Perangkat Lunak', 'semester' => 6, 'kategori' => 'Wajib Konsentrasi TI', 'sks' => 1],
            ['kode' => 'PTEL6395', 'nama' => 'Sistem Operasi', 'semester' => 6, 'kategori' => 'Wajib Konsentrasi TI', 'sks' => 2],
            ['kode' => 'PTEL6396', 'nama' => 'Sistem Informasi', 'semester' => 6, 'kategori' => 'Wajib Konsentrasi TI', 'sks' => 2],
            ['kode' => 'PTEL6397', 'nama' => 'Pengembangan Web', 'semester' => 6, 'kategori' => 'Wajib Konsentrasi TI', 'sks' => 3],

            // PILIHAN TI
            ['kode' => 'LTEL6481', 'nama' => 'Kriptografi', 'semester' => 7, 'kategori' => 'Pilihan TI', 'sks' => 3],
            ['kode' => 'LTEL6482', 'nama' => 'Multimedia', 'semester' => 7, 'kategori' => 'Pilihan TI', 'sks' => 2],
            ['kode' => 'LTEL6483', 'nama' => 'Komputasi Cerdas', 'semester' => 7, 'kategori' => 'Pilihan TI', 'sks' => 2],
            ['kode' => 'LTEL6484', 'nama' => 'Komputasi Terdistribusi dan Cloud', 'semester' => 7, 'kategori' => 'Pilihan TI', 'sks' => 3],
            ['kode' => 'LTEL6485', 'nama' => 'Pengmbangan Aplks Perangkat Bergerak', 'semester' => 7, 'kategori' => 'Pilihan TI', 'sks' => 3],
            ['kode' => 'LTEL6486', 'nama' => 'Jaringan Nirkabel dan Bergerak', 'semester' => 7, 'kategori' => 'Pilihan TI', 'sks' => 2],
            ['kode' => 'LTEL6487', 'nama' => 'Keamanan Jaringan', 'semester' => 7, 'kategori' => 'Pilihan TI', 'sks' => 2],
            ['kode' => 'LTEL6488', 'nama' => 'Interface dan Periperal', 'semester' => 7, 'kategori' => 'Pilihan TI', 'sks' => 3],
            ['kode' => 'LTEL6489', 'nama' => 'Pemrograman Berorientasi Objek', 'semester' => 7, 'kategori' => 'Pilihan TI', 'sks' => 3],
            ['kode' => 'LTEL6490', 'nama' => 'Metoda Pemrograman Modern', 'semester' => 7, 'kategori' => 'Pilihan TI', 'sks' => 2],
            ['kode' => 'LTEL6491', 'nama' => 'Analisis dan Desain Sistem Informasi', 'semester' => 7, 'kategori' => 'Pilihan TI', 'sks' => 3],
            ['kode' => 'LTEL6492', 'nama' => 'Big Data dan Analitik', 'semester' => 7, 'kategori' => 'Pilihan TI', 'sks' => 2],
            ['kode' => 'LTEL6493', 'nama' => 'Perencanaan Teknologi Informasi', 'semester' => 7, 'kategori' => 'Pilihan TI', 'sks' => 2],
            ['kode' => 'LTEL6494', 'nama' => 'Sistem Pendukung Keputusan', 'semester' => 7, 'kategori' => 'Pilihan TI', 'sks' => 3],
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

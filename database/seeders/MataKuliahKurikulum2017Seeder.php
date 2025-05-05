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
            ['kode' => 'TEL21311', 'nama' => 'Fisika Mekanika dan Panas', 'semester' => 1, 'kategori' => 'Wajib', 'sks' => 2],
            ['kode' => 'TEL21312', 'nama' => 'Kalkulus', 'semester' => 1, 'kategori' => 'Wajib', 'sks' => 3],
            ['kode' => 'TEL21313', 'nama' => 'Aljabar Linear', 'semester' => 1, 'kategori' => 'Wajib', 'sks' => 2],
            ['kode' => 'TEL21314', 'nama' => 'Pengantar Teknik Elektro', 'semester' => 1, 'kategori' => 'Wajib', 'sks' => 2],
            ['kode' => 'TEL21315', 'nama' => 'Teknologi Informasi', 'semester' => 1, 'kategori' => 'Wajib', 'sks' => 2],
            ['kode' => 'UNW00001', 'nama' => 'Pendidikan Agama', 'semester' => 1, 'kategori' => 'Wajib', 'sks' => 2],
            ['kode' => 'UNW00002', 'nama' => 'Pancasila', 'semester' => 1, 'kategori' => 'Wajib', 'sks' => 2],
            ['kode' => 'UNW00004', 'nama' => 'Bahasa Indonesia', 'semester' => 1, 'kategori' => 'Wajib', 'sks' => 2],
            ['kode' => 'UNW00005', 'nama' => 'Olahraga', 'semester' => 1, 'kategori' => 'Wajib', 'sks' => 1],

            // SEMESTER 2
            ['kode' => 'UNW00006', 'nama' => 'Bahasa Inggris', 'semester' => 2, 'kategori' => 'Wajib', 'sks' => 2],
            ['kode' => 'TEL21323', 'nama' => 'Analisis Vektor', 'semester' => 2, 'kategori' => 'Wajib', 'sks' => 2],
            ['kode' => 'TEL21322', 'nama' => 'Persamaan Differensial', 'semester' => 2, 'kategori' => 'Wajib', 'sks' => 3],
            ['kode' => 'TEL21321', 'nama' => 'Fisika Listrik, Gelombang dan Cahaya', 'semester' => 2, 'kategori' => 'Wajib', 'sks' => 2],
            ['kode' => 'TEL21324', 'nama' => 'Pengantar Analisis Rangkaian', 'semester' => 2, 'kategori' => 'Wajib', 'sks' => 2],
            ['kode' => 'TEL21325', 'nama' => 'Algoritma dan Pemrograman', 'semester' => 2, 'kategori' => 'Wajib', 'sks' => 3],
            ['kode' => 'TEL21326', 'nama' => 'Prak. Pemrograman Komputer', 'semester' => 2, 'kategori' => 'Wajib', 'sks' => 1],
            ['kode' => 'TEL21327', 'nama' => 'Prak. Fisika Listrik', 'semester' => 2, 'kategori' => 'Wajib', 'sks' => 1],
            ['kode' => 'UNW00003', 'nama' => 'Kewarganegaraan', 'semester' => 2, 'kategori' => 'Wajib', 'sks' => 2],

            // SEMESTER 3
            ['kode' => 'TEL21331', 'nama' => 'Variabel Kompleks', 'semester' => 3, 'kategori' => 'Wajib', 'sks' => 3],
            ['kode' => 'TEL21332', 'nama' => 'Probabilitas dan Stokastik', 'semester' => 3, 'kategori' => 'Wajib', 'sks' => 2],
            ['kode' => 'TEL21333', 'nama' => 'Sistem Waktu Kontinu', 'semester' => 3, 'kategori' => 'Wajib', 'sks' => 3],
            ['kode' => 'TEL21334', 'nama' => 'Dasar Elektronika', 'semester' => 3, 'kategori' => 'Wajib', 'sks' => 3],
            ['kode' => 'TEL21335', 'nama' => 'Sistem Digital', 'semester' => 3, 'kategori' => 'Wajib', 'sks' => 3],
            ['kode' => 'TEL21336', 'nama' => 'Rangkaian Listrik', 'semester' => 3, 'kategori' => 'Wajib', 'sks' => 2],
            ['kode' => 'TEL21337', 'nama' => 'Prak. Rangkaian Listrik', 'semester' => 3, 'kategori' => 'Wajib', 'sks' => 1],
            ['kode' => 'TEL21338', 'nama' => 'Prak. Sistem Digital', 'semester' => 3, 'kategori' => 'Wajib', 'sks' => 1],

            // SEMESTER 4
            ['kode' => 'TEL21341', 'nama' => 'Metode dan Komputasi Numerik', 'semester' => 4, 'kategori' => 'Wajib', 'sks' => 3],
            ['kode' => 'TEL21342', 'nama' => 'Medan Elektromagnetik', 'semester' => 4, 'kategori' => 'Wajib', 'sks' => 3],
            ['kode' => 'TEL21343', 'nama' => 'Dasar Sistem Kontrol', 'semester' => 4, 'kategori' => 'Wajib', 'sks' => 3],
            ['kode' => 'TEL21344', 'nama' => 'Mikroprosesor', 'semester' => 4, 'kategori' => 'Wajib', 'sks' => 3],
            ['kode' => 'TEL21345', 'nama' => 'Sistem Pengukuran dan Instrumentasi', 'semester' => 4, 'kategori' => 'Wajib', 'sks' => 2],
            ['kode' => 'TEL21346', 'nama' => 'Sistem Waktu Diskrit', 'semester' => 4, 'kategori' => 'Wajib', 'sks' => 2],
            ['kode' => 'TEL21347', 'nama' => 'Prak. Dasar Elektronika', 'semester' => 4, 'kategori' => 'Wajib', 'sks' => 1],
            ['kode' => 'TEL21348', 'nama' => 'Prak. Dasar Sistem Kontrol', 'semester' => 4, 'kategori' => 'Wajib', 'sks' => 1],
            ['kode' => 'TEL21349', 'nama' => 'Prak. Mikroprosesor', 'semester' => 4, 'kategori' => 'Wajib', 'sks' => 1],

            // SEMESTER 5
            ['kode' => 'TEL21351', 'nama' => 'Dasar Tenaga Listrik', 'semester' => 5, 'kategori' => 'Wajib', 'sks' => 3],
            ['kode' => 'TEL21352', 'nama' => 'Dasar Sistem Telekomunikasi', 'semester' => 5, 'kategori' => 'Wajib', 'sks' => 3],
            ['kode' => 'TEL21353', 'nama' => 'Prak. Dasar Sistem Telekomunikasi', 'semester' => 5, 'kategori' => 'Wajib', 'sks' => 1],
            ['kode' => 'TEL21354', 'nama' => 'Prak. Dasar Tenaga Listrik', 'semester' => 5, 'kategori' => 'Wajib', 'sks' => 1],

            // SEMESTER 7
            ['kode' => 'TEL21370', 'nama' => 'Proposal Tugas Akhir', 'semester' => 7, 'kategori' => 'Wajib', 'sks' => 2],
            ['kode' => 'UNW00008', 'nama' => 'KKN / Magang', 'semester' => 7, 'kategori' => 'Wajib', 'sks' => 3],

            // SEMESTER 8
            ['kode' => 'TEL21382', 'nama' => 'Kuliah Kerja Lapangan', 'semester' => 8, 'kategori' => 'Wajib', 'sks' => 1],
            ['kode' => 'TEL21381', 'nama' => 'Manajemen dan Ekonomi Teknik', 'semester' => 8, 'kategori' => 'Wajib', 'sks' => 2],
            ['kode' => 'TEL21383', 'nama' => 'Kerja Praktek', 'semester' => 8, 'kategori' => 'Wajib', 'sks' => 2],
            ['kode' => 'UNW00007', 'nama' => 'Kewirausahaan', 'semester' => 8, 'kategori' => 'Wajib', 'sks' => 2],
            ['kode' => 'TEL21380', 'nama' => 'Tugas Akhir', 'semester' => 8, 'kategori' => 'Wajib', 'sks' => 4],

            // KONSENTRASI TTL - SEMESTER 5
            ['kode' => 'TEL21401', 'nama' => 'Bahan-Bahan Listrik', 'semester' => 5, 'kategori' => 'Wajib Konsentrasi TTL', 'sks' => 2],
            ['kode' => 'TEL21402', 'nama' => 'Transmisi Daya Arus Bolak Balik', 'semester' => 5, 'kategori' => 'Wajib Konsentrasi TTL', 'sks' => 2],
            ['kode' => 'TEL21403', 'nama' => 'Mesin Arus Searah dan Transformator', 'semester' => 5, 'kategori' => 'Wajib Konsentrasi TTL', 'sks' => 2],
            ['kode' => 'TEL21404', 'nama' => 'Mesin Asinkron dan Sinkron', 'semester' => 5, 'kategori' => 'Wajib Konsentrasi TTL', 'sks' => 3],
            ['kode' => 'TEL21405', 'nama' => 'Teknik dan Peralatan Tegangan Tinggi', 'semester' => 5, 'kategori' => 'Wajib Konsentrasi TTL', 'sks' => 1],
            ['kode' => 'TEL21406', 'nama' => 'Prakt. Teknik dan Peralatan Tegangan Tinggi', 'semester' => 5, 'kategori' => 'Wajib Konsentrasi TTL', 'sks' => 2],

            // KONSENTRASI TTL - SEMESTER 6
            ['kode' => 'TEL21410', 'nama' => 'Termodinamika dan Pembangkit Tenaga Listrik', 'semester' => 6, 'kategori' => 'Wajib Konsentrasi TTL', 'sks' => 2],
            ['kode' => 'TEL21411', 'nama' => 'Teknik Instalasi Listrik dan Iluminasi', 'semester' => 6, 'kategori' => 'Wajib Konsentrasi TTL', 'sks' => 2],
            ['kode' => 'TEL21412', 'nama' => 'Elektronika Daya', 'semester' => 6, 'kategori' => 'Wajib Konsentrasi TTL', 'sks' => 2],
            ['kode' => 'TEL21413', 'nama' => 'Keamanan dan Keselamatan Kerja', 'semester' => 6, 'kategori' => 'Wajib Konsentrasi TTL', 'sks' => 1],
            ['kode' => 'TEL21414', 'nama' => 'Prakt. Mesin Listrik', 'semester' => 6, 'kategori' => 'Wajib Konsentrasi TTL', 'sks' => 1],
            ['kode' => 'TEL21415', 'nama' => 'Prakt. Teknik Instalasi Listrik dan Iluminasi', 'semester' => 6, 'kategori' => 'Wajib Konsentrasi TTL', 'sks' => 1],
            ['kode' => 'TEL21416', 'nama' => 'Prakt. Elektronika Daya', 'semester' => 6, 'kategori' => 'Wajib Konsentrasi TTL', 'sks' => 3],
            ['kode' => 'TEL21417', 'nama' => 'Analisis Sistem Tenaga dan Sistem Pembumian', 'semester' => 6, 'kategori' => 'Wajib Konsentrasi TTL', 'sks' => 2],
            ['kode' => 'TEL21418', 'nama' => 'Konservasi dan Manajemen Energi Listrik', 'semester' => 6, 'kategori' => 'Wajib Konsentrasi TTL', 'sks' => 2],
            ['kode' => 'TEL21419', 'nama' => 'Pemrograman dalam Sistem Tenaga', 'semester' => 6, 'kategori' => 'Wajib Konsentrasi TTL', 'sks' => 2],
            ['kode' => 'TEL21420', 'nama' => 'Desain Sistem Distribusi Tenaga Listrik', 'semester' => 6, 'kategori' => 'Wajib Konsentrasi TTL', 'sks' => 2],

            // PILIHAN TTL - SEMESTER 7
            ['kode' => 'TEL21437', 'nama' => 'Proteksi Tenaga Listrik', 'semester' => 7, 'kategori' => 'Pilihan TTL', 'sks' => 1],
            ['kode' => 'TEL21438', 'nama' => 'Prakt. Proteksi Tenaga Listrik', 'semester' => 7, 'kategori' => 'Pilihan TTL', 'sks' => 2],
            ['kode' => 'TEL21439', 'nama' => 'Stabilitas dan Keandalan Tenaga Listrik', 'semester' => 7, 'kategori' => 'Pilihan TTL', 'sks' => 2],
            ['kode' => 'TEL21421', 'nama' => 'Kualitas Tenaga Listrik', 'semester' => 7, 'kategori' => 'Pilihan TTL', 'sks' => 2],
            ['kode' => 'TEL21422', 'nama' => 'Perancangan Saluran dan Gardu Induk', 'semester' => 7, 'kategori' => 'Pilihan TTL', 'sks' => 2],
            ['kode' => 'TEL21423', 'nama' => 'Perancangan Mesin Listrik', 'semester' => 7, 'kategori' => 'Pilihan TTL', 'sks' => 2],
            ['kode' => 'TEL21424', 'nama' => 'Perancangan Transmisi Daya Arus Searah', 'semester' => 7, 'kategori' => 'Pilihan TTL', 'sks' => 2],
            ['kode' => 'TEL21425', 'nama' => 'Perancangan Pembangkit Energi Baru & Terbaharukan', 'semester' => 7, 'kategori' => 'Pilihan TTL', 'sks' => 2],
            ['kode' => 'TEL21426', 'nama' => 'Perancangan Pembangkit Tenaga Listrik', 'semester' => 7, 'kategori' => 'Pilihan TTL', 'sks' => 2],
            ['kode' => 'TEL21427', 'nama' => 'Penggunaan Mesin dan Pengemudian Motor Listrik', 'semester' => 7, 'kategori' => 'Pilihan TTL', 'sks' => 2],
            ['kode' => 'TEL21428', 'nama' => 'Optimasi dan Operasi Tenaga Listrik', 'semester' => 7, 'kategori' => 'Pilihan TTL', 'sks' => 2],
            ['kode' => 'TEL21429', 'nama' => 'Gelombang Berjalan dan Proteksi Petir', 'semester' => 7, 'kategori' => 'Pilihan TTL', 'sks' => 2],
            ['kode' => 'TEL21430', 'nama' => 'Metoda Prakiraan Beban & Tarif Listrik', 'semester' => 7, 'kategori' => 'Pilihan TTL', 'sks' => 2],
            ['kode' => 'TEL21431', 'nama' => 'Perancangan Traksi dan Transportasi Listrik', 'semester' => 7, 'kategori' => 'Pilihan TTL', 'sks' => 2],
            ['kode' => 'TEL21432', 'nama' => 'Perancangan Kabel Tenaga Listrik', 'semester' => 7, 'kategori' => 'Pilihan TTL', 'sks' => 2],
            ['kode' => 'TEL21433', 'nama' => 'Perancangan Aplikasi Tegangan Tinggi', 'semester' => 7, 'kategori' => 'Pilihan TTL', 'sks' => 2],
            ['kode' => 'TEL21434', 'nama' => 'Aplikasi Kecerdasan Buatan dalam Tenaga Listrik', 'semester' => 7, 'kategori' => 'Pilihan TTL', 'sks' => 2],
            ['kode' => 'TEL21435', 'nama' => 'Desain Rangkaian Kontrol Elektronika Daya', 'semester' => 7, 'kategori' => 'Pilihan TTL', 'sks' => 2],
            ['kode' => 'TEL21436', 'nama' => 'Desain Isolator Tenaga', 'semester' => 7, 'kategori' => 'Pilihan TTL', 'sks' => 2],

            // KONSENTRASI TELEKOMUNIKASI - SEMESTER 5
            ['kode' => 'TEL21441', 'nama' => 'Saluran Transmisi', 'semester' => 5, 'kategori' => 'Wajib Konsentrasi Telekomunikasi', 'sks' => 2],
            ['kode' => 'TEL21442', 'nama' => 'Jaringan Telekomunikasi', 'semester' => 5, 'kategori' => 'Wajib Konsentrasi Telekomunikasi', 'sks' => 2],
            ['kode' => 'TEL21443', 'nama' => 'Pengenalan Pola', 'semester' => 5, 'kategori' => 'Wajib Konsentrasi Telekomunikasi', 'sks' => 2],
            ['kode' => 'TEL21444', 'nama' => 'Elektronika Telekomunikasi', 'semester' => 5, 'kategori' => 'Wajib Konsentrasi Telekomunikasi', 'sks' => 2],
            ['kode' => 'TEL21445', 'nama' => 'Praktikum Telekomunikasi 1', 'semester' => 5, 'kategori' => 'Wajib Konsentrasi Telekomunikasi', 'sks' => 1],
            ['kode' => 'TEL21446', 'nama' => 'Teori Informasi dan Pengkodean', 'semester' => 5, 'kategori' => 'Wajib Konsentrasi Telekomunikasi', 'sks' => 3],

            // KONSENTRASI TELEKOMUNIKASI - SEMESTER 6
            ['kode' => 'TEL21457', 'nama' => 'Praktikum Telekomunikasi 2', 'semester' => 6, 'kategori' => 'Wajib Konsentrasi Telekomunikasi', 'sks' => 1],
            ['kode' => 'TEL21458', 'nama' => 'Praktikum Telekomunikasi 3', 'semester' => 6, 'kategori' => 'Wajib Konsentrasi Telekomunikasi', 'sks' => 1],
            ['kode' => 'TEL21450', 'nama' => 'Teori & Perancangan Antena', 'semester' => 6, 'kategori' => 'Wajib Konsentrasi Telekomunikasi', 'sks' => 2],
            ['kode' => 'TEL21451', 'nama' => 'Sistem Komunikasi Digital', 'semester' => 6, 'kategori' => 'Wajib Konsentrasi Telekomunikasi', 'sks' => 3],
            ['kode' => 'TEL21452', 'nama' => 'Sistem Terestrial & Satelit', 'semester' => 6, 'kategori' => 'Wajib Konsentrasi Telekomunikasi', 'sks' => 2],
            ['kode' => 'TEL21453', 'nama' => 'Sistem Komunikasi Bergerak', 'semester' => 6, 'kategori' => 'Wajib Konsentrasi Telekomunikasi', 'sks' => 2],
            ['kode' => 'TEL21454', 'nama' => 'Komunikasi Data', 'semester' => 6, 'kategori' => 'Wajib Konsentrasi Telekomunikasi', 'sks' => 2],
            ['kode' => 'TEL21455', 'nama' => 'Trafik & Kinerja Jaringan Telekomunikasi', 'semester' => 6, 'kategori' => 'Wajib Konsentrasi Telekomunikasi', 'sks' => 3],
            ['kode' => 'TEL21456', 'nama' => 'Sistem Komunikasi Serat Optik', 'semester' => 6, 'kategori' => 'Wajib Konsentrasi Telekomunikasi', 'sks' => 2],
            ['kode' => 'TEL21459', 'nama' => 'Telekomunikasi Multimedia', 'semester' => 6, 'kategori' => 'Wajib Konsentrasi Telekomunikasi', 'sks' => 2],

            // PILIHAN TELEKOMUNIKASI - SEMESTER 7
            ['kode' => 'TEL21460', 'nama' => 'Kecerdasan Buatan', 'semester' => 7, 'kategori' => 'Pilihan Telekomunikasi', 'sks' => 3],
            ['kode' => 'TEL21461', 'nama' => 'Pengolahan dan Analisis Sinyal', 'semester' => 7, 'kategori' => 'Pilihan Telekomunikasi', 'sks' => 3],
            ['kode' => 'TEL21462', 'nama' => 'Jaringan Akses Nirkawat', 'semester' => 7, 'kategori' => 'Pilihan Telekomunikasi', 'sks' => 3],
            ['kode' => 'TEL21463', 'nama' => 'Komunikasi Nirkawat (Lanjut)', 'semester' => 7, 'kategori' => 'Pilihan Telekomunikasi', 'sks' => 3],
            ['kode' => 'TEL21464', 'nama' => 'Perancangan Sistem Komunikasi', 'semester' => 7, 'kategori' => 'Pilihan Telekomunikasi', 'sks' => 3],
            ['kode' => 'TEL21465', 'nama' => 'Perencanaan Jaringan Telekomunikasi', 'semester' => 7, 'kategori' => 'Pilihan Telekomunikasi', 'sks' => 3],
            ['kode' => 'TEL21466', 'nama' => 'Manajemen Jaringan Telekomunikasi', 'semester' => 7, 'kategori' => 'Pilihan Telekomunikasi', 'sks' => 3],
            ['kode' => 'TEL21467', 'nama' => 'Perbaikan Kinerja Jaringan', 'semester' => 7, 'kategori' => 'Pilihan Telekomunikasi', 'sks' => 3],
            ['kode' => 'TEL21468', 'nama' => 'Pengolahan Sinyal Digital', 'semester' => 7, 'kategori' => 'Pilihan Telekomunikasi', 'sks' => 3],
            ['kode' => 'TEL21469', 'nama' => 'Pengolahan Suara Digital', 'semester' => 7, 'kategori' => 'Pilihan Telekomunikasi', 'sks' => 3],
            ['kode' => 'TEL21470', 'nama' => 'Pengolahan Citra Digital', 'semester' => 7, 'kategori' => 'Pilihan Telekomunikasi', 'sks' => 3],

            // KONSENTRASI ELEKTRONIKA - SEMESTER 5
            ['kode' => 'TEL21481', 'nama' => 'Elektronika Analog', 'semester' => 5, 'kategori' => 'Wajib Konsentrasi Elektronika', 'sks' => 3],
            ['kode' => 'TEL21482', 'nama' => 'Material Elektronika', 'semester' => 5, 'kategori' => 'Wajib Konsentrasi Elektronika', 'sks' => 3],
            ['kode' => 'TEL21483', 'nama' => 'Optoelektronika', 'semester' => 5, 'kategori' => 'Wajib Konsentrasi Elektronika', 'sks' => 2],
            ['kode' => 'TEL21484', 'nama' => 'Sensor dan Aktuator', 'semester' => 5, 'kategori' => 'Wajib Konsentrasi Elektronika', 'sks' => 3],
            ['kode' => 'TEL21485', 'nama' => 'Praktikum Elektronika Analog', 'semester' => 5, 'kategori' => 'Wajib Konsentrasi Elektronika', 'sks' => 1],

            // KONSENTRASI ELEKTRONIKA - SEMESTER 6
            ['kode' => 'TEL21491', 'nama' => 'Derau dalam Sistem Elektronika', 'semester' => 6, 'kategori' => 'Wajib Konsentrasi Elektronika', 'sks' => 3],
            ['kode' => 'TEL21492', 'nama' => 'Pengolahan Sinyal Elektronik', 'semester' => 6, 'kategori' => 'Wajib Konsentrasi Elektronika', 'sks' => 3],
            ['kode' => 'TEL21493', 'nama' => 'Perancangan Sistem Digital', 'semester' => 6, 'kategori' => 'Wajib Konsentrasi Elektronika', 'sks' => 3],
            ['kode' => 'TEL21494', 'nama' => 'Teknologi IC', 'semester' => 6, 'kategori' => 'Wajib Konsentrasi Elektronika', 'sks' => 3],
            ['kode' => 'TEL21495', 'nama' => 'Elektronika RF', 'semester' => 6, 'kategori' => 'Wajib Konsentrasi Elektronika', 'sks' => 3],
            ['kode' => 'TEL21496', 'nama' => 'VLSI', 'semester' => 6, 'kategori' => 'Wajib Konsentrasi Elektronika', 'sks' => 3],
            ['kode' => 'TEL21497', 'nama' => 'Praktikum Pengolahan Sinyal Elektronika', 'semester' => 6, 'kategori' => 'Wajib Konsentrasi Elektronika', 'sks' => 1],
            ['kode' => 'TEL21498', 'nama' => 'Praktikum Perancangan Sistem Digital', 'semester' => 6, 'kategori' => 'Wajib Konsentrasi Elektronika', 'sks' => 1],

            // PILIHAN ELEKTRONIKA - SEMESTER 7
            ['kode' => 'TEL21501', 'nama' => 'Sistem Cerdas', 'semester' => 7, 'kategori' => 'Pilihan Elektronika', 'sks' => 3],
            ['kode' => 'TEL21502', 'nama' => 'Nanoteknologi dan Thin Film', 'semester' => 7, 'kategori' => 'Pilihan Elektronika', 'sks' => 3],
            ['kode' => 'TEL21503', 'nama' => 'Elektronika Industri', 'semester' => 7, 'kategori' => 'Pilihan Elektronika', 'sks' => 3],
            ['kode' => 'TEL21504', 'nama' => 'Elektronika Kedokteran', 'semester' => 7, 'kategori' => 'Pilihan Elektronika', 'sks' => 3],
            ['kode' => 'TEL21505', 'nama' => 'Perancangan berbasis FPGA', 'semester' => 7, 'kategori' => 'Pilihan Elektronika', 'sks' => 3],
            ['kode' => 'TEL21507', 'nama' => 'Embedded System', 'semester' => 7, 'kategori' => 'Pilihan Elektronika', 'sks' => 3],
            ['kode' => 'TEL21508', 'nama' => 'Pengolahan Citra Medis', 'semester' => 7, 'kategori' => 'Pilihan Elektronika', 'sks' => 3],
            ['kode' => 'TEL21509', 'nama' => 'Teknologi Display dan Memory', 'semester' => 7, 'kategori' => 'Pilihan Elektronika', 'sks' => 3],
            ['kode' => 'TEL21510', 'nama' => 'Perancangan IC Analog/ Mixed Signal', 'semester' => 7, 'kategori' => 'Pilihan Elektronika', 'sks' => 3],

            // KONSENTRASI KONTROL - SEMESTER 5
            ['kode' => 'TEL21521', 'nama' => 'Praktikum Kontrol Analog', 'semester' => 5, 'kategori' => 'Wajib Konsentrasi Kontrol', 'sks' => 1],
            ['kode' => 'TEL21522', 'nama' => 'Sistem Kontrol Analog', 'semester' => 5, 'kategori' => 'Wajib Konsentrasi Kontrol', 'sks' => 2],
            ['kode' => 'TEL21523', 'nama' => 'Sistem Kontrol Multivariabel', 'semester' => 5, 'kategori' => 'Wajib Konsentrasi Kontrol', 'sks' => 3],
            ['kode' => 'TEL21524', 'nama' => 'Teknik Optimasi', 'semester' => 5, 'kategori' => 'Wajib Konsentrasi Kontrol', 'sks' => 3],
            ['kode' => 'TEL21525', 'nama' => 'Sistem Kontrol Tertanam', 'semester' => 5, 'kategori' => 'Wajib Konsentrasi Kontrol', 'sks' => 3],

            // KONSENTRASI KONTROL - SEMESTER 6
            ['kode' => 'TEL21530', 'nama' => 'Pemodelan dan Identifikasi Sistem', 'semester' => 6, 'kategori' => 'Wajib Konsentrasi Kontrol', 'sks' => 3],
            ['kode' => 'TEL21531', 'nama' => 'Sistem Kontrol Digital', 'semester' => 6, 'kategori' => 'Wajib Konsentrasi Kontrol', 'sks' => 2],
            ['kode' => 'TEL21532', 'nama' => 'Kontrol Proses Manufakturing', 'semester' => 6, 'kategori' => 'Wajib Konsentrasi Kontrol', 'sks' => 2],
            ['kode' => 'TEL21533', 'nama' => 'Komponen Sistem Kontrol', 'semester' => 6, 'kategori' => 'Wajib Konsentrasi Kontrol', 'sks' => 3],
            ['kode' => 'TEL21534', 'nama' => 'Menggambar Instrumentasi', 'semester' => 6, 'kategori' => 'Wajib Konsentrasi Kontrol', 'sks' => 1],
            ['kode' => 'TEL21535', 'nama' => 'Prak. Pemodelan dan Identifikasi Sistem', 'semester' => 6, 'kategori' => 'Wajib Konsentrasi Kontrol', 'sks' => 1],
            ['kode' => 'TEL21536', 'nama' => 'Prak. Kontrol Proses Manufakturing', 'semester' => 6, 'kategori' => 'Wajib Konsentrasi Kontrol', 'sks' => 1],
            ['kode' => 'TEL21537', 'nama' => 'Prak. Kontrol Digital', 'semester' => 6, 'kategori' => 'Wajib Konsentrasi Kontrol', 'sks' => 1],
            ['kode' => 'TEL21538', 'nama' => 'Teknik Kontrol Optimal', 'semester' => 6, 'kategori' => 'Wajib Konsentrasi Kontrol', 'sks' => 3],
            ['kode' => 'TEL21539', 'nama' => 'Teknik Kontrol Adaptif', 'semester' => 6, 'kategori' => 'Wajib Konsentrasi Kontrol', 'sks' => 3],

            // PILIHAN KONTROL - SEMESTER 7
            ['kode' => 'TEL21541', 'nama' => 'Sistem Kontrol Cerdas', 'semester' => 7, 'kategori' => 'Pilihan Kontrol', 'sks' => 3],
            ['kode' => 'TEL21542', 'nama' => 'Sistem Skala Besar', 'semester' => 7, 'kategori' => 'Pilihan Kontrol', 'sks' => 3],
            ['kode' => 'TEL21543', 'nama' => 'Sistem Navigasi Inersia', 'semester' => 7, 'kategori' => 'Pilihan Kontrol', 'sks' => 2],
            ['kode' => 'TEL21544', 'nama' => 'Mekatronika', 'semester' => 7, 'kategori' => 'Pilihan Kontrol', 'sks' => 2],
            ['kode' => 'TEL21545', 'nama' => 'Robotika', 'semester' => 7, 'kategori' => 'Pilihan Kontrol', 'sks' => 3],
            ['kode' => 'TEL21546', 'nama' => 'Pembelajaran Mesin', 'semester' => 7, 'kategori' => 'Pilihan Kontrol', 'sks' => 3],
            ['kode' => 'TEL21547', 'nama' => 'Kontrol Remote dan Telemetri', 'semester' => 7, 'kategori' => 'Pilihan Kontrol', 'sks' => 3],
            ['kode' => 'TEL21548', 'nama' => 'Kontrol Otomotif', 'semester' => 7, 'kategori' => 'Pilihan Kontrol', 'sks' => 3],
            ['kode' => 'TEL21549', 'nama' => 'Kontrol Energi Listrik', 'semester' => 7, 'kategori' => 'Pilihan Kontrol', 'sks' => 3],
            ['kode' => 'TEL21550', 'nama' => 'Pemrograman & Simulasi Sistem', 'semester' => 7, 'kategori' => 'Pilihan Kontrol', 'sks' => 3],
            ['kode' => 'TEL21551', 'nama' => 'Kontrol Berbasis Model', 'semester' => 7, 'kategori' => 'Pilihan Kontrol', 'sks' => 2],
            ['kode' => 'TEL21552', 'nama' => 'Identifikasi Sistem Lanjut', 'semester' => 7, 'kategori' => 'Pilihan Kontrol', 'sks' => 2],

            // KONSENTRASI TI - SEMESTER 5
            ['kode' => 'TEL21561', 'nama' => 'Struktur Data', 'semester' => 5, 'kategori' => 'Wajib Konsentrasi TI', 'sks' => 3],
            ['kode' => 'TEL21562', 'nama' => 'Organisasi dan Arsitektur Komputer', 'semester' => 5, 'kategori' => 'Wajib Konsentrasi TI', 'sks' => 3],
            ['kode' => 'TEL21563', 'nama' => 'Basis Data', 'semester' => 5, 'kategori' => 'Wajib Konsentrasi TI', 'sks' => 2],
            ['kode' => 'TEL21564', 'nama' => 'Praktikum Basis Data', 'semester' => 5, 'kategori' => 'Wajib Konsentrasi TI', 'sks' => 1],
            ['kode' => 'TEL21565', 'nama' => 'Jaringan dan Komunikasi Data', 'semester' => 5, 'kategori' => 'Wajib Konsentrasi TI', 'sks' => 3],

            // KONSENTRASI TI - SEMESTER 6
            ['kode' => 'TEL21570', 'nama' => 'Jaringan Komputer', 'semester' => 6, 'kategori' => 'Wajib Konsentrasi TI', 'sks' => 2],
            ['kode' => 'TEL21571', 'nama' => 'Praktikum Jaringan Komputer', 'semester' => 6, 'kategori' => 'Wajib Konsentrasi TI', 'sks' => 1],
            ['kode' => 'TEL21572', 'nama' => 'Interface dan Periperal', 'semester' => 6, 'kategori' => 'Wajib Konsentrasi TI', 'sks' => 2],
            ['kode' => 'TEL21573', 'nama' => 'Praktikum Interface dan Periperal', 'semester' => 6, 'kategori' => 'Wajib Konsentrasi TI', 'sks' => 1],
            ['kode' => 'TEL21574', 'nama' => 'Kriptografi', 'semester' => 6, 'kategori' => 'Wajib Konsentrasi TI', 'sks' => 3],
            ['kode' => 'TEL21575', 'nama' => 'Multimedia', 'semester' => 6, 'kategori' => 'Wajib Konsentrasi TI', 'sks' => 2],
            ['kode' => 'TEL21576', 'nama' => 'Sistem Operasi', 'semester' => 6, 'kategori' => 'Wajib Konsentrasi TI', 'sks' => 2],
            ['kode' => 'TEL21577', 'nama' => 'Pengembangan Web', 'semester' => 6, 'kategori' => 'Wajib Konsentrasi TI', 'sks' => 2],
            ['kode' => 'TEL21578', 'nama' => 'Sistem Informasi', 'semester' => 6, 'kategori' => 'Wajib Konsentrasi TI', 'sks' => 3],
            ['kode' => 'TEL21579', 'nama' => 'Komputasi Cerdas', 'semester' => 6, 'kategori' => 'Wajib Konsentrasi TI', 'sks' => 2],

            // PILIHAN TI - SEMESTER 7
            ['kode' => 'TEL21580', 'nama' => 'Komputasi Terdistribusi dan Cloud', 'semester' => 7, 'kategori' => 'Pilihan TI', 'sks' => 3],
            ['kode' => 'TEL21581', 'nama' => 'Pengmbangan Aplks Perangkat Bergerak', 'semester' => 7, 'kategori' => 'Pilihan TI', 'sks' => 3],
            ['kode' => 'TEL21582', 'nama' => 'Sistem Berbasis Internet of Things', 'semester' => 7, 'kategori' => 'Pilihan TI', 'sks' => 3],
            ['kode' => 'TEL21583', 'nama' => 'Jaringan Nirkabel dan Bergerak', 'semester' => 7, 'kategori' => 'Pilihan TI', 'sks' => 2],
            ['kode' => 'TEL21584', 'nama' => 'Keamanan Jaringan', 'semester' => 7, 'kategori' => 'Pilihan TI', 'sks' => 2],
            ['kode' => 'TEL21585', 'nama' => 'Manajemen Jaringan', 'semester' => 7, 'kategori' => 'Pilihan TI', 'sks' => 2],
            ['kode' => 'TEL21586', 'nama' => 'Rekayasa Perangkat Lunak', 'semester' => 7, 'kategori' => 'Pilihan TI', 'sks' => 3],
            ['kode' => 'TEL21587', 'nama' => 'Pemrograman Berorientasi Objek', 'semester' => 7, 'kategori' => 'Pilihan TI', 'sks' => 3],
            ['kode' => 'TEL21588', 'nama' => 'Metoda Pemrograman Modern', 'semester' => 7, 'kategori' => 'Pilihan TI', 'sks' => 2],
            ['kode' => 'TEL21589', 'nama' => 'Interaksi Manusia dan Komputer', 'semester' => 7, 'kategori' => 'Pilihan TI', 'sks' => 2],
            ['kode' => 'TEL21590', 'nama' => 'Pengembangan Perangkat Lunak Berorientasi Servis', 'semester' => 7, 'kategori' => 'Pilihan TI', 'sks' => 2],
            ['kode' => 'TEL21591', 'nama' => 'Analisis dan Desain Sistem Informasi', 'semester' => 7, 'kategori' => 'Pilihan TI', 'sks' => 3],
            ['kode' => 'TEL21592', 'nama' => 'Intelijen Bisnis', 'semester' => 7, 'kategori' => 'Pilihan TI', 'sks' => 3],
            ['kode' => 'TEL21593', 'nama' => 'Sistem Berbasis Enterprise', 'semester' => 7, 'kategori' => 'Pilihan TI', 'sks' => 3],
            ['kode' => 'TEL21594', 'nama' => 'Big Data dan Analitik', 'semester' => 7, 'kategori' => 'Pilihan TI', 'sks' => 2],
            ['kode' => 'TEL21595', 'nama' => 'Perencanaan Teknologi Informasi', 'semester' => 7, 'kategori' => 'Pilihan TI', 'sks' => 2],
            ['kode' => 'TEL21596', 'nama' => 'Sistem Pendukung Keputusan', 'semester' => 7, 'kategori' => 'Pilihan TI', 'sks' => 2],
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

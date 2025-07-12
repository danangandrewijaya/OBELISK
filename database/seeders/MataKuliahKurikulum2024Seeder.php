<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Kurikulum;
use App\Models\MataKuliahKurikulum;
use App\Models\Prodi;

class MataKuliahKurikulum2024Seeder extends Seeder
{
    public function run()
    {
        // Ambil ID Kurikulum 2024 (ubah kalau nama berbeda)
        $kurikulum = Kurikulum::where('nama', 'Kurikulum 2024')->first();
        if (!$kurikulum) {
            // echo "Seeder gagal: Kurikulum 2024 tidak ditemukan!\n";
            // return;
            $kurikulum = Kurikulum::create([
                'nama' => 'Kurikulum 2024',
                'prodi_id' => Prodi::where('kode', '20201')->first()->id
            ]);
        }

        $mataKuliah = [
            // ========= SEMESTER 1 =========
            ['kode' => 'TEL1624101', 'nama' => 'Etika dan Desain Rekayasa',                                    'semester' => 1, 'kategori' => 'Wajib', 'sks' => 2],
            ['kode' => 'TEL1624102', 'nama' => 'Kimia Dasar',                                                  'semester' => 1, 'kategori' => 'Wajib', 'sks' => 2],
            ['kode' => 'TEL1624103', 'nama' => 'Fisika Mekanika dan Panas',                                    'semester' => 1, 'kategori' => 'Wajib', 'sks' => 3],
            ['kode' => 'TEL1624104', 'nama' => 'Kalkulus',                                                     'semester' => 1, 'kategori' => 'Wajib', 'sks' => 3],
            ['kode' => 'TEL1624105', 'nama' => 'Aljabar Linear',                                               'semester' => 1, 'kategori' => 'Wajib', 'sks' => 2],
            ['kode' => 'UUW1624004', 'nama' => 'Bahasa Indonesia',                                             'semester' => 1, 'kategori' => 'Wajib', 'sks' => 2],
            ['kode' => 'UUW1624005', 'nama' => 'Olahraga',                                                     'semester' => 1, 'kategori' => 'Wajib', 'sks' => 1],
            ['kode' => 'UUW1624006', 'nama' => 'Internet of Things',                                           'semester' => 1, 'kategori' => 'Wajib', 'sks' => 2],
            ['kode' => 'UUW1624107', 'nama' => 'Bahasa Inggris 1',                                             'semester' => 1, 'kategori' => 'Wajib', 'sks' => 1],
            // — variasi Agama, pilih sesuai keycloak/user di app —
            ['kode' => 'UUW1624011', 'nama' => 'Pendidikan Agama Islam',                                       'semester' => 1, 'kategori' => 'Wajib', 'sks' => 2],
            ['kode' => 'UUW1624021', 'nama' => 'Pendidikan Agama Kristen',                                     'semester' => 1, 'kategori' => 'Wajib', 'sks' => 2],
            ['kode' => 'UUW1624031', 'nama' => 'Pendidikan Agama Katolik',                                     'semester' => 1, 'kategori' => 'Wajib', 'sks' => 2],
            ['kode' => 'UUW1624041', 'nama' => 'Pendidikan Agama Hindu',                                       'semester' => 1, 'kategori' => 'Wajib', 'sks' => 2],
            ['kode' => 'UUW1624051', 'nama' => 'Pendidikan Agama Budha',                                       'semester' => 1, 'kategori' => 'Wajib', 'sks' => 2],
            ['kode' => 'UUW1624061', 'nama' => 'Pendidikan Agama Kong Hu Chu',                                 'semester' => 1, 'kategori' => 'Wajib', 'sks' => 2],
            ['kode' => 'UUW1624071', 'nama' => 'Kepercayaan Kepada Tuhan Yang Maha Esa',                       'semester' => 1, 'kategori' => 'Wajib', 'sks' => 2],

            // ========= SEMESTER 2 =========
            ['kode' => 'TEL1624201', 'nama' => 'Persamaan Differensial',                                        'semester' => 2, 'kategori' => 'Wajib', 'sks' => 2],
            ['kode' => 'TEL1624202', 'nama' => 'Analisis Vektor',                                              'semester' => 2, 'kategori' => 'Wajib', 'sks' => 2],
            ['kode' => 'TEL1624203', 'nama' => 'Variabel Kompleks',                                            'semester' => 2, 'kategori' => 'Wajib', 'sks' => 3],
            ['kode' => 'TEL1624204', 'nama' => 'Fisika Listrik, Gelombang dan Cahaya',                         'semester' => 2, 'kategori' => 'Wajib', 'sks' => 3],
            ['kode' => 'TEL1624205', 'nama' => 'Praktikum Fisika Listrik',                                     'semester' => 2, 'kategori' => 'Wajib', 'sks' => 1],
            ['kode' => 'TEL1624206', 'nama' => 'Pengantar Analisis Rangkaian',                                 'semester' => 2, 'kategori' => 'Wajib', 'sks' => 2],
            ['kode' => 'TEL1624207', 'nama' => 'Sistem Pengukuran dan Instrumentasi',                           'semester' => 2, 'kategori' => 'Wajib', 'sks' => 3],
            ['kode' => 'TEL1624208', 'nama' => 'Algoritma dan Pemrograman',                                    'semester' => 2, 'kategori' => 'Wajib', 'sks' => 2],
            ['kode' => 'TEL1624209', 'nama' => 'Praktikum Algoritma dan Pemrograman',                           'semester' => 2, 'kategori' => 'Wajib', 'sks' => 1],
            ['kode' => 'UUW1624207', 'nama' => 'Bahasa Inggris 2',                                             'semester' => 2, 'kategori' => 'Wajib', 'sks' => 1],

            // ========= SEMESTER 3 =========
            ['kode' => 'TEL1624301', 'nama' => 'Metode Numerik',                                               'semester' => 3, 'kategori' => 'Wajib', 'sks' => 2],
            ['kode' => 'TEL1624302', 'nama' => 'Matematika Diskret',                                           'semester' => 3, 'kategori' => 'Wajib', 'sks' => 3],
            ['kode' => 'TEL1624303', 'nama' => 'Sinyal dan Sistem',                                            'semester' => 3, 'kategori' => 'Wajib', 'sks' => 3],
            ['kode' => 'TEL1624304', 'nama' => 'Dasar Elektronika',                                            'semester' => 3, 'kategori' => 'Wajib', 'sks' => 2],
            ['kode' => 'TEL1624305', 'nama' => 'Probabilitas dan Statistik',                                   'semester' => 3, 'kategori' => 'Wajib', 'sks' => 3],
            ['kode' => 'TEL1624306', 'nama' => 'Sistem Digital',                                               'semester' => 3, 'kategori' => 'Wajib', 'sks' => 2],
            ['kode' => 'TEL1624307', 'nama' => 'Rangkaian Listrik',                                           'semester' => 3, 'kategori' => 'Wajib', 'sks' => 2],
            ['kode' => 'TEL1624308', 'nama' => 'Praktikum Rangkaian Listrik',                                  'semester' => 3, 'kategori' => 'Wajib', 'sks' => 1],
            ['kode' => 'TEL1624309', 'nama' => 'Praktikum Dasar Elektronika',                                  'semester' => 3, 'kategori' => 'Wajib', 'sks' => 1],
            ['kode' => 'UUW1624307', 'nama' => 'Bahasa Inggris 3',                                             'semester' => 3, 'kategori' => 'Wajib', 'sks' => 1],

            // ========= SEMESTER 4 =========
            ['kode' => 'TEL1624401', 'nama' => 'Medan Elektromagnetik',                                        'semester' => 4, 'kategori' => 'Wajib', 'sks' => 3],
            ['kode' => 'TEL1624402', 'nama' => 'Fisika Material',                                             'semester' => 4, 'kategori' => 'Wajib', 'sks' => 2],
            ['kode' => 'TEL1624403', 'nama' => 'Desain Sistem Mikroprosesor',                                  'semester' => 4, 'kategori' => 'Wajib', 'sks' => 3],
            ['kode' => 'TEL1624404', 'nama' => 'Dasar Sistem Kontrol',                                         'semester' => 4, 'kategori' => 'Wajib', 'sks' => 2],
            ['kode' => 'TEL1624405', 'nama' => 'Dasar Konversi Energi dan Tenaga Listrik',                     'semester' => 4, 'kategori' => 'Wajib', 'sks' => 2],
            ['kode' => 'TEL1624406', 'nama' => 'Dasar Sistem Telekomunikasi',                                  'semester' => 4, 'kategori' => 'Wajib', 'sks' => 2],
            ['kode' => 'TEL1624407', 'nama' => 'Praktikum Dasar Sistem Kontrol',                               'semester' => 4, 'kategori' => 'Wajib', 'sks' => 1],
            ['kode' => 'TEL1624408', 'nama' => 'Praktikum Dasar Konversi Energi & Tenaga Listrik',             'semester' => 4, 'kategori' => 'Wajib', 'sks' => 1],
            ['kode' => 'TEL1624409', 'nama' => 'Praktikum Dasar Sistem Telekomunikasi',                        'semester' => 4, 'kategori' => 'Wajib', 'sks' => 1],
            ['kode' => 'TEL1624410', 'nama' => 'Praktikum Desain Sistem Mikroprosesor',                        'semester' => 4, 'kategori' => 'Wajib', 'sks' => 1],
            ['kode' => 'UUW1624002', 'nama' => 'Pancasila',                                                    'semester' => 4, 'kategori' => 'Wajib', 'sks' => 2],

            /* ---------- SEMESTER 5 ---------- */
            // Wajib Konsentrasi TTL
            ['kode' => 'TEL1624501', 'nama' => 'Sistem Transmisi, Distribusi dan Aliran Daya Arus Bolak Balik', 'semester' => 5, 'kategori' => 'Wajib Konsentrasi TTL', 'sks' => 2],
            ['kode' => 'TEL1624502', 'nama' => 'Mesin Konversi Energi Listrik Arus Searah dan Bolak Balik',     'semester' => 5, 'kategori' => 'Wajib Konsentrasi TTL', 'sks' => 2],
            ['kode' => 'TEL1624503', 'nama' => 'Konservasi dan Audit Kualitas Energi Listrik',                  'semester' => 5, 'kategori' => 'Wajib Konsentrasi TTL', 'sks' => 2],
            ['kode' => 'TEL1624504', 'nama' => 'Teknik dan Peralatan Tegangan Tinggi',                          'semester' => 5, 'kategori' => 'Wajib Konsentrasi TTL', 'sks' => 2],
            ['kode' => 'TEL1624505', 'nama' => 'Instalasi Pemanfaatan Tenaga Listrik, Iluminasi, dan K3 Listrik','semester' => 5, 'kategori' => 'Wajib Konsentrasi TTL', 'sks' => 2],
            ['kode' => 'TEL1624506', 'nama' => 'Konverter dan Pengemudian Elektronika Daya',                    'semester' => 5, 'kategori' => 'Wajib Konsentrasi TTL', 'sks' => 2],
            ['kode' => 'TEL1624507', 'nama' => 'Analisis HS, Pembumian, Proteksi, Stabilitas & Keandalan ST',   'semester' => 5, 'kategori' => 'Wajib Konsentrasi TTL', 'sks' => 2],
            ['kode' => 'TEL1624508', 'nama' => 'Termodinamika dan Pembangkit Tenaga Listrik',                   'semester' => 5, 'kategori' => 'Wajib Konsentrasi TTL', 'sks' => 2],
            ['kode' => 'TEL1624509', 'nama' => 'Praktikum Teknik dan Peralatan Tegangan Tinggi',                'semester' => 5, 'kategori' => 'Wajib Konsentrasi TTL', 'sks' => 1],
            ['kode' => 'TEL1624510', 'nama' => 'Praktikum Instalasi Pemanfaatan Tenaga Listrik & Iluminasi',    'semester' => 5, 'kategori' => 'Wajib Konsentrasi TTL', 'sks' => 1],
            ['kode' => 'TEL1624511', 'nama' => 'Praktikum Mesin Konversi Energi Listrik DC & AC',               'semester' => 5, 'kategori' => 'Wajib Konsentrasi TTL', 'sks' => 1],
            ['kode' => 'TEL1624512', 'nama' => 'Praktikum Konverter & Pengemudian Elektronika Daya',            'semester' => 5, 'kategori' => 'Wajib Konsentrasi TTL', 'sks' => 1],

            // Wajib Konsentrasi Telekomunikasi
            ['kode' => 'TEL1624521', 'nama' => 'Pengkodean Sumber',                     'semester' => 5, 'kategori' => 'Wajib Konsentrasi Telekomunikasi', 'sks' => 2],
            ['kode' => 'TEL1624522', 'nama' => 'Sistem Komunikasi Digital',            'semester' => 5, 'kategori' => 'Wajib Konsentrasi Telekomunikasi', 'sks' => 3],
            ['kode' => 'TEL1624523', 'nama' => 'Antena',                               'semester' => 5, 'kategori' => 'Wajib Konsentrasi Telekomunikasi', 'sks' => 3],
            ['kode' => 'TEL1624524', 'nama' => 'Sistem Komunikasi Nirkabel',           'semester' => 5, 'kategori' => 'Wajib Konsentrasi Telekomunikasi', 'sks' => 2],
            ['kode' => 'TEL1624525', 'nama' => 'Jaringan Telekomunikasi',              'semester' => 5, 'kategori' => 'Wajib Konsentrasi Telekomunikasi', 'sks' => 3],
            ['kode' => 'TEL1624526', 'nama' => 'Rekayasa Trafik',                      'semester' => 5, 'kategori' => 'Wajib Konsentrasi Telekomunikasi', 'sks' => 2],
            ['kode' => 'TEL1624527', 'nama' => 'Praktikum Modulasi Digital',           'semester' => 5, 'kategori' => 'Wajib Konsentrasi Telekomunikasi', 'sks' => 1],
            ['kode' => 'TEL1624528', 'nama' => 'Praktikum Jaringan Telekomunikasi',    'semester' => 5, 'kategori' => 'Wajib Konsentrasi Telekomunikasi', 'sks' => 1],
            ['kode' => 'TEL1624529', 'nama' => 'Praktikum Antena & Elektronika Telkom', 'semester' => 5, 'kategori' => 'Wajib Konsentrasi Telekomunikasi', 'sks' => 1],
            ['kode' => 'TEL1624530', 'nama' => 'Elektronika Telekomunikasi',           'semester' => 5, 'kategori' => 'Wajib Konsentrasi Telekomunikasi', 'sks' => 2],

            // Wajib Konsentrasi Elektronika
            ['kode' => 'TEL1624541', 'nama' => 'Elektronika Analog',                       'semester' => 5, 'kategori' => 'Wajib Konsentrasi Elektronika', 'sks' => 3],
            ['kode' => 'TEL1624542', 'nama' => 'Divais Semikonduktor',                     'semester' => 5, 'kategori' => 'Wajib Konsentrasi Elektronika', 'sks' => 2],
            ['kode' => 'TEL1624543', 'nama' => 'Elektronika RF',                           'semester' => 5, 'kategori' => 'Wajib Konsentrasi Elektronika', 'sks' => 2],
            ['kode' => 'TEL1624544', 'nama' => 'Sensor dan Aktuator',                      'semester' => 5, 'kategori' => 'Wajib Konsentrasi Elektronika', 'sks' => 2],
            ['kode' => 'TEL1624545', 'nama' => 'Perancangan Sistem Digital',              'semester' => 5, 'kategori' => 'Wajib Konsentrasi Elektronika', 'sks' => 2],
            ['kode' => 'TEL1624546', 'nama' => 'Teknologi Rangkaian Terintegrasi',        'semester' => 5, 'kategori' => 'Wajib Konsentrasi Elektronika', 'sks' => 2],
            ['kode' => 'TEL1624547', 'nama' => 'Derau dalam Sistem Elektronika',          'semester' => 5, 'kategori' => 'Wajib Konsentrasi Elektronika', 'sks' => 2],
            ['kode' => 'TEL1624548', 'nama' => 'Protokol Komunikasi Perangkat',           'semester' => 5, 'kategori' => 'Wajib Konsentrasi Elektronika', 'sks' => 2],
            ['kode' => 'TEL1624549', 'nama' => 'Praktikum Protokol Komunikasi Perangkat', 'semester' => 5, 'kategori' => 'Wajib Konsentrasi Elektronika', 'sks' => 1],
            ['kode' => 'TEL1624550', 'nama' => 'Praktikum Elektronika Analog',            'semester' => 5, 'kategori' => 'Wajib Konsentrasi Elektronika', 'sks' => 1],
            ['kode' => 'TEL1624551', 'nama' => 'Praktikum Perancangan Sistem Digital',    'semester' => 5, 'kategori' => 'Wajib Konsentrasi Elektronika', 'sks' => 1],

            // Wajib Konsentrasi Kontrol
            ['kode' => 'TEL1624561', 'nama' => 'Kontrol Proses Manufakturing',       'semester' => 5, 'kategori' => 'Wajib Konsentrasi Kontrol', 'sks' => 2],
            ['kode' => 'TEL1624562', 'nama' => 'Pemodelan dan Identifikasi Sistem',  'semester' => 5, 'kategori' => 'Wajib Konsentrasi Kontrol', 'sks' => 2],
            ['kode' => 'TEL1624563', 'nama' => 'Sistem Kontrol Digital',            'semester' => 5, 'kategori' => 'Wajib Konsentrasi Kontrol', 'sks' => 2],
            ['kode' => 'TEL1624564', 'nama' => 'Komponen Sistem Kontrol',           'semester' => 5, 'kategori' => 'Wajib Konsentrasi Kontrol', 'sks' => 2],
            ['kode' => 'TEL1624565', 'nama' => 'Sistem Kontrol Analog',             'semester' => 5, 'kategori' => 'Wajib Konsentrasi Kontrol', 'sks' => 2],
            ['kode' => 'TEL1624566', 'nama' => 'Sistem Kontrol Multivariabel',     'semester' => 5, 'kategori' => 'Wajib Konsentrasi Kontrol', 'sks' => 3],
            ['kode' => 'TEL1624567', 'nama' => 'Teknik Optimasi',                   'semester' => 5, 'kategori' => 'Wajib Konsentrasi Kontrol', 'sks' => 2],
            ['kode' => 'TEL1624568', 'nama' => 'Sistem Kontrol Tertanam',           'semester' => 5, 'kategori' => 'Wajib Konsentrasi Kontrol', 'sks' => 2],
            ['kode' => 'TEL1624569', 'nama' => 'Praktikum Kontrol Digital',         'semester' => 5, 'kategori' => 'Wajib Konsentrasi Kontrol', 'sks' => 1],
            ['kode' => 'TEL1624570', 'nama' => 'Praktikum Kontrol Analog',          'semester' => 5, 'kategori' => 'Wajib Konsentrasi Kontrol', 'sks' => 1],
            ['kode' => 'TEL1624571', 'nama' => 'Praktikum Kontrol Proses Manufakturing','semester' => 5, 'kategori' => 'Wajib Konsentrasi Kontrol', 'sks' => 1],

            // Wajib Konsentrasi TI
            ['kode' => 'TEL1624581', 'nama' => 'Jaringan Komputer',                       'semester' => 5, 'kategori' => 'Wajib Konsentrasi TI', 'sks' => 3],
            ['kode' => 'TEL1624582', 'nama' => 'Praktikum Jaringan Komputer',            'semester' => 5, 'kategori' => 'Wajib Konsentrasi TI', 'sks' => 1],
            ['kode' => 'TEL1624583', 'nama' => 'Rekayasa Perangkat Lunak',               'semester' => 5, 'kategori' => 'Wajib Konsentrasi TI', 'sks' => 2],
            ['kode' => 'TEL1624584', 'nama' => 'Praktikum Rekayasa Perangkat Lunak',     'semester' => 5, 'kategori' => 'Wajib Konsentrasi TI', 'sks' => 1],
            ['kode' => 'TEL1624585', 'nama' => 'Sistem Operasi',                         'semester' => 5, 'kategori' => 'Wajib Konsentrasi TI', 'sks' => 2],
            ['kode' => 'TEL1624586', 'nama' => 'Sistem Informasi',                       'semester' => 5, 'kategori' => 'Wajib Konsentrasi TI', 'sks' => 2],
            ['kode' => 'TEL1624587', 'nama' => 'Pengembangan Web',                       'semester' => 5, 'kategori' => 'Wajib Konsentrasi TI', 'sks' => 2],
            ['kode' => 'TEL1624588', 'nama' => 'Struktur Data',                          'semester' => 5, 'kategori' => 'Wajib Konsentrasi TI', 'sks' => 2],
            ['kode' => 'TEL1624589', 'nama' => 'Organisasi dan Arsitektur Komputer',     'semester' => 5, 'kategori' => 'Wajib Konsentrasi TI', 'sks' => 2],
            ['kode' => 'TEL1624590', 'nama' => 'Basis Data',                             'semester' => 5, 'kategori' => 'Wajib Konsentrasi TI', 'sks' => 2],
            ['kode' => 'TEL1624591', 'nama' => 'Praktikum Basis Data',                   'semester' => 5, 'kategori' => 'Wajib Konsentrasi TI', 'sks' => 1],

            /* ---------- SEMESTER 6 ---------- */
            // Wajib umum
            ['kode' => 'TEL1624600', 'nama' => 'Kerja Praktik',                                   'semester' => 6, 'kategori' => 'Wajib', 'sks' => 4],
            ['kode' => 'UUW1624008', 'nama' => 'Kewirausahaan',                                   'semester' => 6, 'kategori' => 'Wajib', 'sks' => 2],

            // Pilihan TTL
            ['kode' => 'TEL1624601', 'nama' => 'Rancangan Pemrograman Sistem Tenaga',              'semester' => 6, 'kategori' => 'Pilihan TTL', 'sks' => 2],
            ['kode' => 'TEL1624602', 'nama' => 'Desain Sistem dan Saluran Distribusi TL',          'semester' => 6, 'kategori' => 'Pilihan TTL', 'sks' => 2],
            ['kode' => 'TEL1624603', 'nama' => 'Perancangan Pembangkit Tenaga Listrik',            'semester' => 6, 'kategori' => 'Pilihan TTL', 'sks' => 2],
            ['kode' => 'TEL1624604', 'nama' => 'Perancangan Pembangkit Energi Baru & Terbarukan',  'semester' => 6, 'kategori' => 'Pilihan TTL', 'sks' => 2],
            ['kode' => 'TEL1624605', 'nama' => 'Rancangan Prakiraan Beban dan Tarif Listrik',      'semester' => 6, 'kategori' => 'Pilihan TTL', 'sks' => 2],
            ['kode' => 'TEL1624606', 'nama' => 'Desain Optimasi Pembangkitan & Operasi TL',        'semester' => 6, 'kategori' => 'Pilihan TTL', 'sks' => 2],
            ['kode' => 'TEL1624607', 'nama' => 'Desain Aplikasi Kecerdasan Buatan dalam TL',       'semester' => 6, 'kategori' => 'Pilihan TTL', 'sks' => 2],
            ['kode' => 'TEL1624608', 'nama' => 'Perancangan Saluran Transmisi dan Gardu Induk',    'semester' => 6, 'kategori' => 'Pilihan TTL', 'sks' => 2],
            ['kode' => 'TEL1624609', 'nama' => 'Desain Sistem Proteksi Petir',                     'semester' => 6, 'kategori' => 'Pilihan TTL', 'sks' => 2],
            ['kode' => 'TEL1624610', 'nama' => 'Perancangan Transformator & Mesin‑Mesin Listrik',  'semester' => 6, 'kategori' => 'Pilihan TTL', 'sks' => 2],
            ['kode' => 'TEL1624611', 'nama' => 'Rancangan Pengemudian Traksi & Transportasi TL',   'semester' => 6, 'kategori' => 'Pilihan TTL', 'sks' => 2],
            ['kode' => 'TEL1624612', 'nama' => 'Perancangan Sistem Transmisi Daya Arus Searah',    'semester' => 6, 'kategori' => 'Pilihan TTL', 'sks' => 2],
            ['kode' => 'TEL1624613', 'nama' => 'Desain Isolator Tenaga',                           'semester' => 6, 'kategori' => 'Pilihan TTL', 'sks' => 2],
            ['kode' => 'TEL1624614', 'nama' => 'Perancangan Konverter & Aplikasi Tegangan Tinggi', 'semester' => 6, 'kategori' => 'Pilihan TTL', 'sks' => 2],
            ['kode' => 'TEL1624615', 'nama' => 'Computer‑Aided Design dalam Tenaga Listrik',       'semester' => 6, 'kategori' => 'Pilihan TTL', 'sks' => 2],

            // Pilihan Telekomunikasi
            ['kode' => 'TEL1624621', 'nama' => 'Pengolahan Sinyal Digital',                        'semester' => 6, 'kategori' => 'Pilihan Telekomunikasi', 'sks' => 2],
            ['kode' => 'TEL1624622', 'nama' => 'Kecerdasan Buatan',                                'semester' => 6, 'kategori' => 'Pilihan Telekomunikasi', 'sks' => 2],
            ['kode' => 'TEL1624623', 'nama' => 'Jaringan Saraf Tiruan',                            'semester' => 6, 'kategori' => 'Pilihan Telekomunikasi', 'sks' => 2],
            ['kode' => 'TEL1624624', 'nama' => 'Pengolahan Citra Digital',                         'semester' => 6, 'kategori' => 'Pilihan Telekomunikasi', 'sks' => 2],
            ['kode' => 'TEL1624625', 'nama' => 'Pengolahan Suara Digital',                         'semester' => 6, 'kategori' => 'Pilihan Telekomunikasi', 'sks' => 2],
            ['kode' => 'TEL1624626', 'nama' => 'Sistem Komunikasi Satelit',                        'semester' => 6, 'kategori' => 'Pilihan Telekomunikasi', 'sks' => 2],
            ['kode' => 'TEL1624627', 'nama' => 'Sistem Komunikasi Bergerak',                       'semester' => 6, 'kategori' => 'Pilihan Telekomunikasi', 'sks' => 2],
            ['kode' => 'TEL1624628', 'nama' => 'Sistem Komunikasi Optik',                          'semester' => 6, 'kategori' => 'Pilihan Telekomunikasi', 'sks' => 2],
            ['kode' => 'TEL1624629', 'nama' => 'Sistem Komunikasi Modern',                         'semester' => 6, 'kategori' => 'Pilihan Telekomunikasi', 'sks' => 2],
            ['kode' => 'TEL1624630', 'nama' => 'Sistem Komunikasi Nirkabel Kecepatan Tinggi',      'semester' => 6, 'kategori' => 'Pilihan Telekomunikasi', 'sks' => 2],
            ['kode' => 'TEL1624631', 'nama' => 'Perencanaan Jaringan Telekomunikasi',              'semester' => 6, 'kategori' => 'Pilihan Telekomunikasi', 'sks' => 2],
            ['kode' => 'TEL1624632', 'nama' => 'Manajemen Jaringan Telekomunikasi',                'semester' => 6, 'kategori' => 'Pilihan Telekomunikasi', 'sks' => 2],
            ['kode' => 'TEL1624633', 'nama' => 'Jaringan Sensor Nirkabel',                         'semester' => 6, 'kategori' => 'Pilihan Telekomunikasi', 'sks' => 2],
            ['kode' => 'TEL1624634', 'nama' => 'Pemrograman Python',                               'semester' => 6, 'kategori' => 'Pilihan Telekomunikasi', 'sks' => 2],
            ['kode' => 'TEL1624635', 'nama' => 'Perancangan Antena Modern',                        'semester' => 6, 'kategori' => 'Pilihan Telekomunikasi', 'sks' => 2],

            // Pilihan Elektronika
            ['kode' => 'TEL1624641', 'nama' => 'Elektronika Industri dan Otomasi',        'semester' => 6, 'kategori' => 'Pilihan Elektronika', 'sks' => 2],
            ['kode' => 'TEL1624642', 'nama' => 'Elektronika Kedokteran',                  'semester' => 6, 'kategori' => 'Pilihan Elektronika', 'sks' => 2],
            ['kode' => 'TEL1624643', 'nama' => 'Sistem Tertanam',                         'semester' => 6, 'kategori' => 'Pilihan Elektronika', 'sks' => 2],
            ['kode' => 'TEL1624644', 'nama' => 'Perancangan sistem VLSI',                'semester' => 6, 'kategori' => 'Pilihan Elektronika', 'sks' => 2],
            ['kode' => 'TEL1624645', 'nama' => 'Perancangan Sistem berbasis FPGA',       'semester' => 6, 'kategori' => 'Pilihan Elektronika', 'sks' => 2],
            ['kode' => 'TEL1624646', 'nama' => 'Teknologi Nano',                          'semester' => 6, 'kategori' => 'Pilihan Elektronika', 'sks' => 2],
            ['kode' => 'TEL1624647', 'nama' => 'Pengolahan Citra Medis',                  'semester' => 6, 'kategori' => 'Pilihan Elektronika', 'sks' => 2],
            ['kode' => 'TEL1624648', 'nama' => 'Perancangan IC Analog/ Mixed Signal',     'semester' => 6, 'kategori' => 'Pilihan Elektronika', 'sks' => 2],
            ['kode' => 'TEL1624649', 'nama' => 'Sistem Cerdas',                           'semester' => 6, 'kategori' => 'Pilihan Elektronika', 'sks' => 2],
            ['kode' => 'TEL1624650', 'nama' => 'Teknologi Display dan Memory',            'semester' => 6, 'kategori' => 'Pilihan Elektronika', 'sks' => 2],
            ['kode' => 'TEL1624651', 'nama' => 'Pengolahan Sinyal Digital',               'semester' => 6, 'kategori' => 'Pilihan Elektronika', 'sks' => 2],
            ['kode' => 'TEL1624652', 'nama' => 'Teknologi Lapisan Tipis',                 'semester' => 6, 'kategori' => 'Pilihan Elektronika', 'sks' => 2],
            ['kode' => 'TEL1624653', 'nama' => 'Mekatronika dan Robot',                   'semester' => 6, 'kategori' => 'Pilihan Elektronika', 'sks' => 2],
            ['kode' => 'TEL1624654', 'nama' => 'Teknik Akuisisi Data',                    'semester' => 6, 'kategori' => 'Pilihan Elektronika', 'sks' => 2],
            ['kode' => 'TEL1624655', 'nama' => 'Teknologi Mobil Listrik',                 'semester' => 6, 'kategori' => 'Pilihan Elektronika', 'sks' => 2],
            ['kode' => 'TEL1624656', 'nama' => 'Teknologi Fuel Cell',                     'semester' => 6, 'kategori' => 'Pilihan Elektronika', 'sks' => 2],
            ['kode' => 'TEL1624657', 'nama' => 'Optoelektronika',                         'semester' => 6, 'kategori' => 'Pilihan Elektronika', 'sks' => 2],

            // Pilihan Kontrol
            ['kode' => 'TEL1624661', 'nama' => 'Teknik Kontrol Optimal',               'semester' => 6, 'kategori' => 'Pilihan Kontrol', 'sks' => 2],
            ['kode' => 'TEL1624662', 'nama' => 'Teknik Kontrol Adaptif',               'semester' => 6, 'kategori' => 'Pilihan Kontrol', 'sks' => 2],
            ['kode' => 'TEL1624663', 'nama' => 'Sistem Kontrol Cerdas',               'semester' => 6, 'kategori' => 'Pilihan Kontrol', 'sks' => 2],
            ['kode' => 'TEL1624664', 'nama' => 'Sistem Skala Besar',                  'semester' => 6, 'kategori' => 'Pilihan Kontrol', 'sks' => 2],
            ['kode' => 'TEL1624665', 'nama' => 'Sistem Navigasi Inersia',             'semester' => 6, 'kategori' => 'Pilihan Kontrol', 'sks' => 2],
            ['kode' => 'TEL1624666', 'nama' => 'Mekatronika',                         'semester' => 6, 'kategori' => 'Pilihan Kontrol', 'sks' => 2],
            ['kode' => 'TEL1624667', 'nama' => 'Robotika',                            'semester' => 6, 'kategori' => 'Pilihan Kontrol', 'sks' => 2],
            ['kode' => 'TEL1624668', 'nama' => 'Pembelajaran Mesin',                  'semester' => 6, 'kategori' => 'Pilihan Kontrol', 'sks' => 2],
            ['kode' => 'TEL1624669', 'nama' => 'Kontrol Remote dan Telemetri',        'semester' => 6, 'kategori' => 'Pilihan Kontrol', 'sks' => 2],
            ['kode' => 'TEL1624670', 'nama' => 'Kontrol Otomotif',                    'semester' => 6, 'kategori' => 'Pilihan Kontrol', 'sks' => 2],
            ['kode' => 'TEL1624671', 'nama' => 'Kontrol Energi Listrik',              'semester' => 6, 'kategori' => 'Pilihan Kontrol', 'sks' => 2],
            ['kode' => 'TEL1624672', 'nama' => 'Pemrograman & Simulasi Sistem',       'semester' => 6, 'kategori' => 'Pilihan Kontrol', 'sks' => 2],
            ['kode' => 'TEL1624673', 'nama' => 'Kontrol Berbasis Model',              'semester' => 6, 'kategori' => 'Pilihan Kontrol', 'sks' => 2],
            ['kode' => 'TEL1624674', 'nama' => 'CAD untuk Instrumentasi',             'semester' => 6, 'kategori' => 'Pilihan Kontrol', 'sks' => 2],
            ['kode' => 'TEL1624675', 'nama' => 'Identifikasi Sistem Lanjut',          'semester' => 6, 'kategori' => 'Pilihan Kontrol', 'sks' => 2],

            // Pilihan TI
            ['kode' => 'TEL1624678', 'nama' => 'Kriptografi',                                 'semester' => 6, 'kategori' => 'Pilihan TI', 'sks' => 2],
            ['kode' => 'TEL1624679', 'nama' => 'Teknologi Multimedia',                        'semester' => 6, 'kategori' => 'Pilihan TI', 'sks' => 2],
            ['kode' => 'TEL1624680', 'nama' => 'Komputasi Terdistribusi dan Cloud',           'semester' => 6, 'kategori' => 'Pilihan TI', 'sks' => 2],
            ['kode' => 'TEL1624681', 'nama' => 'Jaringan Nirkabel dan Bergerak',              'semester' => 6, 'kategori' => 'Pilihan TI', 'sks' => 2],
            ['kode' => 'TEL1624682', 'nama' => 'Keamanan Jaringan',                           'semester' => 6, 'kategori' => 'Pilihan TI', 'sks' => 2],
            ['kode' => 'TEL1624683', 'nama' => 'Antarmuka dan Periferal',                     'semester' => 6, 'kategori' => 'Pilihan TI', 'sks' => 2],
            ['kode' => 'TEL1624684', 'nama' => 'Pemrograman Paralel',                         'semester' => 6, 'kategori' => 'Pilihan TI', 'sks' => 2],
            ['kode' => 'TEL1624685', 'nama' => 'Pemrograman Jaringan',                        'semester' => 6, 'kategori' => 'Pilihan TI', 'sks' => 2],
            ['kode' => 'TEL1624686', 'nama' => 'Sistem Berarsitektur IoT',                    'semester' => 6, 'kategori' => 'Pilihan TI', 'sks' => 2],
            ['kode' => 'TEL1624687', 'nama' => 'Komputasi Cerdas',                            'semester' => 6, 'kategori' => 'Pilihan TI', 'sks' => 2],
            ['kode' => 'TEL1624688', 'nama' => 'Pengembangan Aplikasi Perangkat Bergerak',    'semester' => 6, 'kategori' => 'Pilihan TI', 'sks' => 2],
            ['kode' => 'TEL1624689', 'nama' => 'Metoda Pemrograman Modern',                   'semester' => 6, 'kategori' => 'Pilihan TI', 'sks' => 2],
            ['kode' => 'TEL1624690', 'nama' => 'Jejaring Semantik',                           'semester' => 6, 'kategori' => 'Pilihan TI', 'sks' => 2],
            ['kode' => 'TEL1624691', 'nama' => 'Desain Antarmuka & Pengalaman Pengguna',      'semester' => 6, 'kategori' => 'Pilihan TI', 'sks' => 2],
            ['kode' => 'TEL1624692', 'nama' => 'Teknologi Blockchain',                        'semester' => 6, 'kategori' => 'Pilihan TI', 'sks' => 2],
            ['kode' => 'TEL1624693', 'nama' => 'Teknologi Metaverse',                         'semester' => 6, 'kategori' => 'Pilihan TI', 'sks' => 2],
            ['kode' => 'TEL1624694', 'nama' => 'Analisis dan Desain Sistem Informasi',        'semester' => 6, 'kategori' => 'Pilihan TI', 'sks' => 2],
            ['kode' => 'TEL1624695', 'nama' => 'Big Data dan Analitik',                       'semester' => 6, 'kategori' => 'Pilihan TI', 'sks' => 2],
            ['kode' => 'TEL1624696', 'nama' => 'Perencanaan Teknologi Informasi',             'semester' => 6, 'kategori' => 'Pilihan TI', 'sks' => 2],
            ['kode' => 'TEL1624697', 'nama' => 'Sistem Pendukung Keputusan',                  'semester' => 6, 'kategori' => 'Pilihan TI', 'sks' => 2],
            ['kode' => 'TEL1624698', 'nama' => 'Arsitektur Enterprise',                       'semester' => 6, 'kategori' => 'Pilihan TI', 'sks' => 2],
            ['kode' => 'TEL1624699', 'nama' => 'Sains Data',                                  'semester' => 6, 'kategori' => 'Pilihan TI', 'sks' => 2],

            // Pilihan MBKM
            ['kode' => 'TEL1624710', 'nama' => 'Projek Penelitian',                         'semester' => 6, 'kategori' => 'Pilihan MBKM', 'sks' => 2],
            ['kode' => 'TEL1624751', 'nama' => 'Magang Teknik Elektro',                    'semester' => 6, 'kategori' => 'Pilihan MBKM', 'sks' => 6],
            ['kode' => 'TEL1624752', 'nama' => 'Komunikasi Profesional',                   'semester' => 6, 'kategori' => 'Pilihan MBKM', 'sks' => 4],
            ['kode' => 'TEL1624753', 'nama' => 'Manajemen Aktivitas dan Projek',           'semester' => 6, 'kategori' => 'Pilihan MBKM', 'sks' => 4],
            ['kode' => 'TEL1624754', 'nama' => 'Kerjasama Multikultural dan Multidisiplin','semester' => 6, 'kategori' => 'Pilihan MBKM', 'sks' => 3],
            ['kode' => 'TEL1624755', 'nama' => 'Tanggungjawab Profesional',                'semester' => 6, 'kategori' => 'Pilihan MBKM', 'sks' => 3],

            // ========= SEMESTER 7 =========
            ['kode' => 'TEL1624700', 'nama' => 'Proposal Tugas Akhir',                                         'semester' => 7, 'kategori' => 'Wajib', 'sks' => 2],
            ['kode' => 'TEL1624701', 'nama' => 'Etika Profesi & Kuliah Lapangan',                              'semester' => 7, 'kategori' => 'Wajib', 'sks' => 1],
            ['kode' => 'TEL1624702', 'nama' => 'Manajemen & Ekonomi Teknik',                                   'semester' => 7, 'kategori' => 'Wajib', 'sks' => 2],
            ['kode' => 'UUW1624009', 'nama' => 'Kuliah Kerja Nyata',                                           'semester' => 7, 'kategori' => 'Wajib', 'sks' => 3],

            // ========= SEMESTER 8 =========
            ['kode' => 'TEL1624800', 'nama' => 'Tugas Akhir',                                                  'semester' => 8, 'kategori' => 'Wajib', 'sks' => 4],
            ['kode' => 'UUW1624003', 'nama' => 'Kewarganegaraan',                                              'semester' => 8, 'kategori' => 'Wajib', 'sks' => 2],
        ];

        // Tambah kurikulum_id + timestamps
        $batchInsert = array_map(function ($mk) use ($kurikulum) {
            return array_merge($mk, [
                'kurikulum_id' => $kurikulum->id,
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);
        }, $mataKuliah);

        // Insert batch
        MataKuliahKurikulum::insert($batchInsert);
    }
}

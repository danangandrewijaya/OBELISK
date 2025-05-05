<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cpl;
use App\Models\Kurikulum;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;

class CplSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get or create the 2017 and 2020 kurikulums
        $kurikulum2017 = Kurikulum::firstOrCreate(
            ['nama' => 'Kurikulum 2017'],
            ['prodi_id' => 1]
        );

        $kurikulum2020 = Kurikulum::firstOrCreate(
            ['nama' => 'Kurikulum 2020'],
            ['prodi_id' => 1]
        );

        // Define the base CPL data
        $baseData = [
            [
                'nomor' => 1,
                'nama' => 'SCIENTIFIC KNOWLEDGE',
                'deskripsi' => 'Memiliki pengetahuan sains matematika, komputasi dan komputer untuk menganalisis dan merancang divais/sistem kompleks serta mampu menerapkan untuk memecahkan masalah rekayasa dengan prinsip keteknikan.',
            ],
            [
                'nomor' => 2,
                'nama' => 'ENGINEERING DESIGN',
                'deskripsi' => 'Memiliki kemampuan melakukan perancangan, penerapan dan verifikasi komponen, proses atau sistem sesuai bidang keahlian untuk memenuhi spesifikasi yang diinginkan serta mempertimbangkan faktor-faktor ekonomi, lingkungan, sosial, keselamatan dan keberlanjutan dengan memanfaatkan sumber daya lokal dan nasional.',
            ],
            [
                'nomor' => 3,
                'nama' => 'EXPERIMENTAL EXPLORATION',
                'deskripsi' => 'Memiliki keahlian dalam merancang dan eksplorasi percobaan di laboratorium maupun di lapangan serta menganalisis hasilnya guna memperkuat penilaian.',
            ],
            [
                'nomor' => 4,
                'nama' => 'TECHNICAL ANALYSIS',
                'deskripsi' => 'Memiliki pengetahuan yang memadai dalam mengidentifikasi, merumuskan dan menganalisis serta menyelesaikan masalah atau memberikan penyelesaian alternatif dalam bidang elektro atau keahliannya.',
            ],
            [
                'nomor' => 5,
                'nama' => 'MASTERING MODERN TOOLS',
                'deskripsi' => 'Memiliki keterampilan yang baik dalam menggunakan metode maupun sarana dan peralatan modern yang dibutuhkan dalam keteknikan khususnya bidang Teknik elektro.',
            ],
            [
                'nomor' => 6,
                'nama' => 'COMMUNICATION',
                'deskripsi' => 'Mampu berkomunikasi secara efektif dalam menyampaikan gagasan, lisan maupun tulisan.',
            ],
            [
                'nomor' => 7,
                'nama' => 'PROJECT MANAGEMENT',
                'deskripsi' => 'Memiliki kompetensi dalam perencanaan, penyelesaian dan evaluasi tugas dan pekerjaan secara terukur dan sistematis dengan merujuk batasan-batasan yang ada.',
            ],
            [
                'nomor' => 8,
                'nama' => 'TEAMWORK',
                'deskripsi' => 'Mampu berkerja sama dan berkontribusi secara efektif dalam tim multidisiplin dan multikultural.',
            ],
            [
                'nomor' => 9,
                'nama' => 'ETHICAL CONDUCT',
                'deskripsi' => 'Mempunyai kemampuan bertanggung jawab secara mandiri atas pekerjaannya dan menunjukkan ketaatan terhadap etika profesi dalam setiap permasalahan keteknikan.',
            ],
            [
                'nomor' => 10,
                'nama' => 'LIFELONG LEARNING',
                'deskripsi' => 'Memiliki pemahaman yang mendalam mengenai pentingnya pembelajaran seumur hidup melalui berbagai cara, termasuk mengikuti perkembangan pengetahuan terbaru dan terkini.',
            ],
        ];

        // Clear existing CPL data for these kurikulums
        Cpl::where('kurikulum_id', $kurikulum2017->id)
            ->orWhere('kurikulum_id', $kurikulum2020->id)
            ->delete();

        // Reset auto-increment to ensure IDs start from 1
        DB::statement('ALTER TABLE mst_cpl AUTO_INCREMENT = 1');

        // Seed CPL data for kurikulum 2017
        $data2017 = [];
        foreach ($baseData as $item) {
            $data2017[] = array_merge($item, ['kurikulum_id' => $kurikulum2017->id]);
        }
        Cpl::insert($data2017);

        // Seed CPL data for kurikulum 2020
        $data2020 = [];
        foreach ($baseData as $item) {
            $data2020[] = array_merge($item, ['kurikulum_id' => $kurikulum2020->id]);
        }
        Cpl::insert($data2020);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cpl;

class CplSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'nomor' => 1,
                'nama' => 'SCIENTIFIC KNOWLEDGE',
                'deskripsi' => 'Memiliki pengetahuan sains matematika, komputasi dan komputer untuk menganalisis dan merancang divais/sistem kompleks serta mampu menerapkan untuk memecahkan masalah rekayasa dengan prinsip keteknikan.',
                'prodi_id' => 1,
            ],
            [
                'nomor' => 2,
                'nama' => 'ENGINEERING DESIGN',
                'deskripsi' => 'Memiliki kemampuan melakukan perancangan, penerapan dan verifikasi komponen, proses atau sistem sesuai bidang keahlian untuk memenuhi spesifikasi yang diinginkan serta mempertimbangkan faktor-faktor ekonomi, lingkungan, sosial, keselamatan dan keberlanjutan dengan memanfaatkan sumber daya lokal dan nasional.',
                'prodi_id' => 1,
            ],
            [
                'nomor' => 3,
                'nama' => 'EXPERIMENTAL EXPLORATION',
                'deskripsi' => 'Memiliki keahlian dalam merancang dan eksplorasi percobaan di laboratorium maupun di lapangan serta menganalisis hasilnya guna memperkuat penilaian.',
                'prodi_id' => 1,
            ],
            [
                'nomor' => 4,
                'nama' => 'TECHNICAL ANALYSIS',
                'deskripsi' => 'Memiliki pengetahuan yang memadai dalam mengidentifikasi, merumuskan dan menganalisis serta menyelesaikan masalah atau memberikan penyelesaian alternatif dalam bidang elektro atau keahliannya.',
                'prodi_id' => 1,
            ],
            [
                'nomor' => 5,
                'nama' => 'MASTERING MODERN TOOLS',
                'deskripsi' => 'Memiliki keterampilan yang baik dalam menggunakan metode maupun sarana dan peralatan modern yang dibutuhkan dalam keteknikan khususnya bidang Teknik elektro.',
                'prodi_id' => 1,
            ],
            [
                'nomor' => 6,
                'nama' => 'COMMUNICATION',
                'deskripsi' => 'Mampu berkomunikasi secara efektif dalam menyampaikan gagasan, lisan maupun tulisan.',
                'prodi_id' => 1,
            ],
            [
                'nomor' => 7,
                'nama' => 'PROJECT MANAGEMENT',
                'deskripsi' => 'Memiliki kompetensi dalam perencanaan, penyelesaian dan evaluasi tugas dan pekerjaan secara terukur dan sistematis dengan merujuk batasan-batasan yang ada.',
                'prodi_id' => 1,
            ],
            [
                'nomor' => 8,
                'nama' => 'TEAMWORK',
                'deskripsi' => 'Mampu berkerja sama dan berkontribusi secara efektif dalam tim multidisiplin dan multikultural.',
                'prodi_id' => 1,
            ],
            [
                'nomor' => 9,
                'nama' => 'ETHICAL CONDUCT',
                'deskripsi' => 'Mempunyai kemampuan bertanggung jawab secara mandiri atas pekerjaannya dan menunjukkan ketaatan terhadap etika profesi dalam setiap permasalahan keteknikan.',
                'prodi_id' => 1,
            ],
            [
                'nomor' => 10,
                'nama' => 'LIFELONG LEARNING',
                'deskripsi' => 'Memiliki pemahaman yang mendalam mengenai pentingnya pembelajaran seumur hidup melalui berbagai cara, termasuk mengikuti perkembangan pengetahuan terbaru dan terkini.',
                'prodi_id' => 1,
            ],
        ];

        Cpl::insert($data);
    }
}

<?php

namespace Database\Seeders;

use App\Models\Dosen;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DosenSeeder extends Seeder
{
    public function run()
    {
        DB::table((new Dosen)->getTable())->insert([
            ['nama' => 'Dr. Wahyudi, S.T., M.T.', 'nip' => '196906121994031001', 'nidn' => '0012066904'],
            ['nama' => 'Ir. Agung Nugroho, M.Kom., IPU.', 'nip' => '195901051987031002', 'nidn' => '0005015907'],
            ['nama' => 'Ir. Sumardi, S.T., M.T., IPM., ASEAN Eng.', 'nip' => '196811111994121001', 'nidn' => '0011116801'],
            ['nama' => 'Ir. Ngatelan, M.T.', 'nip' => '195207291982031004', 'nidn' => '0027075211'],
            ['nama' => 'Ir. Juningtijastuti, M.T.', 'nip' => '195209261983032001', 'nidn' => '0026095205'],
            ['nama' => 'Ir. Sudjadi, M.T.', 'nip' => '195906191985111001', 'nidn' => '0019065904'],
            ['nama' => 'Dr. Ir. Jaka Windarta, M.T., IPU, ASEAN Eng.', 'nip' => '196405261989031002', 'nidn' => '0026056407'],
            ['nama' => 'Dr. Ir. Hermawan, DEA', 'nip' => '196002231986021001', 'nidn' => '0023026003'],
            ['nama' => 'Dr. Ir. Abdul Syakur, S.T., M.T., IPU.', 'nip' => '197204221999031004', 'nidn' => '0022047201'],
            ['nama' => 'Ir. Mochammad Facta, S.T., M.T., Ph.D.', 'nip' => '197106161999031003', 'nidn' => '0016067104'],
            ['nama' => 'Ir. Agung Warsito, DHET', 'nip' => '195806171987031002', 'nidn' => '0017065802'],
            ['nama' => 'Achmad Hidayatno, S.T., M.T.', 'nip' => '196912211995121001', 'nidn' => '0021126905'],
            ['nama' => 'Ajub Ajulian Zahra Macrina, S.T., M.T.', 'nip' => '197107191998022001', 'nidn' => '0019077103'],
            ['nama' => 'Dr. Darjat, S.T., M.T.', 'nip' => '197206061999031001', 'nidn' => '0006067205'],
            ['nama' => 'Ir. Aghus Sofwan, S.T., M.T., Ph.D., IPU', 'nip' => '197302041997021001', 'nidn' => '0004027303'],
            ['nama' => 'Yuli Christyono, S.T., M.T.', 'nip' => '196807111997021001', 'nidn' => '0011076805'],
            ['nama' => 'Prof. Dr. Iwan Setiawan, S.T., M.T.', 'nip' => '197309262000121001', 'nidn' => '0026097304'],
            ['nama' => 'Karnoto, S.T., M.T.', 'nip' => '196907091997021001', 'nidn' => '0009076905'],
            ['nama' => 'Ir. Tejo Sukmadi, M.T.', 'nip' => '196111171988031001', 'nidn' => '0017116105'],
            ['nama' => 'Ir. Nugroho Agus Darmanto, M.T.', 'nip' => '195804291986021001', 'nidn' => '0029045805'],
            ['nama' => 'Dr. Ir. Wahyul Amien Syafei, S.T., M.T., IPM', 'nip' => '197112181995121001', 'nidn' => '0018127102'],
            ['nama' => 'Ir. Bambang Winardi, M.Kom.', 'nip' => '196106161993031002', 'nidn' => '0016066104'],
            ['nama' => 'Dr. Maman Somantri, S.T., M.T.', 'nip' => '197406271999031002', 'nidn' => '0027067403'],
            ['nama' => 'Imam Santoso, S.T., M.T.', 'nip' => '197012031997021001', 'nidn' => '0003127004'],
            ['nama' => 'Eko Handoyo, S.T., M.T.', 'nip' => '197506082005011001', 'nidn' => '0008067505'],
            ['nama' => 'Dr. Susatyo Handoko, S.T., M.T.', 'nip' => '197305262000121001', 'nidn' => '0026057302'],
            ['nama' => 'Sukiswo, S.T., M.T.', 'nip' => '196907141997021001', 'nidn' => '0014076902'],
            ['nama' => 'Ir. Munawar Agus Riyadi, S.T., M.T., Ph.D.', 'nip' => '197708262006041001', 'nidn' => '0026087703'],
            ['nama' => 'Ir. Budi Setiyono, S.T., M.T.', 'nip' => '197005212000121001', 'nidn' => '0021057010'],
            ['nama' => 'Ir. Teguh Prakoso, S.T., M.T., Ph.D.', 'nip' => '197706222010121001', 'nidn' => '0022067709'],
            ['nama' => 'Enda Wista Sinuraya, S.T., M.T.', 'nip' => '198012112010121001', 'nidn' => '0011128005'],
            ['nama' => 'Dr. Ir. Aris Triwiyatno, S.T., M.T., IPU., ASEAN Eng., APEC Eng.', 'nip' => '197509081999031002', 'nidn' => '0008097504'],
            ['nama' => 'Trias Andromeda, S.T., M.T., Ph.D.', 'nip' => '197206302000121001', 'nidn' => '0030067203'],
            ['nama' => 'Ir. M. Arfan, S.Kom., M.Eng.', 'nip' => '198408172015041002', 'nidn' => '0617088402'],
            ['nama' => 'Iwan Kusuma, A.Md.', 'nip' => '198708310214011151', 'nidn' => ''],
            ['nama' => 'Denis, S.T., M.Eng.', 'nip' => '199104170117011091', 'nidn' => ''],
            ['nama' => 'Yosua Alvin Adi Soetrisno, S.T., M.Eng.', 'nip' => '199010130117011090', 'nidn' => ''],
            ['nama' => 'Ir. Denis, S.T., M.Eng., IPM., ASEAN Eng.', 'nip' => 'H.7.199104172018071001', 'nidn' => '0017049108'],
            ['nama' => 'Ir. Hadha Afrisal, S.T., M.Sc., IPP.', 'nip' => 'H.7.199104172018071002', 'nidn' => '0017049107'],
            ['nama' => 'Ir. Yosua Alvin Adi Soetrisno, S.T., M.Eng., IPM', 'nip' => 'H.7.199010132018071001', 'nidn' => '0013109008'],
            ['nama' => 'Alfia Putri Wulandari, S.T., M.T.', 'nip' => '199708192024062002', 'nidn' => ''],
            ['nama' => 'Laily Asna Safira, S.T., M.T.', 'nip' => '199902202024062001', 'nidn' => ''],
        ]);
    }
}
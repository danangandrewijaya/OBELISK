<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Address;
use App\Models\Kurikulum;
use App\Models\Prodi;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UsersSeeder::class,
            RolesPermissionsSeeder::class,
        ]);

        \App\Models\User::factory(20)->create();

        Address::factory(20)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        Prodi::insert([
            [
                'nama' => 'Teknik Elektro',
                'kode' => '20201',
            ],
        ]);

        Kurikulum::insert([
            [
                'nama' => 'Kurikulum 2017',
                'prodi_id' => Prodi::where('kode', '20201')->first()->id,
            ],
            [
                'nama' => 'Kurikulum 2020',
                'prodi_id' => Prodi::where('kode', '20201')->first()->id,
            ],
        ]);

        $this->call(CplSeeder::class, [
            'kurikulum_id' => Kurikulum::where('nama', 'Kurikulum 2017')->first()->id,
        ]);
        $this->call(CplSeeder::class, [
            'kurikulum_id' => Kurikulum::where('nama', 'Kurikulum 2020')->first()->id,
        ]);
        $this->call(MataKuliahKurikulum2017Seeder::class);
        $this->call(MataKuliahKurikulum2020Seeder::class);
        $this->call(DosenSeeder::class);
    }
}

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
                'nama' => 'Kurikulum 2020',
                'prodi_id' => Prodi::where('kode', '20201')->first()->id,
            ],
        ]);

        $this->call(MataKuliahKurikulumSeeder::class);
        $this->call(CplSeeder::class);
    }
}

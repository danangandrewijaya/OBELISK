<?php

namespace Database\Seeders;

use App\Models\Dosen;
use App\Models\User;
use Faker\Generator;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    // public function run(Generator $faker)
    // {
    //     $demoUser = User::create([
    //         'name'              => $faker->name,
    //         'email'             => 'demo@demo.com',
    //         'password'          => Hash::make('demo'),
    //         'email_verified_at' => now(),
    //     ]);

    //     $demoUser2 = User::create([
    //         'name'              => $faker->name,
    //         'email'             => 'admin@demo.com',
    //         'password'          => Hash::make('demo'),
    //         'email_verified_at' => now(),
    //     ]);
    // }

    public function run()
    {
        $dosens = Dosen::whereNotNull('tgl')->get();

        foreach($dosens as $dosen){
            $user = User::create([
                'name'              => $dosen->nama,
                'email'             => $dosen->nip . '@undip.ac.id',
                'password'          => Hash::make($dosen->tgl),
                'email_verified_at' => now(),
            ]);

            User::find($user->id)->assignRole('dosen');
        }
    }
}

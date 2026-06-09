<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        for ($i = 0; $i < 10; $i++) {
            DB::table('users')->insert([
                'username'   => $faker->userName,
                'email'      => $faker->unique()->safeEmail,
                'password'   => Hash::make('1234567'),
                'role'       => $faker->randomElement(['admin', 'guest']),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

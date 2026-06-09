<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'nama' => 'ASUS TUF A15 FA506NF',
                'harga'  => 10899000,
                'jumlah' => 5,
                'foto' => 'asus_tuf_a15.jpg',
                
                'created_at' => date("Y-m-d H:i:s"),
            ],
            [
                'nama' => 'Asus Vivobook 14 A1404ZA',
                'harga'  => 6899000,
                'jumlah' => 7,
                'foto' => 'asus_vivobook_14.jpg',
                'created_at' => date("Y-m-d H:i:s"),
            ],
            [
                'nama' => 'Lenovo IdeaPad Slim 3-14IAU7',
                'harga'  => 6299000,
                'jumlah' => 5,
                'foto' => 'lenovo_idepad_slim_3.jpg',
                'created_at' => date("Y-m-d H:i:s"),
            ]
        ];

        foreach ($data as $item) {
            // insert semua data ke tabel
            DB::table('products')->insert($item);
        }
    }
}

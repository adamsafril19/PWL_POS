<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'kategori_id' => 1,
                'kategori_kode' => 'CAT001',
                'kategori_nama' => 'Electronics',
                'created_at' => '2024-09-13 10:00:00',
                'updated_at' => '2024-09-13 10:00:00',
            ],
            [
                'kategori_id' => 2,
                'kategori_kode' => 'CAT002',
                'kategori_nama' => 'Groceries',
                'created_at' => '2024-09-13 10:10:00',
                'updated_at' => '2024-09-13 10:10:00',
            ],
            [
                'kategori_id' => 3,
                'kategori_kode' => 'CAT003',
                'kategori_nama' => 'Clothing',
                'created_at' => '2024-09-13 10:20:00',
                'updated_at' => '2024-09-13 10:20:00',
            ],
            [
                'kategori_id' => 4,
                'kategori_kode' => 'CAT004',
                'kategori_nama' => 'Furniture',
                'created_at' => '2024-09-13 10:30:00',
                'updated_at' => '2024-09-13 10:30:00',
            ],
            [
                'kategori_id' => 5,
                'kategori_kode' => 'CAT005',
                'kategori_nama' => 'Sports Equipment',
                'created_at' => '2024-09-13 10:40:00',
                'updated_at' => '2024-09-13 10:40:00',
            ],
        ];

        DB::table('m_kategori')->insert($data);
    }
}

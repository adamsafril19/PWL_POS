<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'supplier_id' => 1,
                'supplier_kode' => 'SUP001',
                'supplier_nama' => 'ABC Supplies',
                'supplier_alamat' => '123 Main Street, Springfield, USA',
            ],
            [
                'supplier_id' => 2,
                'supplier_kode' => 'SUP002',
                'supplier_nama' => 'XYZ Traders',
                'supplier_alamat' => '456 Elm Street, Metropolis, USA',
            ],
            [
                'supplier_id' => 3,
                'supplier_kode' => 'SUP003',
                'supplier_nama' => 'Fresh Goods',
                'supplier_alamat' => '789 Oak Avenue, Gotham City, USA',
            ],
        ];

        DB::table('m_supplier')->insert($data);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['barang_id' => 1, 'kategori_id' => 1, 'barang_kode' => 'BRG001', 'barang_nama' => 'Laptop A', 'harga_beli' => 5000000, 'harga_jual' => 5500000],
            ['barang_id' => 2, 'kategori_id' => 1, 'barang_kode' => 'BRG002', 'barang_nama' => 'Mouse Wireless', 'harga_beli' => 150000, 'harga_jual' => 200000],
            ['barang_id' => 3, 'kategori_id' => 1, 'barang_kode' => 'BRG003', 'barang_nama' => 'Keyboard Mechanical', 'harga_beli' => 450000, 'harga_jual' => 500000],
            ['barang_id' => 4, 'kategori_id' => 2, 'barang_kode' => 'BRG004', 'barang_nama' => 'Milk 1L', 'harga_beli' => 12000, 'harga_jual' => 15000],
            ['barang_id' => 5, 'kategori_id' => 2, 'barang_kode' => 'BRG005', 'barang_nama' => 'Bread', 'harga_beli' => 7000, 'harga_jual' => 9000],
            ['barang_id' => 6, 'kategori_id' => 2, 'barang_kode' => 'BRG006', 'barang_nama' => 'Olive Oil 500ml', 'harga_beli' => 35000, 'harga_jual' => 40000],
            ['barang_id' => 7, 'kategori_id' => 3, 'barang_kode' => 'BRG007', 'barang_nama' => 'T-Shirt Red', 'harga_beli' => 20000, 'harga_jual' => 25000],
            ['barang_id' => 8, 'kategori_id' => 3, 'barang_kode' => 'BRG008', 'barang_nama' => 'T-Shirt Blue', 'harga_beli' => 20000, 'harga_jual' => 25000],
            ['barang_id' => 9, 'kategori_id' => 3, 'barang_kode' => 'BRG009', 'barang_nama' => 'Hoodie Black', 'harga_beli' => 80000, 'harga_jual' => 100000],
            ['barang_id' => 10, 'kategori_id' => 4, 'barang_kode' => 'BRG010', 'barang_nama' => 'Dining Table Set', 'harga_beli' => 2000000, 'harga_jual' => 2500000],
            ['barang_id' => 11, 'kategori_id' => 4, 'barang_kode' => 'BRG011', 'barang_nama' => 'Office Chair', 'harga_beli' => 750000, 'harga_jual' => 900000],
            ['barang_id' => 12, 'kategori_id' => 4, 'barang_kode' => 'BRG012', 'barang_nama' => 'Bookshelf Wooden', 'harga_beli' => 1500000, 'harga_jual' => 1700000],
            ['barang_id' => 13, 'kategori_id' => 5, 'barang_kode' => 'BRG013', 'barang_nama' => 'Football', 'harga_beli' => 60000, 'harga_jual' => 80000],
            ['barang_id' => 14, 'kategori_id' => 5, 'barang_kode' => 'BRG014', 'barang_nama' => 'Basketball', 'harga_beli' => 50000, 'harga_jual' => 70000],
            ['barang_id' => 15, 'kategori_id' => 5, 'barang_kode' => 'BRG015', 'barang_nama' => 'Tennis Racket', 'harga_beli' => 75000, 'harga_jual' => 95000],
        ];

        DB::table('m_barang')->insert($data);
    }
}

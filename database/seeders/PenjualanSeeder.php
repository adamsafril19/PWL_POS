<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PenjualanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['penjualan_id' => 1, 'user_id' => 1, 'pembeli' => 'John Doe', 'penjualan_kode' => 'PNJ-20240901-01', 'penjualan_tanggal' => '2024-09-01 09:00:00'],
            ['penjualan_id' => 2, 'user_id' => 2, 'pembeli' => 'Jane Smith', 'penjualan_kode' => 'PNJ-20240901-02', 'penjualan_tanggal' => '2024-09-01 10:30:00'],
            ['penjualan_id' => 3, 'user_id' => 1, 'pembeli' => 'Michael Johnson', 'penjualan_kode' => 'PNJ-20240902-01', 'penjualan_tanggal' => '2024-09-02 11:15:00'],
            ['penjualan_id' => 4, 'user_id' => 3, 'pembeli' => 'Emily Davis', 'penjualan_kode' => 'PNJ-20240902-02', 'penjualan_tanggal' => '2024-09-02 13:45:00'],
            ['penjualan_id' => 5, 'user_id' => 2, 'pembeli' => 'Christopher Brown', 'penjualan_kode' => 'PNJ-20240903-01', 'penjualan_tanggal' => '2024-09-03 09:50:00'],
            ['penjualan_id' => 6, 'user_id' => 1, 'pembeli' => 'Jessica Wilson', 'penjualan_kode' => 'PNJ-20240903-02', 'penjualan_tanggal' => '2024-09-03 12:20:00'],
            ['penjualan_id' => 7, 'user_id' => 3, 'pembeli' => 'Daniel Martinez', 'penjualan_kode' => 'PNJ-20240904-01', 'penjualan_tanggal' => '2024-09-04 08:30:00'],
            ['penjualan_id' => 8, 'user_id' => 1, 'pembeli' => 'Laura Anderson', 'penjualan_kode' => 'PNJ-20240904-02', 'penjualan_tanggal' => '2024-09-04 14:10:00'],
            ['penjualan_id' => 9, 'user_id' => 2, 'pembeli' => 'Thomas Taylor', 'penjualan_kode' => 'PNJ-20240905-01', 'penjualan_tanggal' => '2024-09-05 11:00:00'],
            ['penjualan_id' => 10, 'user_id' => 3, 'pembeli' => 'Sarah Thompson', 'penjualan_kode' => 'PNJ-20240905-02', 'penjualan_tanggal' => '2024-09-05 15:30:00'],
        ];

        DB::table('t_penjualan')->insert($data);
    }
}

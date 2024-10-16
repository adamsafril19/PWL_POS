-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 16, 2024 at 02:14 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pwl_pos`
--

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2024_09_11_012836_create_m_level_table', 1),
(6, '2024_09_11_015545_create_m_kategori_table', 2),
(7, '2024_09_11_020306_create_m_supplier_table', 3),
(8, '2024_09_11_021844_create_m_user_table', 4),
(9, '2024_09_11_025336_create_m_barang_table', 5),
(10, '2024_09_11_031140_create_t_penjualan_table', 6),
(11, '2024_09_11_032750_create_t_stok_table', 7),
(12, '2024_09_11_033703_create_t_penjualan_detail_table', 8);

-- --------------------------------------------------------

--
-- Table structure for table `m_barang`
--

CREATE TABLE `m_barang` (
  `barang_id` bigint UNSIGNED NOT NULL,
  `kategori_id` bigint UNSIGNED NOT NULL,
  `barang_kode` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `barang_nama` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `harga_beli` int NOT NULL,
  `harga_jual` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `m_barang`
--

INSERT INTO `m_barang` (`barang_id`, `kategori_id`, `barang_kode`, `barang_nama`, `harga_beli`, `harga_jual`, `created_at`, `updated_at`) VALUES
(1, 1, 'BRG001', 'Laptop A', 5000000, 5500000, NULL, NULL),
(2, 1, 'BRG002', 'Mouse Wireless', 150000, 200000, NULL, NULL),
(3, 1, 'BRG003', 'Keyboard Mechanical', 450000, 500000, NULL, NULL),
(4, 2, 'BRG004', 'Milk 1L', 12000, 15000, NULL, NULL),
(5, 2, 'BRG005', 'Bread', 7000, 9000, NULL, NULL),
(6, 2, 'BRG006', 'Olive Oil 500ml', 35000, 40000, NULL, NULL),
(7, 3, 'BRG007', 'T-Shirt Red', 20000, 25000, NULL, NULL),
(8, 3, 'BRG008', 'T-Shirt Blue', 20000, 25000, NULL, NULL),
(9, 3, 'BRG009', 'Hoodie Black', 80000, 100000, NULL, NULL),
(10, 4, 'BRG010', 'Dining Table Set', 2000000, 2500000, NULL, NULL),
(11, 4, 'BRG011', 'Office Chair', 750000, 900000, NULL, NULL),
(12, 4, 'BRG012', 'Bookshelf Wooden', 1500000, 1700000, NULL, NULL),
(13, 5, 'BRG013', 'Football', 60000, 80000, NULL, NULL),
(14, 5, 'BRG014', 'Basketball', 50000, 70000, NULL, NULL),
(15, 5, 'BRG015', 'Tennis Racket', 75000, 95000, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `m_kategori`
--

CREATE TABLE `m_kategori` (
  `kategori_id` bigint UNSIGNED NOT NULL,
  `kategori_kode` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kategori_nama` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `m_kategori`
--

INSERT INTO `m_kategori` (`kategori_id`, `kategori_kode`, `kategori_nama`, `created_at`, `updated_at`) VALUES
(1, 'CAT001', 'Electronics', '2024-09-13 03:00:00', '2024-09-13 03:00:00'),
(2, 'CAT002', 'Groceries', '2024-09-13 03:10:00', '2024-09-13 03:10:00'),
(3, 'CAT003', 'Clothing', '2024-09-13 03:20:00', '2024-09-13 03:20:00'),
(4, 'CAT004', 'Furniture', '2024-09-13 03:30:00', '2024-09-13 03:30:00'),
(5, 'CAT005', 'Sports Equipment', '2024-09-13 03:40:00', '2024-09-13 03:40:00');

-- --------------------------------------------------------

--
-- Table structure for table `m_level`
--

CREATE TABLE `m_level` (
  `level_id` bigint UNSIGNED NOT NULL,
  `level_kode` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `level_nama` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `m_level`
--

INSERT INTO `m_level` (`level_id`, `level_kode`, `level_nama`, `created_at`, `updated_at`) VALUES
(1, 'ADM', 'Administrator', NULL, NULL),
(2, 'MNG', 'Manager', NULL, NULL),
(3, 'STF', 'Staf/Kasir', NULL, NULL),
(4, 'CUS', 'Ajis', '2024-09-14 07:39:21', '2024-10-03 23:35:48'),
(13, 'CUST', 'Andri', '2024-10-12 23:07:00', '2024-10-12 23:07:00');

-- --------------------------------------------------------

--
-- Table structure for table `m_supplier`
--

CREATE TABLE `m_supplier` (
  `supplier_id` bigint UNSIGNED NOT NULL,
  `supplier_kode` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `supplier_nama` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `supplier_alamat` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `m_supplier`
--

INSERT INTO `m_supplier` (`supplier_id`, `supplier_kode`, `supplier_nama`, `supplier_alamat`, `created_at`, `updated_at`) VALUES
(1, 'SUP001', 'ABC Supplies', '123 Main Street, Springfield, USA', NULL, NULL),
(2, 'SUP002', 'XYZ Traders', '456 Elm Street, Metropolis, USA', NULL, NULL),
(3, 'SUP003', 'Fresh Goods', '789 Oak Avenue, Gotham City, USA', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `m_user`
--

CREATE TABLE `m_user` (
  `user_id` bigint UNSIGNED NOT NULL,
  `level_id` bigint UNSIGNED NOT NULL,
  `username` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `m_user`
--

INSERT INTO `m_user` (`user_id`, `level_id`, `username`, `nama`, `password`, `created_at`, `updated_at`) VALUES
(1, 1, 'admin', 'administrator', '$2y$12$vkDDGJes9hb.EgO0r.lffeAETNTSkhhzK1.raO/SCcXQImyBXGm/e', NULL, '2024-10-15 18:51:28'),
(2, 2, 'manager', 'Manager', '$2y$12$rDd57LqQNmOokhe52mwS.e7FyShS6lhzM.4O5BnyVYRJmiIRDgaIm', NULL, NULL),
(3, 3, 'staff', 'Staff/Kasir', '$2y$12$0NY.LfbzS1TFtEf.35yeMedG0TAwn5N8fA1dmG8leogc1lHzED26u', NULL, NULL),
(7, 2, 'manager_dua', 'Manager 2', '$2y$12$xA2fIACuGmLCHTa4wZiZ.eYpWvAaKz/KPnECDPYhg5HVnzrrrGFUW', '2024-09-17 19:01:13', '2024-09-17 19:01:13'),
(8, 2, 'Manager22', 'Manager Dua dua', '$2y$12$SjoSejDB3ssz3ixU/imAT.VAlK3YoztEO0AZ8V55Jh2VW1qxlyUXm', '2024-09-21 08:51:17', '2024-09-21 08:51:17'),
(9, 2, 'Manager33', 'Manager Tiga tiga', '$2y$12$KmXj39g43A6OP8g/OHMMauOzvaz4zfFyXuLvRGUJNrbCHB383boTC', '2024-09-21 09:28:05', '2024-09-21 09:28:05'),
(15, 2, 'manager45', 'Manager44', '$2y$12$x4fm8q9yCw9YLMhNxgP9F.ODeXi1ZlgLyT6geRVEYCS.lcZnSiIjq', '2024-09-23 02:43:43', '2024-09-23 02:43:43'),
(16, 2, 'manager12', 'Manager11', '$2y$12$w6w7HzcBHNELFQ3.HcRX3OVgyvNsYXlLA1mqTfEqEhOX2flM6racG', '2024-09-23 03:02:06', '2024-09-23 03:02:06'),
(17, 3, 'staff1', 'dika1', '$2y$12$tXeaYw6j3MyEzwo/bkJg3uP4kUAWiRlcuSlZnlmJ4nuew8CI7zAB6', '2024-09-23 18:31:32', '2024-10-15 10:26:19');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `t_penjualan`
--

CREATE TABLE `t_penjualan` (
  `penjualan_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `pembeli` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `penjualan_kode` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `penjualan_tanggal` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `t_penjualan`
--

INSERT INTO `t_penjualan` (`penjualan_id`, `user_id`, `pembeli`, `penjualan_kode`, `penjualan_tanggal`, `created_at`, `updated_at`) VALUES
(1, 1, 'John Doe', 'PNJ-20240901-01', '2024-09-01 09:00:00', NULL, NULL),
(2, 2, 'Jane Smith', 'PNJ-20240901-02', '2024-09-01 10:30:00', NULL, NULL),
(3, 1, 'Michael Johnson', 'PNJ-20240902-01', '2024-09-02 11:15:00', NULL, NULL),
(4, 3, 'Emily Davis', 'PNJ-20240902-02', '2024-09-02 13:45:00', NULL, NULL),
(5, 2, 'Christopher Brown', 'PNJ-20240903-01', '2024-09-03 09:50:00', NULL, NULL),
(6, 1, 'Jessica Wilson', 'PNJ-20240903-02', '2024-09-03 12:20:00', NULL, NULL),
(7, 3, 'Daniel Martinez', 'PNJ-20240904-01', '2024-09-04 08:30:00', NULL, NULL),
(8, 1, 'Laura Anderson', 'PNJ-20240904-02', '2024-09-04 14:10:00', NULL, NULL),
(9, 2, 'Thomas Taylor', 'PNJ-20240905-01', '2024-09-05 11:00:00', NULL, NULL),
(10, 3, 'Sarah Thompson', 'PNJ-20240905-02', '2024-09-05 15:30:00', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `t_penjualan_detail`
--

CREATE TABLE `t_penjualan_detail` (
  `detail_id` bigint UNSIGNED NOT NULL,
  `penjualan_id` bigint UNSIGNED NOT NULL,
  `barang_id` bigint UNSIGNED NOT NULL,
  `jumlah` int NOT NULL,
  `harga` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `t_penjualan_detail`
--

INSERT INTO `t_penjualan_detail` (`detail_id`, `penjualan_id`, `barang_id`, `jumlah`, `harga`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 2, 50000, NULL, NULL),
(2, 1, 2, 1, 75000, NULL, NULL),
(3, 1, 3, 3, 60000, NULL, NULL),
(4, 2, 4, 1, 120000, NULL, NULL),
(5, 2, 5, 2, 95000, NULL, NULL),
(6, 2, 6, 1, 110000, NULL, NULL),
(7, 3, 1, 1, 50000, NULL, NULL),
(8, 3, 3, 2, 60000, NULL, NULL),
(9, 3, 5, 1, 95000, NULL, NULL),
(10, 4, 2, 2, 75000, NULL, NULL),
(11, 4, 4, 1, 120000, NULL, NULL),
(12, 4, 6, 1, 110000, NULL, NULL),
(13, 5, 1, 3, 50000, NULL, NULL),
(14, 5, 2, 1, 75000, NULL, NULL),
(15, 5, 3, 1, 60000, NULL, NULL),
(16, 6, 4, 1, 120000, NULL, NULL),
(17, 6, 5, 2, 95000, NULL, NULL),
(18, 6, 6, 1, 110000, NULL, NULL),
(19, 7, 1, 2, 50000, NULL, NULL),
(20, 7, 3, 1, 60000, NULL, NULL),
(21, 7, 5, 1, 95000, NULL, NULL),
(22, 8, 2, 2, 75000, NULL, NULL),
(23, 8, 4, 1, 120000, NULL, NULL),
(24, 8, 6, 1, 110000, NULL, NULL),
(25, 9, 1, 1, 50000, NULL, NULL),
(26, 9, 3, 2, 60000, NULL, NULL),
(27, 9, 5, 1, 95000, NULL, NULL),
(28, 10, 2, 2, 75000, NULL, NULL),
(29, 10, 4, 1, 120000, NULL, NULL),
(30, 10, 6, 1, 110000, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `t_stok`
--

CREATE TABLE `t_stok` (
  `stok_id` bigint UNSIGNED NOT NULL,
  `barang_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `stok_tanggal` datetime NOT NULL,
  `stok_jumlah` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `t_stok`
--

INSERT INTO `t_stok` (`stok_id`, `barang_id`, `user_id`, `stok_tanggal`, `stok_jumlah`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '2024-09-01 10:00:00', 50, NULL, NULL),
(2, 2, 1, '2024-09-01 11:00:00', 30, NULL, NULL),
(3, 3, 2, '2024-09-01 12:30:00', 45, NULL, NULL),
(4, 4, 2, '2024-09-02 09:15:00', 60, NULL, NULL),
(5, 5, 1, '2024-09-02 10:45:00', 35, NULL, NULL),
(6, 6, 1, '2024-09-02 11:30:00', 20, NULL, NULL),
(7, 7, 3, '2024-09-03 08:00:00', 70, NULL, NULL),
(8, 8, 3, '2024-09-03 09:30:00', 40, NULL, NULL),
(9, 9, 2, '2024-09-03 10:15:00', 25, NULL, NULL),
(10, 10, 1, '2024-09-04 14:00:00', 55, NULL, NULL),
(11, 11, 3, '2024-09-04 15:30:00', 50, NULL, NULL),
(12, 12, 2, '2024-09-05 08:45:00', 60, NULL, NULL),
(13, 13, 1, '2024-09-05 09:15:00', 35, NULL, NULL),
(14, 14, 3, '2024-09-05 11:45:00', 65, NULL, NULL),
(15, 15, 2, '2024-09-05 13:00:00', 80, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `m_barang`
--
ALTER TABLE `m_barang`
  ADD PRIMARY KEY (`barang_id`),
  ADD UNIQUE KEY `m_barang_barang_kode_unique` (`barang_kode`),
  ADD KEY `m_barang_kategori_id_index` (`kategori_id`);

--
-- Indexes for table `m_kategori`
--
ALTER TABLE `m_kategori`
  ADD PRIMARY KEY (`kategori_id`),
  ADD UNIQUE KEY `m_kategori_kategori_kode_unique` (`kategori_kode`);

--
-- Indexes for table `m_level`
--
ALTER TABLE `m_level`
  ADD PRIMARY KEY (`level_id`),
  ADD UNIQUE KEY `m_level_level_kode_unique` (`level_kode`);

--
-- Indexes for table `m_supplier`
--
ALTER TABLE `m_supplier`
  ADD PRIMARY KEY (`supplier_id`),
  ADD UNIQUE KEY `m_supplier_supplier_kode_unique` (`supplier_kode`);

--
-- Indexes for table `m_user`
--
ALTER TABLE `m_user`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `m_user_username_unique` (`username`),
  ADD KEY `m_user_level_id_index` (`level_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `t_penjualan`
--
ALTER TABLE `t_penjualan`
  ADD PRIMARY KEY (`penjualan_id`),
  ADD UNIQUE KEY `t_penjualan_pembeli_unique` (`pembeli`),
  ADD KEY `t_penjualan_user_id_index` (`user_id`);

--
-- Indexes for table `t_penjualan_detail`
--
ALTER TABLE `t_penjualan_detail`
  ADD PRIMARY KEY (`detail_id`),
  ADD KEY `t_penjualan_detail_penjualan_id_index` (`penjualan_id`),
  ADD KEY `t_penjualan_detail_barang_id_index` (`barang_id`);

--
-- Indexes for table `t_stok`
--
ALTER TABLE `t_stok`
  ADD PRIMARY KEY (`stok_id`),
  ADD KEY `t_stok_barang_id_index` (`barang_id`),
  ADD KEY `t_stok_user_id_index` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `m_barang`
--
ALTER TABLE `m_barang`
  MODIFY `barang_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `m_kategori`
--
ALTER TABLE `m_kategori`
  MODIFY `kategori_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `m_level`
--
ALTER TABLE `m_level`
  MODIFY `level_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `m_supplier`
--
ALTER TABLE `m_supplier`
  MODIFY `supplier_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `m_user`
--
ALTER TABLE `m_user`
  MODIFY `user_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `t_penjualan`
--
ALTER TABLE `t_penjualan`
  MODIFY `penjualan_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `t_penjualan_detail`
--
ALTER TABLE `t_penjualan_detail`
  MODIFY `detail_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `t_stok`
--
ALTER TABLE `t_stok`
  MODIFY `stok_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `m_barang`
--
ALTER TABLE `m_barang`
  ADD CONSTRAINT `m_barang_kategori_id_foreign` FOREIGN KEY (`kategori_id`) REFERENCES `m_kategori` (`kategori_id`);

--
-- Constraints for table `m_user`
--
ALTER TABLE `m_user`
  ADD CONSTRAINT `m_user_level_id_foreign` FOREIGN KEY (`level_id`) REFERENCES `m_level` (`level_id`);

--
-- Constraints for table `t_penjualan`
--
ALTER TABLE `t_penjualan`
  ADD CONSTRAINT `t_penjualan_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `m_user` (`user_id`);

--
-- Constraints for table `t_penjualan_detail`
--
ALTER TABLE `t_penjualan_detail`
  ADD CONSTRAINT `t_penjualan_detail_barang_id_foreign` FOREIGN KEY (`barang_id`) REFERENCES `m_barang` (`barang_id`),
  ADD CONSTRAINT `t_penjualan_detail_penjualan_id_foreign` FOREIGN KEY (`penjualan_id`) REFERENCES `t_penjualan` (`penjualan_id`);

--
-- Constraints for table `t_stok`
--
ALTER TABLE `t_stok`
  ADD CONSTRAINT `t_stok_barang_id_foreign` FOREIGN KEY (`barang_id`) REFERENCES `m_barang` (`barang_id`),
  ADD CONSTRAINT `t_stok_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `m_user` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

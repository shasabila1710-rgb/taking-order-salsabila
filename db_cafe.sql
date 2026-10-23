-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 23, 2026 at 01:15 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_cafe`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) UNSIGNED NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`, `nama_lengkap`, `email`, `created_at`, `updated_at`) VALUES
(1, 'admin', '$2y$10$wVhOipC1EODYGLhb/aEFXuPVxUyaaynJUA20A9mMD4QJB6zbf0Smm', 'Administrator', 'admin@suduttemu.com', '2026-10-23 07:20:19', '2026-10-23 09:44:12');

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE `menu` (
  `id` int(11) UNSIGNED NOT NULL,
  `nama_menu` varchar(100) NOT NULL,
  `kategori` varchar(50) NOT NULL,
  `harga` decimal(12,2) NOT NULL DEFAULT 0.00,
  `deskripsi` text DEFAULT NULL,
  `stok` int(11) NOT NULL DEFAULT 0,
  `gambar` varchar(255) DEFAULT NULL,
  `status` enum('tersedia','habis') NOT NULL DEFAULT 'tersedia',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`id`, `nama_menu`, `kategori`, `harga`, `deskripsi`, `stok`, `gambar`, `status`, `created_at`, `updated_at`) VALUES
(1, 'ube matcha', 'non-kopi', 23000.00, 'dingin', 19, 'menu_20261023170703_30b4aae7.jpg', 'tersedia', '2026-10-23 10:07:03', '2026-10-23 10:45:32'),
(2, 'stick kentang', 'makanan', 15000.00, 'dipanaskan', 45, 'menu_20261023171406_25ff7807.jpg', 'tersedia', '2026-10-23 10:14:06', '2026-10-23 10:45:32'),
(3, 'frappucino', 'kopi', 26000.00, '', 42, 'menu_20261023171716_da90dc9c.jpg', 'tersedia', '2026-10-23 10:17:16', '2026-10-23 10:17:16'),
(4, 'spaghetti bolognese', 'makanan', 32000.00, '', 31, 'menu_20261023171916_96f2d63e.jpg', 'tersedia', '2026-10-23 10:19:16', '2026-10-23 10:19:16'),
(5, 'dimsum mentai', 'makanan', 23000.00, 'dibakar lebih mateng', 24, 'menu_20261023172835_e7aac2d5.jpg', 'tersedia', '2026-10-23 10:28:35', '2026-10-23 10:28:35'),
(6, 'lemon tea', 'minuman', 20000.00, '', 45, 'menu_20261023173029_aabd97f2.jpg', 'tersedia', '2026-10-23 10:30:29', '2026-10-23 10:30:29');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) UNSIGNED NOT NULL,
  `kode_order` varchar(30) NOT NULL,
  `pelanggan_id` int(11) UNSIGNED NOT NULL,
  `no_meja` varchar(10) NOT NULL,
  `total_harga` decimal(14,2) NOT NULL DEFAULT 0.00,
  `status` enum('pending','diproses','selesai','dibatalkan') NOT NULL DEFAULT 'pending',
  `catatan` text DEFAULT NULL,
  `waktu_order` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `kode_order`, `pelanggan_id`, `no_meja`, `total_harga`, `status`, `catatan`, `waktu_order`) VALUES
(1, 'ORD-20261023174532-402', 1, 'TB-03', 38000.00, 'selesai', '', '2026-10-23 10:45:32');

-- --------------------------------------------------------

--
-- Table structure for table `order_detail`
--

CREATE TABLE `order_detail` (
  `id` int(11) UNSIGNED NOT NULL,
  `order_id` int(11) UNSIGNED NOT NULL,
  `menu_id` int(11) UNSIGNED NOT NULL,
  `nama_menu` varchar(100) NOT NULL,
  `harga` decimal(12,2) NOT NULL DEFAULT 0.00,
  `jumlah` int(11) NOT NULL DEFAULT 1,
  `subtotal` decimal(14,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_detail`
--

INSERT INTO `order_detail` (`id`, `order_id`, `menu_id`, `nama_menu`, `harga`, `jumlah`, `subtotal`) VALUES
(1, 1, 1, 'ube matcha', 23000.00, 1, 23000.00),
(2, 1, 2, 'stick kentang', 15000.00, 1, 15000.00);

-- --------------------------------------------------------

--
-- Table structure for table `pelanggan`
--

CREATE TABLE `pelanggan` (
  `id` int(11) UNSIGNED NOT NULL,
  `nama` varchar(100) NOT NULL,
  `no_meja` varchar(10) NOT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `waktu_datang` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pelanggan`
--

INSERT INTO `pelanggan` (`id`, `nama`, `no_meja`, `no_hp`, `waktu_datang`) VALUES
(1, 'salsa', 'TB-03', '', '2026-10-23 10:45:10');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_username` (`username`);

--
-- Indexes for table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_kategori` (`kategori`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_kode_order` (`kode_order`),
  ADD KEY `idx_pelanggan_id` (`pelanggan_id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_waktu_order` (`waktu_order`);

--
-- Indexes for table `order_detail`
--
ALTER TABLE `order_detail`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_order_id` (`order_id`),
  ADD KEY `idx_menu_id` (`menu_id`);

--
-- Indexes for table `pelanggan`
--
ALTER TABLE `pelanggan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_waktu_datang` (`waktu_datang`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `menu`
--
ALTER TABLE `menu`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `order_detail`
--
ALTER TABLE `order_detail`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `pelanggan`
--
ALTER TABLE `pelanggan`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_orders_pelanggan` FOREIGN KEY (`pelanggan_id`) REFERENCES `pelanggan` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `order_detail`
--
ALTER TABLE `order_detail`
  ADD CONSTRAINT `fk_detail_menu` FOREIGN KEY (`menu_id`) REFERENCES `menu` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_detail_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

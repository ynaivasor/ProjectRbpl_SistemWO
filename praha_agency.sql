-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 27, 2026 at 06:22 AM
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
-- Database: `praha_agency`
--

-- --------------------------------------------------------

--
-- Table structure for table `paket`
--

CREATE TABLE `paket` (
  `id_paket` int(11) NOT NULL,
  `nama_paket` varchar(100) NOT NULL,
  `harga` bigint(20) NOT NULL,
  `minimum_pax` int(11) NOT NULL,
  `facility` text NOT NULL,
  `contact_person` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `paket`
--

INSERT INTO `paket` (`id_paket`, `nama_paket`, `harga`, `minimum_pax`, `facility`, `contact_person`) VALUES
(1, 'Premium', 250000000, 100, 'Konsultasi konsep pernikahan tanpa batas\nPerencanaan timeline & rundown acara\nWedding Organizer full day (Hari H)\nKoordinasi seluruh vendor\nDekorasi premium (pelaminan, aisle, meja tamu)\nCatering untuk tamu (menu standar premium)\nMakeup Artist (akad & resepsi)\nDokumentasi foto & video\nMC & entertainment\nBuku Rantaman Digital (rundown online)\nReminder otomatis ke vendor (hari H)\nLaporan progres persiapan acara', '081801234567'),
(2, 'All In Exclusive', 400000000, 150, 'Konsultasi konsep pernikahan tanpa batas (unlimited meeting)\nPerencanaan timeline & rundown acara detail\nWedding Organizer full team (Hari H)\nKoordinasi seluruh vendor & technical meeting\nDekorasi super premium custom design\nCatering premium international menu\nMakeup Artist profesional (akad & resepsi)\nDokumentasi foto, video & cinematic wedding film\nDrone documentation\nMC profesional & live band\nEntertainment tambahan (traditional performance / modern dance)\nBuku Rantaman Digital (rundown online real-time)\nReminder otomatis ke seluruh vendor (H-30 sampai hari H)\nWedding website & undangan digital premium\nSouvenir premium custom design\nLuxury bridal car + driver\nLaporan progres persiapan acara lengkap & real-time monitoring', '081801234567'),
(3, 'Hemat', 100000000, 50, 'Konsultasi konsep pernikahan (2x meeting)\nPerencanaan rundown acara sederhana\nWedding Organizer pada hari H (tim inti)\nKoordinasi vendor utama\nDekorasi standar (pelaminan & meja tamu)\nCatering standar lokal\nMakeup Artist untuk pengantin\nDokumentasi foto standar\nMC acara\nBuku Rantaman Digital (akses terbatas)\nLaporan progres persiapan acara', '081801234567');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id_role` int(11) NOT NULL,
  `role_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id_role`, `role_name`) VALUES
(1, 'client'),
(3, 'owner'),
(2, 'staff_wo'),
(4, 'vendor');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_users` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `id_role` int(11) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_users`, `full_name`, `username`, `id_role`, `password`) VALUES
(1, 'Rosa Viany Herdante Putri', 'oowcah', 1, 'ochacantik');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `paket`
--
ALTER TABLE `paket`
  ADD PRIMARY KEY (`id_paket`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id_role`),
  ADD UNIQUE KEY `role_name` (`role_name`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_users`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `id_role` (`id_role`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `paket`
--
ALTER TABLE `paket`
  MODIFY `id_paket` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id_role` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_users` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`id_role`) REFERENCES `roles` (`id_role`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

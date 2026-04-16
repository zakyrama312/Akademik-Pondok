-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Apr 16, 2026 at 10:29 AM
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
-- Database: `db_akademik`
--

-- --------------------------------------------------------

--
-- Table structure for table `jadwal_pengampu`
--

CREATE TABLE `jadwal_pengampu` (
  `id_pengampu` int(11) NOT NULL,
  `id_pengajar` int(11) NOT NULL,
  `id_mapel` int(11) NOT NULL,
  `kelas` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jadwal_pengampu`
--

INSERT INTO `jadwal_pengampu` (`id_pengampu`, `id_pengajar`, `id_mapel`, `kelas`) VALUES
(1, 1, 1, 'X');

-- --------------------------------------------------------

--
-- Table structure for table `mata_pelajaran`
--

CREATE TABLE `mata_pelajaran` (
  `id_mapel` int(11) NOT NULL,
  `nama_mapel` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mata_pelajaran`
--

INSERT INTO `mata_pelajaran` (`id_mapel`, `nama_mapel`) VALUES
(1, 'Matematika'),
(2, 'Bahasa Indonesia'),
(3, 'IPA');

-- --------------------------------------------------------

--
-- Table structure for table `nilai_akademik`
--

CREATE TABLE `nilai_akademik` (
  `id_nilai` int(11) NOT NULL,
  `id_santri` int(11) NOT NULL,
  `id_mapel` int(11) NOT NULL,
  `semester` enum('Ganjil','Genap') NOT NULL,
  `tahun_ajaran` varchar(20) NOT NULL,
  `nilai_angka` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `nilai_sikap_keaktifan`
--

CREATE TABLE `nilai_sikap_keaktifan` (
  `id_sikap` int(11) NOT NULL,
  `id_santri` int(11) NOT NULL,
  `semester` enum('Ganjil','Genap') NOT NULL,
  `tahun_ajaran` varchar(20) NOT NULL,
  `nilai_sikap` varchar(10) DEFAULT NULL,
  `nilai_keaktifan` varchar(10) DEFAULT NULL,
  `catatan_wali_kelas` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pembayaran`
--

CREATE TABLE `pembayaran` (
  `id_pembayaran` int(11) NOT NULL,
  `id_tagihan` int(11) NOT NULL,
  `tanggal_bayar` datetime DEFAULT current_timestamp(),
  `bukti_transfer` varchar(255) NOT NULL,
  `status_acc` enum('Pending','Diterima','Ditolak') DEFAULT 'Pending',
  `catatan_admin` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pembayaran`
--

INSERT INTO `pembayaran` (`id_pembayaran`, `id_tagihan`, `tanggal_bayar`, `bukti_transfer`, `status_acc`, `catatan_admin`) VALUES
(1, 1, '2026-04-16 15:21:29', 'tf_1776327689_69e09c0906e9d.png', 'Diterima', '');

-- --------------------------------------------------------

--
-- Table structure for table `pengajar`
--

CREATE TABLE `pengajar` (
  `id_pengajar` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `nip` varchar(50) DEFAULT NULL,
  `nama_pengajar` varchar(100) NOT NULL,
  `no_hp` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pengajar`
--

INSERT INTO `pengajar` (`id_pengajar`, `id_user`, `nip`, `nama_pengajar`, `no_hp`) VALUES
(1, 6, '', 'Santi, S.Pd', '0851235464');

-- --------------------------------------------------------

--
-- Table structure for table `santri`
--

CREATE TABLE `santri` (
  `id_santri` int(11) NOT NULL,
  `id_wali` int(11) DEFAULT NULL,
  `nis` varchar(50) NOT NULL,
  `nama_santri` varchar(100) NOT NULL,
  `kelas` varchar(20) NOT NULL,
  `status_aktif` enum('Aktif','Lulus','Pindah') DEFAULT 'Aktif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `santri`
--

INSERT INTO `santri` (`id_santri`, `id_wali`, `nis`, `nama_santri`, `kelas`, `status_aktif`) VALUES
(1, 1, '123456', 'Ahmad Rohyan', 'X', 'Aktif');

-- --------------------------------------------------------

--
-- Table structure for table `tagihan`
--

CREATE TABLE `tagihan` (
  `id_tagihan` int(11) NOT NULL,
  `id_santri` int(11) NOT NULL,
  `jenis_tagihan` enum('SPP','Daftar Ulang') NOT NULL,
  `bulan` varchar(20) DEFAULT NULL,
  `tahun` varchar(10) NOT NULL,
  `nominal` int(11) NOT NULL,
  `status_tagihan` enum('Belum Lunas','Menunggu Konfirmasi','Lunas') DEFAULT 'Belum Lunas'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tagihan`
--

INSERT INTO `tagihan` (`id_tagihan`, `id_santri`, `jenis_tagihan`, `bulan`, `tahun`, `nominal`, `status_tagihan`) VALUES
(1, 1, 'SPP', 'Januari', '2026', 120000, 'Lunas');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','pengajar','walisantri','pimpinan') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_user`, `username`, `password`, `role`, `created_at`) VALUES
(1, 'admin', 'e10adc3949ba59abbe56e057f20f883e', 'admin', '2026-04-16 05:01:36'),
(2, 'guru_ahmad', 'e10adc3949ba59abbe56e057f20f883e', 'pengajar', '2026-04-16 05:01:36'),
(3, 'wali_budi', 'e10adc3949ba59abbe56e057f20f883e', 'walisantri', '2026-04-16 05:01:36'),
(4, 'pimpinan_yayasan', 'e10adc3949ba59abbe56e057f20f883e', 'pimpinan', '2026-04-16 05:01:36'),
(5, 'rahmat', 'e10adc3949ba59abbe56e057f20f883e', 'walisantri', '2026-04-16 05:51:59'),
(6, 'santi', 'e10adc3949ba59abbe56e057f20f883e', 'pengajar', '2026-04-16 06:14:31');

-- --------------------------------------------------------

--
-- Table structure for table `wali_santri`
--

CREATE TABLE `wali_santri` (
  `id_wali` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `nama_ayah` varchar(100) DEFAULT NULL,
  `nama_ibu` varchar(100) DEFAULT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `alamat` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `wali_santri`
--

INSERT INTO `wali_santri` (`id_wali`, `id_user`, `nama_ayah`, `nama_ibu`, `no_hp`, `alamat`) VALUES
(1, 5, 'Rahmat', 'Maryam', '0851235464', 'Songgom');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `jadwal_pengampu`
--
ALTER TABLE `jadwal_pengampu`
  ADD PRIMARY KEY (`id_pengampu`),
  ADD KEY `id_pengajar` (`id_pengajar`),
  ADD KEY `id_mapel` (`id_mapel`);

--
-- Indexes for table `mata_pelajaran`
--
ALTER TABLE `mata_pelajaran`
  ADD PRIMARY KEY (`id_mapel`);

--
-- Indexes for table `nilai_akademik`
--
ALTER TABLE `nilai_akademik`
  ADD PRIMARY KEY (`id_nilai`),
  ADD KEY `id_santri` (`id_santri`),
  ADD KEY `id_mapel` (`id_mapel`);

--
-- Indexes for table `nilai_sikap_keaktifan`
--
ALTER TABLE `nilai_sikap_keaktifan`
  ADD PRIMARY KEY (`id_sikap`),
  ADD KEY `id_santri` (`id_santri`);

--
-- Indexes for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD PRIMARY KEY (`id_pembayaran`),
  ADD KEY `id_tagihan` (`id_tagihan`);

--
-- Indexes for table `pengajar`
--
ALTER TABLE `pengajar`
  ADD PRIMARY KEY (`id_pengajar`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `santri`
--
ALTER TABLE `santri`
  ADD PRIMARY KEY (`id_santri`),
  ADD UNIQUE KEY `nis` (`nis`),
  ADD KEY `id_wali` (`id_wali`);

--
-- Indexes for table `tagihan`
--
ALTER TABLE `tagihan`
  ADD PRIMARY KEY (`id_tagihan`),
  ADD KEY `id_santri` (`id_santri`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `wali_santri`
--
ALTER TABLE `wali_santri`
  ADD PRIMARY KEY (`id_wali`),
  ADD KEY `id_user` (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `jadwal_pengampu`
--
ALTER TABLE `jadwal_pengampu`
  MODIFY `id_pengampu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `mata_pelajaran`
--
ALTER TABLE `mata_pelajaran`
  MODIFY `id_mapel` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `nilai_akademik`
--
ALTER TABLE `nilai_akademik`
  MODIFY `id_nilai` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `nilai_sikap_keaktifan`
--
ALTER TABLE `nilai_sikap_keaktifan`
  MODIFY `id_sikap` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pembayaran`
--
ALTER TABLE `pembayaran`
  MODIFY `id_pembayaran` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pengajar`
--
ALTER TABLE `pengajar`
  MODIFY `id_pengajar` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `santri`
--
ALTER TABLE `santri`
  MODIFY `id_santri` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tagihan`
--
ALTER TABLE `tagihan`
  MODIFY `id_tagihan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `wali_santri`
--
ALTER TABLE `wali_santri`
  MODIFY `id_wali` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `jadwal_pengampu`
--
ALTER TABLE `jadwal_pengampu`
  ADD CONSTRAINT `jadwal_pengampu_ibfk_1` FOREIGN KEY (`id_pengajar`) REFERENCES `pengajar` (`id_pengajar`) ON DELETE CASCADE,
  ADD CONSTRAINT `jadwal_pengampu_ibfk_2` FOREIGN KEY (`id_mapel`) REFERENCES `mata_pelajaran` (`id_mapel`) ON DELETE CASCADE;

--
-- Constraints for table `nilai_akademik`
--
ALTER TABLE `nilai_akademik`
  ADD CONSTRAINT `nilai_akademik_ibfk_1` FOREIGN KEY (`id_santri`) REFERENCES `santri` (`id_santri`) ON DELETE CASCADE,
  ADD CONSTRAINT `nilai_akademik_ibfk_2` FOREIGN KEY (`id_mapel`) REFERENCES `mata_pelajaran` (`id_mapel`) ON DELETE CASCADE;

--
-- Constraints for table `nilai_sikap_keaktifan`
--
ALTER TABLE `nilai_sikap_keaktifan`
  ADD CONSTRAINT `nilai_sikap_keaktifan_ibfk_1` FOREIGN KEY (`id_santri`) REFERENCES `santri` (`id_santri`) ON DELETE CASCADE;

--
-- Constraints for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD CONSTRAINT `pembayaran_ibfk_1` FOREIGN KEY (`id_tagihan`) REFERENCES `tagihan` (`id_tagihan`) ON DELETE CASCADE;

--
-- Constraints for table `pengajar`
--
ALTER TABLE `pengajar`
  ADD CONSTRAINT `pengajar_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE;

--
-- Constraints for table `santri`
--
ALTER TABLE `santri`
  ADD CONSTRAINT `santri_ibfk_1` FOREIGN KEY (`id_wali`) REFERENCES `wali_santri` (`id_wali`) ON DELETE SET NULL;

--
-- Constraints for table `tagihan`
--
ALTER TABLE `tagihan`
  ADD CONSTRAINT `tagihan_ibfk_1` FOREIGN KEY (`id_santri`) REFERENCES `santri` (`id_santri`) ON DELETE CASCADE;

--
-- Constraints for table `wali_santri`
--
ALTER TABLE `wali_santri`
  ADD CONSTRAINT `wali_santri_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

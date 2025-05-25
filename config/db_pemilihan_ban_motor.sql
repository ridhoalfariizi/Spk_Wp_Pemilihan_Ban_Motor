-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 25 Bulan Mei 2025 pada 16.07
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_pemilihan_ban_motor`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `alternatif`
--

CREATE TABLE `alternatif` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `c1_ukuran` int(11) NOT NULL,
  `c1_range` varchar(50) DEFAULT NULL,
  `c2_tipe` int(11) NOT NULL,
  `c2_range` varchar(50) DEFAULT NULL,
  `c3_beban` int(11) NOT NULL,
  `c3_range` varchar(50) DEFAULT NULL,
  `c4_harga` int(11) NOT NULL,
  `c4_range` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `alternatif`
--

INSERT INTO `alternatif` (`id`, `nama`, `c1_ukuran`, `c1_range`, `c2_tipe`, `c2_range`, `c3_beban`, `c3_range`, `c4_harga`, `c4_range`) VALUES
(31, 'IRC GP-210F Series ', 80, 'Standar', 60, 'Onroad', 432, 'Berat', 190000, 'Murah'),
(32, 'FDR Genzi PRO', 90, 'Standar', 55, 'Onroad', 412, 'Berat', 175000, 'Murah'),
(33, 'Dunlop TT900', 80, 'Standar', 65, 'Onroad', 425, 'Berat', 198000, 'Murah'),
(34, 'Zeneos STRATO', 100, 'Besar', 66, 'Onroad', 450, 'Berat', 185000, 'Murah'),
(35, 'Maxxis M6029W ', 80, 'Standar', 55, 'Onroad', 435, 'Berat', 170000, 'Murah');

-- --------------------------------------------------------

--
-- Struktur dari tabel `hasil`
--

CREATE TABLE `hasil` (
  `id_alternatif` int(11) DEFAULT NULL,
  `nilai_s` double DEFAULT NULL,
  `nilai_v` double DEFAULT NULL,
  `ranking` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `hasil`
--

INSERT INTO `hasil` (`id_alternatif`, `nilai_s`, `nilai_v`, `ranking`) VALUES
(34, 41.827337494932, 0.21649400338474, 1),
(33, 38.262568864258, 0.1980431270389, 2),
(31, 37.890680385471, 0.19611827046405, 3),
(32, 37.693977676982, 0.19510015744544, 4),
(35, 37.528650637383, 0.19424444166686, 5);

-- --------------------------------------------------------

--
-- Struktur dari tabel `kriteria`
--

CREATE TABLE `kriteria` (
  `id` int(11) NOT NULL,
  `nama_kriteria` varchar(50) NOT NULL,
  `bobot` int(11) NOT NULL,
  `jenis` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kriteria`
--

INSERT INTO `kriteria` (`id`, `nama_kriteria`, `bobot`, `jenis`) VALUES
(1, 'Ukuran', 3, 'benefit'),
(2, 'Tipe', 3, 'benefit'),
(3, 'Beban Maksimal', 5, 'benefit'),
(4, 'Harga', 1, 'cost');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kriteria_range`
--

CREATE TABLE `kriteria_range` (
  `id` int(11) NOT NULL,
  `kriteria_id` int(11) NOT NULL,
  `nama_range` varchar(50) NOT NULL,
  `min_value` int(11) NOT NULL,
  `max_value` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kriteria_range`
--

INSERT INTO `kriteria_range` (`id`, `kriteria_id`, `nama_range`, `min_value`, `max_value`) VALUES
(1, 1, 'Kecil', 50, 70),
(2, 1, 'Standar', 71, 90),
(3, 1, 'Besar', 91, 130),
(4, 2, 'Offroad', 0, 33),
(5, 2, 'Onroad', 34, 66),
(6, 2, 'Racing', 67, 100),
(7, 3, 'Ringan', 0, 299),
(8, 3, 'Sedang', 300, 400),
(9, 3, 'Berat', 401, 1000),
(10, 4, 'Murah', 0, 199999),
(11, 4, 'Standar', 200000, 400000),
(12, 4, 'Mahal', 401000, 1000000);

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `password`) VALUES
(1, 'admin', '0192023a7bbd73250516f069df18b500');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `alternatif`
--
ALTER TABLE `alternatif`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `hasil`
--
ALTER TABLE `hasil`
  ADD KEY `id_alternatif` (`id_alternatif`);

--
-- Indeks untuk tabel `kriteria`
--
ALTER TABLE `kriteria`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `kriteria_range`
--
ALTER TABLE `kriteria_range`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kriteria_id` (`kriteria_id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `alternatif`
--
ALTER TABLE `alternatif`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT untuk tabel `kriteria_range`
--
ALTER TABLE `kriteria_range`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `hasil`
--
ALTER TABLE `hasil`
  ADD CONSTRAINT `hasil_ibfk_1` FOREIGN KEY (`id_alternatif`) REFERENCES `alternatif` (`id`);

--
-- Ketidakleluasaan untuk tabel `kriteria_range`
--
ALTER TABLE `kriteria_range`
  ADD CONSTRAINT `kriteria_range_ibfk_1` FOREIGN KEY (`kriteria_id`) REFERENCES `kriteria` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

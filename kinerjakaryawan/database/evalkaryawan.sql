-- phpMyAdmin SQL Dump
-- version 2.11.9.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 28, 2011 at 06:00 AM
-- Server version: 5.0.67
-- PHP Version: 5.2.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `evalkaryawan`
--

-- --------------------------------------------------------

--
-- Table structure for table `bobot_kriteria`
--

CREATE TABLE IF NOT EXISTS `bobot_kriteria` (
  `id_kriteria` varchar(5) collate latin1_general_ci NOT NULL,
  `nama_kriteria` varchar(50) collate latin1_general_ci NOT NULL,
  `bobot` float NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `bobot_kriteria`
--

INSERT INTO `bobot_kriteria` (`id_kriteria`, `nama_kriteria`, `bobot`) VALUES
('1', 'Kedisiplinan', 0.189),
('2', 'Prestasi Kerja', 0.098),
('3', 'Pengalaman Kerja', 0.666),
('4', 'Perilaku', 0.047);

-- --------------------------------------------------------

--
-- Table structure for table `evaluasi`
--

CREATE TABLE IF NOT EXISTS `evaluasi` (
  `nip` varchar(5) collate latin1_general_ci NOT NULL,
  `id_kriteria` varchar(5) collate latin1_general_ci NOT NULL,
  `nilai` int(5) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `evaluasi`
--

INSERT INTO `evaluasi` (`nip`, `id_kriteria`, `nilai`) VALUES
('10102', '4', 80),
('10102', '3', 75),
('10102', '2', 70),
('10102', '1', 72),
('10103', '1', 71),
('10103', '2', 73),
('10103', '3', 86),
('10103', '4', 70),
('10104', '1', 70),
('10104', '2', 70),
('10104', '3', 80),
('10104', '4', 70),
('10105', '1', 90),
('10105', '2', 80),
('10105', '3', 80),
('10105', '4', 80);

-- --------------------------------------------------------

--
-- Table structure for table `hasil_evaluasi`
--

CREATE TABLE IF NOT EXISTS `hasil_evaluasi` (
  `nip` varchar(5) collate latin1_general_ci NOT NULL,
  `total_nilai` float NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `hasil_evaluasi`
--

INSERT INTO `hasil_evaluasi` (`nip`, `total_nilai`) VALUES
('10105', 81.89),
('10104', 76.66),
('10103', 81.139),
('10102', 74.178);

-- --------------------------------------------------------

--
-- Table structure for table `karyawan`
--

CREATE TABLE IF NOT EXISTS `karyawan` (
  `nip` varchar(5) collate latin1_general_ci NOT NULL,
  `nama_karyawan` varchar(30) collate latin1_general_ci NOT NULL,
  `jabatan` varchar(30) collate latin1_general_ci NOT NULL,
  `divisi` varchar(30) collate latin1_general_ci NOT NULL,
  `alamat` varchar(50) collate latin1_general_ci NOT NULL,
  `telp` varchar(30) collate latin1_general_ci NOT NULL,
  PRIMARY KEY  (`nip`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `karyawan`
--

INSERT INTO `karyawan` (`nip`, `nama_karyawan`, `jabatan`, `divisi`, `alamat`, `telp`) VALUES
('10101', 'Karen Wijaya', 'Manajer', 'Marketing', 'Jl. Mawar No.8 Jakarta Barat', '0812888123'),
('10102', 'Karin Lidya', 'Staf', 'Marketing', 'Jl. Indah No.9 Jakarta Barat', '082190986752'),
('10103', 'Bob Anderson', 'Staf', 'Marketing', 'Jl. Angin No.10 Jakarta Barat', '085390981567'),
('10104', 'Steven Willem', 'Staf', 'Marketing', 'Jl. Maju No.2 Jakarta Barat', '085298761234'),
('10105', 'Andrea Sari', 'Staf', 'Marketing', 'Jl. Melati No.5 Jakarta Barat', '081390785645');

-- --------------------------------------------------------

--
-- Table structure for table `konsistensi`
--

CREATE TABLE IF NOT EXISTS `konsistensi` (
  `cr` float NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `konsistensi`
--

INSERT INTO `konsistensi` (`cr`) VALUES
(0.099);

-- --------------------------------------------------------

--
-- Table structure for table `kriteria`
--

CREATE TABLE IF NOT EXISTS `kriteria` (
  `id_kriteria` varchar(5) collate latin1_general_ci NOT NULL,
  `nama_kriteria` varchar(50) collate latin1_general_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `kriteria`
--

INSERT INTO `kriteria` (`id_kriteria`, `nama_kriteria`) VALUES
('1', 'Kedisiplinan'),
('2', 'Prestasi Kerja'),
('3', 'Pengalaman Kerja'),
('4', 'Perilaku');

-- --------------------------------------------------------

--
-- Table structure for table `matrik_kriteria`
--

CREATE TABLE IF NOT EXISTS `matrik_kriteria` (
  `indeks` int(5) NOT NULL auto_increment,
  `id_kriteria` varchar(5) collate latin1_general_ci NOT NULL,
  `id_bandingan` varchar(5) collate latin1_general_ci NOT NULL,
  `nilai` float NOT NULL,
  PRIMARY KEY  (`indeks`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=131 ;

--
-- Dumping data for table `matrik_kriteria`
--

INSERT INTO `matrik_kriteria` (`indeks`, `id_kriteria`, `id_bandingan`, `nilai`) VALUES
(115, '1', '1', 1),
(116, '2', '2', 1),
(117, '3', '3', 1),
(118, '4', '4', 1),
(119, '1', '2', 3),
(120, '1', '3', 0.14),
(121, '1', '4', 5),
(122, '2', '3', 0.14),
(123, '2', '4', 3),
(124, '3', '4', 9),
(125, '4', '3', 0.11),
(126, '4', '2', 0.33),
(127, '4', '1', 0.2),
(128, '3', '2', 7.14),
(129, '3', '1', 7.14),
(130, '2', '1', 0.33);

-- --------------------------------------------------------

--
-- Table structure for table `matrik_normalisasi_kriteria`
--

CREATE TABLE IF NOT EXISTS `matrik_normalisasi_kriteria` (
  `indeks` int(5) NOT NULL auto_increment,
  `id_kriteria` varchar(5) collate latin1_general_ci NOT NULL,
  `id_bandingan` varchar(5) collate latin1_general_ci NOT NULL,
  `nilai` float NOT NULL,
  PRIMARY KEY  (`indeks`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=129 ;

--
-- Dumping data for table `matrik_normalisasi_kriteria`
--

INSERT INTO `matrik_normalisasi_kriteria` (`indeks`, `id_kriteria`, `id_bandingan`, `nilai`) VALUES
(113, '1', '1', 0.115),
(114, '1', '2', 0.038),
(115, '1', '3', 0.824),
(116, '1', '4', 0.023),
(117, '2', '1', 0.262),
(118, '2', '2', 0.087),
(119, '2', '3', 0.622),
(120, '2', '4', 0.029),
(121, '3', '1', 0.101),
(122, '3', '2', 0.101),
(123, '3', '3', 0.719),
(124, '3', '4', 0.079),
(125, '4', '1', 0.278),
(126, '4', '2', 0.167),
(127, '4', '3', 0.5),
(128, '4', '4', 0.056);

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE IF NOT EXISTS `menu` (
  `menu` varchar(30) collate latin1_general_ci NOT NULL,
  `link` varchar(50) collate latin1_general_ci NOT NULL,
  `status` enum('admin','user') collate latin1_general_ci NOT NULL,
  `aktif` enum('y','n') collate latin1_general_ci NOT NULL,
  `urutan` int(5) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`menu`, `link`, `status`, `aktif`, `urutan`) VALUES
('Data Karyawan', '?modul=dtakary', 'admin', 'y', 1),
('Kriteria Penilaian', '?modul=kriteria', 'admin', 'y', 2),
('Bobot Kriteria', '?modul=bobot', 'admin', 'y', 3),
('Skor Penilaian', '?modul=evaluasi', 'admin', 'y', 4),
('Laporan Evaluasi Kinerja', '?modul=laporan', 'admin', 'y', 5),
('Profil', '?modul=profil', 'user', 'y', 1),
('Hasil Evaluasi', '?modul=hasil', 'user', 'y', 2);

-- --------------------------------------------------------

--
-- Table structure for table `pengguna`
--

CREATE TABLE IF NOT EXISTS `pengguna` (
  `nip` varchar(5) collate latin1_general_ci NOT NULL,
  `username` varchar(10) collate latin1_general_ci NOT NULL,
  `password` varchar(32) collate latin1_general_ci NOT NULL,
  `level` enum('admin','user') collate latin1_general_ci NOT NULL default 'user',
  PRIMARY KEY  (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `pengguna`
--

INSERT INTO `pengguna` (`nip`, `username`, `password`, `level`) VALUES
('10101', 'karen', 'ba952731f97fb058035aa399b1cb3d5c', 'admin'),
('10102', 'karin', '97468f1980416a4376b44e701d25f24b', 'user'),
('10103', 'bob', '9f9d51bc70ef21ca5c14f307980a29d8', 'user'),
('10104', 'steven', '6ed61d4b80bb0f81937b32418e98adca', 'user'),
('10105', 'andrea', '1c42f9c1ca2f65441465b43cd9339d6c', 'user');

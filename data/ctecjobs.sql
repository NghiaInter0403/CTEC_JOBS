-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 12, 2026 at 02:10 PM
-- Server version: 8.0.40
-- PHP Version: 8.3.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ctecjobs`
--

-- --------------------------------------------------------

--
-- Table structure for table `donungvien`
--

CREATE TABLE `donungvien` (
  `id` int NOT NULL,
  `idvieclam` int DEFAULT NULL,
  `idsinhvien` int DEFAULT NULL,
  `ngaynop` datetime DEFAULT CURRENT_TIMESTAMP,
  `trangthai` enum('choxuly','daxem','tuchoi','chapnhan') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'choxuly'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `donungvien`
--

INSERT INTO `donungvien` (`id`, `idvieclam`, `idsinhvien`, `ngaynop`, `trangthai`) VALUES
(15, 26, 7, '2025-12-08 12:38:59', 'chapnhan'),
(20, 45, 42, '2026-04-26 13:14:12', 'choxuly');

-- --------------------------------------------------------

--
-- Table structure for table `hosonhatuyendung`
--

CREATE TABLE `hosonhatuyendung` (
  `idnguoidung` int NOT NULL,
  `tencongty` varchar(150) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `sodienthoai` varchar(15) DEFAULT NULL,
  `emailcongty` varchar(150) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `diachi` text,
  `mota` text,
  `quymo` enum('1-10','11-50','51-100','100+') DEFAULT NULL,
  `ngaycapnhat` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `hosonhatuyendung`
--

INSERT INTO `hosonhatuyendung` (`idnguoidung`, `tencongty`, `logo`, `sodienthoai`, `emailcongty`, `website`, `diachi`, `mota`, `quymo`, `ngaycapnhat`) VALUES
(41, 'công ty a', '1777187102_dienytb tt.jpg', '1234567890', 'phat1@gmail.com', 'không có', 'cần thơ', 'làm việc khó', '1-10', '2026-04-26 14:05:02');

-- --------------------------------------------------------

--
-- Table structure for table `hosoungvien`
--

CREATE TABLE `hosoungvien` (
  `idnguoidung` int NOT NULL,
  `sodienthoai` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `diachi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `duongdancv` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kynang` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hosoungvien`
--

INSERT INTO `hosoungvien` (`idnguoidung`, `sodienthoai`, `diachi`, `duongdancv`, `kynang`) VALUES
(7, '0352755926', 'CÀ MAU', 'uploads/Screenshot 2025-11-29 131750.png', 'Lập trình web'),
(9, '0352755926', 'Bạc Liêu', 'uploads/Screenshot 2025-11-29 131750.png', 'HTML, CSS, PHP, MySQL, FIGMA, GIT, GITHUB'),
(42, '123123123', '123123123123', 'uploads/HS_42_1777184534.png', '123123123123');

-- --------------------------------------------------------

--
-- Table structure for table `nguoidung`
--

CREATE TABLE `nguoidung` (
  `id` int NOT NULL,
  `hoten` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `username` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `matkhau` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `vaitro` enum('sinhvien','nhatuyendung','quantrivien') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'sinhvien',
  `ngaytao` datetime DEFAULT CURRENT_TIMESTAMP,
  `avatar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `nguoidung`
--

INSERT INTO `nguoidung` (`id`, `hoten`, `username`, `email`, `matkhau`, `vaitro`, `ngaytao`, `avatar`) VALUES
(6, 'Công Ty A', 'A@gmail.com', NULL, '$2y$10$Y1yNhBjowF.ev1lsBlclPuwOBSPqyufWWSWY0E8K52uX3mtd29I7u', 'nhatuyendung', '2025-11-17 18:25:59', NULL),
(7, 'SinhVien', 'SV@gmail.com', NULL, '$2y$10$VqPu5J4HK1qBoBg0spd4JOeyEFt9oZXgLQ6gPMv9zjUSBuZm6vYA.', 'sinhvien', '2025-11-17 18:26:30', NULL),
(8, 'Admin', 'Ad@gmail.com', NULL, '$2y$10$hqA2J2ZFWKMTQotIrQrSw.iGs6vYZcNBJpd2yWyI7fBe.Ik40Eodq', 'quantrivien', '2025-11-17 18:26:51', NULL),
(9, 'Nghĩa', 'Nghia@gmail.com', NULL, '$2y$10$dKeTUJIlt/EJwWp0txpXNeGkAq27D.1.nTLXMMddSJuPxqXi5dVjW', 'sinhvien', '2025-11-20 22:02:18', NULL),
(10, 'Phát', 'Phat@gmail.com', NULL, '$2y$10$xwoUT39Y.azbZ9K5C3nlp.DkY8Yrb07q.M83A.oqMF0oV04SCpnCG', 'sinhvien', '2025-11-24 14:49:05', NULL),
(11, 'Công Ty Beta', 'B@gmail.com', NULL, '$2y$10$5I.QGGBrUZAYGtkjkPQFQ.GSYq9YaUCrZTSFocYArYSVk05Xl.Uga', 'nhatuyendung', '2025-12-05 13:57:48', NULL),
(12, 'Công ty CTEC', 'C@gmail.com', NULL, '$2y$10$KtgVwvMg00kvHBjLEkXOX.NtYqfJKXy9zl.1Ml9Tv3MJAfkSVo./S', 'nhatuyendung', '2025-12-05 13:58:26', NULL),
(13, 'Công Ty Dental', 'D@gmail.com', NULL, '$2y$10$P8yr5efGsqyC/nnLXjS4ielajNtv4EJpTQyhliGqQ4G85VzLpVQZ.', 'nhatuyendung', '2025-12-05 13:58:51', NULL),
(14, 'Công ty Enter', 'Egmail.com', NULL, '$2y$10$kezH.oeqT6vW68gkUGC5c.zqch1OT/Y8GIqio52XklFN58KbGU4zK', 'nhatuyendung', '2025-12-05 13:59:26', NULL),
(15, 'Công ty Fluter', 'F@gmail.com', NULL, '$2y$10$0GdpW7GeMbZZC5RODxftU.hkQw9zX2OE5M2A2mmFHjgdaawRjpBDK', 'nhatuyendung', '2025-12-05 13:59:51', NULL),
(16, 'Công Ty Gaytar', 'G@gmail.com', NULL, '$2y$10$u.JLqHsAokSHF27lcydQCuZqs96en41Pk9X2zGseFMkMKF.VdMXOK', 'nhatuyendung', '2025-12-05 14:00:21', NULL),
(17, 'Công Ty Hennry', 'H@gmail.com', NULL, '$2y$10$dhvCYYjIql51HLoTsAtY.uu9nleKZs2yOn5kois9LjW36hHz/7ZLC', 'nhatuyendung', '2025-12-05 14:00:45', NULL),
(19, 'phat huynh', 'p@gmail.com', NULL, '$2y$10$vgzt1m9Sac2U5PGcfI4wguGHSP0R6IiHUIHHm9nQZ12dKQ0Qo77e.', 'sinhvien', '2025-12-08 20:48:46', NULL),
(20, 'Kim Jong Un', 'kju@gmail.com', NULL, '$2y$10$ocAg4bxQ5lc7djYmj96CSekNE0qXRA3ClBqEQvATdwd6wDJYmpzC6', 'sinhvien', '2025-12-08 20:55:58', NULL),
(22, 'admin1', 'ad1@gmail.com', NULL, '$2y$10$1vVYlsBBCMLHMvIbHRpaQurHliCONZWKQxCIkEnMM0iQdk4Mdu1xG', 'quantrivien', '2026-03-03 08:22:46', NULL),
(23, 'ntd', 'ntd@gmail.com', NULL, '$2y$10$SYpjOPjFPA73f1Uo7FuROeBfojXS1./dgNhg7ZP7Xb/SumBosEz7O', 'nhatuyendung', '2026-03-03 08:27:02', NULL),
(24, 'sv1', 'sv1@gmail.com', NULL, '$2y$10$CGCleqo8Tv2af5mCXJJy0ODDYS7mUH1EHUduqsgEhPZ6d10eTKY2q', 'sinhvien', '2026-03-03 08:31:54', NULL),
(25, 'phat', 'ad123@gmail.com', NULL, '$2y$10$uDta9NsH1QQS9BKaNRB7IutSKrL006CUIfjG2N2OPZZsTmOdxunMm', 'quantrivien', '2026-04-06 09:57:50', NULL),
(26, 'phat', 'admin', NULL, '$2y$10$fUF9b1zS/M9/lcjg1ELU1uzPKAL4mVK0sYAbzc69m38G3WvAOZXHC', 'quantrivien', '2026-04-06 10:07:42', 'avatar_69eb04e21aa9b1.63491787.jpg'),
(40, 'ntd', 'ntd', NULL, '$2y$10$dqqYjkLRQ762SVSPnWf7U.aZ7BkFR6dRIbGoGvB0./5.j8Jiylj6.', 'nhatuyendung', '2026-04-06 14:21:13', NULL),
(41, 'ntd', 'ntd11', NULL, '$2y$10$R6sZzghNCIigpXFRY9JaBuErjKcrarK8FRXtZ.HOtnpPfZIQaC.N6', 'nhatuyendung', '2026-04-06 14:21:26', '1776994152_dienytb tt.jpg'),
(42, 'phat', 'phat', NULL, '$2y$10$u2X5JLqB16k7OcZmG6/NE.zdUOFvH4bU.jDm8rvS.1.k77BrVqpwm', 'sinhvien', '2026-04-07 18:39:03', 'avatar_69edb3bd144d78.34485478.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `otp_reset`
--

CREATE TABLE `otp_reset` (
  `id` int NOT NULL,
  `username` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `email` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `otp` varchar(6) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `expire` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `otp_reset`
--

INSERT INTO `otp_reset` (`id`, `username`, `email`, `otp`, `expire`, `created_at`) VALUES
(1, 'phat1', 'phat1@gmail.com', '878567', '2026-04-20 09:31:19', '2026-04-20 09:26:19'),
(6, 'phat2', 'phtproqua@gmail.com', '369033', '2026-04-22 06:08:47', '2026-04-22 06:03:47');

-- --------------------------------------------------------

--
-- Table structure for table `thongke`
--

CREATE TABLE `thongke` (
  `id` int NOT NULL,
  `trang` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `solanxem` int DEFAULT '1',
  `lanxemcuoi` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `thongke`
--

INSERT INTO `thongke` (`id`, `trang`, `solanxem`, `lanxemcuoi`) VALUES
(1, 'job_1', 1, '2025-11-15 18:22:39'),
(2, 'job_1', 1, '2025-11-15 18:29:24'),
(3, 'job_2', 1, '2025-11-15 18:35:25'),
(4, 'job_2', 1, '2025-11-15 18:35:49'),
(5, 'job_1', 1, '2025-11-15 18:35:53'),
(6, 'Việc làm_1', 1, '2025-11-17 18:24:04'),
(7, 'Việc làm_2', 1, '2025-11-17 18:24:10'),
(8, 'Việc làm_3', 1, '2025-11-17 19:24:13'),
(9, 'Việc làm_6', 1, '2025-11-20 14:59:11'),
(10, 'Việc làm_6', 1, '2025-11-20 15:00:30'),
(11, 'Việc làm_6', 1, '2025-11-20 15:27:28'),
(12, 'Việc làm_6', 1, '2025-11-20 21:58:48'),
(13, 'Việc làm_6', 1, '2025-11-20 21:58:50'),
(14, 'Việc làm_4', 1, '2025-11-20 22:06:35'),
(15, 'Việc làm_4', 1, '2025-11-20 22:06:36'),
(16, 'Việc làm_6', 1, '2025-11-20 22:13:17'),
(17, 'Việc làm_6', 1, '2025-11-20 22:13:34'),
(18, 'Việc làm_6', 1, '2025-11-20 22:13:35'),
(19, 'Việc làm_7', 1, '2025-11-23 11:56:46'),
(20, 'Việc làm_7', 1, '2025-11-23 11:56:47'),
(21, 'Việc làm_7', 1, '2025-11-25 21:16:34'),
(22, 'Việc làm_7', 1, '2025-11-25 21:16:36'),
(23, 'Việc làm_8', 1, '2025-11-25 21:16:40'),
(24, 'Việc làm_8', 1, '2025-11-25 21:16:42'),
(25, 'Việc làm_8', 1, '2025-11-25 21:16:44'),
(26, 'Việc làm_7', 1, '2025-11-25 21:16:46'),
(27, 'Việc làm_7', 1, '2025-11-28 10:43:09'),
(28, 'Việc làm_7', 1, '2025-11-28 12:51:02'),
(29, 'Việc làm_7', 1, '2025-11-28 13:16:06'),
(30, 'Việc làm_7', 1, '2025-11-28 13:20:51'),
(31, 'Việc làm_4', 1, '2025-11-29 18:57:43'),
(32, 'Việc làm_4', 1, '2025-11-29 18:57:44'),
(33, 'Việc làm_6', 1, '2025-11-29 18:57:47'),
(34, 'Việc làm_8', 1, '2025-11-29 18:57:50'),
(35, 'Việc làm_8', 1, '2025-11-29 18:57:50'),
(36, 'Việc làm_7', 1, '2025-11-29 18:57:52'),
(37, 'Việc làm_10', 1, '2025-11-29 19:01:40'),
(38, 'Việc làm_10', 1, '2025-11-29 19:01:42'),
(39, 'Việc làm_10', 1, '2025-11-29 19:16:23'),
(40, 'Việc làm_10', 1, '2025-11-29 19:16:24'),
(41, 'Việc làm_8', 1, '2025-11-29 19:16:26'),
(42, 'Việc làm_8', 1, '2025-11-29 19:16:27'),
(43, 'Việc làm_7', 1, '2025-11-29 19:16:29'),
(44, 'Việc làm_7', 1, '2025-11-29 19:16:29'),
(45, 'Việc làm_6', 1, '2025-11-29 19:16:31'),
(46, 'Việc làm_6', 1, '2025-11-29 19:16:32'),
(47, 'Việc làm_4', 1, '2025-11-29 19:16:35'),
(48, 'Việc làm_4', 1, '2025-11-29 19:16:36'),
(49, 'Việc làm_10', 1, '2025-12-01 10:24:13'),
(50, 'Việc làm_7', 1, '2025-12-03 23:12:52'),
(51, 'Việc làm_10', 1, '2025-12-03 23:15:03'),
(52, 'Việc làm_10', 1, '2025-12-03 23:19:55'),
(53, 'Việc làm_6', 1, '2025-12-03 23:42:58'),
(54, 'Việc làm_8', 1, '2025-12-05 09:28:17'),
(55, 'Việc làm_11', 1, '2025-12-05 09:50:56'),
(56, 'Việc làm_12', 1, '2025-12-05 13:36:17'),
(57, 'Việc làm_7', 1, '2025-12-05 13:36:25'),
(58, 'Việc làm_13', 1, '2025-12-05 14:10:59'),
(59, 'Việc làm_27', 1, '2025-12-06 14:49:53'),
(60, 'Việc làm_26', 1, '2025-12-08 12:38:57'),
(61, 'Việc làm_26', 1, '2025-12-08 12:38:59'),
(62, 'Việc làm_41', 1, '2025-12-09 12:49:37'),
(63, 'Việc làm_46', 1, '2026-03-03 08:18:11'),
(64, 'Việc làm_46', 1, '2026-03-03 08:18:11'),
(65, 'Việc làm_47', 1, '2026-03-03 08:33:03'),
(66, 'Việc làm_47', 1, '2026-03-03 08:33:05'),
(67, 'Việc làm_46', 1, '2026-04-06 09:26:25'),
(68, 'Việc làm_47', 1, '2026-04-06 14:47:33'),
(69, 'Việc làm_47', 1, '2026-04-06 14:47:35'),
(70, 'Việc làm_50', 1, '2026-04-06 14:49:51'),
(71, 'Việc làm_50', 1, '2026-04-06 14:52:07'),
(72, 'Việc làm_50', 1, '2026-04-06 15:11:43'),
(73, 'Việc làm_47', 1, '2026-04-06 15:11:46'),
(74, 'Việc làm_47', 1, '2026-04-06 15:15:29'),
(75, 'Việc làm_47', 1, '2026-04-06 15:15:49'),
(76, 'Việc làm_47', 1, '2026-04-06 15:15:49'),
(77, 'Việc làm_47', 1, '2026-04-06 15:15:50'),
(78, 'Việc làm_47', 1, '2026-04-06 15:19:38'),
(79, 'Việc làm_47', 1, '2026-04-06 15:20:02'),
(80, 'Việc làm_50', 1, '2026-04-06 15:20:05'),
(81, 'Việc làm_50', 1, '2026-04-07 17:51:26'),
(82, 'Việc làm_50', 1, '2026-04-07 18:11:19'),
(83, 'Việc làm_50', 1, '2026-04-07 18:39:16'),
(84, 'Việc làm_50', 1, '2026-04-07 18:39:24'),
(85, 'Việc làm_50', 1, '2026-04-17 08:03:41'),
(86, 'Việc làm_50', 1, '2026-04-17 08:08:10'),
(87, 'Việc làm_50', 1, '2026-04-20 15:14:46'),
(88, 'Việc làm_50', 1, '2026-04-20 15:15:01'),
(89, 'Việc làm_50', 1, '2026-04-20 15:16:38'),
(90, 'Việc làm_45', 1, '2026-04-20 15:16:45'),
(91, 'Việc làm_47', 1, '2026-04-20 15:16:47'),
(92, 'Việc làm_46', 1, '2026-04-20 15:16:49'),
(93, 'Việc làm_47', 1, '2026-04-20 15:16:54'),
(94, 'Việc làm_50', 1, '2026-04-20 15:17:57'),
(95, 'Việc làm_50', 1, '2026-04-20 15:18:11'),
(96, 'Việc làm_50', 1, '2026-04-20 15:18:40'),
(97, 'Việc làm_47', 1, '2026-04-20 17:01:59'),
(98, 'Việc làm_47', 1, '2026-04-20 17:02:08'),
(99, 'Việc làm_50', 1, '2026-04-22 13:20:11'),
(100, 'Việc làm_50', 1, '2026-04-22 13:20:11'),
(101, 'Việc làm_50', 1, '2026-04-22 13:24:15'),
(102, 'Việc làm_50', 1, '2026-04-24 08:35:04'),
(103, 'Việc làm_50', 1, '2026-04-24 08:38:46'),
(104, 'Việc làm_50', 1, '2026-04-24 08:40:55'),
(105, 'Việc làm_50', 1, '2026-04-24 08:42:23'),
(106, 'Việc làm_50', 1, '2026-04-24 08:44:12'),
(107, 'Việc làm_50', 1, '2026-04-24 08:44:19'),
(108, 'Việc làm_50', 1, '2026-04-24 08:44:23'),
(109, 'Việc làm_50', 1, '2026-04-24 08:45:43'),
(110, 'Việc làm_50', 1, '2026-04-24 08:47:13'),
(111, 'Việc làm_47', 1, '2026-04-24 08:47:28'),
(112, 'Việc làm_47', 1, '2026-04-24 08:47:37'),
(113, 'Việc làm_44', 1, '2026-04-24 08:47:40'),
(114, 'Việc làm_44', 1, '2026-04-24 08:49:39'),
(115, 'Việc làm_50', 1, '2026-04-24 08:50:01'),
(116, 'Việc làm_50', 1, '2026-04-24 08:50:07'),
(117, 'Việc làm_58', 1, '2026-04-26 13:05:03'),
(118, 'Việc làm_58', 1, '2026-04-26 13:14:05'),
(119, 'Việc làm_58', 1, '2026-04-26 13:14:06'),
(120, 'Việc làm_50', 1, '2026-04-26 13:14:07'),
(121, 'Việc làm_47', 1, '2026-04-26 13:14:09'),
(122, 'Việc làm_47', 1, '2026-04-26 13:14:10'),
(123, 'Việc làm_45', 1, '2026-04-26 13:14:11'),
(124, 'Việc làm_45', 1, '2026-04-26 13:14:12'),
(125, 'Việc làm_58', 1, '2026-04-26 13:55:56'),
(126, 'Việc làm_58', 1, '2026-04-26 13:56:00'),
(127, 'Việc làm_50', 1, '2026-04-26 13:56:04'),
(128, 'Việc làm_50', 1, '2026-04-26 13:56:17'),
(129, 'Việc làm_40', 1, '2026-04-26 13:56:20'),
(130, 'Việc làm_40', 1, '2026-04-26 13:56:26'),
(131, 'Việc làm_58', 1, '2026-04-26 13:56:28'),
(132, 'Việc làm_58', 1, '2026-04-26 13:56:31'),
(133, 'Việc làm_40', 1, '2026-04-26 13:56:51'),
(134, 'Việc làm_59', 1, '2026-04-26 14:05:24'),
(135, 'Việc làm_59', 1, '2026-04-26 14:05:30'),
(136, 'Việc làm_59', 1, '2026-05-05 08:08:36'),
(137, 'Việc làm_60', 1, '2026-05-05 08:13:25'),
(138, 'Việc làm_60', 1, '2026-05-05 08:13:26'),
(139, 'Việc làm_60', 1, '2026-05-05 08:13:56');

-- --------------------------------------------------------

--
-- Table structure for table `vieclam`
--

CREATE TABLE `vieclam` (
  `id` int NOT NULL,
  `idnhatuyendung` int DEFAULT NULL,
  `tieude` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tencongty` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `mucluong` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `diadiem` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nganhnghe` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `mota` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `yeucau` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `chitiet` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `emaillienhe` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `trangthai` enum('choxuly','daduyet','tuchoi') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'choxuly',
  `ngaydang` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vieclam`
--

INSERT INTO `vieclam` (`id`, `idnhatuyendung`, `tieude`, `tencongty`, `mucluong`, `diadiem`, `nganhnghe`, `mota`, `yeucau`, `chitiet`, `emaillienhe`, `trangthai`, `ngaydang`) VALUES
(13, 6, 'Lập trình viên Backend', 'Alpha', '15 triệu', 'Cần Thơ', 'IT', 'Thực hiện các nhiệm vụ chuyên môn và phối hợp với các phòng ban liên quan.', 'Chịu được áp lực công việc, sẵn sàng học hỏi.', NULL, 'phanhieunghia13052019@gmail.com', 'daduyet', '2025-12-05 13:53:20'),
(14, 6, 'Lập trình viên Frontend', 'Alpha', '20 triệu', 'Cần Thơ', 'Công nghệ thông tin', 'Phân tích yêu cầu, thực thi công việc đảm bảo đúng chất lượng.', 'Thành thạo công cụ, phần mềm chuyên ngành.', NULL, 'phanhieunghia13052019@gmail.com', 'daduyet', '2025-12-05 13:54:18'),
(15, 6, 'QA Tester', 'Alpha', '25 triệu', 'Thành Phố Hồ Chí Minh', 'Công nghệ thông tin', 'Phân tích yêu cầu, thực thi công việc đảm bảo đúng chất lượng.', 'Kỹ năng giao tiếp tốt, tinh thần trách nhiệm cao.', NULL, 'phanhieunghia13052019@gmail.com', 'daduyet', '2025-12-05 13:55:13'),
(16, 6, 'DevOps Engineer', 'Alpha', '15 triệu', 'Hà Nội', 'Công nghệ thông tin', 'Làm việc trong môi trường năng động, chuyên nghiệp.', 'Chịu được áp lực công việc, sẵn sàng học hỏi.', NULL, 'phanhieunghia13052019@gmail.com', 'daduyet', '2025-12-05 13:56:00'),
(17, 6, 'Data Analyst', 'Alpha', '35 triệu', 'Cà Mau', 'Công nghệ thông tin', 'Thực hiện các nhiệm vụ chuyên môn và phối hợp với các phòng ban liên quan.', 'Thành thạo công cụ, phần mềm chuyên ngành.', NULL, 'phanhieunghia13052019@gmail.com', 'daduyet', '2025-12-05 13:56:49'),
(18, 11, 'Chuyên viên Content', 'Beta', '15 triệu', 'Cần Thơ', 'Marketing', 'Làm việc trong môi trường năng động, chuyên nghiệp.', 'Chịu được áp lực công việc, sẵn sàng học hỏi.', NULL, 'B@gmail.com', 'daduyet', '2025-12-05 14:02:03'),
(19, 11, 'Chuyên viên SEO', 'Beta', '25 triệu', 'Hà Nội', 'Marketing', 'Báo cáo kết quả công việc định kỳ và đề xuất cải tiến.', 'Thành thạo công cụ, phần mềm chuyên ngành.', NULL, 'B@gmail.com', 'daduyet', '2025-12-05 14:03:52'),
(20, 11, 'Chuyên viên Social Media', 'Beta', '35 triệu', 'Hậu Giang', 'Marketing', 'Thực hiện các nhiệm vụ chuyên môn và phối hợp với các phòng ban liên quan.', 'Có khả năng làm việc nhóm và làm việc độc lập.', NULL, 'B@gmail.com', 'daduyet', '2025-12-05 14:04:37'),
(21, 11, 'Digital Planner', 'Beta', '18 triệu', 'Hải Phòng', 'Marketing', 'Làm việc trong môi trường năng động, chuyên nghiệp.\r\n', 'Chịu được áp lực công việc, sẵn sàng học hỏi.\r\n', NULL, 'B@gmail.com', 'daduyet', '2025-12-05 14:05:18'),
(22, 11, 'Brand Executive', 'Beta', '18 triệu', 'An Giang', 'Marketing', 'Làm việc trong môi trường năng động, chuyên nghiệp.\r\n', 'Có khả năng làm việc nhóm và làm việc độc lập.\r\n', NULL, 'B@gmail.com', 'daduyet', '2025-12-05 14:05:58'),
(23, 12, 'Nhân viên Sales', 'Central', '18 triệu', 'Cần Thơ', 'Kinh doanh', 'Báo cáo kết quả công việc định kỳ và đề xuất cải tiến.\r\n', 'Chịu được áp lực công việc, sẵn sàng học hỏi.\r\n', NULL, 'B@gmail.com', 'daduyet', '2025-12-05 14:07:12'),
(24, 12, 'Sales Admin', 'Central', '30 triệu', 'Hà Nội', 'Kinh doanh', 'Thực hiện các nhiệm vụ chuyên môn và phối hợp với các phòng ban liên quan.\r\n', 'Chịu được áp lực công việc, sẵn sàng học hỏi.\r\n', NULL, 'B@gmail.com', 'daduyet', '2025-12-05 14:08:00'),
(25, 12, 'Tư vấn khách hàng', 'Central', '30 triệu', 'Thành Phố Hồ Chí Minh', 'Công nghệ thông tin', 'Báo cáo kết quả công việc định kỳ và đề xuất cải tiến.\r\n', 'Kỹ năng giao tiếp tốt, tinh thần trách nhiệm cao.\r\n', NULL, 'C@gmail.com', 'daduyet', '2025-12-05 14:08:43'),
(26, 12, 'Quản lý kinh doanh', 'Central', '30 triệu', 'Thành Phố Hồ Chí Minh', 'Kinh doanh', 'Báo cáo kết quả công việc định kỳ và đề xuất cải tiến.\r\n', 'Chịu được áp lực công việc, sẵn sàng học hỏi.\r\n', NULL, 'C@gmail.com', 'daduyet', '2025-12-05 14:09:17'),
(27, 12, 'Chuyên viên phát triển thị trường', 'Central', '30 triệu', 'Thành Phố Hồ Chí Minh', 'Kinh doanh', 'Làm việc trong môi trường năng động, chuyên nghiệp.\r\n', 'Có kinh nghiệm tối thiểu 1 năm trong lĩnh vực liên quan.\r\n', NULL, 'C@gmail.com', 'daduyet', '2025-12-05 14:09:46'),
(28, 13, 'Kỹ thuật viên nông nghiệp', 'Dental', '10-20 triệu', 'Cà Mau', 'Nông Nghiệp', 'Làm việc trong môi trường năng động, chuyên nghiệp.', 'Có khả năng làm việc nhóm và làm việc độc lập.', NULL, 'phanhieunghia13052019@gmail.com', 'daduyet', '2025-12-06 14:54:05'),
(29, 13, 'Chuyên viên phân tích đất', 'Dental', '7-10 triệu', 'Cần Thơ', 'Nông Nghiệp', 'Chuyên viên phân tích đất', 'Có khả năng làm việc nhóm và làm việc độc lập.\r\n', NULL, 'phanhieunghia13052019@gmail.com', 'daduyet', '2025-12-06 14:54:58'),
(30, 13, 'Quản lý trang trại', 'Dental', '5-10 triệu', 'Thành Phố Hồ Chí Minh', 'Nông Nghiệp', 'Báo cáo kết quả công việc định kỳ và đề xuất cải tiến.\r\n', 'Chịu được áp lực công việc, sẵn sàng học hỏi.\r\n', NULL, 'phanhieunghia13052019@gmail.com', 'daduyet', '2025-12-06 14:55:54'),
(31, 13, 'Chuyên viên thủy canh', 'Dental', '7-10 triệu', 'Cần Thơ', 'Nông Nghiệp', 'Báo cáo kết quả công việc định kỳ và đề xuất cải tiến.\r\n', 'Có khả năng làm việc nhóm và làm việc độc lập.\r\n', NULL, 'Phat@gmail.com', 'daduyet', '2025-12-06 14:56:54'),
(32, 14, 'Kế toán tổng hợp', 'Enter', '10 - 20 triệu', 'Thành Phố Hồ Chí Minh', 'Kế Toán', 'Tham gia xây dựng, tối ưu quy trình làm việc và hỗ trợ nhóm.\r\n', 'Có kinh nghiệm tối thiểu 1 năm trong lĩnh vực liên quan.\r\n', NULL, 'E@gmail.com', 'daduyet', '2025-12-09 09:55:39'),
(33, 14, 'Kế toán kho', 'Enter', '10 - 20 triệu', 'An Giang', 'Kế Toán', 'Phân tích yêu cầu, thực thi công việc đảm bảo đúng chất lượng.\r\n', 'Có khả năng làm việc nhóm và làm việc độc lập.\r\n', NULL, 'E@gmail.com', 'daduyet', '2025-12-09 09:56:18'),
(34, 14, 'Kế toán thuế', 'Enter', '10 - 20 triệu', 'Cà Mau', 'Kế Toán', 'Tham gia xây dựng, tối ưu quy trình làm việc và hỗ trợ nhóm.\r\n', 'Có khả năng làm việc nhóm và làm việc độc lập.\r\n', NULL, 'E@gmail.com', 'daduyet', '2025-12-09 09:57:18'),
(35, 14, 'Kế toán thanh toán', 'Enter', '10 - 20 triệu', 'Cần Thơ', 'Kế Toán', 'Làm việc trong môi trường năng động, chuyên nghiệp.\r\n', 'Có kinh nghiệm tối thiểu 1 năm trong lĩnh vực liên quan.\r\n', NULL, 'E@gmail.com', 'daduyet', '2025-12-09 09:58:04'),
(36, 14, 'Trợ lý kế toán', 'Enter', '10 - 20 triệu', 'Hà Nội', 'Kế Toán', 'Tham gia xây dựng, tối ưu quy trình làm việc và hỗ trợ nhóm.\r\n', 'Có kinh nghiệm tối thiểu 1 năm trong lĩnh vực liên quan.\r\n', NULL, 'E@gmail.com', 'daduyet', '2025-12-09 09:58:45'),
(37, 15, 'Gia sư toán', 'Fluter', '9 - 10 triệu', 'Hà Nội', 'Gia Sư', 'Giảng dạy toán tại trung tâm các lớp 6 7 8 9', 'Có bằng sư phạm toán hoặc các ngành nghề liên quan', NULL, 'F@gmail.com', 'daduyet', '2025-12-09 10:00:54'),
(38, 15, 'Gia sư lý', 'Fluter', '9 - 10 triệu', 'Cần Thơ', 'Gia Sư', 'Giảng dạy lý tại trung tâm các lớp 6 7 8 9', 'Có bằng sư phạm lý', NULL, 'F@gmail.com', 'daduyet', '2025-12-09 10:01:29'),
(39, 15, 'Gia Sư Hóa', 'Fluter', '9 - 10 triệu', 'Cà Mau', 'Gia Sư', 'Giảng dạy Hóa tại trung tâm các lớp 6 7 8 9', 'Có bằng gia sư hóa', NULL, 'F@gmail.com', 'daduyet', '2025-12-09 10:02:09'),
(40, 15, 'Gia sư Văn', 'Fluter', '9 - 10 triệu', 'Cà Mau', 'Gia Sư', 'Giảng dạy Văn tại trung tâm các lớp 6 7 8 9', 'Có bằng sư phạm văn', NULL, 'F@gmail.com', 'daduyet', '2025-12-09 10:03:25'),
(41, 15, 'Gia Sư Địa Lý', 'Fluter', '9 - 10 triệu', 'An Giang', 'Gia Sư', 'Giảng dạy Địa lý tại trung tâm các lớp 6 7 8 9', 'có bằng sư phạm địa lý', NULL, 'F@gmail.com', 'daduyet', '2025-12-09 10:04:13'),
(42, 16, 'Nhân viên bán hàng Part-time', 'Gaytar', '9 - 10 triệu', 'An Giang', 'Bán Thời Gian', 'Làm việc trong môi trường năng động, chuyên nghiệp.\r\n', 'Chịu được áp lực công việc, sẵn sàng học hỏi.\r\n', NULL, 'G@gmail.com', 'daduyet', '2025-12-09 10:05:21'),
(43, 16, 'Phục vụ Part-time', 'Gaytar', '9 - 10 triệu', 'Cần Thơ', 'Bán Thời Gian', 'Chạy bàn, phục phụ món\r\n', 'nhanh nhẹn hoạt bát', NULL, 'G@gmail.com', 'daduyet', '2025-12-09 10:06:30'),
(44, 16, 'Đóng gói hàng shoppe', 'Gaytar', '9 - 10 triệu', 'Thành Phố Hồ Chí Minh', 'Bán Thời Gian', 'Đóng gói hàng để gửi shoppe', 'nhanh nhẹn', NULL, 'G@gmail.com', 'daduyet', '2025-12-09 10:07:21'),
(45, 17, 'Freelancer thiết kế', 'Hennry', '30 triệu', 'Cần Thơ', 'Freelancer', 'làm các sản phẩm pts tại nhà', 'biết dùng các phần mềm thiết kế', NULL, 'H@gmail.com', 'daduyet', '2025-12-09 10:08:33'),
(48, 41, 'sdf', 'sdf', '23123', '123123', 'Kế Toán', '12313', '123123', NULL, '123123123@GAMIL.COM', 'choxuly', '2026-04-06 14:22:16'),
(49, 41, 'ADAA', 'ĐÁ', 'ÁD', 'ÁDAD', 'Công nghệ thông tin', 'ÁDASDASD', 'ÁDASD', NULL, 'ADADD@GMAIL.COM', 'choxuly', '2026-04-06 14:22:32'),
(51, 41, 'aád', 'ád', 'ád', 'áda', 'Marketing', 'ád', 'ád', 'VL_1775561571_69d4eb63ab041.png', 'aasdasd@gmai.com', 'tuchoi', '2026-04-07 18:32:51'),
(52, 40, 'FDSDF', 'SDF', '7 triệu', 'ádas', 'Kinh doanh', 'à', 'sdf', 'VL_1776679718_69e5fb2630cb0.png', 'sdf@gmail.com', 'tuchoi', '2026-04-20 17:08:38'),
(54, 40, 'ádd', 'dsfsdf', 'sfsdf', 'sdf', 'Marketing', 'sdf', 'sdf', 'VL_1776679832_69e5fb9818819.pdf', 'phat4@gmail.com', 'tuchoi', '2026-04-20 17:10:32'),
(55, 40, 'fsdf', 'svcvzxv', 'zxcz', 'czxczc', 'Marketing', 'ádasd', 'dfsdfs', 'VL_1776679854_69e5fbae188e5.pdf', 'phat3@gmail.com', 'tuchoi', '2026-04-20 17:10:54'),
(56, 40, 'FDSDF', 'sdfsdf', 'sdfsdf', 'sdfdfsd', 'Công nghệ thông tin', 'sdfsdf', 'fdsfdsf', 'VL_1776679875_69e5fbc39f00b.pdf', 'phat4@gmail.com', 'tuchoi', '2026-04-20 17:11:15'),
(57, 40, 'FDSDF', 'sdfsdf', 'sdfsdf', 'sdfdfsd', 'Công nghệ thông tin', 'sdfsdf', 'fdsfdsf', 'VL_1776679875_69e5fbc3a6978.pdf', 'phat4@gmail.com', 'choxuly', '2026-04-20 17:11:15'),
(59, 41, 'Lập trình web', 'công ty a', '10 triệu', 'cần thơ', 'Công nghệ thông tin', 'cũng hơi khó', 'chịu khó', 'VL_1777187006_69edb8bea42cb.pdf', 'phat1@gmail.com', 'daduyet', '2026-04-26 14:03:26'),
(60, 41, 'Lập trình web', 'công ty c', '10 triệu', 'cần thơ', 'Công nghệ thông tin', 'thông thạo các kỹ năng ...', 'thông thạo các kỹ năng ...', 'VL_1777943441_69f94391aaaae.pdf', 'phat3@gmail.com', 'daduyet', '2026-05-05 08:10:41');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `donungvien`
--
ALTER TABLE `donungvien`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idvieclam` (`idvieclam`),
  ADD KEY `idsinhvien` (`idsinhvien`);

--
-- Indexes for table `hosonhatuyendung`
--
ALTER TABLE `hosonhatuyendung`
  ADD PRIMARY KEY (`idnguoidung`);

--
-- Indexes for table `hosoungvien`
--
ALTER TABLE `hosoungvien`
  ADD PRIMARY KEY (`idnguoidung`);

--
-- Indexes for table `nguoidung`
--
ALTER TABLE `nguoidung`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`username`);

--
-- Indexes for table `otp_reset`
--
ALTER TABLE `otp_reset`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `thongke`
--
ALTER TABLE `thongke`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vieclam`
--
ALTER TABLE `vieclam`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idnhatuyendung` (`idnhatuyendung`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `donungvien`
--
ALTER TABLE `donungvien`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `nguoidung`
--
ALTER TABLE `nguoidung`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT for table `otp_reset`
--
ALTER TABLE `otp_reset`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `thongke`
--
ALTER TABLE `thongke`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=140;

--
-- AUTO_INCREMENT for table `vieclam`
--
ALTER TABLE `vieclam`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `donungvien`
--
ALTER TABLE `donungvien`
  ADD CONSTRAINT `donungvien_ibfk_1` FOREIGN KEY (`idvieclam`) REFERENCES `vieclam` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `donungvien_ibfk_2` FOREIGN KEY (`idsinhvien`) REFERENCES `nguoidung` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hosonhatuyendung`
--
ALTER TABLE `hosonhatuyendung`
  ADD CONSTRAINT `fk_ntd_profile_user` FOREIGN KEY (`idnguoidung`) REFERENCES `nguoidung` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hosoungvien`
--
ALTER TABLE `hosoungvien`
  ADD CONSTRAINT `hosoungvien_ibfk_1` FOREIGN KEY (`idnguoidung`) REFERENCES `nguoidung` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vieclam`
--
ALTER TABLE `vieclam`
  ADD CONSTRAINT `vieclam_ibfk_1` FOREIGN KEY (`idnhatuyendung`) REFERENCES `nguoidung` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 4.5.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 29, 2016 at 11:57 AM
-- Server version: 10.1.10-MariaDB
-- PHP Version: 5.6.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `2016_sinar_purnama`
--

-- --------------------------------------------------------

--
-- Table structure for table `migration`
--

CREATE TABLE `migration` (
  `version` varchar(180) NOT NULL,
  `apply_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `migration`
--

INSERT INTO `migration` (`version`, `apply_time`) VALUES
('m000000_000000_base', 1467079620),
('m130524_201442_init', 1467079814);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_ticket`
--

CREATE TABLE `tbl_ticket` (
  `ticket_id` int(15) NOT NULL,
  `ticket_name` varchar(100) NOT NULL,
  `ticket_desc` varchar(255) NOT NULL,
  `ticket_desc_support` varchar(255) NOT NULL,
  `ticket_date_create` date NOT NULL,
  `ticket_date_update` datetime NOT NULL,
  `ticket_status` smallint(6) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_ticket`
--

INSERT INTO `tbl_ticket` (`ticket_id`, `ticket_name`, `ticket_desc`, `ticket_desc_support`, `ticket_date_create`, `ticket_date_update`, `ticket_status`, `user_id`) VALUES
(1, 'Laptop Asus A8 I3', 'Kurang Ram 4 GB untuk Built Android Studio', '', '2016-05-28', '2016-06-28 00:00:00', 0, 1),
(2, 'Komputer Mati Total', 'Komputer Mati Total, mungkin di minta install ulang', '', '2016-06-28', '2016-06-28 00:00:00', 1, 3),
(3, 'Jaringan Lantai 2 XKK Mati', 'Jaringan Lantai 2 Mati Tolang Di Perbaiki a', '', '2016-06-28', '2016-06-28 00:00:00', 1, 1),
(4, 'Ganti Monitor Baru', 'Ganti Monitor Tabung Dengan LCD', '', '2016-06-28', '2016-06-28 00:00:00', 1, 3),
(5, 'PC Lantai 3 Mati', 'PC Lantai 3 Mati Punya Aldi', '', '2016-06-28', '2016-06-28 00:00:00', 2, 7),
(6, 'Tes', 'Tes Lorem Ipsum Dolor Itamet', '', '2016-06-28', '2016-06-28 00:00:00', 2, 1),
(7, 'Wifi Rusak', 'Antena Wifi Rusak', '', '2016-06-29', '2016-06-29 00:00:00', 2, 7),
(8, 'PC Admiistrasi Tidak Terkoneksi Ke Internet', 'PC Admiistrasi Tidak Terkoneksi Ke Internet', '', '2016-06-29', '2016-06-29 00:00:00', 1, 4),
(9, 'Tes Lorem Ipsum Dolor Sit Amet', 'Tes Lorem Ipsum Dolor Sit Amet', '', '2016-06-29', '2016-06-29 00:00:00', 0, 3);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_ticket_assigned`
--

CREATE TABLE `tbl_ticket_assigned` (
  `ticket_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `assigned_date` datetime NOT NULL,
  `assigned_user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_ticket_assigned`
--

INSERT INTO `tbl_ticket_assigned` (`ticket_id`, `user_id`, `assigned_date`, `assigned_user_id`) VALUES
(1, 5, '2016-06-29 09:56:04', 4),
(2, 2, '2016-06-29 07:52:05', 1),
(3, 5, '2016-06-29 07:52:02', 1),
(4, 2, '2016-06-29 07:51:56', 1),
(5, 5, '2016-06-29 07:51:52', 1),
(6, 2, '2016-06-29 07:51:48', 1),
(6, 5, '2016-06-29 07:51:43', 1),
(7, 2, '2016-06-29 07:21:13', 1),
(7, 5, '2016-06-29 07:21:17', 1),
(8, 2, '2016-06-29 04:12:44', 1),
(8, 5, '2016-06-29 07:18:31', 1);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `auth_key` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password_reset_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `firstname` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `lastname` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `birthday` date NOT NULL,
  `status` smallint(6) NOT NULL DEFAULT '10',
  `level_user` smallint(6) NOT NULL DEFAULT '0',
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `auth_key`, `password_hash`, `password_reset_token`, `email`, `firstname`, `lastname`, `birthday`, `status`, `level_user`, `created_at`, `updated_at`) VALUES
(1, 'admin', '85YxO6jcLfEmcSaByJLlQ3e3fsI-NKGV', '$2y$13$5u0aIzuay2JjA0HT5wlv6.wokcIEjPHJTX/CvT1Iu.qpLtlkUmLoe', NULL, 'admin@admin.com', 'Admin', '', '0000-00-00', 10, 10, 1467080368, 1467080368),
(2, 'herubudiman', 'PgSsKvVTp-_PNdMchjE4v3Pi73SibyoX', '$2y$13$5u0aIzuay2JjA0HT5wlv6.wokcIEjPHJTX/CvT1Iu.qpLtlkUmLoe', NULL, 'heru@qajoo.com', 'Heru', 'Budiman', '0000-00-00', 10, 7, 1467088165, 1467118375),
(3, 'muribudiman', 'jgQWrVilXX0vgh0QdEJaAJaITalHJgTw', '$2y$13$brCvrmmOTm5mcQL.RvZyxealZLzjCTaxzQAWDKx5D/enEjaWLXe5y', NULL, 'muri@qajoo.com', 'Muri', 'Budiman', '0000-00-00', 10, 0, 1467088344, 1467093407),
(4, 'karambia', 'SF1omawyRehdStyUOT8IN6xpigxW8qHk', '$2y$13$CP5FxuIPlEwo.qUmKgEHl.XD.XYAarOMs2NMcyu.DXQWgS198nJfa', NULL, 'karambia@sinar.com', 'Karambil', 'Cukil', '0000-00-00', 10, 8, 1467088499, 1467093416),
(5, 'galang', '7LX0LE-AjkNtUS4y3a1eWWeVssehU8l9', '$2y$13$F2vluJHhcMGaoRQmcA7yuuahPw1GJMHuexgQqdaNQlbNzs5yxY4Yq', NULL, 'ganag@sinar.com', 'Galang', 'Hanafi', '0000-00-00', 10, 7, 1467088558, 1467170432),
(6, 'devid', 'Y9-ZZhbW95M-Enj5NnZeDVa4xj6oZzWP', '$2y$13$4TcQIGk3ZzIjAlu8S97FWO6DGW7zh3ECW7nGBKqAed/iGgr1XzKaK', NULL, 'karambilcukil@sinar.com', 'Devid', 'Hanafi', '0000-00-00', 10, 8, 1467090962, 1467092090),
(7, 'aldi', 'ygpTOK1HGpj_QQidGgPJ3EkyDTTltooC', '$2y$13$iEliAuXJUHYV1QOXiz6gfOR3YC5woAcXrhBwLxPjeIKShmQkhi5WS', NULL, 'sawal@sinar.com', 'Muhammad', 'Sawaldi', '0000-00-00', 10, 9, 1467091313, 1467091422);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `migration`
--
ALTER TABLE `migration`
  ADD PRIMARY KEY (`version`);

--
-- Indexes for table `tbl_ticket`
--
ALTER TABLE `tbl_ticket`
  ADD PRIMARY KEY (`ticket_id`);

--
-- Indexes for table `tbl_ticket_assigned`
--
ALTER TABLE `tbl_ticket_assigned`
  ADD PRIMARY KEY (`ticket_id`,`user_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `password_reset_token` (`password_reset_token`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_ticket`
--
ALTER TABLE `tbl_ticket`
  MODIFY `ticket_id` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

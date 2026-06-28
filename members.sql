-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 28, 2026 at 04:01 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `fivem_family`
--

-- --------------------------------------------------------

--
-- Table structure for table `members`
--

CREATE TABLE `members` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `facebook_url` varchar(255) DEFAULT NULL,
  `avatar_url` mediumtext DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `pin_order` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `members`
--

INSERT INTO `members` (`id`, `name`, `facebook_url`, `avatar_url`, `created_at`, `pin_order`) VALUES
(4, 'Niran Niran Niran', 'https://www.facebook.com/profile.php?id=100071542086558', 'https://media.discordapp.net/attachments/1492171518204055753/1500407312039547010/25.png?ex=6a4226b3&is=6a40d533&hm=8997bff9790a7fde4ed98aba2cdb51012ac85f92a81ada87b8c5f7ec37e850e6&=&format=webp&quality=lossless&width=864&height=864', '2026-06-28 08:23:12', 1),
(6, 'Knorr Cupp', 'https://www.facebook.com/profile.php?id=61576146820522', 'https://media.discordapp.net/attachments/1509495859736215562/1520714152212168714/6727efc0-67b3-4d5f-b3fd-4e41a4572a9f.png?ex=6a4232eb&is=6a40e16b&hm=ebc18d2ef4f4d7862621f22a033fe0e21baa0fb40af1cf4bf27bcd5a2a8b72a1&=&format=webp&quality=lossless&width=544&height=968', '2026-06-28 08:25:08', 3),
(8, 'Nava Phaisangkom', 'https://www.facebook.com/NavaRetarded', 'https://media.discordapp.net/attachments/1509495859736215562/1520707053860028437/471149321_122142755378342373_4472529405479444992_n.png?ex=6a422c4f&is=6a40dacf&hm=569860f95cc978efe99548946d2e73bbc125ca1f3bec8cccb4f253e2e1dc4c18&=&format=webp&quality=lossless&width=968&height=968', '2026-06-28 08:26:19', 4),
(10, 'Hope Eighteenmongkut', 'https://www.facebook.com/profile.php?id=61576516436872', 'https://media.discordapp.net/attachments/1509495859736215562/1520707086852427858/710279812_122112809888883881_5720094410403183603_n.png?ex=6a422c56&is=6a40dad6&hm=49db22d0a8d897c5bed801d6705d67b8f76256c1871571bb22c743e71180a561&=&format=webp&quality=lossless&width=968&height=968', '2026-06-28 08:26:38', 2),
(14, 'Willy Idk', 'https://www.facebook.com/profile.php?id=61589929716788', 'https://media.discordapp.net/attachments/1509495859736215562/1520724933955551243/702250529_122096896419330990_1891485305696506568_n.png?ex=6a423cf5&is=6a40eb75&hm=faa434cbc959862b4cd35051ddf0a60e296d1fd788e2a44a210ea0ff0f57b795&=&format=webp&quality=lossless&width=968&height=968', '2026-06-28 09:37:20', 6),
(15, 'White Maverick', 'https://www.facebook.com/profile.php?id=61562936614887', 'https://scontent.fhdy3-1.fna.fbcdn.net/v/t39.30808-1/682062676_122095650710431220_1415051296208384703_n.jpg?stp=dst-jpg_tt6&cstp=mx443x443&ctp=s200x200&_nc_cat=111&ccb=1-7&_nc_sid=e99d92&_nc_ohc=1Tu9455uFw4Q7kNvwHdzXcO&_nc_oc=Adpveirhyp1_0Ne4szBMXVzB2mHdX6avXfCCuv5oBLgONjAZvR27mmJqixKDxXoYuyo&_nc_zt=24&_nc_ht=scontent.fhdy3-1.fna&_nc_gid=3HsSYsTuB0dttKN0nlxHig&_nc_ss=7b2a8&oh=00_Af8okA_vI2Cj_syUWpOvB0fiYEIrtFzBqJ5W9WcxvS6ABA&oe=6A46CCD7', '2026-06-28 09:38:30', 5),
(16, 'Kaning Erictia', 'https://www.facebook.com/profile.php?id=61581425278367', 'https://media.discordapp.net/attachments/1509495859736215562/1520728112713695312/d83611613b5de40c755cdcb711fbd2fc.png?ex=6a423feb&is=6a40ee6b&hm=818a83af1fd99c3d243d4480ef8f9e5a6ea94ea563c8769eae45377d314461db&=&format=webp&quality=lossless', '2026-06-28 09:40:12', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `members`
--
ALTER TABLE `members`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `members`
--
ALTER TABLE `members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

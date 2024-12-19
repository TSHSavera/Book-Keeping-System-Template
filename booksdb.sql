-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 19, 2024 at 07:43 PM
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
-- Database: `test`
--

-- --------------------------------------------------------

--
-- Table structure for table `booksdb`
--

CREATE TABLE `booksdb` (
  `id` int(11) NOT NULL,
  `title` varchar(2500) NOT NULL,
  `author` varchar(2500) NOT NULL,
  `publisher` varchar(2500) NOT NULL,
  `descr` varchar(2500) NOT NULL,
  `category` varchar(2500) NOT NULL,
  `date_published` date NOT NULL,
  `isbn` varchar(2500) NOT NULL,
  `img` varchar(2500) NOT NULL,
  `archiveStatus` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `booksdb`
--

INSERT INTO `booksdb` (`id`, `title`, `author`, `publisher`, `descr`, `category`, `date_published`, `isbn`, `img`, `archiveStatus`) VALUES
(3, 'mamamo', 'mamammo', 'pogi', 'hahaha gagi', 'fwa', '2024-12-20', '4124115', 'SIR ISRAEL SAGUINSIN.jpg', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `booksdb`
--
ALTER TABLE `booksdb`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `booksdb`
--
ALTER TABLE `booksdb`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 12, 2019 at 11:41 AM
-- Server version: 5.7.23
-- PHP Version: 7.2.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `fun`
--

-- --------------------------------------------------------

--
-- Table structure for table `mlane_hunts`
--

CREATE TABLE `mlane_hunts` (
  `huntId` int(11) NOT NULL,
  `huntTitle` varchar(255) NOT NULL,
  `huntLocations` text NOT NULL,
  `huntClues` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `mlane_hunts`
--

INSERT INTO `mlane_hunts` (`huntId`, `huntTitle`, `huntLocations`, `huntClues`) VALUES
(1, 'hunt1', '[{\"lat\":46.57572129088804,\"lng\":7.263511923828105},{\"lat\":46.135938717327846,\"lng\":6.714195517578105},{\"lat\":45.94910497741024,\"lng\":7.230552939453105}]', '[\"asdf\",\"adf1231\",\"132131212\"]'),
(2, 'hunt2', '[{\"lat\":46.57572129088804,\"lng\":7.263511923828105},{\"lat\":46.135938717327846,\"lng\":6.714195517578105},{\"lat\":45.94910497741024,\"lng\":7.230552939453105}]', '[\"asdf\",\"adf1231\",\"132131212\"]'),
(3, 'UCSB', '[{\"lat\":34.411875826842596,\"lng\":-119.8459041862518},{\"lat\":34.408889164502874,\"lng\":-119.84626729840693},{\"lat\":34.40764993595171,\"lng\":-119.84697540158686}]', '[\"Near you\",\"Second one\",\"Third one\"]'),
(4, 'UCSB the worst hunt', '[{\"lat\":34.41368984245492,\"lng\":-119.84556198120117},{\"lat\":34.41265426214957,\"lng\":-119.8483943939209},{\"lat\":34.41176028935178,\"lng\":-119.84812617301941}]', '[\"UCSB Library\",\"Stork\'s Tower\",\"Jamba Juice\"]');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `mlane_hunts`
--
ALTER TABLE `mlane_hunts`
  ADD PRIMARY KEY (`huntId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `mlane_hunts`
--
ALTER TABLE `mlane_hunts`
  MODIFY `huntId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

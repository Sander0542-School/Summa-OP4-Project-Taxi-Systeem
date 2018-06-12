-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Gegenereerd op: 12 jun 2018 om 08:57
-- Serverversie: 10.1.26-MariaDB
-- PHP-versie: 7.1.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ixat_taxis`
--
CREATE DATABASE IF NOT EXISTS `ixat_taxis` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `ixat_taxis`;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `chauffeur`
--

DROP TABLE IF EXISTS `chauffeur`;
CREATE TABLE `chauffeur` (
  `id` int(11) NOT NULL,
  `automerk` varchar(50) NOT NULL,
  `autotype` varchar(50) NOT NULL,
  `kenteken` varchar(10) NOT NULL,
  `aantal_passagiers` int(2) NOT NULL,
  `laadruimte` int(11) NOT NULL,
  `schadevrije_jaren` int(2) NOT NULL,
  `latitude` decimal(11,8) NOT NULL,
  `longitude` decimal(11,8) NOT NULL,
  `vrij` tinyint(1) NOT NULL DEFAULT '1',
  `rijbewijs` set('A','B','C') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `chauffeur_aanvraag`
--

DROP TABLE IF EXISTS `chauffeur_aanvraag`;
CREATE TABLE `chauffeur_aanvraag` (
  `klantID` int(11) NOT NULL,
  `automerk` varchar(50) NOT NULL,
  `autotype` varchar(50) NOT NULL,
  `kenteken` varchar(10) NOT NULL,
  `aantal_passagiers` int(2) NOT NULL DEFAULT '1',
  `laadruimte` int(11) NOT NULL DEFAULT '0',
  `schadevrije_jaren` int(2) NOT NULL DEFAULT '0',
  `rijbewijs` set('A','B','C') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `klant`
--

DROP TABLE IF EXISTS `klant`;
CREATE TABLE `klant` (
  `id` int(11) NOT NULL,
  `gebruikersnaam` varchar(100) NOT NULL,
  `wachtwoord` varchar(128) NOT NULL,
  `naam` varchar(250) NOT NULL,
  `mobiel` varchar(20) NOT NULL,
  `email` varchar(250) NOT NULL,
  `chauffeurID` int(11) DEFAULT NULL,
  `applicatie` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `taxi_aanvraag`
--

DROP TABLE IF EXISTS `taxi_aanvraag`;
CREATE TABLE `taxi_aanvraag` (
  `aanvraagID` int(11) NOT NULL,
  `klantID` int(11) NOT NULL,
  `chauffeurID` int(11) DEFAULT NULL,
  `datum_tijd` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `minimale_laadruimte` int(11) NOT NULL,
  `passagiers` int(1) NOT NULL,
  `latitude` decimal(11,8) NOT NULL,
  `longitude` decimal(11,8) NOT NULL,
  `geaccepteerd` tinyint(1) NOT NULL DEFAULT '0',
  `klaar` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `chauffeur`
--
ALTER TABLE `chauffeur`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `chauffeur_aanvraag`
--
ALTER TABLE `chauffeur_aanvraag`
  ADD PRIMARY KEY (`klantID`);

--
-- Indexen voor tabel `klant`
--
ALTER TABLE `klant`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQUE` (`gebruikersnaam`),
  ADD KEY `foreign_klanten_chauffeur` (`chauffeurID`);

--
-- Indexen voor tabel `taxi_aanvraag`
--
ALTER TABLE `taxi_aanvraag`
  ADD PRIMARY KEY (`aanvraagID`),
  ADD KEY `foreign_taxi_aanvraag_klanten` (`klantID`),
  ADD KEY `foreign_taxi_aanvraag_chauffeur` (`chauffeurID`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `chauffeur`
--
ALTER TABLE `chauffeur`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `klant`
--
ALTER TABLE `klant`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `taxi_aanvraag`
--
ALTER TABLE `taxi_aanvraag`
  MODIFY `aanvraagID` int(11) NOT NULL AUTO_INCREMENT;
--
-- Beperkingen voor geëxporteerde tabellen
--

--
-- Beperkingen voor tabel `chauffeur_aanvraag`
--
ALTER TABLE `chauffeur_aanvraag`
  ADD CONSTRAINT `foreign_chuaffeur_aanvraag_klanten` FOREIGN KEY (`klantID`) REFERENCES `klant` (`id`);

--
-- Beperkingen voor tabel `klant`
--
ALTER TABLE `klant`
  ADD CONSTRAINT `foreign_klanten_chauffeur` FOREIGN KEY (`chauffeurID`) REFERENCES `chauffeur` (`id`);

--
-- Beperkingen voor tabel `taxi_aanvraag`
--
ALTER TABLE `taxi_aanvraag`
  ADD CONSTRAINT `foreign_taxi_aanvraag_chauffeur` FOREIGN KEY (`chauffeurID`) REFERENCES `chauffeur` (`id`),
  ADD CONSTRAINT `foreign_taxi_aanvraag_klanten` FOREIGN KEY (`klantID`) REFERENCES `klant` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

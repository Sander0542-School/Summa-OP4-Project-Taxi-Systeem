-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Gegenereerd op: 24 apr 2018 om 09:55
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
-- Tabelstructuur voor tabel `chaffeur`
--

DROP TABLE IF EXISTS `chaffeur`;
CREATE TABLE `chaffeur` (
  `id` int(11) NOT NULL,
  `automerk` varchar(50) NOT NULL,
  `autotype` varchar(50) NOT NULL,
  `kenteken` varchar(10) NOT NULL,
  `aantal_passagiers` int(2) NOT NULL,
  `laadruimte` int(11) NOT NULL,
  `schadevrije_jaren` int(2) NOT NULL,
  `latitude` decimal(11,8) NOT NULL,
  `longitude` decimal(11,8) NOT NULL,
  `vrij` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `chaffeur_aanvraag`
--

DROP TABLE IF EXISTS `chaffeur_aanvraag`;
CREATE TABLE `chaffeur_aanvraag` (
  `gebruikersnaam` varchar(100) NOT NULL,
  `automerk` varchar(50) NOT NULL,
  `autotype` varchar(50) NOT NULL,
  `kenteken` varchar(10) NOT NULL,
  `aantal_passagiers` int(2) NOT NULL DEFAULT '1',
  `laadruimte` int(11) NOT NULL DEFAULT '0',
  `schadevrije_jaren` int(2) NOT NULL DEFAULT '0',
  `latitude` decimal(11,8) NOT NULL,
  `longitude` decimal(11,8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `chaffeur_rijbewijs`
--

DROP TABLE IF EXISTS `chaffeur_rijbewijs`;
CREATE TABLE `chaffeur_rijbewijs` (
  `chaffeurID` int(11) NOT NULL,
  `rijbewijsID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `klant`
--

DROP TABLE IF EXISTS `klant`;
CREATE TABLE `klant` (
  `gebruikersnaam` varchar(100) NOT NULL,
  `wachtwoord` varchar(128) NOT NULL,
  `naam` varchar(250) NOT NULL,
  `mobiel` varchar(20) NOT NULL,
  `email` varchar(250) NOT NULL,
  `chauffeurID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `rijbewijs`
--

DROP TABLE IF EXISTS `rijbewijs`;
CREATE TABLE `rijbewijs` (
  `id` int(11) NOT NULL,
  `naam` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `taxi_aanvraag`
--

DROP TABLE IF EXISTS `taxi_aanvraag`;
CREATE TABLE `taxi_aanvraag` (
  `gebruikersnaam` varchar(100) NOT NULL,
  `datum_tijd` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `minimale_laadruimte` int(11) NOT NULL,
  `passagiers` int(1) NOT NULL,
  `latitude` decimal(11,8) NOT NULL,
  `longitude` decimal(11,8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `chaffeur`
--
ALTER TABLE `chaffeur`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `chaffeur_aanvraag`
--
ALTER TABLE `chaffeur_aanvraag`
  ADD PRIMARY KEY (`gebruikersnaam`);

--
-- Indexen voor tabel `chaffeur_rijbewijs`
--
ALTER TABLE `chaffeur_rijbewijs`
  ADD PRIMARY KEY (`chaffeurID`,`rijbewijsID`),
  ADD KEY `foreign_chaffeur_rijbewijs_rijbewijs` (`rijbewijsID`);

--
-- Indexen voor tabel `klant`
--
ALTER TABLE `klant`
  ADD PRIMARY KEY (`gebruikersnaam`),
  ADD KEY `foreign_klanten_chaffeur` (`chauffeurID`);

--
-- Indexen voor tabel `rijbewijs`
--
ALTER TABLE `rijbewijs`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `taxi_aanvraag`
--
ALTER TABLE `taxi_aanvraag`
  ADD PRIMARY KEY (`gebruikersnaam`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `chaffeur`
--
ALTER TABLE `chaffeur`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- Beperkingen voor geëxporteerde tabellen
--

--
-- Beperkingen voor tabel `chaffeur_aanvraag`
--
ALTER TABLE `chaffeur_aanvraag`
  ADD CONSTRAINT `foreign_chaffeur_aanvraag_klanten` FOREIGN KEY (`gebruikersnaam`) REFERENCES `klant` (`gebruikersnaam`);

--
-- Beperkingen voor tabel `chaffeur_rijbewijs`
--
ALTER TABLE `chaffeur_rijbewijs`
  ADD CONSTRAINT `foreign_chaffeur_rijbewijs_chaffeur` FOREIGN KEY (`chaffeurID`) REFERENCES `chaffeur` (`id`),
  ADD CONSTRAINT `foreign_chaffeur_rijbewijs_rijbewijs` FOREIGN KEY (`rijbewijsID`) REFERENCES `rijbewijs` (`id`);

--
-- Beperkingen voor tabel `klant`
--
ALTER TABLE `klant`
  ADD CONSTRAINT `foreign_klanten_chaffeur` FOREIGN KEY (`chauffeurID`) REFERENCES `chaffeur` (`id`);

--
-- Beperkingen voor tabel `taxi_aanvraag`
--
ALTER TABLE `taxi_aanvraag`
  ADD CONSTRAINT `foreign_taxi_aanvraag_klanten` FOREIGN KEY (`gebruikersnaam`) REFERENCES `klant` (`gebruikersnaam`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

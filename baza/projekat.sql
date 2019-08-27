-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Oct 29, 2018 at 11:50 AM
-- Server version: 5.7.21
-- PHP Version: 5.6.35

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `projekat`
--
CREATE DATABASE IF NOT EXISTS `projekat` DEFAULT CHARACTER SET utf8 COLLATE utf8_slovenian_ci;
USE `projekat`;

-- --------------------------------------------------------

--
-- Table structure for table `kategorije`
--

DROP TABLE IF EXISTS `kategorije`;
CREATE TABLE IF NOT EXISTS `kategorije` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kategorija` varchar(25) COLLATE utf8_slovenian_ci NOT NULL,
  `top_kategorija` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=90 DEFAULT CHARSET=utf8 COLLATE=utf8_slovenian_ci;

--
-- Dumping data for table `kategorije`
--

INSERT INTO `kategorije` (`id`, `kategorija`, `top_kategorija`) VALUES
(1, 'Playstation', 0),
(2, 'XBOX', 0),
(3, 'Nintendo', 0),
(4, 'PC', 0),
(5, 'Igre', 1),
(6, 'Konzole', 1),
(7, 'Dodatna oprema', 1),
(9, 'Igre', 2),
(10, 'Konzole', 2),
(11, 'Dodatna oprema', 2),
(13, 'Igre', 3),
(14, 'Konzole', 3),
(15, 'Dodatna oprema', 3),
(17, 'Igre', 4),
(18, 'Dodatna oprema', 4),
(36, 'Figure', 33),
(33, 'Merchandise', 0),
(48, 'Razno', 33);

-- --------------------------------------------------------

--
-- Table structure for table `korisnici`
--

DROP TABLE IF EXISTS `korisnici`;
CREATE TABLE IF NOT EXISTS `korisnici` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ime_prezime` varchar(50) COLLATE utf8_slovenian_ci NOT NULL,
  `email` varchar(50) COLLATE utf8_slovenian_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_slovenian_ci NOT NULL,
  `datum_registracije` datetime DEFAULT CURRENT_TIMESTAMP,
  `ovlascenje` varchar(255) COLLATE utf8_slovenian_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_slovenian_ci;

--
-- Dumping data for table `korisnici`
--

INSERT INTO `korisnici` (`id`, `ime_prezime`, `email`, `password`, `datum_registracije`, `ovlascenje`) VALUES
(1, 'Admin Admin', 'admin@gmail.com', '$2y$10$N5FibAOpPezJRWktmF0faejSMVzxv9Ys/ScpG8oIbE0CU5WxjuZoC', '2018-10-21 14:15:54', 'admin,urednik');

-- --------------------------------------------------------

--
-- Table structure for table `proizvodi`
--

DROP TABLE IF EXISTS `proizvodi`;
CREATE TABLE IF NOT EXISTS `proizvodi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `naziv` varchar(50) COLLATE utf8_slovenian_ci NOT NULL,
  `cena` decimal(10,2) NOT NULL,
  `stara_cena` decimal(10,2) DEFAULT NULL,
  `zanr` int(11) NOT NULL,
  `kategorije` int(11) NOT NULL,
  `slika` varchar(255) COLLATE utf8_slovenian_ci NOT NULL,
  `opis` text COLLATE utf8_slovenian_ci NOT NULL,
  `izdvojeno` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=187 DEFAULT CHARSET=utf8 COLLATE=utf8_slovenian_ci;

--
-- Dumping data for table `proizvodi`
--

INSERT INTO `proizvodi` (`id`, `naziv`, `cena`, `stara_cena`, `zanr`, `kategorije`, `slika`, `opis`, `izdvojeno`) VALUES
(171, 'PS4 konzola 500GB Slim + igra do 4.999', '43999.00', NULL, 149, 6, '171-pro_paket_4999.jpg', 'Napravite sami svoj Playstation paket, dodajuÄ‡i jednu od najpopularnijih igara, uz najprodavaniju Playstation konzolu. Sigurno Ä‡ete pronaÄ‡i neÅ¡to po svom ukusu.', 0),
(170, 'PS4 konzola 500GB Slim + igra do 9.999', '46999.00', NULL, 149, 6, '170-pro_paket_9999.jpg', 'Napravite sami svoj Playstation paket, dodajuÄ‡i jednu od najpopularnijih igara, uz najprodavaniju Playstation konzolu. Sa igrama kao Å¡to su DOOM, Uncharted 4, FIFA 18 i drugim hitovima, sigurno Ä‡ete poÄeti svoj gejming na PS4 konzoli na pravi naÄin.', 0),
(165, 'PS4 God of War 4', '8999.00', NULL, 1, 5, '165-god_of_war_ps4.jpg', 'Od studija Santa Monica stiÅ¾e nam novi poÄetak God of War serijala. Kratos Å¾ivi kao Äovek, van senke bogova i prinuÄ‘en je da se prilagodi nepoznatoj zemlji, neoÄekivanim pretnjama i drugoj Å¡ansi da bude otac. Zajedno sa njegovim sinom Atreusom krenuÄ‡e na opasno putovanje kroz Nordijsku divljinu da se bore i ispune veoma liÄni misiju.', 1),
(172, 'PS4 sluÅ¡alice Razer Thresher 7.1', '41999.00', NULL, 149, 7, '172-razer-gaming-headset.jpg', 'Predstavljamo vam beÅ¾iÄ‡ne gejming sluÅ¡alice sertifikovane od strane THX sa Dolby tehnologijom. Pored zvuka bez laga koji vas potpuno utapa u igru, ove premium sluÅ¡alice su takoÄ‘e neverovatno udobne. Lagana memorijska pena idealno prijanja za prirodni oseÄ‡aj koji ne dobijate sa drugim sluÅ¡alicama. DugmiÄ‡i na sluÅ¡alicama za brzu kontrolu zvuka i digitalni mikrofon koji se uvlaÄi pruÅ¾aju vam jednostavnu kontrolu Äak i u Å¾aru borbe.', 0),
(166, 'PS4 Uncharted 4 - The Thief\'s End', '5999.00', NULL, 1, 5, '166-uncharted_4_ps4.png', 'Nekoliko godina nakon svoje poslednje avanture, penzionisani lovac na blago, Nathan Drake je primoran da se vrati u svet lopova. Ulog je ovaj put liÄne prirode i Drake kreÄ‡e na put po svetu u potrazi za istorijskom zaverom koja se krije iza bajkovitog piratskog blaga. NajveÄ‡a avantura testirati Ä‡e njegove fiziÄke limite, odluÄnost i konaÄno, Å¡ta je spreman da Å¾rtvuje da bi spasio voljene.', 1),
(159, 'PS4 Dying Light', '3999.00', '5999.00', 1, 5, '159-ps4_dying_light.jpg', 'Zombi igra nove generacije - u velikom otvorenom svetu preÅ¾ivite zombi apokalipsu koristeÄ‡i parkur sposobnosti i oruÅ¾ja napravljena od prvog Å¡to vam padne pod ruke. Adrenalinom nabijena akcija tokom noÄ‡nih sekvenci, kooperativni mod za 4 igraÄa i grafika najnovije generacije vas oÄekuju u Dying light. Ne propustite!', 1),
(163, 'PS4 Mortal Kombat X', '2999.00', '4999.00', 90, 5, '163-mkx_ps4.jpg', 'Deseta po redu igra u najpopularnijem serijalu borilaÄkih igara donosi joÅ¡ bolje borbe, brutalnije zavrÅ¡ne udarce, novu priÄu u stilu filma i mnogo novih modova za jednog ili viÅ¡e igraÄa. Nextgen evolucija Mortal Kombata i jedan od najboljih naslova ove godine!', 0),
(164, 'PS4 The Last Of Us Remastered', '3499.00', '5999.00', 1, 5, '164-last_of_us_ps4.jpg', 'Igra koja se smatra jednom od najboljih Sony ekskluziva svih vremena, The Last of Us, nedavno je u vidu Remastered izdanja stigla na Playstation 4. Ukoliko ste ponosni vlasnik PS4 sistema nikako ne propustite ovaj naslov, jer vas oÄekuje jedna od najboljih priÄa u video igrama, sada u modernom grafiÄkom ruhu.', 0),
(167, 'PS4 konzola 500GB White Slim', '42999.00', NULL, 149, 6, '167-playstation_4_500gb_white_slim_playstation_4.jpg', 'NajmoÄ‡nija konzola koja se ikada pojavila i vrhunski ureÄ‘aj koji Ä‡e zadovoljiti potrebe gamera i ljubitelja multimedije sada joÅ¡ manja.\n\n8 Core AMD CPU x86-64 â€œJaguarâ€\n8GB GDDR5 MEMORIJA\nAMD Radeonâ„¢ Graphics Core Next engine\nHDD 500GB\nSadrÅ¾i:\n\n500 GB PlayStation 4 konzolu\n1 DualShock 4 beÅ¾iÄni gamepad\nAC strujni kabl, HDMI kabl\nUSB 2.0 kabl\nmono sluÅ¡alice', 0),
(162, 'PS4 Agony', '2999.00', '4999.00', 87, 5, '162-ps4_agony.jpg', 'Agony je igra preÅ¾ivljavanja, smeÅ¡tena u paklu. Svoje putovanje poÄinjeÅ¡ kao muÄena duÅ¡a u dubina podzemlja, bez seÄ‡anja. Specijalna moÄ‡ da kontroliÅ¡eÅ¡ ljude i obuzmeÅ¡ slabije demone, daÄ‡e ti potreban alat da preÅ¾iviÅ¡ ekstremne uslove pakla.', 0),
(173, 'PS4 Dual Controller Charge Dock', '1999.00', '2499.00', 149, 7, '173-orb_dual_controller_charge_dock_12.jpg', 'Stanica za istovremeno punjenje dva Playstation 4 kontrolera.', 0),
(174, 'PS4 Vertical Stand (Slim & Pro)', '2499.00', NULL, 149, 7, '174-orb_stand.jpg', 'Postolje za vertikalan poloÅ¾aj PS4 konzole.\nKompatibilan sa Slim i Pro verzijom.', 0);

-- --------------------------------------------------------

--
-- Table structure for table `zanr`
--

DROP TABLE IF EXISTS `zanr`;
CREATE TABLE IF NOT EXISTS `zanr` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `zanr` varchar(50) COLLATE utf8_slovenian_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=150 DEFAULT CHARSET=utf8 COLLATE=utf8_slovenian_ci;

--
-- Dumping data for table `zanr`
--

INSERT INTO `zanr` (`id`, `zanr`) VALUES
(1, 'Akcija - Avantura'),
(85, 'Sport'),
(71, 'Akcioni RPG'),
(89, 'Strategija'),
(88, 'Platformska'),
(87, 'Horor - Avantura'),
(90, 'BorilaÄka'),
(81, 'VoÅ¾nja'),
(79, 'FPS'),
(149, 'Ostalo');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

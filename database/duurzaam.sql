SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";

START TRANSACTION;

SET time_zone = "+00:00";
 
SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci;
 
--

-- Database: `duurzaam`

--
 
CREATE DATABASE IF NOT EXISTS `duurzaam`

  DEFAULT CHARACTER SET utf8mb4

  COLLATE utf8mb4_unicode_ci;
 
USE `duurzaam`;
 
-- --------------------------------------------------------

-- Tabelstructuur voor tabel `categorie`

-- --------------------------------------------------------
 
CREATE TABLE `categorie` (

  `id` int NOT NULL,

  `categorie` varchar(255) NOT NULL,

  `subcategorie` VARCHAR(100),

  `code` VARCHAR(20) UNIQUE

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
 
-- --------------------------------------------------------

-- Tabelstructuur voor tabel `artikel`

-- --------------------------------------------------------
 
CREATE TABLE `artikel` (

  `id` int NOT NULL,

  `categorie_id` int NOT NULL,

  `naam` varchar(255) NOT NULL,

  `omschrijving` TEXT,

  `merk` VARCHAR(100),

  `kleur` VARCHAR(50),

  `afmeting_maat` VARCHAR(50),

  `prijs_ex_btw` decimal(10,2) NOT NULL,

  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
 
-- --------------------------------------------------------

-- Tabelstructuur voor tabel `rollen`

-- --------------------------------------------------------

CREATE TABLE `rollen` (
  `id` int NOT NULL,
  `naam` varchar(50) NOT NULL,
  `beschrijving` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

-- Tabelstructuur voor tabel `gebruiker`

-- --------------------------------------------------------
 
CREATE TABLE `gebruiker` (

  `id` int NOT NULL,

  `gebruikersnaam` varchar(255) NOT NULL,

  `wachtwoord` varchar(255) NOT NULL,

  `rol_id` int DEFAULT NULL,

  `is_geverifieerd` tinyint(1) NOT NULL

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
 
-- --------------------------------------------------------

-- Tabelstructuur voor tabel `personen`

-- --------------------------------------------------------

CREATE TABLE `personen` (
  `id` int NOT NULL,
  `type` enum('klant','leverancier') NOT NULL DEFAULT 'klant',
  `voornaam` varchar(100) NOT NULL,
  `achternaam` varchar(100) NOT NULL,
  `adres` varchar(255) NOT NULL,
  `plaats` varchar(100) NOT NULL,
  `postcode` varchar(10) NOT NULL,
  `email` varchar(255) NOT NULL,
  `telefoon` varchar(20) NOT NULL,
  `geboortedatum` date DEFAULT NULL,
  `datum_ingevoerd` datetime DEFAULT CURRENT_TIMESTAMP,
  `actief` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
 
-- --------------------------------------------------------

-- Tabelstructuur voor tabel `planning`

-- --------------------------------------------------------
 
CREATE TABLE `planning` (

  `id` int NOT NULL,

  `artikel_id` int NOT NULL,

  `persoon_id` int NOT NULL,

  `kenteken` varchar(255) NOT NULL,

  `ophalen_of_bezorgen` enum('ophalen','bezorgen') NOT NULL,

  `afspraak_op` datetime NOT NULL,

  `omschrijving` text NOT NULL

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
 
-- --------------------------------------------------------

-- Tabelstructuur voor tabel `status`

-- --------------------------------------------------------
 
CREATE TABLE `status` (

  `id` int NOT NULL,

  `status` varchar(255) NOT NULL

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
 
-- --------------------------------------------------------

-- Tabelstructuur voor tabel `verkopen`

-- --------------------------------------------------------
 
CREATE TABLE `verkopen` (

  `id` int NOT NULL,

  `persoon_id` int NOT NULL,

  `artikel_id` int NOT NULL,

  `verkocht_op` datetime NOT NULL

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
 
-- --------------------------------------------------------

-- Tabelstructuur voor tabel `voorraad`

-- --------------------------------------------------------
 
CREATE TABLE `voorraad` (

  `id` int NOT NULL,

  `artikel_id` int NOT NULL,

  `locatie` varchar(255) NOT NULL,

  `aantal` int NOT NULL,

  `status_id` int NOT NULL,

  `ingeboekt_op` datetime NOT NULL

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
 
-- --------------------------------------------------------

-- PRIMARY KEYS & INDEXES

-- --------------------------------------------------------
 
ALTER TABLE `categorie`

  ADD PRIMARY KEY (`id`);

ALTER TABLE `rollen`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `naam` (`naam`);

ALTER TABLE `personen`
  ADD PRIMARY KEY (`id`),
  ADD KEY `type` (`type`),
  ADD KEY `email` (`email`);
 
ALTER TABLE `artikel`

  ADD PRIMARY KEY (`id`),

  ADD KEY `categorie_id` (`categorie_id`);
 
ALTER TABLE `planning`

  ADD PRIMARY KEY (`id`),

  ADD KEY `persoon_id` (`persoon_id`);
 
ALTER TABLE `status`

  ADD PRIMARY KEY (`id`);
 
ALTER TABLE `verkopen`

  ADD PRIMARY KEY (`id`),

  ADD KEY `persoon_id` (`persoon_id`),

  ADD KEY `artikel_id` (`artikel_id`);
 
ALTER TABLE `voorraad`

  ADD PRIMARY KEY (`id`),

  ADD KEY `artikel_id` (`artikel_id`),

  ADD KEY `status_id` (`status_id`);
 
-- --------------------------------------------------------

-- AUTO_INCREMENT

-- --------------------------------------------------------
 
ALTER TABLE `categorie`

  MODIFY `id` int NOT NULL AUTO_INCREMENT;

ALTER TABLE `rollen`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

ALTER TABLE `personen`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;
 
ALTER TABLE `artikel`

  MODIFY `id` int NOT NULL AUTO_INCREMENT;
 
ALTER TABLE `planning`

  MODIFY `id` int NOT NULL AUTO_INCREMENT;
 
ALTER TABLE `status`

  MODIFY `id` int NOT NULL AUTO_INCREMENT;
 
ALTER TABLE `verkopen`

  MODIFY `id` int NOT NULL AUTO_INCREMENT;
 
ALTER TABLE `voorraad`

  MODIFY `id` int NOT NULL AUTO_INCREMENT;
 
-- --------------------------------------------------------

-- FOREIGN KEYS

-- --------------------------------------------------------
 
ALTER TABLE `artikel`

  ADD CONSTRAINT `artikel_ibfk_1`

  FOREIGN KEY (`categorie_id`) REFERENCES `categorie` (`id`);

ALTER TABLE `gebruiker`
  ADD CONSTRAINT `fk_gebruiker_rol`
  FOREIGN KEY (`rol_id`) REFERENCES `rollen` (`id`) ON DELETE SET NULL;
 
ALTER TABLE `planning`

  ADD CONSTRAINT `planning_ibfk_1`

  FOREIGN KEY (`artikel_id`) REFERENCES `artikel` (`id`),

  ADD CONSTRAINT `planning_ibfk_2`

  FOREIGN KEY (`persoon_id`) REFERENCES `personen` (`id`);
 
ALTER TABLE `verkopen`

  ADD CONSTRAINT `verkopen_ibfk_1`

  FOREIGN KEY (`persoon_id`) REFERENCES `personen` (`id`),

  ADD CONSTRAINT `verkopen_ibfk_2`

  FOREIGN KEY (`artikel_id`) REFERENCES `artikel` (`id`);
 
ALTER TABLE `voorraad`

  ADD CONSTRAINT `voorraad_ibfk_1`

  FOREIGN KEY (`artikel_id`) REFERENCES `artikel` (`id`),

  ADD CONSTRAINT `voorraad_ibfk_2`

  FOREIGN KEY (`status_id`) REFERENCES `status` (`id`);
 
COMMIT;

 
-- Voeg rollen toe
INSERT INTO rollen (id, naam, beschrijving) VALUES
(1, 'directie', 'Directie - Volledige toegang tot alle functionaliteiten'),
(2, 'medewerker', 'Medewerker - Toegang tot magazijn, voorraad en algemene functionaliteiten'),
(3, 'winkelpersoneel', 'Winkelpersoneel - Toegang tot verkoop en klant gerelateerde functionaliteiten'),
(4, 'chauffeur', 'Chauffeur - Toegang tot planning en transport gerelateerde functionaliteiten');

-- Voeg directie account toe (wachtwoord: admin123)
INSERT INTO gebruiker (id, gebruikersnaam, wachtwoord, rol_id, is_geverifieerd) VALUES
(1, 'directie', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1, 1);

-- Voeg dummy gegevens toe aan personen-tabel
INSERT INTO personen (id, type, voornaam, achternaam, adres, plaats, postcode, email, telefoon, geboortedatum) VALUES
(1, 'klant', 'Piet', 'Pietersen', 'Dorpsstraat 2', 'Rotterdam', '3012AB', 'piet@example.com', '0698765432', '1985-03-10'),
(2, 'klant', 'Anna', 'de Vries', 'Marktplein 15', 'Den Haag', '2511CD', 'anna.devries@example.com', '0634567890', '1992-07-22'),
(3, 'leverancier', 'Jan', 'Jansen', 'Hoofdstraat 1', 'Amsterdam', '1001AB', 'jan.jansen@example.com', '0612345678', '1980-05-15'),
(4, 'leverancier', 'Maria', 'Peters', 'Kerkstraat 25', 'Utrecht', '3511BT', 'maria.peters@example.com', '0687654321', '1975-11-22');

-- Voeg de nieuwe categorieën toe met hoofd- en subcategorieën
INSERT INTO `categorie` (`id`, `categorie`, `subcategorie`, `code`) VALUES
-- Kleding
(1, 'Kleding', 'Dameskleding', 'KL-DA'),
(2, 'Kleding', 'Herenkleding', 'KL-HE'),
(3, 'Kleding', 'Kinderkleding', 'KL-KI'),

-- Meubels
(4, 'Meubels', 'Tafels', 'ME-TA'),
(5, 'Meubels', 'Stoelen', 'ME-ST'),
(6, 'Meubels', 'Eettafels', 'ME-ET'),
(7, 'Meubels', 'Salontafels', 'ME-SA'),
(8, 'Meubels', 'Bankstel', 'ME-BA'),
(9, 'Meubels', 'Dressoir', 'ME-DR'),
(10, 'Meubels', 'Kaptafels', 'ME-KA'),

-- Bedden
(11, 'Bedden', '1-persoons', 'BE-1P'),
(12, 'Bedden', '2-persoons', 'BE-2P'),

-- Kledingkasten
(13, 'Kledingkasten', '1-deurs', 'KK-1D'),
(14, 'Kledingkasten', '2-deurs', 'KK-2D'),
(15, 'Kledingkasten', '3-deurs', 'KK-3D'),

-- Overige
(16, 'Spiegels', NULL, 'SP'),
(17, 'Kapstokken', NULL, 'KS'),
(18, 'Garderobekasten', NULL, 'GK'),
(19, 'Schoenenkasten', NULL, 'SK'),

-- Witgoed
(20, 'Witgoed', 'Wasmachines', 'WG-WA'),
(21, 'Witgoed', 'Gasfornuizen', 'WG-GF'),
(22, 'Witgoed', 'Vaatwassers', 'WG-VW'),
(23, 'Witgoed', 'Koelkasten', 'WG-KO'),
(24, 'Witgoed', 'Diepvriezers', 'WG-DV'),

-- Bruingoed
(25, 'Bruingoed', 'Radio\'s', 'BG-RA'),
(26, 'Bruingoed', 'Televisietoestellen', 'BG-TV'),
(27, 'Bruingoed', 'Ander electronisch apparaten', 'BG-AN'),

-- Grijsgoed
(28, 'Grijsgoed', 'ICT gerelateerde artikelen', 'GG-IC'),

-- Serviesgoed
(29, 'Serviesgoed', 'Glazen', 'SG-GL'),
(30, 'Serviesgoed', 'Borden', 'SG-BO'),
(31, 'Serviesgoed', 'Bestek', 'SG-BE'),

-- Boeken
(32, 'Boeken', NULL, 'BK');

-- Voeg sample artikelen toe
INSERT INTO `artikel` (`categorie_id`, `naam`, `prijs_ex_btw`) VALUES
(1, 'T-shirt dames', 4.13),
(2, 'Spijkerbroek heren', 20.66),
(6, 'Eettafel eiken', 123.97),
(8, 'Bankstel 3-zits', 289.26),
(20, 'Wasmachine', 165.29),
(26, 'LED Televisie', 148.76),
(14, 'Kledingkast 2-deurs', 144.63);

-- Voeg statussen toe
INSERT INTO `status` (`id`, `status`) VALUES
(1, 'In magazijn'),
(2, 'In winkel'),
(3, 'Verkocht'),
(4, 'Gereserveerd'),
(5, 'In reparatie');

-- Voeg voorraad toe voor de artikelen
INSERT INTO `voorraad` (`artikel_id`, `locatie`, `aantal`, `status_id`, `ingeboekt_op`) VALUES
(1, 'Winkel A1', 15, 2, NOW()),
(2, 'Magazijn B2', 8, 1, NOW()),
(3, 'Winkel C3', 3, 2, NOW()),
(4, 'Magazijn D4', 2, 1, NOW()),
(5, 'Winkel E5', 4, 2, NOW()),
(6, 'Magazijn F6', 5, 1, NOW()),
(7, 'Winkel G7', 1, 4, NOW());

-- Voeg planning dummy data toe
INSERT INTO planning (id, artikel_id, persoon_id, kenteken, ophalen_of_bezorgen, afspraak_op, omschrijving) VALUES
(1, 1, 1, 'AB-123-CD', 'ophalen', '2026-02-01 10:00:00', 'Ophalen van T-shirt'),
(2, 3, 2, 'EF-456-GH', 'bezorgen', '2026-02-02 14:00:00', 'Bezorgen van eettafel');
 
COMMIT;


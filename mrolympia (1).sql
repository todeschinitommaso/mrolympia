-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Feb 27, 2024 alle 22:29
-- Versione del server: 10.4.32-MariaDB
-- Versione PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mrolympia`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `atleti`
--

CREATE TABLE `atleti` (
  `id` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `nazionalita` varchar(50) NOT NULL,
  `eta` int(11) NOT NULL,
  `altezza` varchar(50) NOT NULL,
  `peso_gara` int(50) NOT NULL,
  `peso_massa` int(11) NOT NULL,
  `id_preparatore` int(11) NOT NULL,
  `id_biografia` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `atleti`
--

INSERT INTO `atleti` (`id`, `nome`, `nazionalita`, `eta`, `altezza`, `peso_gara`, `peso_massa`, `id_preparatore`, `id_biografia`) VALUES
(5, 'Luca Puma', 'Italia', 23, '189', 103, 120, 2, 1),
(6, 'Chris Bumstead', 'Canada', 29, '185', 104, 120, 2, 1),
(7, 'Ramon Rocha Queiroz', 'Brasile', 29, '181', 105, 115, 1, 2),
(8, 'Arnold Schwarzenegger', 'Austriaco', 77, '188', 107, 118, 3, 3),
(9, 'Ronnie Coleman', 'Americano', 60, '180', 136, 150, 4, 4);

-- --------------------------------------------------------

--
-- Struttura della tabella `biografia`
--

CREATE TABLE `biografia` (
  `id` int(11) NOT NULL,
  `descrizione` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `biografia`
--

INSERT INTO `biografia` (`id`, `descrizione`) VALUES
(1, 'È nato nella città canadese di Ottawa, figlio di Jeff e Mary Bumstead, nonché fratello minore di Melissa Bumstead, anche lei culturista. Avvicinatosi al mondo dello sport fin da piccolo, durante l\'adolescenza pratica diverse discipline quali calcio, baseball, pallacanestro e hockey su ghiaccio.\r\n\r\nScopre la passione per il culturismo all\'età di 14 anni e più tardi inizia ad allenarsi sotto la guida di Iain Valliere, marito della sorella, che intravide il suo potenziale. Ancora oggi, Chris attrib'),
(2, 'In 2018, Mr. Dino competed in Olympia Brasil, one of Brazil\'s most prestigious bodybuilding competitions. At this event, he earned his Pro Card in the Classic Physique category. In 2021, Mr. Dino made his international debut at the Mr. Olympia, placing fifth. Shortly thereafter, he secured a spot at the 2022 Olympia at the Expo Super Show in Rio de Janeiro\r\n\r\nRamon returned in December 2022 to compete in the Mr. Olympia again. The Brazilian placed second, and four months later, he participated in the 2023 Arnold Classic Ohio secured the win. Then in November 2023, he participated in the Mr. Olympia 2023 was the runner up in Classic Physique division behind Chris Bumstead.[1]'),
(3, 'Arnold Schwarzenegger è nato in Austria nel 1947. Ha iniziato il bodybuilding da giovane, mostrando un talento straordinario. Si trasferì negli Stati Uniti negli anni \'60 e divenne rapidamente una figura dominante nel mondo del bodybuilding, vincendo il titolo di Mr. Olympia sette volte. La sua presenza scenica e il suo carisma lo resero una star del cinema di successo, con film come \"Conan il barbaro\" e \"Terminator\". Schwarzenegger ha anche intrapreso una carriera politica, diventando governatore della California dal 2003 al 2011.'),
(4, 'Ronnie Coleman è nato nel 1964 a Monroe, in Louisiana. Ha iniziato a praticare il bodybuilding come hobby durante gli anni \'80, mentre lavorava come poliziotto. Negli anni successivi, si è dedicato seriamente al bodybuilding competitivo, diventando uno dei più grandi atleti della storia del sport. Ha vinto il titolo di Mr. Olympia per otto volte consecutive, dal 1998 al 2005, eguagliando il record di Lee Haney. La sua fisicità massiccia e definita, insieme alla sua forza e simpatia, lo hanno reso una figura iconica nel mondo del bodybuilding. Tuttavia, la sua carriera è stata segnata da numerosi infortuni dovuti alla sua intensa routine di allenamento. Dopo il ritiro dalle competizioni, ha continuato a essere un\'icona nel fitness e nel mondo del bodybuilding, anche attraverso la sua linea di integratori e altri prodotti legati al fitness.');

-- --------------------------------------------------------

--
-- Struttura della tabella `competizioni`
--

CREATE TABLE `competizioni` (
  `id` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `data` date NOT NULL,
  `luogo` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `competizioni`
--

INSERT INTO `competizioni` (`id`, `nome`, `data`, `luogo`) VALUES
(1, 'Mr Olympia Open 2023', '2024-02-01', 'Orlando, Canada'),
(2, 'Mr Olympia Classic Physic 2023', '2024-02-01', 'Orlando, Canada'),
(3, 'Mr Olympia 1998', '1998-10-10', 'New York'),
(4, 'Mr Olympia 1999', '1999-10-22', 'Las Vegas'),
(5, 'Mr Olympia 2000', '2000-10-20', 'Las Vegas'),
(6, 'Mr Olympia 2001', '2001-10-26', 'Las Vegas'),
(7, 'Mr Olympia 2002', '2002-10-18', 'Las Vegas'),
(8, 'Mr Olympia 2003', '2003-10-15', 'Las Vegas'),
(9, 'Mr Olympia 2004', '2005-09-30', 'Las Vegas'),
(10, 'Mr Olympia 2005', '2005-10-15', 'Las Vegas');

-- --------------------------------------------------------

--
-- Struttura della tabella `partecipazione`
--

CREATE TABLE `partecipazione` (
  `id` int(11) NOT NULL,
  `id_atleta` int(11) NOT NULL,
  `id_competizione` int(11) NOT NULL,
  `premio` int(11) NOT NULL,
  `posizione` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `partecipazione`
--

INSERT INTO `partecipazione` (`id`, `id_atleta`, `id_competizione`, `premio`, `posizione`) VALUES
(2, 6, 2, 50000, 1),
(3, 7, 2, 25000, 2),
(4, 5, 2, 5000, 10),
(6, 9, 3, 50000, 1),
(7, 9, 4, 50000, 1),
(8, 9, 10, 50000, 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `preparatori`
--

CREATE TABLE `preparatori` (
  `id` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `eta` int(11) NOT NULL,
  `nazionalita` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `preparatori`
--

INSERT INTO `preparatori` (`id`, `nome`, `eta`, `nazionalita`) VALUES
(1, 'Mauro Sassi', 60, 'Italia'),
(2, 'Hany Rambod', 60, 'America'),
(3, 'Joe Weider', 93, 'America'),
(4, 'Chad Nicholls', 91, 'Americano');

-- --------------------------------------------------------

--
-- Struttura della tabella `utenti`
--

CREATE TABLE `utenti` (
  `id` int(11) NOT NULL,
  `password` varchar(100) NOT NULL,
  `nome` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `utenti`
--

INSERT INTO `utenti` (`id`, `password`, `nome`) VALUES
(5, '$2y$10$i5K1O14U8ZJijC3EYluje./0/o63bkZ2aZKxJivD5fPO81ZHjRnEe', 'Tommaso'),
(8, '$2y$10$JfLQ1WDz6ezM6CKnshlKD.rt5Qiplh8jmuZPTn7wIUeyFGTVuFOJC', 'Tommy'),
(9, '1234', 'Tode');

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `atleti`
--
ALTER TABLE `atleti`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rif_preparatore` (`id_preparatore`),
  ADD KEY `rif_biografia` (`id_biografia`);

--
-- Indici per le tabelle `biografia`
--
ALTER TABLE `biografia`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `competizioni`
--
ALTER TABLE `competizioni`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `partecipazione`
--
ALTER TABLE `partecipazione`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rif_atleta` (`id_atleta`),
  ADD KEY `rif_competizione` (`id_competizione`);

--
-- Indici per le tabelle `preparatori`
--
ALTER TABLE `preparatori`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `utenti`
--
ALTER TABLE `utenti`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `atleti`
--
ALTER TABLE `atleti`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT per la tabella `biografia`
--
ALTER TABLE `biografia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT per la tabella `competizioni`
--
ALTER TABLE `competizioni`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT per la tabella `partecipazione`
--
ALTER TABLE `partecipazione`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT per la tabella `preparatori`
--
ALTER TABLE `preparatori`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT per la tabella `utenti`
--
ALTER TABLE `utenti`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `atleti`
--
ALTER TABLE `atleti`
  ADD CONSTRAINT `rif_biografia` FOREIGN KEY (`id_biografia`) REFERENCES `biografia` (`id`),
  ADD CONSTRAINT `rif_preparatore` FOREIGN KEY (`id_preparatore`) REFERENCES `preparatori` (`id`);

--
-- Limiti per la tabella `partecipazione`
--
ALTER TABLE `partecipazione`
  ADD CONSTRAINT `rif_atleta` FOREIGN KEY (`id_atleta`) REFERENCES `atleti` (`id`),
  ADD CONSTRAINT `rif_competizione` FOREIGN KEY (`id_competizione`) REFERENCES `competizioni` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

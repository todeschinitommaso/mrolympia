-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Feb 21, 2024 alle 11:42
-- Versione del server: 10.4.28-MariaDB
-- Versione PHP: 8.2.4

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

-- --------------------------------------------------------

--
-- Struttura della tabella `biografia`
--

CREATE TABLE `biografia` (
  `id` int(11) NOT NULL,
  `descrizione` varchar(500) NOT NULL,
  `id_atleta` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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

-- --------------------------------------------------------

--
-- Struttura della tabella `partecipazione`
--

CREATE TABLE `partecipazione` (
  `id_atleta` int(11) NOT NULL,
  `id_competizione` int(11) NOT NULL,
  `punteggio` int(11) NOT NULL,
  `posizione` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(2, 'Hany Rambod', 60, 'America');

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
(5, '$2y$10$i5K1O14U8ZJijC3EYluje./0/o63bkZ2aZKxJivD5fPO81ZHjRnEe', 'Tommaso');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT per la tabella `biografia`
--
ALTER TABLE `biografia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `competizioni`
--
ALTER TABLE `competizioni`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `preparatori`
--
ALTER TABLE `preparatori`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT per la tabella `utenti`
--
ALTER TABLE `utenti`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

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

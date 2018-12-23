-- phpMyAdmin SQL Dump
-- version 4.8.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 23-Dez-2018 às 19:37
-- Versão do servidor: 10.1.34-MariaDB
-- PHP Version: 7.2.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `binary`
--

--
-- Extraindo dados da tabela `person`
--

INSERT INTO `person` (`id`, `name`) VALUES
(1, 'Leonardo'),
(2, 'Queizy'),
(3, 'João'),
(9, 'Bruno'),
(10, 'Renan'),
(11, 'Wesley'),
(12, 'Arthur'),
(13, 'Daniel');

--
-- Extraindo dados da tabela `tree`
--

INSERT INTO `tree` (`id`, `left_node`, `right_node`, `father`) VALUES
(1, 2, 3, NULL),
(2, 9, 11, 1),
(3, 13, 12, 1),
(9, 10, NULL, 1),
(10, NULL, NULL, 1),
(11, NULL, NULL, 2),
(12, NULL, NULL, 3),
(13, NULL, NULL, 3);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

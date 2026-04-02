-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 01, 2026 at 10:15 PM
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
-- Database: `medistock`
--

-- --------------------------------------------------------

--
-- Table structure for table `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `codigo` varchar(20) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `categoria` varchar(50) NOT NULL,
  `presentacion` varchar(50) NOT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `precio_compra` decimal(10,2) NOT NULL,
  `precio_venta` decimal(10,2) NOT NULL,
  `laboratorio` varchar(50) DEFAULT NULL,
  `fecha_llegada` date DEFAULT NULL,
  `fecha_vencimiento` date DEFAULT NULL,
  `estado` enum('Óptimo','Crítico','Vencido') DEFAULT 'Óptimo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `productos`
--

INSERT INTO `productos` (`id`, `codigo`, `nombre`, `categoria`, `presentacion`, `stock`, `precio_compra`, `precio_venta`, `laboratorio`, `fecha_llegada`, `fecha_vencimiento`, `estado`) VALUES
(4, '75910001', 'Atamel 500mg (Acetaminofén)', 'Analgésicos', 'Caja x20 tabletas', 120, 1.50, 2.50, 'Leti', '2026-01-10', '2028-05-30', 'Óptimo'),
(5, '75910002', 'Amoxicilina 500mg', 'Antibióticos', 'Caja x10 cápsulas', 8, 2.00, 3.20, 'Calox', '2026-02-15', '2027-11-20', 'Crítico'),
(6, '75910003', 'Diclofenac Potásico 50mg', 'Analgésicos', 'Caja x20 grageas', 45, 1.80, 2.80, 'Genven', '2026-01-20', '2027-08-15', 'Óptimo'),
(7, '75910004', 'Desloratadina 5mg', 'Alergias', 'Caja x10 tabletas', 30, 2.20, 3.50, 'Meyer', '2026-03-05', '2028-01-10', 'Óptimo'),
(8, '75910005', 'Tachipirin Fuerte 650mg', 'Analgésicos', 'Caja x10 tabletas', 60, 2.50, 3.80, 'SM Pharma', '2025-12-10', '2027-10-25', 'Óptimo'),
(9, '75910006', 'Solución Fisiológica 0.9%', 'Insumos', 'Frasco 500ml', 15, 1.00, 2.00, 'Behrens', '2025-05-20', '2026-03-15', 'Vencido'),
(10, '75910007', 'Omeprazol 20mg', 'Gastrointestinal', 'Caja x14 cápsulas', 85, 3.00, 4.50, 'Leti', '2026-02-28', '2028-06-30', 'Óptimo'),
(11, '75910008', 'Losartán Potásico 50mg', 'Cardiología', 'Caja x30 tabletas', 150, 4.00, 6.00, 'Calox', '2026-01-05', '2028-12-31', 'Óptimo'),
(12, '40010009', 'Aspirina 100mg', 'Analgésicos', 'Caja x28 tabletas', 200, 3.50, 5.00, 'Bayer', '2026-03-10', '2029-01-01', 'Óptimo'),
(13, '35010010', 'Allegra 120mg (Fexofenadina)', 'Alergias', 'Caja x10 tabletas', 40, 6.50, 9.00, 'Sanofi', '2026-02-01', '2027-05-15', 'Óptimo'),
(14, '50010011', 'Augmentin 875mg/125mg', 'Antibióticos', 'Caja x14 tabletas', 25, 12.00, 16.50, 'GSK', '2026-01-15', '2027-09-20', 'Óptimo'),
(15, '76010012', 'Cataflam 50mg', 'Analgésicos', 'Caja x20 grageas', 55, 5.00, 7.50, 'Novartis', '2026-03-20', '2028-02-28', 'Óptimo'),
(16, '40010013', 'Ciproxina 500mg', 'Antibióticos', 'Caja x10 tabletas', 35, 8.00, 11.50, 'Bayer', '2026-02-10', '2027-11-30', 'Óptimo'),
(17, '35010014', 'Enterogermina', 'Gastrointestinal', 'Caja x10 viales', 90, 7.00, 10.50, 'Sanofi', '2026-03-25', '2028-04-15', 'Óptimo'),
(18, '50010015', 'Panadol Extra Fuerte', 'Analgésicos', 'Caja x20 tabletas', 110, 3.00, 4.80, 'GSK', '2026-01-08', '2028-08-10', 'Óptimo');

-- --------------------------------------------------------

--
-- Table structure for table `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `tipo_doc` varchar(2) NOT NULL,
  `cedula` varchar(20) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellido` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `rol` enum('Administrador','Vendedor') DEFAULT 'Vendedor',
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `codigo` (`codigo`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cedula` (`cedula`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

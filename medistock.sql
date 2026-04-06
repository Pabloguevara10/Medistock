-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 06, 2026 at 08:33 AM
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
-- Table structure for table `clientes`
--

CREATE TABLE `clientes` (
  `id` int(11) NOT NULL,
  `cedula` varchar(20) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellido` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `telefono` varchar(20) NOT NULL,
  `direccion` text DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `clientes`
--

INSERT INTO `clientes` (`id`, `cedula`, `nombre`, `apellido`, `email`, `telefono`, `direccion`, `fecha_registro`) VALUES
(1, '31993721', 'Pablo', 'Guevara', 'pablo@gmail.com', '04248268559', 'valle', '2026-04-06 05:13:13'),
(2, '31993722', 'Miranda', 'Brito', 'mimi@gmail.com', '04249993332', 'jorgecoll', '2026-04-06 05:18:20'),
(3, '31993723', 'Carlos', 'Garcia', 'sas@gmail.com', '04248268556', 'jorgecoll', '2026-04-06 05:26:17'),
(4, '29591791', 'Santiago', 'Guevara', 'san@gmail.com', '04248268888', 'valle', '2026-04-06 06:29:19');

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
(4, '75910001', 'Atamel 500mg (Acetaminofén)', 'Analgésicos', 'Caja x20 tabletas', 119, 1.50, 2.50, 'Leti', '2026-01-10', '2028-05-30', 'Óptimo'),
(5, '75910002', 'Amoxicilina 500mg', 'Antibióticos', 'Caja x10 cápsulas', 0, 2.00, 3.20, 'Calox', '2026-02-15', '2027-11-20', 'Crítico'),
(6, '75910003', 'Diclofenac Potásico 50mg', 'Analgésicos', 'Caja x20 grageas', 3, 1.80, 2.80, 'Genven', '2026-01-20', '2027-08-15', 'Crítico'),
(8, '75910005', 'Tachipirin Fuerte 650mg', 'Analgésicos', 'Caja x10 tabletas', 2, 2.50, 3.80, 'SM Pharma', '2025-12-10', '2027-10-25', 'Crítico'),
(9, '75910006', 'Solución Fisiológica 0.9%', 'Insumos', 'Frasco 500ml', 15, 1.00, 2.00, 'Behrens', '2025-05-20', '2026-03-15', 'Vencido'),
(10, '75910007', 'Omeprazol 20mg', 'Gastrointestinal', 'Caja x14 cápsulas', 91, 3.00, 4.50, 'Leti', '2026-02-28', '2028-06-30', 'Óptimo'),
(11, '75910008', 'Losartán Potásico 50mg', 'Cardiología', 'Caja x30 tabletas', 150, 4.00, 6.00, 'Calox', '2026-01-05', '2028-12-31', 'Óptimo'),
(12, '40010009', 'Aspirina 100mg', 'Analgésicos', 'Caja x28 tabletas', 200, 3.50, 5.00, 'Bayer', '2026-03-10', '2029-01-01', 'Óptimo'),
(13, '35010010', 'Allegra 120mg (Fexofenadina)', 'Alergias', 'Caja x10 tabletas', 39, 6.50, 9.00, 'Sanofi', '2026-02-01', '2027-05-15', 'Óptimo'),
(14, '50010011', 'Augmentin 875mg/125mg', 'Antibióticos', 'Caja x14 tabletas', 25, 12.00, 16.50, 'GSK', '2026-01-15', '2027-09-20', 'Óptimo'),
(15, '76010012', 'Cataflam 50mg', 'Analgésicos', 'Caja x20 grageas', 55, 5.00, 7.50, 'Novartis', '2026-03-20', '2028-02-28', 'Óptimo'),
(16, '40010013', 'Ciproxina 500mg', 'Antibióticos', 'Caja x10 tabletas', 35, 8.00, 11.50, 'Bayer', '2026-02-10', '2027-11-30', 'Óptimo'),
(17, '35010014', 'Enterogermina', 'Gastrointestinal', 'Caja x10 viales', 95, 7.00, 10.50, 'Sanofi', '2026-03-25', '2028-04-15', 'Óptimo'),
(18, '50010015', 'Panadol Extra Fuerte', 'Analgésicos', 'Caja x20 tabletas', 110, 3.00, 10.00, 'GSK', '2026-01-08', '2028-08-10', 'Óptimo'),
(49, '75910091', 'Amoxicilina 500mg', 'Antibióticos', 'Caja x 21 Cáps', 45, 2.50, 4.00, 'Genven', '2023-10-01', '2025-10-01', 'Óptimo'),
(50, '75910202', 'Ibuprofeno 400mg', 'Analgésicos', 'Caja x 10 Tabs', 120, 1.00, 2.50, 'Leti', '2023-10-05', '2026-01-15', 'Óptimo'),
(51, '75910203', 'Loratadina 10mg', 'Alergias', 'Caja x 10 Tabs', 5, 0.80, 1.50, 'Bayer', '2023-09-12', '2024-11-20', 'Crítico'),
(52, '75910204', 'Omeprazol 20mg', 'Gastrointestinal', 'Frasco x 14 Cáps', 60, 3.00, 5.50, 'Calox', '2023-11-01', '2025-05-10', 'Óptimo'),
(53, '75910205', 'Acetaminofén 500mg', 'Analgésicos', 'Caja x 20 Tabs', 199, 0.50, 1.20, 'Leti', '2023-08-15', '2026-08-15', 'Óptimo'),
(54, '75910206', 'Azitromicina 500mg', 'Antibióticos', 'Caja x 3 Tabs', 8, 4.50, 7.00, 'Genven', '2023-07-20', '2024-07-20', 'Crítico'),
(55, '75910207', 'Desloratadina 5mg', 'Alergias', 'Caja x 10 Tabs', 35, 2.00, 3.80, 'Bayer', '2023-10-22', '2025-12-01', 'Óptimo'),
(56, '75910208', 'Pantoprazol 40mg', 'Gastrointestinal', 'Caja x 14 Tabs', 40, 4.00, 6.50, 'Meyer', '2023-11-10', '2026-02-28', 'Óptimo'),
(57, '75910209', 'Cefadroxilo 500mg', 'Antibióticos', 'Caja x 12 Cáps', 0, 3.50, 6.00, 'Calox', '2022-01-10', '2023-12-01', 'Vencido'),
(58, '75910210', 'Diclofenac Potásico 50mg', 'Analgésicos', 'Caja x 20 Tabs', 85, 1.50, 3.00, 'Leti', '2023-09-05', '2025-09-05', 'Óptimo'),
(59, '75910211', 'Cetirizina 10mg', 'Alergias', 'Caja x 10 Tabs', 12, 1.20, 2.50, 'Genven', '2023-08-30', '2025-08-30', 'Óptimo'),
(60, '75910212', 'Domperidona 10mg', 'Gastrointestinal', 'Caja x 20 Tabs', 25, 2.20, 4.00, 'Meyer', '2023-10-15', '2026-04-10', 'Óptimo'),
(61, '75910213', 'Ciprofloxacina 500mg', 'Antibióticos', 'Caja x 10 Tabs', 50, 3.80, 6.50, 'Bayer', '2023-11-20', '2025-11-20', 'Óptimo'),
(62, '75910214', 'Meloxicam 15mg', 'Analgésicos', 'Caja x 10 Tabs', 3, 2.50, 4.50, 'Calox', '2023-06-10', '2024-10-15', 'Crítico'),
(63, '75910215', 'Ranitidina 150mg', 'Gastrointestinal', 'Caja x 20 Tabs', 13, 1.00, 2.00, 'Leti', '2021-05-20', '2023-05-20', 'Vencido'),
(64, '75910016', 'Azitromicina 500mg', 'Antibióticos', 'Caja x3 tabletas', 45, 4.50, 7.50, 'Leti', '2026-01-15', '2028-06-20', 'Óptimo'),
(65, '75910017', 'Cefadroxilo 500mg', 'Antibióticos', 'Caja x12 cápsulas', 30, 3.80, 6.00, 'Genven', '2026-02-10', '2027-12-15', 'Óptimo'),
(66, '75910018', 'Loratadina 10mg', 'Alergias', 'Caja x10 tabletas', 5, 1.20, 2.50, 'Calox', '2025-10-05', '2027-08-10', 'Crítico'),
(67, '75910019', 'Cetirizina 10mg', 'Alergias', 'Caja x10 tabletas', 80, 1.50, 3.00, 'Meyer', '2026-03-01', '2028-10-30', 'Óptimo'),
(68, '75910020', 'Pantoprazol 40mg', 'Gastrointestinal', 'Caja x14 tabletas', 55, 4.00, 6.80, 'Leti', '2026-01-22', '2028-02-28', 'Óptimo'),
(69, '75910021', 'Esomeprazol 40mg', 'Gastrointestinal', 'Caja x14 cápsulas', 40, 5.50, 8.50, 'AstraZeneca', '2026-02-18', '2029-01-15', 'Óptimo'),
(70, '75910022', 'Domperidona 10mg', 'Gastrointestinal', 'Caja x20 tabletas', 13, 2.00, 4.00, 'Calox', '2025-08-12', '2027-05-20', 'Óptimo'),
(71, '75910023', 'Metoclopramida 10mg', 'Gastrointestinal', 'Caja x20 tabletas', 153, 1.00, 2.20, 'Genven', '2026-03-10', '2028-11-10', 'Óptimo'),
(72, '75910024', 'Bisoprolol 5mg', 'Cardiología', 'Caja x30 tabletas', 95, 3.50, 5.80, 'Merck', '2026-01-30', '2028-04-15', 'Óptimo'),
(73, '75910025', 'Amlodipina 5mg', 'Cardiología', 'Caja x30 tabletas', 109, 2.80, 4.50, 'Leti', '2026-02-05', '2028-09-20', 'Óptimo'),
(74, '75910026', 'Enalapril 20mg', 'Cardiología', 'Caja x20 tabletas', 60, 1.50, 3.00, 'Genven', '2025-11-15', '2027-10-10', 'Óptimo'),
(75, '75910027', 'Valsartán 80mg', 'Cardiología', 'Caja x14 tabletas', 4, 4.20, 7.00, 'Novartis', '2025-09-10', '2027-06-05', 'Crítico'),
(76, '75910028', 'Carvedilol 6.25mg', 'Cardiología', 'Caja x30 tabletas', 75, 3.80, 6.20, 'Roemmers', '2026-03-12', '2028-12-01', 'Óptimo'),
(77, '75910029', 'Ibuprofeno 400mg', 'Analgésicos', 'Caja x20 grageas', 200, 1.20, 2.50, 'Leti', '2026-01-08', '2029-02-15', 'Óptimo'),
(78, '75910030', 'Ibuprofeno 600mg', 'Analgésicos', 'Caja x10 tabletas', 140, 1.50, 3.20, 'Calox', '2026-02-25', '2028-08-30', 'Óptimo'),
(79, '75910031', 'Ketoprofeno 100mg', 'Analgésicos', 'Caja x20 grageas', 85, 2.50, 4.50, 'Genven', '2026-03-02', '2027-11-20', 'Óptimo'),
(80, '75910032', 'Naproxeno 500mg', 'Analgésicos', 'Caja x20 tabletas', 9, 3.00, 5.20, 'Bayer', '2025-07-20', '2027-04-10', 'Crítico'),
(81, '75910033', 'Meloxicam 15mg', 'Analgésicos', 'Caja x10 tabletas', 65, 2.80, 4.80, 'Meyer', '2026-01-18', '2028-05-05', 'Óptimo'),
(82, '75910034', 'Tramadol 50mg', 'Analgésicos', 'Caja x10 cápsulas', 30, 4.50, 7.50, 'Leti', '2026-02-14', '2028-01-25', 'Óptimo'),
(83, '75910035', 'Complejo B', 'Vitaminas', 'Frasco x30 grageas', 150, 2.00, 4.00, 'Vargas', '2026-01-10', '2028-10-15', 'Óptimo'),
(84, '75910036', 'Vitamina C 1000mg', 'Vitaminas', 'Tubo x10 efervescentes', 85, 3.50, 6.00, 'Bayer', '2026-03-15', '2028-12-20', 'Óptimo'),
(85, '75910037', 'Ácido Fólico 5mg', 'Vitaminas', 'Caja x20 tabletas', 0, 1.80, 3.50, 'Leti', '2025-10-12', '2027-09-30', 'Crítico'),
(86, '75910038', 'Vitamina D3 400 UI', 'Vitaminas', 'Frasco x30 cápsulas', 107, 4.00, 7.20, 'Calox', '2026-02-28', '2029-01-10', 'Óptimo'),
(87, '75910039', 'Hierro Sulfato', 'Vitaminas', 'Caja x30 tabletas', 60, 2.20, 4.50, 'Genven', '2026-01-25', '2028-07-05', 'Óptimo'),
(88, '75910040', 'Calcio + Vitamina D', 'Vitaminas', 'Frasco x60 tabletas', 45, 5.50, 9.50, 'Meyer', '2026-03-08', '2028-08-15', 'Óptimo'),
(89, '75910041', 'Budesonida Nasal', 'Respiratorio', 'Spray 120 dosis', 25, 8.00, 13.50, 'AstraZeneca', '2026-01-12', '2027-11-30', 'Óptimo'),
(90, '75910042', 'Salbutamol Inhalador', 'Respiratorio', 'Inhalador 200 dosis', 80, 4.50, 7.80, 'GSK', '2026-02-20', '2028-03-25', 'Óptimo'),
(91, '75910043', 'Bromhexina Jarabe', 'Respiratorio', 'Frasco 120ml', 55, 3.00, 5.50, 'Leti', '2026-03-05', '2028-05-10', 'Óptimo'),
(92, '75910044', 'Ambroxol Jarabe', 'Respiratorio', 'Frasco 120ml', 14, 2.00, 2.00, 'Calox', '2024-01-10', '2025-12-15', 'Vencido'),
(93, '75910045', 'Dextrometorfano Jarabe', 'Respiratorio', 'Frasco 120ml', 40, 3.50, 6.00, 'Meyer', '2026-01-15', '2028-09-05', 'Óptimo'),
(94, '75910046', 'Clotrimazol Crema 1%', 'Dermatología', 'Tubo 20g', 65, 2.50, 4.50, 'Bayer', '2026-02-10', '2028-11-20', 'Óptimo'),
(95, '75910047', 'Ketoconazol Crema 2%', 'Dermatología', 'Tubo 15g', 50, 2.80, 5.00, 'Genven', '2026-03-12', '2028-10-15', 'Óptimo'),
(96, '75910048', 'Betametasona Crema', 'Dermatología', 'Tubo 15g', 6, 3.00, 5.50, 'Leti', '2025-08-25', '2027-06-30', 'Crítico'),
(97, '75910049', 'Hidrocortisona Crema 1%', 'Dermatología', 'Tubo 15g', 35, 3.20, 5.80, 'Calox', '2026-01-05', '2028-04-20', 'Óptimo'),
(98, '75910050', 'Terbinafina Crema 1%', 'Dermatología', 'Tubo 15g', 45, 4.50, 7.50, 'Novartis', '2026-02-28', '2028-12-10', 'Óptimo'),
(99, '75910051', 'Jeringa 5ml con aguja', 'Insumos Médicos', 'Unidad', 300, 0.15, 0.50, 'NIPRO', '2026-01-20', '2030-01-20', 'Óptimo'),
(100, '75910052', 'Jeringa 10ml con aguja', 'Insumos Médicos', 'Unidad', 250, 0.20, 0.60, 'NIPRO', '2026-01-20', '2030-01-20', 'Óptimo'),
(101, '75910053', 'Gasas Estériles 10x10', 'Insumos Médicos', 'Sobre x2 unidades', 180, 0.30, 0.80, 'Johnson', '2026-02-15', '2031-02-15', 'Óptimo'),
(102, '75910054', 'Alcohol Isopropílico 70%', 'Insumos Médicos', 'Frasco 250ml', 6, 1.50, 3.00, 'Genérico', '2025-09-10', '2028-09-10', 'Crítico'),
(103, '75910055', 'Agua Oxigenada', 'Insumos Médicos', 'Frasco 120ml', 81, 1.00, 2.00, 'Genérico', '2026-03-01', '2029-03-01', 'Óptimo'),
(104, '75910056', 'Algodón 100g', 'Insumos Médicos', 'Bolsa', 119, 1.20, 2.50, 'Johnson', '2026-01-12', '2031-01-12', 'Óptimo'),
(105, '75910057', 'Guantes de Látex Talla M', 'Insumos Médicos', 'Caja x100 unidades', 25, 6.00, 10.00, 'NIPRO', '2026-02-08', '2029-02-08', 'Óptimo'),
(106, '75910058', 'Mascarillas Descartables', 'Insumos Médicos', 'Caja x50 unidades', 60, 3.50, 6.50, '3M', '2026-03-15', '2029-03-15', 'Óptimo'),
(107, '75910059', 'Micropore 1 pulgada', 'Insumos Médicos', 'Rollo', 75, 1.80, 3.50, '3M', '2026-01-28', '2031-01-28', 'Óptimo'),
(108, '75910060', 'Fluconazol 150mg', 'Antimicóticos', 'Caja x1 cápsula', 4, 3.00, 5.50, 'Leti', '2025-11-20', '2027-10-15', 'Crítico'),
(109, '75910061', 'Metronidazol 500mg', 'Antibióticos', 'Caja x10 tabletas', 55, 2.50, 4.50, 'Calox', '2026-02-14', '2028-08-20', 'Óptimo'),
(110, '75910062', 'Cefalexina 500mg', 'Antibióticos', 'Caja x20 cápsulas', 40, 4.00, 7.00, 'Genven', '2026-01-10', '2028-05-30', 'Óptimo'),
(111, '75910063', 'Levofloxacina 500mg', 'Antibióticos', 'Caja x7 tabletas', 8, 6.50, 11.00, 'Meyer', '2023-05-15', '2025-05-15', 'Vencido'),
(112, '75910064', 'Clindamicina 300mg', 'Antibióticos', 'Caja x16 cápsulas', 35, 5.00, 8.50, 'Leti', '2026-03-20', '2028-11-10', 'Óptimo'),
(113, '75910065', 'Claritromicina 500mg', 'Antibióticos', 'Caja x10 tabletas', 20, 7.00, 12.00, 'Calox', '2026-02-05', '2028-06-25', 'Óptimo'),
(114, '75910066', 'Ranitidina 150mg', 'Gastrointestinal', 'Caja x20 tabletas', 100, 1.50, 3.00, 'Genven', '2026-01-22', '2028-09-15', 'Óptimo'),
(115, '75910067', 'Sucralfato 1g', 'Gastrointestinal', 'Caja x24 tabletas', 45, 4.50, 7.50, 'Meyer', '2026-03-10', '2028-12-05', 'Óptimo'),
(116, '75910068', 'Loperamida 2mg', 'Gastrointestinal', 'Caja x10 tabletas', 15, 1.80, 3.50, 'Leti', '2025-10-18', '2027-08-20', 'Óptimo'),
(117, '75910069', 'Simeticona 40mg', 'Gastrointestinal', 'Caja x20 tabletas', 60, 2.00, 3.80, 'Calox', '2026-02-12', '2028-07-30', 'Óptimo'),
(118, '75910070', 'Diosmina + Hesperidina', 'Cardiología', 'Caja x30 tabletas', 35, 8.50, 14.00, 'Servier', '2026-01-05', '2028-04-10', 'Óptimo'),
(119, '75910071', 'Clopidogrel 75mg', 'Cardiología', 'Caja x14 tabletas', 0, 5.00, 8.50, 'Sanofi', '2022-09-15', '2024-09-15', 'Vencido'),
(120, '75910072', 'Atorvastatina 20mg', 'Cardiología', 'Caja x10 tabletas', 78, 3.50, 6.00, 'Leti', '2026-02-20', '2028-10-25', 'Óptimo'),
(121, '75910073', 'Rosuvastatina 10mg', 'Cardiología', 'Caja x14 tabletas', 50, 4.80, 8.00, 'AstraZeneca', '2026-03-08', '2029-01-15', 'Óptimo'),
(122, '75910074', 'Furosemida 40mg', 'Cardiología', 'Caja x20 tabletas', 10, 1.20, 2.50, 'Genven', '2025-12-10', '2027-11-05', 'Crítico'),
(123, '75910075', 'Espironolactona 25mg', 'Cardiología', 'Caja x20 tabletas', 45, 2.50, 4.50, 'Meyer', '2026-01-28', '2028-06-20', 'Óptimo');

-- --------------------------------------------------------

--
-- Table structure for table `proveedores`
--

CREATE TABLE `proveedores` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `rif` varchar(20) NOT NULL,
  `categorias` varchar(255) NOT NULL,
  `telefono` varchar(20) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `direccion` text NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `proveedores`
--

INSERT INTO `proveedores` (`id`, `nombre`, `rif`, `categorias`, `telefono`, `correo`, `direccion`, `fecha_registro`) VALUES
(2, 'Panadol', '121323222', 'Gastrointestinal', '04248268559', 'pppfff@h.nnb', 'dsad', '2026-04-02 06:16:15');

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
  `telefono` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rol` enum('Administrador','Vendedor') DEFAULT 'Vendedor',
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `usuarios`
--

INSERT INTO `usuarios` (`id`, `tipo_doc`, `cedula`, `nombre`, `apellido`, `email`, `telefono`, `password`, `rol`, `fecha_registro`) VALUES
(1, 'V-', '31993721', 'Joseph', 'Perez', 'yo@gmail.com', '04248268559', '$2y$10$t5tJD6vv5y/n9aO1.Du3I.EFouZCLyiQtCAv9bl7aq.gJBE0/zmHa', 'Vendedor', '2026-04-03 23:01:03'),
(2, 'V-', '31993722', 'Panadol', 'Escobar', 'yod@gmail.com', '04248268554', '$2y$10$pewafbeFz9EAx1KukBsEie8Qjb3aUgUnmLU7inmjgfawj.KOWePdu', 'Administrador', '2026-04-05 03:25:32');

-- --------------------------------------------------------

--
-- Table structure for table `ventas`
--

CREATE TABLE `ventas` (
  `id` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `cedula_cliente` varchar(20) NOT NULL,
  `nombre_cliente` varchar(100) NOT NULL,
  `telefono_cliente` varchar(20) NOT NULL,
  `total_venta` decimal(10,2) NOT NULL,
  `id_vendedor` int(11) NOT NULL,
  `fecha_venta` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ventas`
--

INSERT INTO `ventas` (`id`, `id_cliente`, `cedula_cliente`, `nombre_cliente`, `telefono_cliente`, `total_venta`, `id_vendedor`, `fecha_venta`) VALUES
(1, 2, '31993722', 'Miranda Brito', '0000', 23.60, 1, '2026-04-06 05:21:44'),
(2, 3, '31993723', 'as asas', '0000', 6.00, 1, '2026-04-06 05:26:21'),
(3, 1, '31993721', 'Pablo Guevara', '04248268559', 6.00, 1, '2026-04-06 05:29:19'),
(4, 3, '31993723', 'Carlos Garcia', '04248268556', 26.50, 1, '2026-04-06 06:01:40'),
(5, 3, '31993723', 'Carlos Garcia', '04248268556', 21.70, 1, '2026-04-06 06:26:58'),
(6, 4, '29591791', 'samn Guevara', '0000', 13.50, 1, '2026-04-06 06:29:28');

-- --------------------------------------------------------

--
-- Table structure for table `ventas_detalle`
--

CREATE TABLE `ventas_detalle` (
  `id` int(11) NOT NULL,
  `id_venta` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `nombre_producto` varchar(100) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ventas_detalle`
--

INSERT INTO `ventas_detalle` (`id`, `id_venta`, `id_producto`, `nombre_producto`, `cantidad`, `precio_unitario`, `subtotal`) VALUES
(1, 5, 102, 'Alcohol Isopropílico 70%', 1, 3.00, 3.00),
(2, 5, 103, 'Agua Oxigenada', 1, 2.00, 2.00),
(3, 5, 53, 'Acetaminofén 500mg', 1, 1.20, 1.20),
(4, 5, 13, 'Allegra 120mg (Fexofenadina)', 1, 9.00, 9.00),
(5, 5, 92, 'Ambroxol Jarabe', 1, 2.00, 2.00),
(6, 5, 73, 'Amlodipina 5mg', 1, 4.50, 4.50),
(7, 6, 103, 'Agua Oxigenada', 1, 2.00, 2.00),
(8, 6, 102, 'Alcohol Isopropílico 70%', 3, 3.00, 9.00),
(9, 6, 104, 'Algodón 100g', 1, 2.50, 2.50);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cedula` (`cedula`);

--
-- Indexes for table `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `codigo` (`codigo`);

--
-- Indexes for table `proveedores`
--
ALTER TABLE `proveedores`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cedula` (`cedula`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `ventas`
--
ALTER TABLE `ventas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ventas_detalle`
--
ALTER TABLE `ventas_detalle`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_venta` (`id_venta`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=124;

--
-- AUTO_INCREMENT for table `proveedores`
--
ALTER TABLE `proveedores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `ventas`
--
ALTER TABLE `ventas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `ventas_detalle`
--
ALTER TABLE `ventas_detalle`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `ventas_detalle`
--
ALTER TABLE `ventas_detalle`
  ADD CONSTRAINT `ventas_detalle_ibfk_1` FOREIGN KEY (`id_venta`) REFERENCES `ventas` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

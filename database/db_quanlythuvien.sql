-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 15, 2026 at 04:57 PM
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
-- Database: `db_quanlythuvien`
--

-- --------------------------------------------------------

--
-- Table structure for table `ctphieumuon`
--

CREATE TABLE `ctphieumuon` (
  `mamuon` varchar(50) NOT NULL,
  `macuonsach` varchar(50) NOT NULL,
  `tinhtrang_truoc` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ctphieunhap`
--

CREATE TABLE `ctphieunhap` (
  `maphieunhap` varchar(50) NOT NULL,
  `madausach` varchar(50) NOT NULL,
  `dongianhap` decimal(10,2) DEFAULT NULL,
  `soluong` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ctphieuphat`
--

CREATE TABLE `ctphieuphat` (
  `maphat` varchar(50) NOT NULL,
  `macuonsach` varchar(50) NOT NULL,
  `lydo` varchar(255) DEFAULT NULL,
  `songayquahan` int(11) DEFAULT NULL,
  `sotienphat` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ctphieutra`
--

CREATE TABLE `ctphieutra` (
  `matra` varchar(50) NOT NULL,
  `macuonsach` varchar(50) NOT NULL,
  `maphat` varchar(50) NOT NULL,
  `tinhtrang_sau` varchar(50) DEFAULT NULL,
  `tienphathuha` decimal(10,2) DEFAULT NULL,
  `songayquahan` int(11) DEFAULT NULL,
  `tienphatquahan` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ctquyen`
--

CREATE TABLE `ctquyen` (
  `manhomquyen` varchar(50) NOT NULL,
  `machucnang` varchar(50) NOT NULL,
  `hanhdong` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cuonsach`
--

CREATE TABLE `cuonsach` (
  `macuonsach` varchar(50) NOT NULL,
  `madausach` varchar(50) DEFAULT NULL,
  `mavitri` varchar(50) DEFAULT NULL,
  `trangthai` varchar(50) DEFAULT NULL,
  `tinhtrang` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cuonsach`
--

INSERT INTO `cuonsach` (`macuonsach`, `madausach`, `mavitri`, `trangthai`, `tinhtrang`) VALUES
('CS0001', 'DS001', 'VT001', 'SanSang', 'Tot'),
('CS0002', 'DS001', 'VT002', 'SanSang', 'Moi'),
('CS0003', 'DS001', 'VT001', 'SanSang', 'Moi'),
('CS0004', 'DS001', 'VT001', 'DangMuon', 'Tot'),
('CS0005', 'DS001', 'VT003', 'SanSang', 'Moi'),
('CS0006', 'DS002', 'VT002', 'SanSang', 'Tot'),
('CS0007', 'DS002', 'VT003', 'SanSang', 'Tot'),
('CS0008', 'DS002', 'VT001', 'SanSang', 'Tot'),
('CS0009', 'DS002', 'VT001', 'DangMuon', 'Moi'),
('CS0010', 'DS002', 'VT003', 'SanSang', 'Moi'),
('CS0011', 'DS002', 'VT002', 'SanSang', 'Tot'),
('CS0012', 'DS002', 'VT002', 'SanSang', 'Moi'),
('CS0013', 'DS003', 'VT001', 'DangMuon', 'Tot'),
('CS0014', 'DS003', 'VT001', 'SanSang', 'Moi'),
('CS0015', 'DS003', 'VT003', 'SanSang', 'Tot'),
('CS0016', 'DS003', 'VT003', 'DangMuon', 'Tot'),
('CS0017', 'DS003', 'VT002', 'SanSang', 'Moi'),
('CS0018', 'DS003', 'VT001', 'SanSang', 'Moi'),
('CS0019', 'DS003', 'VT003', 'DangMuon', 'Moi'),
('CS0020', 'DS003', 'VT002', 'SanSang', 'Moi'),
('CS0021', 'DS003', 'VT001', 'SanSang', 'Moi'),
('CS0022', 'DS004', 'VT002', 'DangMuon', 'Moi'),
('CS0023', 'DS004', 'VT003', 'DangMuon', 'Tot'),
('CS0024', 'DS004', 'VT002', 'DangMuon', 'Moi'),
('CS0025', 'DS004', 'VT001', 'SanSang', 'Moi'),
('CS0026', 'DS004', 'VT002', 'SanSang', 'Moi'),
('CS0027', 'DS004', 'VT003', 'SanSang', 'Moi'),
('CS0028', 'DS004', 'VT001', 'SanSang', 'Tot'),
('CS0029', 'DS005', 'VT002', 'SanSang', 'Moi'),
('CS0030', 'DS005', 'VT002', 'SanSang', 'Moi'),
('CS0031', 'DS005', 'VT003', 'SanSang', 'Moi'),
('CS0032', 'DS005', 'VT001', 'SanSang', 'Tot'),
('CS0033', 'DS005', 'VT002', 'SanSang', 'Tot'),
('CS0034', 'DS005', 'VT002', 'DangMuon', 'Tot'),
('CS0035', 'DS005', 'VT001', 'DangMuon', 'Tot'),
('CS0036', 'DS005', 'VT003', 'SanSang', 'Tot'),
('CS0037', 'DS005', 'VT002', 'SanSang', 'Tot'),
('CS0038', 'DS006', 'VT001', 'SanSang', 'Tot'),
('CS0039', 'DS006', 'VT002', 'SanSang', 'Tot'),
('CS0040', 'DS006', 'VT001', 'DangMuon', 'Moi'),
('CS0041', 'DS006', 'VT003', 'DangMuon', 'Tot'),
('CS0042', 'DS006', 'VT001', 'SanSang', 'Tot'),
('CS0043', 'DS007', 'VT002', 'SanSang', 'Tot'),
('CS0044', 'DS007', 'VT003', 'DangMuon', 'Moi'),
('CS0045', 'DS007', 'VT001', 'SanSang', 'Moi'),
('CS0046', 'DS007', 'VT001', 'SanSang', 'Tot'),
('CS0047', 'DS007', 'VT001', 'SanSang', 'Moi'),
('CS0048', 'DS007', 'VT002', 'SanSang', 'Moi'),
('CS0049', 'DS008', 'VT001', 'SanSang', 'Moi'),
('CS0050', 'DS008', 'VT001', 'SanSang', 'Tot'),
('CS0051', 'DS008', 'VT003', 'DangMuon', 'Moi'),
('CS0052', 'DS008', 'VT001', 'Hong', 'HuHong'),
('CS0053', 'DS008', 'VT003', 'DangMuon', 'Tot'),
('CS0054', 'DS008', 'VT001', 'SanSang', 'Moi'),
('CS0055', 'DS008', 'VT002', 'SanSang', 'Tot'),
('CS0056', 'DS009', 'VT002', 'SanSang', 'Tot'),
('CS0057', 'DS009', 'VT001', 'DangMuon', 'Moi'),
('CS0058', 'DS009', 'VT003', 'DangMuon', 'Moi'),
('CS0059', 'DS009', 'VT003', 'DangMuon', 'Moi'),
('CS0060', 'DS009', 'VT003', 'SanSang', 'Moi'),
('CS0061', 'DS009', 'VT001', 'SanSang', 'Tot'),
('CS0062', 'DS009', 'VT003', 'SanSang', 'Moi'),
('CS0063', 'DS009', 'VT001', 'SanSang', 'Moi'),
('CS0064', 'DS009', 'VT001', 'SanSang', 'Moi'),
('CS0065', 'DS009', 'VT001', 'Hong', 'HuHong'),
('CS0066', 'DS010', 'VT003', 'SanSang', 'Moi'),
('CS0067', 'DS010', 'VT003', 'SanSang', 'Tot'),
('CS0068', 'DS010', 'VT001', 'SanSang', 'Tot'),
('CS0069', 'DS010', 'VT003', 'SanSang', 'Tot'),
('CS0070', 'DS010', 'VT002', 'SanSang', 'Tot'),
('CS0071', 'DS010', 'VT002', 'SanSang', 'Tot'),
('CS0072', 'DS010', 'VT002', 'SanSang', 'Tot'),
('CS0073', 'DS010', 'VT001', 'SanSang', 'Tot'),
('CS0074', 'DS011', 'VT001', 'DangMuon', 'Tot'),
('CS0075', 'DS011', 'VT003', 'SanSang', 'Moi'),
('CS0076', 'DS011', 'VT002', 'DangMuon', 'Moi'),
('CS0077', 'DS011', 'VT003', 'SanSang', 'Tot'),
('CS0078', 'DS011', 'VT002', 'SanSang', 'Moi'),
('CS0079', 'DS011', 'VT001', 'SanSang', 'Moi'),
('CS0080', 'DS012', 'VT003', 'SanSang', 'Tot'),
('CS0081', 'DS012', 'VT002', 'DangMuon', 'Moi'),
('CS0082', 'DS012', 'VT003', 'SanSang', 'Tot'),
('CS0083', 'DS012', 'VT001', 'SanSang', 'Moi'),
('CS0084', 'DS012', 'VT002', 'SanSang', 'Moi'),
('CS0085', 'DS012', 'VT001', 'SanSang', 'Tot'),
('CS0086', 'DS012', 'VT001', 'SanSang', 'Tot'),
('CS0087', 'DS012', 'VT003', 'SanSang', 'Tot'),
('CS0088', 'DS013', 'VT003', 'SanSang', 'Tot'),
('CS0089', 'DS013', 'VT002', 'SanSang', 'Moi'),
('CS0090', 'DS013', 'VT001', 'SanSang', 'Tot'),
('CS0091', 'DS013', 'VT001', 'DangMuon', 'Moi'),
('CS0092', 'DS013', 'VT003', 'SanSang', 'Tot'),
('CS0093', 'DS013', 'VT002', 'SanSang', 'Moi'),
('CS0094', 'DS013', 'VT001', 'DangMuon', 'Moi'),
('CS0095', 'DS014', 'VT001', 'DangMuon', 'Moi'),
('CS0096', 'DS014', 'VT002', 'SanSang', 'Moi'),
('CS0097', 'DS014', 'VT001', 'SanSang', 'Moi'),
('CS0098', 'DS014', 'VT001', 'SanSang', 'Moi'),
('CS0099', 'DS014', 'VT002', 'SanSang', 'Tot'),
('CS0100', 'DS015', 'VT002', 'SanSang', 'Tot'),
('CS0101', 'DS015', 'VT001', 'SanSang', 'Moi'),
('CS0102', 'DS015', 'VT001', 'SanSang', 'Moi'),
('CS0103', 'DS015', 'VT003', 'SanSang', 'Tot'),
('CS0104', 'DS015', 'VT003', 'SanSang', 'Moi'),
('CS0105', 'DS015', 'VT002', 'SanSang', 'Moi'),
('CS0106', 'DS016', 'VT001', 'SanSang', 'Moi'),
('CS0107', 'DS016', 'VT001', 'SanSang', 'Tot'),
('CS0108', 'DS016', 'VT003', 'Hong', 'HuHong'),
('CS0109', 'DS016', 'VT003', 'DangMuon', 'Moi'),
('CS0110', 'DS016', 'VT002', 'SanSang', 'Tot'),
('CS0111', 'DS016', 'VT002', 'SanSang', 'Tot'),
('CS0112', 'DS016', 'VT003', 'SanSang', 'Moi'),
('CS0113', 'DS016', 'VT001', 'DangMuon', 'Tot'),
('CS0114', 'DS016', 'VT003', 'DangMuon', 'Tot'),
('CS0115', 'DS017', 'VT001', 'SanSang', 'Tot'),
('CS0116', 'DS017', 'VT002', 'DangMuon', 'Moi'),
('CS0117', 'DS017', 'VT003', 'SanSang', 'Moi'),
('CS0118', 'DS017', 'VT002', 'SanSang', 'Tot'),
('CS0119', 'DS017', 'VT001', 'DangMuon', 'Moi'),
('CS0120', 'DS017', 'VT002', 'SanSang', 'Tot'),
('CS0121', 'DS017', 'VT002', 'SanSang', 'Tot'),
('CS0122', 'DS017', 'VT003', 'DangMuon', 'Moi'),
('CS0123', 'DS017', 'VT003', 'SanSang', 'Moi'),
('CS0124', 'DS017', 'VT003', 'SanSang', 'Moi'),
('CS0125', 'DS018', 'VT001', 'SanSang', 'Moi'),
('CS0126', 'DS018', 'VT003', 'SanSang', 'Moi'),
('CS0127', 'DS018', 'VT001', 'SanSang', 'Tot'),
('CS0128', 'DS018', 'VT001', 'SanSang', 'Moi'),
('CS0129', 'DS018', 'VT003', 'SanSang', 'Moi'),
('CS0130', 'DS019', 'VT001', 'SanSang', 'Moi'),
('CS0131', 'DS019', 'VT003', 'SanSang', 'Moi'),
('CS0132', 'DS019', 'VT003', 'DangMuon', 'Tot'),
('CS0133', 'DS019', 'VT002', 'SanSang', 'Moi'),
('CS0134', 'DS019', 'VT001', 'SanSang', 'Tot'),
('CS0135', 'DS019', 'VT001', 'SanSang', 'Tot'),
('CS0136', 'DS019', 'VT001', 'SanSang', 'Moi'),
('CS0137', 'DS019', 'VT002', 'DangMuon', 'Tot'),
('CS0138', 'DS019', 'VT002', 'SanSang', 'Moi'),
('CS0139', 'DS020', 'VT001', 'SanSang', 'Tot'),
('CS0140', 'DS020', 'VT003', 'SanSang', 'Moi'),
('CS0141', 'DS020', 'VT003', 'SanSang', 'Moi'),
('CS0142', 'DS020', 'VT003', 'SanSang', 'Moi'),
('CS0143', 'DS020', 'VT002', 'SanSang', 'Moi'),
('CS0144', 'DS020', 'VT003', 'SanSang', 'Tot'),
('CS0145', 'DS020', 'VT001', 'SanSang', 'Moi'),
('CS0146', 'DS020', 'VT002', 'DangMuon', 'Moi'),
('CS0147', 'DS020', 'VT002', 'SanSang', 'Tot'),
('CS0148', 'DS020', 'VT001', 'SanSang', 'Tot'),
('CS0149', 'DS021', 'VT003', 'SanSang', 'Moi'),
('CS0150', 'DS021', 'VT002', 'SanSang', 'Moi'),
('CS0151', 'DS021', 'VT001', 'DangMuon', 'Tot'),
('CS0152', 'DS021', 'VT001', 'SanSang', 'Tot'),
('CS0153', 'DS021', 'VT003', 'SanSang', 'Moi'),
('CS0154', 'DS021', 'VT001', 'SanSang', 'Tot'),
('CS0155', 'DS021', 'VT001', 'DangMuon', 'Moi'),
('CS0156', 'DS021', 'VT003', 'SanSang', 'Moi'),
('CS0157', 'DS021', 'VT003', 'SanSang', 'Moi'),
('CS0158', 'DS021', 'VT002', 'SanSang', 'Moi'),
('CS0159', 'DS022', 'VT003', 'SanSang', 'Tot'),
('CS0160', 'DS022', 'VT002', 'SanSang', 'Tot'),
('CS0161', 'DS022', 'VT003', 'Hong', 'HuHong'),
('CS0162', 'DS022', 'VT002', 'DangMuon', 'Tot'),
('CS0163', 'DS022', 'VT003', 'SanSang', 'Tot'),
('CS0164', 'DS022', 'VT002', 'SanSang', 'Moi'),
('CS0165', 'DS022', 'VT002', 'DangMuon', 'Moi'),
('CS0166', 'DS022', 'VT003', 'SanSang', 'Moi'),
('CS0167', 'DS022', 'VT003', 'SanSang', 'Tot'),
('CS0168', 'DS022', 'VT001', 'SanSang', 'Tot'),
('CS0169', 'DS023', 'VT001', 'Hong', 'HuHong'),
('CS0170', 'DS023', 'VT002', 'SanSang', 'Tot'),
('CS0171', 'DS023', 'VT003', 'Hong', 'HuHong'),
('CS0172', 'DS023', 'VT001', 'DangMuon', 'Moi'),
('CS0173', 'DS023', 'VT001', 'DangMuon', 'Tot'),
('CS0174', 'DS023', 'VT002', 'SanSang', 'Moi'),
('CS0175', 'DS023', 'VT002', 'Hong', 'HuHong'),
('CS0176', 'DS023', 'VT003', 'SanSang', 'Moi'),
('CS0177', 'DS024', 'VT003', 'SanSang', 'Moi'),
('CS0178', 'DS024', 'VT001', 'DangMuon', 'Tot'),
('CS0179', 'DS024', 'VT002', 'SanSang', 'Moi'),
('CS0180', 'DS024', 'VT003', 'Hong', 'HuHong'),
('CS0181', 'DS024', 'VT001', 'SanSang', 'Tot'),
('CS0182', 'DS024', 'VT003', 'SanSang', 'Moi'),
('CS0183', 'DS024', 'VT003', 'SanSang', 'Tot'),
('CS0184', 'DS025', 'VT001', 'SanSang', 'Tot'),
('CS0185', 'DS025', 'VT003', 'SanSang', 'Tot'),
('CS0186', 'DS025', 'VT002', 'DangMuon', 'Tot'),
('CS0187', 'DS025', 'VT001', 'DangMuon', 'Tot'),
('CS0188', 'DS025', 'VT002', 'DangMuon', 'Moi'),
('CS0189', 'DS026', 'VT001', 'DangMuon', 'Tot'),
('CS0190', 'DS026', 'VT003', 'SanSang', 'Tot'),
('CS0191', 'DS026', 'VT003', 'DangMuon', 'Moi'),
('CS0192', 'DS026', 'VT003', 'SanSang', 'Moi'),
('CS0193', 'DS026', 'VT002', 'DangMuon', 'Moi'),
('CS0194', 'DS026', 'VT001', 'SanSang', 'Tot'),
('CS0195', 'DS026', 'VT002', 'SanSang', 'Moi'),
('CS0196', 'DS026', 'VT003', 'DangMuon', 'Moi'),
('CS0197', 'DS026', 'VT001', 'SanSang', 'Moi'),
('CS0198', 'DS026', 'VT002', 'DangMuon', 'Tot'),
('CS0199', 'DS027', 'VT002', 'SanSang', 'Moi'),
('CS0200', 'DS027', 'VT002', 'SanSang', 'Moi'),
('CS0201', 'DS027', 'VT003', 'SanSang', 'Tot'),
('CS0202', 'DS027', 'VT002', 'SanSang', 'Moi'),
('CS0203', 'DS027', 'VT002', 'SanSang', 'Moi'),
('CS0204', 'DS027', 'VT002', 'DangMuon', 'Tot'),
('CS0205', 'DS027', 'VT001', 'SanSang', 'Tot'),
('CS0206', 'DS027', 'VT001', 'Hong', 'HuHong'),
('CS0207', 'DS028', 'VT003', 'DangMuon', 'Tot'),
('CS0208', 'DS028', 'VT001', 'DangMuon', 'Tot'),
('CS0209', 'DS028', 'VT002', 'Hong', 'HuHong'),
('CS0210', 'DS028', 'VT003', 'SanSang', 'Moi'),
('CS0211', 'DS028', 'VT003', 'DangMuon', 'Tot'),
('CS0212', 'DS029', 'VT003', 'SanSang', 'Moi'),
('CS0213', 'DS029', 'VT002', 'SanSang', 'Tot'),
('CS0214', 'DS029', 'VT001', 'SanSang', 'Moi'),
('CS0215', 'DS029', 'VT001', 'SanSang', 'Tot'),
('CS0216', 'DS029', 'VT002', 'DangMuon', 'Moi'),
('CS0217', 'DS029', 'VT002', 'SanSang', 'Tot'),
('CS0218', 'DS030', 'VT003', 'SanSang', 'Tot'),
('CS0219', 'DS030', 'VT002', 'DangMuon', 'Moi'),
('CS0220', 'DS030', 'VT001', 'DangMuon', 'Tot'),
('CS0221', 'DS030', 'VT003', 'SanSang', 'Moi'),
('CS0222', 'DS030', 'VT003', 'SanSang', 'Moi'),
('CS0223', 'DS030', 'VT001', 'DangMuon', 'Tot'),
('CS0224', 'DS030', 'VT001', 'SanSang', 'Tot'),
('CS0225', 'DS030', 'VT002', 'DangMuon', 'Moi'),
('CS0226', 'DS030', 'VT003', 'SanSang', 'Moi'),
('CS0227', 'DS030', 'VT002', 'SanSang', 'Tot'),
('CS0228', 'DS031', 'VT002', 'SanSang', 'Tot'),
('CS0229', 'DS031', 'VT002', 'SanSang', 'Moi'),
('CS0230', 'DS031', 'VT003', 'SanSang', 'Tot'),
('CS0231', 'DS031', 'VT001', 'SanSang', 'Moi'),
('CS0232', 'DS031', 'VT002', 'Hong', 'HuHong'),
('CS0233', 'DS031', 'VT002', 'SanSang', 'Moi'),
('CS0234', 'DS031', 'VT002', 'SanSang', 'Tot'),
('CS0235', 'DS031', 'VT003', 'SanSang', 'Moi'),
('CS0236', 'DS032', 'VT003', 'SanSang', 'Tot'),
('CS0237', 'DS032', 'VT001', 'SanSang', 'Moi'),
('CS0238', 'DS032', 'VT003', 'SanSang', 'Moi'),
('CS0239', 'DS032', 'VT001', 'DangMuon', 'Moi'),
('CS0240', 'DS032', 'VT003', 'SanSang', 'Tot'),
('CS0241', 'DS033', 'VT002', 'SanSang', 'Tot'),
('CS0242', 'DS033', 'VT002', 'SanSang', 'Moi'),
('CS0243', 'DS033', 'VT002', 'SanSang', 'Tot'),
('CS0244', 'DS033', 'VT002', 'SanSang', 'Moi'),
('CS0245', 'DS033', 'VT001', 'SanSang', 'Tot'),
('CS0246', 'DS033', 'VT001', 'SanSang', 'Tot'),
('CS0247', 'DS033', 'VT003', 'SanSang', 'Tot'),
('CS0248', 'DS033', 'VT001', 'SanSang', 'Moi'),
('CS0249', 'DS034', 'VT002', 'SanSang', 'Tot'),
('CS0250', 'DS034', 'VT003', 'DangMuon', 'Moi'),
('CS0251', 'DS034', 'VT003', 'SanSang', 'Tot'),
('CS0252', 'DS034', 'VT001', 'SanSang', 'Tot'),
('CS0253', 'DS034', 'VT001', 'SanSang', 'Moi'),
('CS0254', 'DS034', 'VT001', 'DangMuon', 'Moi'),
('CS0255', 'DS034', 'VT001', 'DangMuon', 'Moi'),
('CS0256', 'DS034', 'VT001', 'SanSang', 'Moi'),
('CS0257', 'DS034', 'VT002', 'SanSang', 'Tot'),
('CS0258', 'DS035', 'VT001', 'SanSang', 'Moi'),
('CS0259', 'DS035', 'VT003', 'SanSang', 'Moi'),
('CS0260', 'DS035', 'VT003', 'SanSang', 'Moi'),
('CS0261', 'DS035', 'VT002', 'SanSang', 'Tot'),
('CS0262', 'DS035', 'VT003', 'SanSang', 'Tot'),
('CS0263', 'DS035', 'VT001', 'Hong', 'HuHong'),
('CS0264', 'DS035', 'VT002', 'SanSang', 'Tot'),
('CS0265', 'DS036', 'VT003', 'SanSang', 'Tot'),
('CS0266', 'DS036', 'VT001', 'SanSang', 'Tot'),
('CS0267', 'DS036', 'VT003', 'DangMuon', 'Tot'),
('CS0268', 'DS036', 'VT002', 'DangMuon', 'Moi'),
('CS0269', 'DS036', 'VT003', 'SanSang', 'Moi'),
('CS0270', 'DS036', 'VT002', 'SanSang', 'Moi'),
('CS0271', 'DS036', 'VT001', 'DangMuon', 'Tot'),
('CS0272', 'DS037', 'VT001', 'DangMuon', 'Tot'),
('CS0273', 'DS037', 'VT002', 'DangMuon', 'Tot'),
('CS0274', 'DS037', 'VT003', 'SanSang', 'Moi'),
('CS0275', 'DS037', 'VT002', 'SanSang', 'Tot'),
('CS0276', 'DS037', 'VT001', 'SanSang', 'Moi'),
('CS0277', 'DS037', 'VT002', 'DangMuon', 'Moi'),
('CS0278', 'DS038', 'VT002', 'SanSang', 'Tot'),
('CS0279', 'DS038', 'VT003', 'DangMuon', 'Tot'),
('CS0280', 'DS038', 'VT001', 'Hong', 'HuHong'),
('CS0281', 'DS038', 'VT001', 'SanSang', 'Tot'),
('CS0282', 'DS038', 'VT001', 'SanSang', 'Moi'),
('CS0283', 'DS038', 'VT002', 'SanSang', 'Tot'),
('CS0284', 'DS038', 'VT003', 'SanSang', 'Moi'),
('CS0285', 'DS038', 'VT002', 'SanSang', 'Tot'),
('CS0286', 'DS038', 'VT001', 'SanSang', 'Moi'),
('CS0287', 'DS039', 'VT003', 'SanSang', 'Tot'),
('CS0288', 'DS039', 'VT002', 'SanSang', 'Moi'),
('CS0289', 'DS039', 'VT001', 'SanSang', 'Tot'),
('CS0290', 'DS039', 'VT001', 'SanSang', 'Tot'),
('CS0291', 'DS039', 'VT001', 'DangMuon', 'Moi'),
('CS0292', 'DS039', 'VT002', 'SanSang', 'Moi'),
('CS0293', 'DS039', 'VT001', 'SanSang', 'Tot'),
('CS0294', 'DS039', 'VT003', 'SanSang', 'Moi'),
('CS0295', 'DS039', 'VT002', 'SanSang', 'Tot'),
('CS0296', 'DS040', 'VT003', 'DangMuon', 'Moi'),
('CS0297', 'DS040', 'VT002', 'SanSang', 'Tot'),
('CS0298', 'DS040', 'VT001', 'SanSang', 'Tot'),
('CS0299', 'DS040', 'VT001', 'DangMuon', 'Tot'),
('CS0300', 'DS040', 'VT003', 'SanSang', 'Moi'),
('CS0301', 'DS040', 'VT001', 'SanSang', 'Tot'),
('CS0302', 'DS040', 'VT002', 'SanSang', 'Tot'),
('CS0303', 'DS040', 'VT002', 'DangMuon', 'Tot'),
('CS0304', 'DS040', 'VT001', 'DangMuon', 'Tot'),
('CS0305', 'DS041', 'VT001', 'DangMuon', 'Moi'),
('CS0306', 'DS041', 'VT003', 'SanSang', 'Moi'),
('CS0307', 'DS041', 'VT001', 'SanSang', 'Tot'),
('CS0308', 'DS041', 'VT003', 'DangMuon', 'Moi'),
('CS0309', 'DS041', 'VT003', 'SanSang', 'Tot'),
('CS0310', 'DS041', 'VT001', 'SanSang', 'Moi'),
('CS0311', 'DS041', 'VT002', 'SanSang', 'Tot'),
('CS0312', 'DS041', 'VT001', 'DangMuon', 'Tot'),
('CS0313', 'DS041', 'VT001', 'SanSang', 'Tot'),
('CS0314', 'DS041', 'VT002', 'SanSang', 'Moi'),
('CS0315', 'DS042', 'VT002', 'SanSang', 'Tot'),
('CS0316', 'DS042', 'VT001', 'SanSang', 'Tot'),
('CS0317', 'DS042', 'VT001', 'SanSang', 'Moi'),
('CS0318', 'DS042', 'VT002', 'SanSang', 'Moi'),
('CS0319', 'DS042', 'VT001', 'SanSang', 'Tot'),
('CS0320', 'DS042', 'VT002', 'SanSang', 'Moi'),
('CS0321', 'DS042', 'VT002', 'SanSang', 'Moi'),
('CS0322', 'DS043', 'VT001', 'DangMuon', 'Tot'),
('CS0323', 'DS043', 'VT003', 'SanSang', 'Tot'),
('CS0324', 'DS043', 'VT002', 'SanSang', 'Tot'),
('CS0325', 'DS043', 'VT003', 'DangMuon', 'Moi'),
('CS0326', 'DS043', 'VT001', 'SanSang', 'Moi'),
('CS0327', 'DS043', 'VT002', 'SanSang', 'Moi'),
('CS0328', 'DS044', 'VT001', 'SanSang', 'Moi'),
('CS0329', 'DS044', 'VT003', 'SanSang', 'Moi'),
('CS0330', 'DS044', 'VT003', 'SanSang', 'Moi'),
('CS0331', 'DS044', 'VT001', 'SanSang', 'Tot'),
('CS0332', 'DS044', 'VT002', 'SanSang', 'Tot'),
('CS0333', 'DS044', 'VT003', 'SanSang', 'Tot'),
('CS0334', 'DS044', 'VT002', 'SanSang', 'Tot'),
('CS0335', 'DS045', 'VT002', 'DangMuon', 'Tot'),
('CS0336', 'DS045', 'VT003', 'SanSang', 'Moi'),
('CS0337', 'DS045', 'VT003', 'SanSang', 'Moi'),
('CS0338', 'DS045', 'VT002', 'DangMuon', 'Tot'),
('CS0339', 'DS045', 'VT001', 'DangMuon', 'Moi'),
('CS0340', 'DS045', 'VT001', 'DangMuon', 'Tot'),
('CS0341', 'DS046', 'VT003', 'DangMuon', 'Tot'),
('CS0342', 'DS046', 'VT001', 'DangMuon', 'Moi'),
('CS0343', 'DS046', 'VT003', 'SanSang', 'Moi'),
('CS0344', 'DS046', 'VT001', 'DangMuon', 'Moi'),
('CS0345', 'DS046', 'VT001', 'SanSang', 'Tot'),
('CS0346', 'DS046', 'VT002', 'SanSang', 'Moi'),
('CS0347', 'DS046', 'VT002', 'SanSang', 'Tot'),
('CS0348', 'DS047', 'VT003', 'DangMuon', 'Tot'),
('CS0349', 'DS047', 'VT002', 'DangMuon', 'Tot'),
('CS0350', 'DS047', 'VT002', 'Hong', 'HuHong'),
('CS0351', 'DS047', 'VT003', 'Hong', 'HuHong'),
('CS0352', 'DS047', 'VT002', 'SanSang', 'Tot'),
('CS0353', 'DS047', 'VT002', 'DangMuon', 'Moi'),
('CS0354', 'DS047', 'VT001', 'DangMuon', 'Moi'),
('CS0355', 'DS047', 'VT003', 'SanSang', 'Moi'),
('CS0356', 'DS047', 'VT002', 'SanSang', 'Tot'),
('CS0357', 'DS047', 'VT003', 'SanSang', 'Moi'),
('CS0358', 'DS048', 'VT002', 'SanSang', 'Moi'),
('CS0359', 'DS048', 'VT001', 'SanSang', 'Tot'),
('CS0360', 'DS048', 'VT003', 'SanSang', 'Tot'),
('CS0361', 'DS048', 'VT003', 'SanSang', 'Tot'),
('CS0362', 'DS048', 'VT001', 'SanSang', 'Tot'),
('CS0363', 'DS048', 'VT003', 'SanSang', 'Tot'),
('CS0364', 'DS048', 'VT001', 'SanSang', 'Tot'),
('CS0365', 'DS048', 'VT001', 'SanSang', 'Tot'),
('CS0366', 'DS049', 'VT002', 'Hong', 'HuHong'),
('CS0367', 'DS049', 'VT003', 'DangMuon', 'Tot'),
('CS0368', 'DS049', 'VT002', 'SanSang', 'Tot'),
('CS0369', 'DS049', 'VT002', 'SanSang', 'Tot'),
('CS0370', 'DS049', 'VT001', 'SanSang', 'Tot'),
('CS0371', 'DS050', 'VT002', 'SanSang', 'Moi'),
('CS0372', 'DS050', 'VT002', 'DangMuon', 'Moi'),
('CS0373', 'DS050', 'VT002', 'SanSang', 'Tot'),
('CS0374', 'DS050', 'VT001', 'DangMuon', 'Moi'),
('CS0375', 'DS050', 'VT003', 'DangMuon', 'Moi'),
('CS0376', 'DS050', 'VT001', 'SanSang', 'Tot'),
('CS0377', 'DS050', 'VT002', 'SanSang', 'Moi'),
('CS0378', 'DS050', 'VT003', 'SanSang', 'Moi'),
('CS0379', 'DS050', 'VT001', 'SanSang', 'Tot'),
('CS0380', 'DS051', 'VT003', 'SanSang', 'Moi'),
('CS0381', 'DS051', 'VT001', 'SanSang', 'Tot'),
('CS0382', 'DS051', 'VT001', 'Hong', 'HuHong'),
('CS0383', 'DS051', 'VT001', 'SanSang', 'Moi'),
('CS0384', 'DS051', 'VT001', 'SanSang', 'Moi'),
('CS0385', 'DS052', 'VT003', 'SanSang', 'Moi'),
('CS0386', 'DS052', 'VT002', 'SanSang', 'Moi'),
('CS0387', 'DS052', 'VT001', 'SanSang', 'Moi'),
('CS0388', 'DS052', 'VT002', 'DangMuon', 'Moi'),
('CS0389', 'DS052', 'VT003', 'DangMuon', 'Tot'),
('CS0390', 'DS053', 'VT003', 'DangMuon', 'Moi'),
('CS0391', 'DS053', 'VT003', 'SanSang', 'Tot'),
('CS0392', 'DS053', 'VT003', 'DangMuon', 'Moi'),
('CS0393', 'DS053', 'VT003', 'SanSang', 'Moi'),
('CS0394', 'DS053', 'VT001', 'SanSang', 'Moi'),
('CS0395', 'DS053', 'VT001', 'SanSang', 'Moi'),
('CS0396', 'DS053', 'VT002', 'SanSang', 'Moi'),
('CS0397', 'DS054', 'VT002', 'SanSang', 'Tot'),
('CS0398', 'DS054', 'VT003', 'Hong', 'HuHong'),
('CS0399', 'DS054', 'VT002', 'SanSang', 'Tot'),
('CS0400', 'DS054', 'VT001', 'SanSang', 'Moi'),
('CS0401', 'DS054', 'VT003', 'DangMuon', 'Tot'),
('CS0402', 'DS054', 'VT003', 'SanSang', 'Moi'),
('CS0403', 'DS054', 'VT001', 'SanSang', 'Tot'),
('CS0404', 'DS054', 'VT003', 'DangMuon', 'Moi'),
('CS0405', 'DS055', 'VT001', 'SanSang', 'Moi'),
('CS0406', 'DS055', 'VT002', 'SanSang', 'Tot'),
('CS0407', 'DS055', 'VT003', 'SanSang', 'Tot'),
('CS0408', 'DS055', 'VT003', 'SanSang', 'Tot'),
('CS0409', 'DS055', 'VT003', 'SanSang', 'Tot'),
('CS0410', 'DS055', 'VT002', 'SanSang', 'Moi'),
('CS0411', 'DS055', 'VT003', 'DangMuon', 'Tot'),
('CS0412', 'DS055', 'VT001', 'DangMuon', 'Moi'),
('CS0413', 'DS056', 'VT001', 'SanSang', 'Tot'),
('CS0414', 'DS056', 'VT001', 'DangMuon', 'Moi'),
('CS0415', 'DS056', 'VT001', 'SanSang', 'Tot'),
('CS0416', 'DS056', 'VT001', 'SanSang', 'Moi'),
('CS0417', 'DS056', 'VT002', 'SanSang', 'Tot'),
('CS0418', 'DS056', 'VT003', 'SanSang', 'Moi'),
('CS0419', 'DS056', 'VT002', 'SanSang', 'Moi'),
('CS0420', 'DS057', 'VT001', 'SanSang', 'Tot'),
('CS0421', 'DS057', 'VT002', 'SanSang', 'Moi'),
('CS0422', 'DS057', 'VT001', 'SanSang', 'Tot'),
('CS0423', 'DS057', 'VT003', 'SanSang', 'Tot'),
('CS0424', 'DS057', 'VT003', 'SanSang', 'Tot'),
('CS0425', 'DS057', 'VT003', 'SanSang', 'Tot'),
('CS0426', 'DS058', 'VT003', 'Hong', 'HuHong'),
('CS0427', 'DS058', 'VT003', 'DangMuon', 'Tot'),
('CS0428', 'DS058', 'VT002', 'SanSang', 'Moi'),
('CS0429', 'DS058', 'VT003', 'Hong', 'HuHong'),
('CS0430', 'DS058', 'VT001', 'SanSang', 'Moi'),
('CS0431', 'DS058', 'VT002', 'SanSang', 'Tot'),
('CS0432', 'DS058', 'VT001', 'SanSang', 'Moi'),
('CS0433', 'DS059', 'VT003', 'SanSang', 'Moi'),
('CS0434', 'DS059', 'VT001', 'DangMuon', 'Tot'),
('CS0435', 'DS059', 'VT003', 'SanSang', 'Moi'),
('CS0436', 'DS059', 'VT001', 'SanSang', 'Moi'),
('CS0437', 'DS059', 'VT001', 'SanSang', 'Tot'),
('CS0438', 'DS059', 'VT001', 'SanSang', 'Moi'),
('CS0439', 'DS059', 'VT001', 'DangMuon', 'Moi'),
('CS0440', 'DS059', 'VT003', 'SanSang', 'Moi'),
('CS0441', 'DS059', 'VT001', 'SanSang', 'Tot'),
('CS0442', 'DS059', 'VT002', 'DangMuon', 'Tot'),
('CS0443', 'DS060', 'VT002', 'SanSang', 'Tot'),
('CS0444', 'DS060', 'VT001', 'DangMuon', 'Tot'),
('CS0445', 'DS060', 'VT001', 'SanSang', 'Moi'),
('CS0446', 'DS060', 'VT001', 'SanSang', 'Moi'),
('CS0447', 'DS060', 'VT002', 'SanSang', 'Tot'),
('CS0448', 'DS060', 'VT002', 'SanSang', 'Moi'),
('CS0449', 'DS060', 'VT001', 'DangMuon', 'Tot'),
('CS0450', 'DS060', 'VT002', 'DangMuon', 'Tot'),
('CS0451', 'DS061', 'VT002', 'SanSang', 'Moi'),
('CS0452', 'DS061', 'VT002', 'DangMuon', 'Tot'),
('CS0453', 'DS061', 'VT002', 'SanSang', 'Moi'),
('CS0454', 'DS061', 'VT001', 'DangMuon', 'Tot'),
('CS0455', 'DS061', 'VT001', 'SanSang', 'Tot'),
('CS0456', 'DS061', 'VT002', 'SanSang', 'Tot'),
('CS0457', 'DS061', 'VT001', 'SanSang', 'Tot'),
('CS0458', 'DS061', 'VT001', 'SanSang', 'Moi'),
('CS0459', 'DS062', 'VT001', 'SanSang', 'Moi'),
('CS0460', 'DS062', 'VT001', 'SanSang', 'Moi'),
('CS0461', 'DS062', 'VT001', 'SanSang', 'Tot'),
('CS0462', 'DS062', 'VT003', 'DangMuon', 'Tot'),
('CS0463', 'DS062', 'VT002', 'SanSang', 'Tot'),
('CS0464', 'DS062', 'VT003', 'SanSang', 'Tot'),
('CS0465', 'DS062', 'VT003', 'Hong', 'HuHong'),
('CS0466', 'DS062', 'VT002', 'SanSang', 'Tot'),
('CS0467', 'DS062', 'VT003', 'SanSang', 'Moi'),
('CS0468', 'DS062', 'VT001', 'SanSang', 'Tot'),
('CS0469', 'DS063', 'VT003', 'SanSang', 'Tot'),
('CS0470', 'DS063', 'VT001', 'SanSang', 'Moi'),
('CS0471', 'DS063', 'VT001', 'DangMuon', 'Tot'),
('CS0472', 'DS063', 'VT001', 'SanSang', 'Tot'),
('CS0473', 'DS063', 'VT002', 'SanSang', 'Moi'),
('CS0474', 'DS063', 'VT001', 'DangMuon', 'Tot'),
('CS0475', 'DS063', 'VT001', 'SanSang', 'Tot'),
('CS0476', 'DS063', 'VT001', 'SanSang', 'Moi'),
('CS0477', 'DS063', 'VT002', 'SanSang', 'Tot'),
('CS0478', 'DS064', 'VT003', 'SanSang', 'Tot'),
('CS0479', 'DS064', 'VT001', 'SanSang', 'Moi'),
('CS0480', 'DS064', 'VT003', 'DangMuon', 'Moi'),
('CS0481', 'DS064', 'VT001', 'DangMuon', 'Moi'),
('CS0482', 'DS064', 'VT003', 'DangMuon', 'Tot'),
('CS0483', 'DS064', 'VT001', 'DangMuon', 'Tot'),
('CS0484', 'DS064', 'VT001', 'SanSang', 'Tot'),
('CS0485', 'DS065', 'VT003', 'SanSang', 'Tot'),
('CS0486', 'DS065', 'VT003', 'SanSang', 'Moi'),
('CS0487', 'DS065', 'VT001', 'Hong', 'HuHong'),
('CS0488', 'DS065', 'VT001', 'DangMuon', 'Tot'),
('CS0489', 'DS065', 'VT003', 'SanSang', 'Tot'),
('CS0490', 'DS065', 'VT003', 'DangMuon', 'Moi'),
('CS0491', 'DS065', 'VT001', 'SanSang', 'Tot'),
('CS0492', 'DS065', 'VT003', 'SanSang', 'Moi'),
('CS0493', 'DS065', 'VT001', 'DangMuon', 'Moi'),
('CS0494', 'DS065', 'VT003', 'SanSang', 'Moi'),
('CS0495', 'DS066', 'VT003', 'SanSang', 'Moi'),
('CS0496', 'DS066', 'VT001', 'SanSang', 'Moi'),
('CS0497', 'DS066', 'VT003', 'DangMuon', 'Tot'),
('CS0498', 'DS066', 'VT001', 'DangMuon', 'Moi'),
('CS0499', 'DS066', 'VT003', 'DangMuon', 'Tot'),
('CS0500', 'DS066', 'VT002', 'SanSang', 'Tot'),
('CS0501', 'DS066', 'VT002', 'DangMuon', 'Tot'),
('CS0502', 'DS066', 'VT001', 'DangMuon', 'Moi'),
('CS0503', 'DS066', 'VT003', 'DangMuon', 'Tot'),
('CS0504', 'DS067', 'VT002', 'SanSang', 'Tot'),
('CS0505', 'DS067', 'VT002', 'DangMuon', 'Moi'),
('CS0506', 'DS067', 'VT001', 'SanSang', 'Tot'),
('CS0507', 'DS067', 'VT001', 'DangMuon', 'Moi'),
('CS0508', 'DS067', 'VT001', 'SanSang', 'Tot'),
('CS0509', 'DS067', 'VT003', 'SanSang', 'Tot'),
('CS0510', 'DS067', 'VT001', 'SanSang', 'Moi'),
('CS0511', 'DS068', 'VT003', 'SanSang', 'Tot'),
('CS0512', 'DS068', 'VT002', 'SanSang', 'Moi'),
('CS0513', 'DS068', 'VT001', 'SanSang', 'Moi'),
('CS0514', 'DS068', 'VT001', 'SanSang', 'Tot'),
('CS0515', 'DS068', 'VT001', 'DangMuon', 'Tot'),
('CS0516', 'DS068', 'VT002', 'SanSang', 'Tot'),
('CS0517', 'DS068', 'VT001', 'SanSang', 'Tot'),
('CS0518', 'DS068', 'VT003', 'SanSang', 'Tot'),
('CS0519', 'DS069', 'VT002', 'DangMuon', 'Tot'),
('CS0520', 'DS069', 'VT001', 'DangMuon', 'Moi'),
('CS0521', 'DS069', 'VT001', 'DangMuon', 'Tot'),
('CS0522', 'DS069', 'VT003', 'SanSang', 'Moi'),
('CS0523', 'DS069', 'VT001', 'DangMuon', 'Moi'),
('CS0524', 'DS069', 'VT001', 'SanSang', 'Moi'),
('CS0525', 'DS069', 'VT001', 'SanSang', 'Tot'),
('CS0526', 'DS070', 'VT001', 'SanSang', 'Moi'),
('CS0527', 'DS070', 'VT002', 'SanSang', 'Tot'),
('CS0528', 'DS070', 'VT003', 'SanSang', 'Moi'),
('CS0529', 'DS070', 'VT002', 'SanSang', 'Moi'),
('CS0530', 'DS070', 'VT003', 'SanSang', 'Tot'),
('CS0531', 'DS070', 'VT003', 'SanSang', 'Moi'),
('CS0532', 'DS070', 'VT003', 'SanSang', 'Moi'),
('CS0533', 'DS071', 'VT001', 'SanSang', 'Moi'),
('CS0534', 'DS071', 'VT003', 'Hong', 'HuHong'),
('CS0535', 'DS071', 'VT003', 'SanSang', 'Tot'),
('CS0536', 'DS071', 'VT002', 'DangMuon', 'Moi'),
('CS0537', 'DS071', 'VT002', 'DangMuon', 'Tot'),
('CS0538', 'DS071', 'VT003', 'SanSang', 'Tot'),
('CS0539', 'DS072', 'VT003', 'SanSang', 'Moi'),
('CS0540', 'DS072', 'VT003', 'DangMuon', 'Moi'),
('CS0541', 'DS072', 'VT003', 'SanSang', 'Tot'),
('CS0542', 'DS072', 'VT003', 'SanSang', 'Tot'),
('CS0543', 'DS072', 'VT002', 'SanSang', 'Moi'),
('CS0544', 'DS072', 'VT002', 'SanSang', 'Moi'),
('CS0545', 'DS073', 'VT003', 'SanSang', 'Moi'),
('CS0546', 'DS073', 'VT001', 'SanSang', 'Tot'),
('CS0547', 'DS073', 'VT003', 'SanSang', 'Tot'),
('CS0548', 'DS073', 'VT001', 'SanSang', 'Moi'),
('CS0549', 'DS073', 'VT001', 'SanSang', 'Tot'),
('CS0550', 'DS074', 'VT001', 'Hong', 'HuHong'),
('CS0551', 'DS074', 'VT001', 'SanSang', 'Tot'),
('CS0552', 'DS074', 'VT002', 'Hong', 'HuHong'),
('CS0553', 'DS074', 'VT003', 'DangMuon', 'Moi'),
('CS0554', 'DS074', 'VT003', 'SanSang', 'Tot'),
('CS0555', 'DS074', 'VT002', 'DangMuon', 'Tot'),
('CS0556', 'DS074', 'VT003', 'SanSang', 'Tot'),
('CS0557', 'DS075', 'VT002', 'Hong', 'HuHong'),
('CS0558', 'DS075', 'VT003', 'DangMuon', 'Moi'),
('CS0559', 'DS075', 'VT003', 'SanSang', 'Moi'),
('CS0560', 'DS075', 'VT002', 'SanSang', 'Moi'),
('CS0561', 'DS075', 'VT001', 'DangMuon', 'Moi'),
('CS0562', 'DS075', 'VT003', 'SanSang', 'Moi'),
('CS0563', 'DS075', 'VT002', 'SanSang', 'Moi'),
('CS0564', 'DS075', 'VT003', 'SanSang', 'Tot'),
('CS0565', 'DS075', 'VT002', 'SanSang', 'Moi'),
('CS0566', 'DS076', 'VT001', 'SanSang', 'Moi'),
('CS0567', 'DS076', 'VT003', 'SanSang', 'Tot'),
('CS0568', 'DS076', 'VT002', 'SanSang', 'Tot'),
('CS0569', 'DS076', 'VT002', 'SanSang', 'Tot'),
('CS0570', 'DS076', 'VT002', 'SanSang', 'Tot'),
('CS0571', 'DS076', 'VT002', 'SanSang', 'Moi'),
('CS0572', 'DS076', 'VT002', 'SanSang', 'Moi'),
('CS0573', 'DS076', 'VT001', 'SanSang', 'Moi'),
('CS0574', 'DS076', 'VT002', 'DangMuon', 'Moi'),
('CS0575', 'DS077', 'VT003', 'SanSang', 'Tot'),
('CS0576', 'DS077', 'VT002', 'SanSang', 'Tot'),
('CS0577', 'DS077', 'VT003', 'SanSang', 'Tot'),
('CS0578', 'DS077', 'VT003', 'SanSang', 'Moi'),
('CS0579', 'DS077', 'VT003', 'DangMuon', 'Tot'),
('CS0580', 'DS077', 'VT003', 'SanSang', 'Tot'),
('CS0581', 'DS078', 'VT001', 'SanSang', 'Tot'),
('CS0582', 'DS078', 'VT001', 'SanSang', 'Moi'),
('CS0583', 'DS078', 'VT003', 'SanSang', 'Tot'),
('CS0584', 'DS078', 'VT001', 'SanSang', 'Tot'),
('CS0585', 'DS078', 'VT002', 'SanSang', 'Tot'),
('CS0586', 'DS078', 'VT003', 'SanSang', 'Tot'),
('CS0587', 'DS078', 'VT003', 'SanSang', 'Moi'),
('CS0588', 'DS078', 'VT002', 'SanSang', 'Moi'),
('CS0589', 'DS078', 'VT002', 'DangMuon', 'Moi'),
('CS0590', 'DS079', 'VT003', 'Hong', 'HuHong'),
('CS0591', 'DS079', 'VT003', 'SanSang', 'Moi'),
('CS0592', 'DS079', 'VT002', 'DangMuon', 'Tot'),
('CS0593', 'DS079', 'VT003', 'DangMuon', 'Tot'),
('CS0594', 'DS079', 'VT002', 'SanSang', 'Tot'),
('CS0595', 'DS079', 'VT001', 'SanSang', 'Moi'),
('CS0596', 'DS079', 'VT003', 'SanSang', 'Moi'),
('CS0597', 'DS079', 'VT001', 'SanSang', 'Tot'),
('CS0598', 'DS080', 'VT002', 'SanSang', 'Tot'),
('CS0599', 'DS080', 'VT001', 'Hong', 'HuHong'),
('CS0600', 'DS080', 'VT002', 'DangMuon', 'Moi'),
('CS0601', 'DS080', 'VT003', 'SanSang', 'Moi'),
('CS0602', 'DS080', 'VT003', 'SanSang', 'Moi'),
('CS0603', 'DS080', 'VT002', 'SanSang', 'Tot'),
('CS0604', 'DS080', 'VT002', 'SanSang', 'Moi'),
('CS0605', 'DS081', 'VT003', 'SanSang', 'Moi'),
('CS0606', 'DS081', 'VT002', 'DangMuon', 'Tot'),
('CS0607', 'DS081', 'VT002', 'Hong', 'HuHong'),
('CS0608', 'DS081', 'VT001', 'DangMuon', 'Tot'),
('CS0609', 'DS081', 'VT002', 'SanSang', 'Tot'),
('CS0610', 'DS081', 'VT003', 'Hong', 'HuHong'),
('CS0611', 'DS081', 'VT003', 'SanSang', 'Moi'),
('CS0612', 'DS081', 'VT001', 'SanSang', 'Moi'),
('CS0613', 'DS081', 'VT002', 'SanSang', 'Tot'),
('CS0614', 'DS081', 'VT001', 'SanSang', 'Tot'),
('CS0615', 'DS082', 'VT003', 'DangMuon', 'Moi'),
('CS0616', 'DS082', 'VT001', 'SanSang', 'Tot'),
('CS0617', 'DS082', 'VT002', 'SanSang', 'Moi'),
('CS0618', 'DS082', 'VT003', 'SanSang', 'Moi'),
('CS0619', 'DS082', 'VT001', 'DangMuon', 'Moi'),
('CS0620', 'DS082', 'VT002', 'SanSang', 'Tot'),
('CS0621', 'DS082', 'VT001', 'Hong', 'HuHong'),
('CS0622', 'DS082', 'VT002', 'SanSang', 'Tot'),
('CS0623', 'DS082', 'VT001', 'SanSang', 'Moi'),
('CS0624', 'DS082', 'VT002', 'DangMuon', 'Tot'),
('CS0625', 'DS083', 'VT003', 'SanSang', 'Moi'),
('CS0626', 'DS083', 'VT003', 'SanSang', 'Tot'),
('CS0627', 'DS083', 'VT003', 'DangMuon', 'Tot'),
('CS0628', 'DS083', 'VT003', 'SanSang', 'Tot'),
('CS0629', 'DS083', 'VT003', 'DangMuon', 'Moi'),
('CS0630', 'DS083', 'VT001', 'SanSang', 'Tot'),
('CS0631', 'DS083', 'VT002', 'SanSang', 'Moi'),
('CS0632', 'DS083', 'VT002', 'SanSang', 'Tot'),
('CS0633', 'DS083', 'VT002', 'SanSang', 'Tot'),
('CS0634', 'DS083', 'VT002', 'DangMuon', 'Tot'),
('CS0635', 'DS084', 'VT001', 'SanSang', 'Moi'),
('CS0636', 'DS084', 'VT001', 'SanSang', 'Tot'),
('CS0637', 'DS084', 'VT002', 'Hong', 'HuHong'),
('CS0638', 'DS084', 'VT002', 'SanSang', 'Tot'),
('CS0639', 'DS084', 'VT003', 'SanSang', 'Moi'),
('CS0640', 'DS084', 'VT003', 'SanSang', 'Moi'),
('CS0641', 'DS084', 'VT002', 'SanSang', 'Moi'),
('CS0642', 'DS084', 'VT003', 'SanSang', 'Tot'),
('CS0643', 'DS084', 'VT003', 'SanSang', 'Moi'),
('CS0644', 'DS085', 'VT001', 'SanSang', 'Tot'),
('CS0645', 'DS085', 'VT003', 'Hong', 'HuHong'),
('CS0646', 'DS085', 'VT001', 'SanSang', 'Tot'),
('CS0647', 'DS085', 'VT002', 'DangMuon', 'Moi'),
('CS0648', 'DS085', 'VT003', 'SanSang', 'Moi'),
('CS0649', 'DS085', 'VT001', 'SanSang', 'Tot'),
('CS0650', 'DS085', 'VT001', 'DangMuon', 'Moi'),
('CS0651', 'DS086', 'VT003', 'SanSang', 'Moi'),
('CS0652', 'DS086', 'VT003', 'DangMuon', 'Moi'),
('CS0653', 'DS086', 'VT003', 'SanSang', 'Moi'),
('CS0654', 'DS086', 'VT002', 'SanSang', 'Moi'),
('CS0655', 'DS086', 'VT001', 'SanSang', 'Moi'),
('CS0656', 'DS086', 'VT001', 'DangMuon', 'Moi'),
('CS0657', 'DS086', 'VT002', 'SanSang', 'Tot'),
('CS0658', 'DS086', 'VT001', 'DangMuon', 'Moi'),
('CS0659', 'DS087', 'VT001', 'SanSang', 'Moi'),
('CS0660', 'DS087', 'VT003', 'SanSang', 'Moi'),
('CS0661', 'DS087', 'VT001', 'DangMuon', 'Tot'),
('CS0662', 'DS087', 'VT002', 'Hong', 'HuHong'),
('CS0663', 'DS087', 'VT003', 'DangMuon', 'Moi'),
('CS0664', 'DS087', 'VT001', 'SanSang', 'Tot'),
('CS0665', 'DS087', 'VT001', 'SanSang', 'Tot'),
('CS0666', 'DS087', 'VT001', 'SanSang', 'Moi'),
('CS0667', 'DS087', 'VT003', 'SanSang', 'Tot'),
('CS0668', 'DS087', 'VT002', 'SanSang', 'Moi'),
('CS0669', 'DS088', 'VT002', 'DangMuon', 'Tot'),
('CS0670', 'DS088', 'VT001', 'SanSang', 'Tot'),
('CS0671', 'DS088', 'VT003', 'SanSang', 'Tot'),
('CS0672', 'DS088', 'VT001', 'DangMuon', 'Tot'),
('CS0673', 'DS088', 'VT002', 'SanSang', 'Tot'),
('CS0674', 'DS088', 'VT001', 'SanSang', 'Tot'),
('CS0675', 'DS088', 'VT003', 'DangMuon', 'Tot'),
('CS0676', 'DS088', 'VT003', 'Hong', 'HuHong'),
('CS0677', 'DS089', 'VT003', 'SanSang', 'Moi'),
('CS0678', 'DS089', 'VT003', 'SanSang', 'Moi'),
('CS0679', 'DS089', 'VT002', 'SanSang', 'Moi'),
('CS0680', 'DS089', 'VT002', 'SanSang', 'Tot'),
('CS0681', 'DS089', 'VT001', 'DangMuon', 'Moi'),
('CS0682', 'DS090', 'VT002', 'SanSang', 'Tot'),
('CS0683', 'DS090', 'VT003', 'SanSang', 'Tot'),
('CS0684', 'DS090', 'VT003', 'SanSang', 'Tot'),
('CS0685', 'DS090', 'VT001', 'Hong', 'HuHong'),
('CS0686', 'DS090', 'VT001', 'SanSang', 'Moi'),
('CS0687', 'DS090', 'VT003', 'SanSang', 'Tot'),
('CS0688', 'DS090', 'VT002', 'SanSang', 'Tot'),
('CS0689', 'DS090', 'VT001', 'SanSang', 'Tot'),
('CS0690', 'DS090', 'VT003', 'DangMuon', 'Moi'),
('CS0691', 'DS090', 'VT001', 'Hong', 'HuHong'),
('CS0692', 'DS091', 'VT003', 'SanSang', 'Moi'),
('CS0693', 'DS091', 'VT003', 'DangMuon', 'Tot'),
('CS0694', 'DS091', 'VT003', 'DangMuon', 'Tot'),
('CS0695', 'DS091', 'VT003', 'SanSang', 'Moi'),
('CS0696', 'DS091', 'VT002', 'SanSang', 'Moi'),
('CS0697', 'DS091', 'VT003', 'SanSang', 'Moi'),
('CS0698', 'DS091', 'VT001', 'DangMuon', 'Moi'),
('CS0699', 'DS092', 'VT003', 'DangMuon', 'Tot'),
('CS0700', 'DS092', 'VT002', 'SanSang', 'Tot'),
('CS0701', 'DS092', 'VT003', 'SanSang', 'Tot'),
('CS0702', 'DS092', 'VT001', 'Hong', 'HuHong'),
('CS0703', 'DS092', 'VT001', 'SanSang', 'Moi'),
('CS0704', 'DS092', 'VT001', 'SanSang', 'Tot'),
('CS0705', 'DS092', 'VT003', 'SanSang', 'Tot'),
('CS0706', 'DS092', 'VT001', 'SanSang', 'Moi'),
('CS0707', 'DS092', 'VT003', 'SanSang', 'Tot'),
('CS0708', 'DS093', 'VT002', 'DangMuon', 'Tot'),
('CS0709', 'DS093', 'VT003', 'DangMuon', 'Tot'),
('CS0710', 'DS093', 'VT002', 'DangMuon', 'Moi'),
('CS0711', 'DS093', 'VT003', 'SanSang', 'Tot'),
('CS0712', 'DS093', 'VT003', 'SanSang', 'Tot'),
('CS0713', 'DS093', 'VT001', 'SanSang', 'Moi'),
('CS0714', 'DS093', 'VT002', 'SanSang', 'Tot'),
('CS0715', 'DS093', 'VT003', 'SanSang', 'Tot'),
('CS0716', 'DS093', 'VT001', 'DangMuon', 'Tot'),
('CS0717', 'DS093', 'VT002', 'Hong', 'HuHong'),
('CS0718', 'DS094', 'VT002', 'SanSang', 'Moi'),
('CS0719', 'DS094', 'VT001', 'SanSang', 'Moi'),
('CS0720', 'DS094', 'VT003', 'SanSang', 'Moi'),
('CS0721', 'DS094', 'VT003', 'DangMuon', 'Moi'),
('CS0722', 'DS094', 'VT003', 'SanSang', 'Moi'),
('CS0723', 'DS094', 'VT002', 'SanSang', 'Tot');

-- --------------------------------------------------------

--
-- Table structure for table `danhmucchucnang`
--

CREATE TABLE `danhmucchucnang` (
  `machucnang` varchar(50) NOT NULL,
  `tenchucnang` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dausach`
--

CREATE TABLE `dausach` (
  `madausach` varchar(50) NOT NULL,
  `tensach` varchar(255) DEFAULT NULL,
  `namxuatban` int(11) DEFAULT NULL,
  `dongia` decimal(12,2) DEFAULT NULL,
  `matacgia` varchar(50) DEFAULT NULL,
  `matheloai` varchar(50) DEFAULT NULL,
  `manxb` varchar(50) DEFAULT NULL,
  `mota` text DEFAULT NULL,
  `anhbia` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dausach`
--

INSERT INTO `dausach` (`madausach`, `tensach`, `namxuatban`, `dongia`, `matacgia`, `matheloai`, `manxb`, `mota`, `anhbia`) VALUES
('DS001', 'Cách Tạo Video Triệu View', 2026, 268000.00, 'TG001', 'TL001', 'NXB003', 'Cách Tạo Video Triệu View\n\nBAN SẼ HOC ĐƯỢC GÌ TỪ CUỐN SÁCH NÀY?\n\n- Kiến thức nhập môn liên quan đến kịch bản video ngắn bao gồm cấu trúc tinh tiết và phân tích tâm lý, thông qua các bước cư thể như lựa chọn chủ để, lên kế hoạch chủ để, quay phim, cách biểu đạt tới thoại và ngôn ngữ mấy quay.\n\n- 129 kỳ thuật viết kịch bản bao cảm xác định nội dung chính, tình tiết cao tiếng vang thiết lập các nút thắc, quản lý tiết tấu cầu chuyện, xây dựng nhân vật, bố cục cảnh quay.\n\n- Các nguyên tốc viết kịch bản, logic cảm xúc và kỳ thuật tạo xung đột, đẩy cảm xúc đến cao trào.\n\nĐể tạo được một video triệu view, có khả năng viral trên mạng xã hội, đói hỏi các nhà sáng tạo nội dung phải có một kịch bán hấp dẫn, lôi cuốn Và thông qua các góc nhìn đa chiêu, cuốn sách này giống như một cuốn cẩm nang toàn diện giúp bạn trở thành một biên kịch video ngắn xuất sắc.\n\nVới bố cục rõ ràng cuốn sách phủ hợp với những người mới bước chân vào ngành biên kịch video ngắn, cùng như các nhớ sáng tạo biên cập viên video ngắn, phim truyện hình dồi tập, chương trình truyền hình và phim ảnh Sách cùng có thể dùng làm giáo trình tham khảo cho sinh viên đại học chuyên ngành điện ảnh, truyên hình và kịch.', '8935235247314_4fb356aaf56f479f929b31c15c378e2a_medium.jpg'),
('DS002', 'Đặt Tên Thương Hiệu', 2024, 70000.00, 'TG002', 'TL001', 'NXB002', 'Đặt Tên Thương Hiệu\n\nTên thương hiệu là một thành tố cực kỳ quan trọng của thương hiệu, vì nó chính là sự khác biệt đầu tiên và lớn nhất giữa sản phẩm/ dịch vụ này với sản phẩm/ dịch vụ khác cùng loại.\n\nTrong dài hạn, thương hiệu cũng chỉ tồn tại dưới dạng một cái tên. Và theo thời gian, nó cũng chính là tài sản lớn nhất của doanh nghiệp.\n\nCái tên có thể làm nên một thương hiệu mạnh. Vì vậy sở hữu một tên độc đáo và hấp dẫn sẽ là một lợi thế rất lớn. Nó không chỉ giúp bạn khác biệt với đối thủ cạnh tranh mà còn gây thiện cảm và kết nối với khách hàng mục tiêu một cách đầy cảm xúc.\n\nChính vì vậy đặt tên thương hiệu bao giờ cũng là việc trọng tâm của bất kì một kế hoạch phát triển thương hiệu nào.\n\nĐây là cuốn đầu tiên của tác giả trong combo 3 cuốn viết về thương hiệu.\n\nCuốn thứ hai sẽ viết các bước còn lại của quy trình xây dựng thương hiệu doanh nghiệp gồm: định vị thương hiệu, tính cách thương hiệu, kiến trúc thương hiệu và truyền thông thương hiệu.\n\nCuốn này có tựa là \"Định vị thương hiệu\".\n\nVà cuốn thứ ba sẽ viết về thương hiệu cá nhân, bao gồm cách thức và các bước cụ thể để xây dựng một thương hiệu cá nhân nổi bật.', 'b_a---_t-t_n-th_ng-hi_u_fc80c58702074f9c8023365cee90350e_medium.jpg'),
('DS003', 'Ứng Dụng Agile Marketing', 2025, 195000.00, 'TG004', 'TL001', 'NXB004', 'Ứng Dụng Agile Marketing\n\nTrong một thị trường thay đổi từng ngày, marketing không thể vận hành theo lối mòn. Các doanh nghiệp và nhà quản lý cần một phương pháp tư duy linh hoạt, có khả năng thích ứng nhanh với biến động nhưng vẫn đảm bảo hiệu quả bền vững. Và đó chính là giá trị cốt lõi mà “Ứng Dụng Agile Marketing” mang đến.\n\nDựa trên nguyên lý Agile tư duy đã tạo nên bước ngoặt trong lĩnh vực phát triển phần mềm. Cuốn sách chỉ ra cách đưa sự linh hoạt, thử nghiệm và tối ưu liên tục vào chiến lược marketing. Không dừng lại ở lý thuyết, sách đưa ra khung làm việc rõ ràng, case study thực tiễn và công cụ triển khai cụ thể, giúp đội ngũ marketing:\n\n- Rút ngắn thời gian từ ý tưởng đến hành động.\n\n- Đo lường và cải tiến chiến dịch liên tục.\n\n- Thích ứng nhanh với hành vi và nhu cầu khách hàng luôn biến đổi.\n\n- Tăng tính phối hợp đa chức năng trong doanh nghiệp, phá vỡ “silo” giữa các bộ phận.\n\nĐiểm đặc biệt của cuốn sách là cách tiếp cận “thực chiến”: mỗi chương đều gắn với bài học thực tế, từ quản lý backlog, sprint, đến việc áp dụng Kanban và Scrum trong marketing. Thay vì những khái niệm mơ hồ, tất cả đều được minh họa bằng quy trình và tình huống cụ thể để người đọc có thể áp dụng ngay.\n\n“Ứng Dụng Agile Marketing” là tài liệu không thể thiếu cho:\n\n- Nhà quản lý và lãnh đạo doanh nghiệp muốn xây dựng đội ngũ marketing linh hoạt và hiệu quả.\n\n- Marketer, chuyên viên truyền thông, digital muốn nâng cao năng lực thích ứng và sáng tạo.\n\n- Doanh nhân khởi nghiệp cần cách tiếp cận marketing tinh gọn nhưng vẫn hiệu quả.\n\n- Sinh viên, học viên MBA và nhà nghiên cứu quan tâm đến xu hướng marketing hiện đại.', 'ung-dung-agile-marketing---converted-01_576b070204534ec38be121b19d116aa8_medium.jpg'),
('DS004', 'Manifest Marketing', 2025, 159000.00, 'TG003', 'TL001', 'NXB004', 'Manifest Marketing\n\nTrong một thế giới kỹ thuật số ngày càng minh bạch, nơi thông tin về sản phẩm có thể được truy cập tức thì chỉ bằng một cú nhấp chuột, niềm tin đã trở thành yếu tố sống còn của mọi chiến lược marketing. Những phương pháp tiếp thị truyền thống, nặng về thao túng và thiếu chân thực, đang dần mất hiệu lực trong một thị trường mà người tiêu dùng biết nhiều như người bán. Trước bối cảnh đó, \"Manifest Marketing\" của Joe Vitale đưa ra một cách tiếp cận hoàn toàn khác: tiếp thị nhân quả - một mô hình lấy \"cho đi vô điều kiện\" làm nền tảng để xây dựng lòng tin và tạo ảnh hưởng sâu sắc.\n\nCuốn sách không đơn thuần là lý thuyết, mà là một bản hướng dẫn thực tiễn được hình thành từ chính trải nghiệm sống và kinh doanh của tác giả, một chuyên gia marketing từng đi lên từ con số không. Với giọng văn mạch lạc, dễ tiếp cận nhưng không thiếu chiều sâu, Joe Vitale lần lượt khai mở:\n\n- Cốt lõi của marketing nhân quả: cho đi không điều kiện, để nhận lại niềm tin và sự gắn bó từ khách hàng.\n\n- Những rào cản tâm lý khiến con người sợ phải \"cho\", và cách vượt qua để kiến tạo dòng chảy thịnh vượng.\n\n- Cách áp dụng nguyên lý nhân quả vào tiếp thị qua internet, truyền thông xã hội, email và quan hệ công chúng.\n\n- Những nguyên tắc bất biến trong tiếp thị, dù công cụ có đổi thay.\n\nĐiều khiến “Manifest Marketing” khác biệt chính là tính kết nối giữa tâm lý học, hành vi tiêu dùng và triết lý sống. Marketing ở đây không còn là kỹ năng thao túng mà trở thành nghệ thuật lan tỏa giá trị thực. Người đọc sẽ nhận ra rằng: tiếp thị không chỉ để bán hàng, mà còn là một hành trình kiến tạo ảnh hưởng tích cực đến cộng đồng, và chính cuộc đời mình.\n\nDành cho các nhà tiếp thị, doanh nhân, chuyên gia thương hiệu hoặc bất kỳ ai muốn trở thành người truyền cảm hứng bằng sự chân thành và giá trị thực, \"Manifest Marketing\" không chỉ là một cuốn sách về kỹ thuật tiếp thị, mà là lời mời gọi tái định nghĩa lại cách tạo ra ảnh hưởng trong thời đại mới.', 'manifest-marketing---b_a-1_4357b56059e34434b9e1b8c99d30cedc_medium.jpg'),
('DS005', 'Công Thức Bán Hàng Bất Bại', 2025, 88000.00, 'TG005', 'TL001', 'NXB002', 'Công Thức Bán Hàng Bất Bại  Bạn là người bán hàng, chủ doanh nghiệp hay đang khởi nghiệp? Bạn muốn tăng doanh số, chốt đơn nhanh chóng và xây dựng mối quan hệ bền vững với khách hàng? Cuốn sáchCông thức bán hàng bất bại: Làm chủ tâm lý - Thống trị doanh sốcủa tác giả Khôi chính là chìa khóa bạn cần!  Vì sao bạn nên đọc cuốn sách này?  Hiểu tâm lý khách hàng: Nắm bắt suy nghĩ, cảm xúc và hành vi của khách hàng để đưa ra giải pháp phù hợp.  Xây dựng chiến lược bán hàng hiệu quả: Áp dụng các kỹ thuật và chiến thuật đã được kiểm chứng để tăng tỷ lệ chốt đơn.  Phát triển kỹ năng giao tiếp: Học cách lắng nghe, thuyết phục và tạo dựng niềm tin với khách hàng.  Đối mặt với từ chối: Biến mỗi lần từ chối thành cơ hội học hỏi và cải thiện.  Nội dung cuốn sách bao gồm:  Phân tích tâm lý khách hàng trong từng giai đoạn mua hàng.  Các bước xây dựng quy trình bán hàng từ tiếp cận đến chốt đơn.  Kỹ thuật xử lý từ chối và biến khách hàng tiềm năng thành khách hàng trung thành.  Cách tạo dựng thương hiệu cá nhân và uy tín trong mắt khách hàng.  Lợi ích khi áp dụng công thức bán hàng bất bại:  Tăng doanh số bán hàng một cách bền vững.  Xây dựng mối quan hệ lâu dài với khách hàng.  Phát triển kỹ năng bán hàng chuyên nghiệp và tự tin.  Đối tượng phù hợp:  Người mới bắt đầu trong lĩnh vực bán hàng.  Chủ doanh nghiệp muốn cải thiện doanh số.  Những ai muốn nâng cao kỹ năng giao tiếp và thuyết phục.', '9786326042788_80ee7caa4bfa488fa274d1b90a563f91_medium.jpg'),
('DS006', 'Đổi Mới Với Intergrated Brand Thinking', 2025, 250000.00, 'TG006', 'TL001', 'NXB005', '“Đổi Mới Với Integrated Brand Thinking” là cuốn sách thứ hai trong bộ ba ấn phẩm về xây dựng thương hiệu của tác giả Richard Moore, đem đến hành trình khám phá sâu rộng các mô hình thương hiệu từ những năm 50 đến nay, cũng như giới thiệu phương pháp “Integrated Brand Thinking” để giúp doanh nghiệp xây dựng thương hiệu toàn diện và hiệu quả hơn trong thời kỳ hậu COVID.\n\nChủ đề trọng tâm - Tư duy Thương hiệu Tích hợp (Integrated Brand Thinking - IBT) là một bước tiến vượt bậc so với phương pháp Nhận diện Thương hiệu truyền thống. Xuất phát từ thấu hiểu bản chất thương hiệu, IBT sẽ khiến hình ảnh thương hiệu trở nên sống động hơn, vừa tối ưu hóa việc áp dụng tích cách thương hiệu trong nội bộ, vừa củng cố hình ảnh khác biệt ra bên ngoài, thấu hiểu và đồng hành cùng khách hàng như những người bạn đích thực.\n\nCuốn sách không chỉ mang đến góc nhìn mới mẻ về bản chất của thương hiệu mà còn là kho tàng kiến thức phong phú về các mô hình thương hiệu nổi tiếng trong suốt 70 năm qua. Đặc biệt, tác giả Richard Moore đã trình bày một cách đầy đủ và chi tiết các nguyên tắc cốt lõi của IBT và minh họa bằng nhiều ví dụ thực tiễn, sáng tạo.\n\nVề Richard Moore, ông là Nhà sáng lập, Chủ tịch kiêm Tổng Giám đốc Sáng tạo của Công ty Tư vấn Hình ảnh Thương hiệu Richard Moore Associates, đơn vị đồng hành cùng hơn 100 doanh nghiệp hàng đầu tại Việt Nam trong việc xây dựng và phát triển bản sắc nhận diện thương hiệu toàn diện.\n\nNếu bạn muốn kiến tạo một hình ảnh thương hiệu thống nhất, sâu sắc, có tính linh hoạt cao và dễ dàng điều chỉnh để phù hợp với mọi quy mô và đặc thù doanh nghiệp, thì chắc chắn “Đổi Mới Với Integrated Brand Thinking” sẽ là cuốn sách bạn nhất định phải đọc. Đừng bỏ lỡ!', '8936170870902_e6e16aea8b3144edbe8fc40862c97744_medium.jpg'),
('DS007', 'Thuật Cạnh Tranh', 2025, 229000.00, 'TG007', 'TL001', 'NXB004', 'Thuật Cạnh Tranh\n\nTại sao cùng một món ăn nhưng nó lại trở nên ngon miệng hơn chỉ nhờ cách trình bày bắt mắt? Vì sao một chai rượu được cho là “đắt tiền” lại khiến ta cảm nhận vị ngon sâu sắc hơn, dù thực chất chỉ là sản phẩm bình dân? “Thuật Cạnh Tranh” là một lời giải cho những câu hỏi này, và còn nhiều hơn thế nữa.\n\nCuốn sách là sự kết hợp giữa khoa học thần kinh, tâm lý học nhận thức và kinh nghiệm thực chiến trong lĩnh vực marketing, nhằm khám phá cách các thương hiệu hiện đại len lỏi vào hệ thần kinh của người tiêu dùng để tác động lên cảm xúc, hành vi và quyết định mua sắm của họ.\n\nKhông đơn thuần là một cuốn sách về kỹ thuật tiếp thị, “Thuật Cạnh Tranh” giúp người đọc thấu hiểu bản chất của những thao tác tinh vi đang diễn ra đằng sau từng cú click chuột, từng mẫu quảng cáo tưởng như vô hại. Với những nghiên cứu khoa học và ví dụ cụ thể, cuốn sách lý giải tại sao một chiến dịch truyền thông lại khiến người tiêu dùng “nghiện” sản phẩm mà không hề hay biết, và làm thế nào để thương hiệu tạo ra sự gắn bó cảm xúc lâu dài.\n\n12 chương sách là 12 lớp lang khám phá từ hiệu ứng thị giác mù, sức mạnh của cảm xúc, mùi vị và ký ức, đến cách các thương hiệu “đọc vị” bản đồ tư duy và thay đổi hành vi tiêu dùng. Đây là hành trình từ nhận thức đến vô thức, từ lý trí đến cảm xúc, nơi những quyết định tưởng chừng cá nhân lại được dẫn dắt bởi hệ thống được thiết kế kỹ lưỡng bởi các nhà tiếp thị.\n\n“Thuật Cạnh Tranh” không chỉ hữu ích với những người làm marketing, bán hàng, mà còn là cuốn cẩm nang giúp người tiêu dùng hiểu rõ bản than, để trở thành người cầm lái thay vì chỉ là hành khách trong hành trình tiêu dùng đầy cám dỗ.', 'thu_t-c_nh-tranh---b_a-1_53f298b3db0d4b6a9d70df8e2ef1e76f_medium.jpg'),
('DS008', 'Chiến Lược Marketing Thực Chiến', 2025, 169000.00, 'TG008', 'TL001', 'NXB004', 'Chiến Lược Marketing Thực Chiến\n\nTrong một thế giới mà hành vi tiêu dùng không ngừng biến chuyển bởi công nghệ và dữ liệu, năng lực xây dựng chiến lược marketing vừa linh hoạt vừa hiệu quả đang trở thành yếu tố sống còn đối với các doanh nghiệp. Chiến Lược Marketing Thực Chiến của Dan White là một cẩm nang được thiết kế để đáp ứng chính nhu cầu đó – giúp người đọc không chỉ hiểu mà còn có thể áp dụng các nguyên lý marketing vào thực tiễn một cách trực tiếp và có hệ thống.\n\nĐiểm nổi bật của cuốn sách nằm ở cấu trúc rõ ràng, trực quan và tính ứng dụng cao. Dan White chia toàn bộ hành trình marketing thành 10 phần từ phát triển thương hiệu, trải nghiệm khách hàng, đổi mới, truyền thông, đến đo lường và mở rộng thương hiệu. Mỗi phần trình bày những khái niệm nền tảng đi kèm với hình ảnh minh họa sinh động và các khung tư duy đã được kiểm chứng. Đây là điểm đặc biệt giúp ngay cả những người mới bước chân vào lĩnh vực marketing cũng có thể nắm bắt và vận dụng một cách hiệu quả.\n\nKhông sa đà vào lý thuyết trừu tượng hay thuật ngữ chuyên ngành phức tạp, Chiến Lược Marketing Thực Chiến tập trung vào giải pháp: cách xác định đúng mục tiêu, lập kế hoạch cụ thể, thấu hiểu tâm lý khách hàng, tối ưu chi phí quảng cáo và sáng tạo truyền thông độc đáo. Những nội dung tưởng chừng rời rạc như xây dựng thương hiệu hay đo lường hiệu quả đều được kết nối khéo léo để phục vụ một mục tiêu chung: thúc đẩy doanh số và phát triển bền vững.\n\nCuốn sách không chỉ dành cho marketer chuyên nghiệp mà còn phù hợp với nhà khởi nghiệp, chủ doanh nghiệp nhỏ và bất kỳ ai muốn học hỏi bài bản về marketing hiện đại. Đây là bản hướng dẫn thiết yếu cho hành trình tạo dựng giá trị thương hiệu và khả năng cạnh tranh thực chất trong bối cảnh kinh doanh đầy biến động.', 'b_a-1_5_34_216867ec60e340409dcde4350f989732_medium.jpg'),
('DS009', 'Human Branding - Nuôi Dưỡng Thương Hiệu Bằ̀ng Sự Thấu Cảm', 2025, 219000.00, 'TG009', 'TL001', 'NXB004', 'Human Branding - Nuôi Dưỡng Thương Hiệu Bằ̀ng Sự Thấu Cảm\n\nChúng ta kết nối với thương hiệu giống như cách chúng ta kết nối với con người – bằng cảm xúc, niềm tin và những tương tác có ý nghĩa. Từ việc lựa chọn sản phẩm trên website đến việc trực tiếp mua sắm tại cửa hàng, người tiêu dùng luôn tìm kiếm một sự kết nối. Những thương hiệu thành công nhất không chỉ cung cấp sản phẩm hay dịch vụ, mà còn biết cách tạo ra sự gắn bó về cảm xúc, gây dựng tình yêu thương hiệu từ những trải nghiệm để đời.\n\nTương tự như cách chúng ta duy trì các mối quan hệ cá nhân, một thương hiệu cần xây dựng sự tin tưởng, trung thực và mang lại giá trị đích thực cho khách hàng. Khi mối quan hệ giữa thương hiệu và người dùng được xây dựng dựa trên lòng tin, sự thấu cảm và những câu chuyện cốt lõi, đó chính là lúc tình yêu thương hiệu được hình thành.\n\nHãy tưởng tượng, khi bạn muốn kết nối với ai đó ở một mức độ sâu sắc, bạn sẽ chia sẻ những điều thân mật về bản thân mình. Và đổi phương thường sẽ đáp lại bằng những câu chuyện cá nhân của họ. Trong thương hiệu cũng vậy, khách hàng sẽ yêu mến những thương hiệu dám chính trực, dám kể những câu chuyện thật, và dám chấp nhận tạo nên những trải nghiệm chân thành và đáng nhớ.\n\nMột thương hiệu thành công không chỉ dựa trên sự kết nối cảm xúc mà còn phải mang lại giá trị thiết thực. Con người không chỉ muốn mua sản phẩm, họ muốn đồng hành cùng những thương hiệu hiểu về mình, chia sẻ những giá trị chung và có khả năng thấu cảm với họ.\n\nCuốn sách \"Human Branding – Nuôi dưỡng thương hiệu bằng sự thấu cảm\" sẽ giúp bạn hiểu rõ cách xây dựng một thương hiệu vững chắc theo thời gian, vừng chắc trong lòng khách hàng và vừng chắc trên thị trường.', 'nding--nuoi-duong-bang-su-thau-cam------full-----t1-2025----outline-02_17ba7d5bdd584466a5895d17371669c1_medium.jpg'),
('DS010', 'Inamori Kazuo Mỗi Ngày Một Câu Nói Nâng Tầm Vận Mệnh', 2021, 95000.00, 'TG010', 'TL001', 'NXB001', 'Inamori Kazuo Mỗi Ngày Một Câu Nói Nâng Tầm Vận Mệnh\n\nCuốn sách Inamori Kazuo Mỗi Ngày Một Câu Nói Nâng Tầm Vận Mệnh là một bộ sưu tập các câu nói, triết lý về cuộc sống và công việc, được chia thành từng ngày trong năm. Mỗi ngày, tác giả chia sẻ một câu nói hoặc một bài học ngắn gọn, thể hiện triết lý sống của ông, khuyến khích Cuốn sách được chia thành 12 chương, tương ứng với 12 tháng trong năm. Mỗi tháng chứa các câu nói và triết lý được trình bày theo từng ngày, với các bài học khác nhau về cuộc sống, công việc, và nhân cách.', '2025_01_13_16_45_47_1-390x510_1ea3a854d455410f948c98b2696f949d_medium.jpg'),
('DS011', 'The AI Edge - Khai Thác Thế Mạnh AI Trong Sales Và Marketing', 2025, 219000.00, 'TG011', 'TL001', 'NXB004', 'The AI Edge - Khai Thác Thế Mạnh AI Trong Sales Và Marketing\n\nTrí tuệ nhân tạo – AI đang có những bước phát triển mạnh mẽ và được ứng dụng ngày càng nhiều trong hầu hết các lĩnh vực từ giáo dục, y tế, tài chính đến sản xuất công nghiệp… Trong lĩnh vực Sale & Marketing, trí tuệ nhân tạo không chỉ nâng cấp quy trình bán hàng mà còn giúp phân tích dữ liệu khách hàng một cách hiệu quả. Từ đó, doanh nghiệp có thể cá nhân hóa trải nghiệm khách hàng và tối ưu hóa chiến dịch quảng cáo.\n\nThế giới bán hàng đang cạnh tranh ngày càng khốc liệt, việc tìm kiếm và tận dụng những lợi thế để vượt lên đối thủ cạnh tranh là vô cùng quan trọng. Hãy tận dụng sức mạnh của trí tuệ nhân tạo để tinh gọn quy trình bán hàng, tối ưu nội dung quảng cáo, giảm thiểu chi phí bán hàng, ra quyết định chiến lược hiệu quả… và The AI Edge: Khai thác thế mạnh AI trong Sales & Marketing sẽ là cuốn sách dẫn đường cho bạn trong hành trình này.\n\nTrong cuốn sách The AI Edge: Khai thác thế mạnh AI trong Sales & Marketing, 2 tác giả Jeb Blount và Anthony Iannarino, đã mở ra một tư duy mới kết hợp các chiến lược bán hàng độc đáo với sức mạnh biến đổi của AI.\n\nCác tác giả làm rõ cách khai thác trí tuệ nhân tạo và chứng minh tiềm năng của nó trong việc giúp bạn có thêm thời gian để phát huy những lợi thế chỉ thuộc về con người: năng lực sáng tạo, khả năng đồng cảm và sự chân thành. Đây chính là yếu tố then chốt khiến bạn trở thành nhân sự không thể thay thế trong kỷ nguyên công nghệ.\n\nVới The AI Edge: Khai thác thế mạnh AI trong Sales & Marketing, bạn có thể khám phá lộ trình để tích hợp AI vào chiến lược bán hàng, cụ thể:\n\n- Tinh gọn quy trình và tương tác hiệu quả: Tìm hiểu vai trò của AI trong việc tự động hóa các nhiệm vụ lặp đi lặp lại, giúp bạn được giải phóng để tập trung vào các khía cạnh độc quyền của con người như xây dựng mối quan hệ và khai phá năng lực sáng tạo.\n\n- Nắm được kỹ thuật thiết kế câu lệnh AI: Thực hành thiết kế câu lệnh phù hợp để tận dụng AI tạo sinh, từ đó đạt được kết quả tối ưu trong thời gian ngắn.\n\n- Xử lý dữ liệu thông minh: Sử dụng AI phân tích dữ liệu và ứng dụng vào các cuộc gặp gỡ với khách hàng, tăng cơ hội giao dịch thành công.\n\n- Tăng cường hiệu quả tìm kiếm khách hàng: Tận dụng sức mạnh của AI để xây dựng danh sách khách hàng tiềm năng chất lượng, mở ra nhiều cơ hội kinh doanh trong khi giảm thiểu nguy cơ bị từ chối.', 'the-ai-edge---bia-1_724ce444630e4d97b4a92074ffe8dea4_medium.jpg'),
('DS012', 'Inbox Marketing', 2022, 198000.00, 'TG012', 'TL001', 'NXB002', 'Tăng Khách Hàng Và\nChốt Sales Với Chatbot\nTiếp thị và bán hàng trò chuyện là một phần của phương pháp luận mới tập trung vào các cuộc trò chuyện trực tiếp với khách hàng trong thời gian thực thông qua chatbot và nhắn tin. \n\nBằng cách cho phép doanh nghiệp của bạn giao tiếp với khách hàng trong thời gian thực — khi thuận tiện nhất cho họ — tiếp thị trò chuyện cải thiện trải nghiệm của khách hàng, tạo ra nhiều khách hàng tiềm năng hơn và giúp bạn chuyển đổi nhiều khách hàng tiềm năng hơn thành khách hàng.\n\nCải Tiến Chiến Lược Bán Hàng\nCác ứng dụng nhắn tin hiện đại, cho phép các cuộc trò chuyện thời gian thực và phản hồi tức thì, đã thay đổi cách chúng ta tương tác trong cuộc sống cá nhân và nghề nghiệp của mình, tuy nhiên hầu hết các doanh nghiệp vẫn dựa vào công nghệ thế kỷ 20 để giao tiếp với khách hàng thế kỷ 21. Các biểu mẫu trực tuyến, các câu hỏi qua email và các cuộc gọi bán hàng tiếp theo không mang lại hiệu quả tức thì mà người tiêu dùng hiện đại mong đợi.', 'inbox_marketing_15afb629ea474cb5a9ad344b439c7308_22276044ff2840b9a6bbab9f0e24f20b_medium.jpg'),
('DS013', 'Thấy Trước Doanh Thu - Aaron Ross, Marylou Tyler', 2024, 250000.00, 'TG013', 'TL001', 'NXB002', 'Đây không phải một cuốn sách khác về cách có những cuộc gọi lạnh hay chốt giao dịch. Đây là toàn bộ kinh thư về bán hàng mới cho CEO những doanh nhân và Phó giám đốc bán hàng để hỗ trợ bạn xây dựng một cỗ máy bán hàng. Những gì có thể giúp đội ngũ bán hàng của bạn tạo ra nhiều khách hàng tiềm năng mứi siêu chất lượng,tạo ra doanh thu thấy trước,và đáp ứng mục tiêu tài chính mà không cần sự tập trung và chú ý liên tục của bạn? Thấy trước doanh thu trả lời?', 'thay_truoc_doanh_thu_6f4812d84de049068dfedc6405a76699_2244be3944224857a078d77494e3f9f9_medium.jpg'),
('DS014', 'Marketing Trực Tiếp - Dan S. Kennedy', 2019, 85000.00, 'TG014', 'TL001', 'NXB002', 'Cẩm Nang Bách Thắng - Marketing Trực Tiếp\n\nTrong bối cảnh hội nhập, sức ép cạnh tranh ngày càng gay gắt, các doanh nghiệp muốn tồn tại cần đặc biệt chú trọng đến các hoạt động Marketing. Marketing không chỉ là một chức năng trong hoạt động kinh doanh, Marketing còn là một triết lý dẫn dắt toàn bộ hoạt động của doanh nghiệp trong việc phát hiện ra, đáp ứng và làm thỏa mãn nhu cầu của khách hàng. Nhờ có các hoạt động Marketing, các doanh nghiệp hiểu hơn về người tiêu dùng, thỏa mãn nhu cầu và nguyện vọng của họ qua đó sẽ làm cho việc sản xuất kinh doanh đạt hiệu quả cao hơn.\n\nĐể các doanh nghiệp, cá nhân có được tài liệu tham khảo hữu ích liên quan đến lĩnh vực marketing và bán hàng, Nhà xuất bản Thế Giới cho phát hành cuốn sách:\n\nHãy khám phá những thủ thuật thu hút khách hàng, đẩy mạnh doanh thu mà bạn chưa từng biết đến cũng như phương pháp ứng dụng hiệu quả 10 quy tắc giúp chuyển đổi doanh nghiệp của bạn thành một doanh nghiệp marketing trực tiếp với hiệu quả cao hơn nhiều\n\nQuy tắc 1 . Luôn luôn có một hoặc nhiều chương trình khuyến mãi\n\nQuy tắc 2. Đưa ra lý do để khách hàng phản hồi ngay lập tức\n\nQuy tắc 3. Đưa ra chỉ dẫn cụ thể\n\nQuy tắc 4. Phải có phương thức theo dõi, đo lường và kiểm định hiệu quả\n\nQuy tắc 5. Xây dựng thương hiệu, nhưng không tốn tiền\n\nQuy tắc 6. Phải theo dõi và nhắc nhở\n\nQuy tắc 7. Nội dung phải thật ấn tượng\n\nQuy tắc 8. Hình thức giống quảng cáo qua thư\n\nQuy tắc 9. Kết quả là trên hết. Chấm Hết\n\nQuy Tắc 10. Bạn phải là một người quản lý cực kỳ cương quyết để rèn doanh nghiệp của mình theo đúng định hướng Marketing trực tiếp.', 'marketing_truc_tiep_0181e1af8c2d4d009ece017382dccb53_cbb0f857539f4305874b787103978ccd_medium.jpg'),
('DS015', 'Bán Hàng Cho Người Giàu - Dan S Kennedy', 2018, 79000.00, 'TG014', 'TL001', 'NXB002', 'Cẩm Nang Bách Thắng - Bán Hàng Cho Người Giàu (Tái Bản)\n\nSỰ THẬT ĐÁNG SỢ : Tầng lớp trung lưu- và những khoản chi tiêu của họ- đang biến mất với tốc độ cực kỳ nhanh chóng trên diện rộng. Khách hàng giờ đây mua hàng ít hơn và chi tiêu cho một số ít, các lĩnh vực tiêu dùng.\n\nCƠ HỘI DÀNH CHO BẠN : Giờ đây bạn không còn phải làm việc cật lực để lôi kéo khách hàng từ những tầng lớp khá giả, giàu có và siêu giàu vì nhóm khách hàng nàyhiện đang bùng nổ về số lượng và luôn luôn sẵn sàng chi trả mức giá cao cấp để có được để có được các dịch vụ đẳng cấp, những chuyên gia tên tuổi và những trải nghiệm độc đáo.\n\nTrong quyển sách này, triệu phú DAN Kennedy, cùng với những chuyên gia xây dựng thương hiệu là Nick Nanton, J.W. Dicks và đội ngũ của mình sẽ trình bày cho bạn phương pháp để tái định vị doanh nghiệp, cửa hàng hoặc sự nghiệp bán hàng của mình, giúp bạn thu hút những khách hàng không quan tâm đến mức giá khi mua hàng. Hãy đọc cách để bán hàng cho những người luôn luôn có tiền để cho trả.', 'ban_hang_cho_nguoi_giau_4ba2acb9b52742eea9151eac9a7231af_61bcd38d676d49b59c1a94c9e29f8eed_medium.jpg'),
('DS016', 'Bí Mật Traffic - Russell Brunson', 2023, 185000.00, 'TG015', 'TL001', 'NXB002', 'Bí mật - Traffic - Bìa Cứng\n\nVấn đề lớn nhất mà các doanh nhân gặp phải không phải là họ không có sản phẩm hay dịch vụ tuyệt vời; vấn đề đó là việc họ không khiến cho khách hàng tương lai của họ biết đến sự tồn tại sản phẩm dịch vụ của họ. Mỗi năm, có hàng nghìn công ty được thành lập và phá sản bởi vì các doanh nhân không hiểu được kỹ năng vô cùng thiết yếu: nghệ thuật và khoa học để khiến cho traffic (con người) tìm đến bạn.\n\nVà đó thực sự là một thảm kịch.\n\nBí Mật Traffic được viết ra để giúp bạn đưa thông điệp về sản phẩm và dịch vụ của bạn đến được với thế giới. Tôi có niềm tin mạnh mẽ rằng các doanh nhân là những người duy nhất trên thế giới này có thể thực sự thay đổi thế giới. Nhiệm vụ này không thuộc về các chính phủ và tôi cũng không tin rằng nó có thể được thực hiện bởi các trường học.\n\nĐiều này xảy ra bởi vì những doanh nhân như bạn, người có đủ sự điên rồ đủ lớn để xây dựng sản phẩm và dịch vụ có thể thay đổi thế giới. Nó sẽ xảy ra bởi vì chúng ta đủ điên rồ để mạo hiểm mọi thứ với mục đích biến ước mơ trở thành hiện thực.\n\nTôi muốn gửi đến tất cả các doanh nhân đã từng thất bại trong những năm đầu tiên khởi nghiệp, rằng đó là một bi kịch khi một người đã sẵn sàng mạo hiểm với mọi thứ họ có nhưng lại không bao giờ nhận lại được đầy đủ những gì họ xứng đáng.\n\nChờ đợi mọi người tìm đến bạn không phải là một chiến lược.\n\nHiểu được chính xác AI là khách hàng lý tưởng của bạn, khám phá xem họ đang Ở ĐÂU, và thả những MỖI CÂU sắc bén để bắt được sự quan tâm của họ rồi kéo họ vào hệ thống PHỄU của bạn (nơi bạn sẽ bắt đầu kể cho họ câu chuyện và đưa ra lời chào hàng) là một chiến lược. Đó là một Bí MẬT lớn.\n\nTraffic cũng là con người. Cuốn sách này sẽ giúp bạn tìm ra được NGƯỜI của bạn, sau đó bạn có thể tập trung thay đổi thế giới thông qua sản phẩm và dịch vụ mà bạn cung cấp.', 'bi_mat_-_traffic_-_bia_cung_f21cc7cf71894c5fa8a6a1aae4d1aca2_61c81c3e2fa347d0925a6d13ab8f8d4a_medium.jpg'),
('DS017', 'Phân Tích Dữ Liệu Marketing', 2022, 350000.00, 'TG016', 'TL001', 'NXB002', 'TEXTBOOK - PHÂN TÍCH DỮ LIỆU MARKETING\n\nChiến lược marketing hiệu quả: Tầm nhìn dựa trên dữ liệu\n\n \n\nĐể đạt được thành công, một doanh nghiệp cần có chiến lược marketing rõ ràng và quy trình phân tích dữ liệu hiệu quả. Là người đứng đầu doanh nghiệp, bạn phải nắm rõ lĩnh vực đầu tư, đồng thời dựa vào dữ liệu để xây dựng tầm nhìn và chiến lược phát triển. Đây là yếu tố then chốt giúp doanh nghiệp duy trì mối quan hệ với khách hàng và đảm bảo sự phát triển bền vững. Do đó, việc phân tích dữ liệu trong marketing không chỉ cần thiết mà còn đóng vai trò quan trọng trong việc định hình con đường kinh doanh và tối ưu hóa hiệu quả hoạt động.\n\n \n\nPhân tích dữ liệu trong marketing đóng vai trò then chốt trong suốt quá trình từ hoạch định chiến lược, triển khai đến đánh giá hiệu quả dự án. Thay vì chỉ dựa vào trực giác hay kinh nghiệm, doanh nghiệp có thể tận dụng dữ liệu để xây dựng chiến lược marketing tối ưu, từ việc thấu hiểu hành vi, sở thích và nhu cầu của khách hàng, đến việc chọn lựa kênh tiếp thị phù hợp, đo lường và tối ưu hóa hiệu suất chiến dịch.\n\n \n\nCách tiếp cận này không chỉ nâng cao khả năng thành công của các chiến dịch marketing mà còn tăng cường sức cạnh tranh trên thị trường, giúp doanh nghiệp nhanh chóng đạt được mục tiêu kinh doanh.\n\n \n\nTextbook Phân tích dữ liệu Marketing – Chìa khóa dẫn lối thành công cho chiến lược marketing\n\nCuốn sách Phân tích dữ liệu marketing là một tài liệu không thể thiếu cho bất kỳ ai muốn khám phá lĩnh vực phân tích marketing. Cuốn sách này khéo léo kết nối giữa lý thuyết và thực tiễn, cung cấp cho người đọc sự hiểu biết toàn diện về các khái niệm và công cụ cần thiết cho việc ra quyết định marketing hiệu quả.\n\n \n\nMột trong những điểm nổi bật của cuốn sách là cách giải thích các kỹ thuật phân tích phức tạp một cách đơn giản và dễ hiểu, giúp người đọc dễ tiếp cận ngay cả khi họ chưa biết nhiều về kỹ thuật phân tích dữ liệu. Mỗi chương đều có bổ sung các ví dụ thực tế và tình huống nghiên cứu để minh họa cách áp dụng các kỹ thuật phân tích trong các kịch bản kinh doanh khác nhau.\n\n \n\nNgoài ra, Textbook Phân tích dữ liệu Marketing còn cung cấp nhiều bài tập thực hành cùng bộ dữ liệu thực tế, giúp người đọc dễ dàng áp dụng kiến thức vừa học. Phương pháp tiếp cận này không chỉ củng cố các khái niệm lý thuyết mà còn rèn luyện kỹ năng phân tích, đồng thời nâng cao sự tự tin trong việc sử dụng các công cụ phân tích marketing vào các tình huống thực tế.\n\n \n\nMột số lời đánh giá của các chuyên gia về sách:\n\n“Cuốn sách “Phân tích dữ liệu marketing” còn đưa ra các phương pháp chi tiết nhất, giúp bạn nắm rõ những cách khai thác nguồn dữ liệu hiệu quả trong marketing để nâng cao chiến lược tiếp cận khách hàng. Cuốn sách này như một lời khẳng định rằng, phân tích dữ liệu là điều bắt buộc để các doanh nghiệp có thể sinh tồn và phát triển trong thời đại số.”\n\nÔng Lê Trí Thông – Tổng Giám đốc Công ty Cổ phần Vàng bạc Đá quý Phú Nhuận (PNJ) nhận xét về sách.\n\n \n\n“Đối với bất kỳ ai nghiêm túc muốn nắm vững phân tích marketing, cuốn sách này là một công cụ quan trọng để đưa ra các quyết định thông minh và thúc đẩy hoạt động kinh doanh thông qua việc sử dụng phân tích hiệu quả.”\n\nÔng Nguyễn Phúc Trọng – Giám đốc Phân tích tại Tiki & Đồng sáng lập Trường Công\n\nnghệ Animum', 'ph_n_t_ch_d_li_u_marketing_-_b_a_1_5fcbefdf10ac4cddbd395acd581ba96c_medium.jpg'),
('DS018', 'Trò Chơi Tâm Lý Trong Marketing', 2021, 110000.00, 'TG017', 'TL001', 'NXB004', 'Trò Chơi Tâm Lý Trong Marketing\n\nChúng ta có bị ảnh hưởng bởi quảng cáo kể cả khi nhanh chóng lướt qua chúng? Các thương hiệu có mở rộng những cá tính của ta không? Vì sao chúng ta chi tiêu nhiều hơn khi trả bằng thẻ tín dụng?\n\nTrong thời kỳ hiện đại, việc mua và sở hữu của cải vật chất, cùng với sự phát triển của ngành dịch vụ khác nhau, đã trở thành trung tâm trong nhận thức của con người về sự tồn tại, bên cạnh gia đình và sự nghiệp.\n\nCuốn sách TRÒ CHƠI TÂM LÝ TRONG MARKETING xem xét tác động của tâm lý học đến thực tiễn marketing và nghiên cứu marketing, làm nổi bật những khía cạnh đã được ứng dụng của nghiên cứu tâm lý học vào thị trường.\n\nMỗi chương bàn đến một lĩnh vực chủ đề quan trọng trong tâm lý học, liệt kê những lý thuyết chính, và giới thiệu nhiều ứng dụng thực tiễn của nghiên cứu. Những khái niệm chính được nhấn mạnh và những « case study » liên quan đến tài liệu được nêu ở cuối mỗi chương.\n\n- Những lĩnh vực được xét đến gồm có:\n\n● Động lực: Những nhu cầu của con người là nguồn gốc nhiều hành vi của người tiêu dùng và những quyết định trong marketing.\n\n● Nhận thức: Bản chất của sự lựa chọn, sự chú ý và sự sắp xếp mang tính nhận thức và mối quan hệ của chúng đối với lĩnh vực marketing đang ngày càng phát triển.\n\n● Ra quyết định: Bằng cách nào và trong những tình huống nào mà ta có thể thể dự đoán lựa chọn, thái độ của người tiêu dùng và cách thuyết phục họ.\n\n● Cá tính và phong cách sống: Cách vận dụng những kiến thức sâu sắc về cá tính của người tiêu dùng để tạo ra các kế hoạch marketing.\n\n● Hành vi xã hội: Vai trò to lớn của sự ảnh hưởng của xã hội với hoạt động tiêu dùng.\n\nCuốn sách này sẽ có được sự quan tâm lớn từ nhiều đối tượng độc giả như các nhà nghiên cứu, các sinh viên và những chuyên gia, và sẽ là tài liệu bắt buộc cho những khóa học về marketing, tâm lý học, hành vi tiêu dùng và quảng cáo.', 'tro-choi-tam-ly-trong-marketing_e4254fea668e4f86aaac77269595bee5_medium.jpg'),
('DS019', 'Nghệ Thuật Bán Hàng Của Người Hướng Nội - Trở Thành Số 1 Bán Hàng Khi Là Người Nhút Nhát', 2020, 95000.00, 'TG019', 'TL001', 'NXB005', 'Nghệ Thuật Bán Hàng Của Người Hướng Nội - Trở Thành Số 1 Bán Hàng Khi Là Người Nhút Nhát\n\nQuy trình bán hàng gồm 7 bước giúp tận dụng các ưu điểm bẩm sinh của người hướng nội để bán hàng thành công và vượt cả những người hướng ngoại.\n\nCuốn sách này sẽ chỉ cho các bạn cách:\n\nTìm được sự tự tin có sẵn trong bạn\n\nChuẩn bị cho mọi tình huống\n\nThể hiện giá trị mà khách hàng mong muốn\n\nTránh được sự từ chối\n\nPhán đoán thời điểm khách hàng sẵn sàng mua hàng\n\nVà nhiều điều nữa trong đó không thể thiếu việc tận hưởng thành quả bán hàng của bạn!', '8935235243231_7195ea3c5e9f4c548f374831c9035533_medium.jpg'),
('DS020', 'GAM7 BOOK SPECIAL 2020 - Marketing Thời Bình Thường Mới - Sẵn Sàng Chuyển Dịch Để Vươn Lên', 2019, 165000.00, 'TG012', 'TL001', 'NXB005', 'GAM7 Special 2020 lấy bối cảnh của đại dịch COVID-19, tập trung phân tích giải pháp marketing nhằm giúp doanh nghiệp đi lên. Ở chủ đề đặc biệt này, hoạt động marketing được gắn trực tiếp với sự dịch chuyển của khách hàng và thị trường ở thời bình thường mới với những CÁI NHẤT không thể bỏ qua:\n\n▶ SỐ GAM7 ĐẶC BIỆT NHẤT với duy nhất 01 ấn phẩm được xuất bản trong năm\n\n▶ Ấn phẩm với sự tham gia của NHIỀU CỐ VẤN CHUYÊN MÔN NHẤT từ trước tới nay\n\n▶ Số lượng TRANG NỘI DUNG VƯỢT TRỘI NHẤT với 160 trang thay vì 124 trang như các số trước\n\n▶ Chủ đề mang tính ảnh hưởng TOÀN CẦU với đa dạng góc nhìn thực tiễn cho doanh nghiệp tại Việt Nam\n\nBên cạnh đề cập trực tiếp sự thay đổi của các hoạt động marketing, các bài viết mở rộng khai thác tới các yếu tố cốt lõi tạo ra những định hướng phát triển mới trong doanh nghiệp, như sản phẩm, mô hình kinh doanh, bộ máy doanh nghiệp,... với 03 theme nội dung:\n\n▶THEME 1: BỨC TRANH TOÀN CẢNH - Cho thấy động thái của các doanh nghiệp; diễn biến tâm lý; và phản ứng của khách hàng qua từng giai đoạn của đại dịch.\n\n▶THEME 2: ỨNG BIẾN LINH HOẠT - Là hàng loạt giải pháp tức thời nhưng cương quyết đã được đưa ra để phản ứng nhanh với đại dịch.\n\n▶THEME 3: SẴN SÀNG CHO TƯƠNG LAI - Sẵn sàng cho tương lai gần, khi đại dịch kết thúc, và cả tương lai rất xa.', 'image_195509_1_45100_1_f305e36ac4bd44c68cd62c7c6daacba2_medium.jpg'),
('DS021', 'Conversion Hacking - Gia Tăng Tỷ Lệ Chốt Đơn Hàng', 2024, 140000.00, 'TG020', 'TL001', 'NXB005', 'Conversion Hacking - Gia Tăng Tỷ Lệ Chốt Đơn Hàng\n\nĐối mặt với KPI doanh thu mỗi ngày, những người làm việc trực tiếp với Digital Marketing luôn đau đầu với những câu hỏi: Làm thế nào để gia tăng lượng khách hàng thanh toán? Cách giảm chi phí chạy quảng cáo? Tối ưu lợi nhuận trên đơn hàng như thế nào?.. \n\nHiểu được điều này, RIO Book cùng tác giả Bình Nguyễn - Founder/CEO LadiPage và Việt Anh Nori - Founder ECOMME xuất bản cuốn sách Conversion Hacking - Gia tăng tỷ lệ chốt đơn hàng giúp bạn:\n\n - Chốt được nhiều đơn hàng thành công\n\n- Trả lời câu hỏi vì sao khách hàng không mua hàng của bạn\n\n- Tìm kiếm và gia tăng đúng lượng khách hàng sẽ mua sản phẩm\n\n- Gắn kết với khách hàng cũ khiến họ sẵn sàng quay trở lại\n\n- Giảm chi phí, tối ưu hoá doanh thu và gia tăng lợi nhuận trực tiếp\n\n- Mở rộng tư duy đa kênh và vận hành các kênh Digital Marketing\n\n- Thực hành lên kế hoạch Digital Marketing Plan với ấn phẩm tặng kèm đặc biệt\n\nĐừng bỏ lỡ Conversion Hacking - Bí kíp gia tăng tỷ lệ chốt đơn hàng của bạn.', 'untitled__8__307f3c28ba554e09b11107a5c1f716e1_medium.jpg'),
('DS022', 'Vua Gia Long', 2024, 159000.00, 'TG021', 'TL002', 'NXB002', 'Cuốn sách là công trình biên khảo bằng tiếng Pháp của Marcel Gaultier được xuất bản lần đầu tại Sài Gòn vào năm 1933. \nMarcel Gaultier (1900-1960) là nhà văn đồng thời là biên tập viên cho Ban Dân sự của Đông Dương. Ông để lại cho đời hơn mười tác phẩm, trong đó có ba tiểu thuyết, còn lại là hồi ký và những nghiên cứu sử học về các vị vua triều Nguyễn: Gia Long, Minh Mạng và Hàm Nghi. Trong số đó, Gia-Long (Vua Gia Long, 1933) là tác phẩm đầu tay về nghiên cứu sử học với đề tựa của Pierre Pasquier, Toàn quyền Đông Dương. \n\nĐây là công trình viết về Gia Long - Nguyễn Ánh, vị vua đầu tiên cũng là người mở ra vương triều Nguyễn, triều đại phong kiến cuối cùng của Việt Nam, cũng đồng thời là một nhân vật tạo nên nhiều tranh luận, đánh giá. \n\nTrong tác phẩm nghiên cứu đầu tay Gia-Long, Marcel Gaultier mong muốn trình bày toàn cảnh lịch sử Việt Nam từ thời lập quốc mãi cho đến năm 1802 - thời điểm Việt Nam được thống nhất sau bao nhiêu năm chia rẽ, chiến tranh, sau đó là giai đoạn xây dựng và hàn gắn đất nước trong truyền thống Á châu, khép lại với những quan hệ phương Tây vốn đã hình thành từ nhiều năm trước đó. \n\nTác phẩm cho chúng ta những thông tin về những sự kiện lịch sử trong nước và nhất là ngoài nước vào những thế kỷ XVII, XVIII hoặc XIX. Độc giả trong nước hiểu được cái nhìn từ bên ngoài về tình hình Việt Nam, những nỗ lực tiếp cận và những nhận định không hẳn chính xác nhưng đã góp phần làm nền tảng tư tưởng cho những cuộc can thiệp về sau của phương Tây trên nhiều nước châu Á, trong đó có Việt Nam. ', 'p92245mgia_long_43ef89bb604545f2bb6c89a8198a6cc0_large_f5e9f038c9e1400c922ba4c5f165fb12_medium.jpg'),
('DS023', 'Ngụy Võ Đế Tào Tháo', 2022, 468000.00, 'TG022', 'TL002', 'NXB006', 'Ngụy Võ Đế - Tào Tháo (Tái bản 2022)\n\nTrong suốt chiều dài lịch sử Trung Quốc, có lẽ không có nhân vật nào phức tạp, đa dạng và gây tranh cãi nhiều như Ngụy Võ Đế Tào Tháo.\n\nNhiều người cho rằng, Tào Tháo là một bậc anh hùng đã dẹp yên cục diện cát cứ kéo dài, thống nhất Trung Nguyên, nhưng do ảnh hưởng sâu đậm của bộ tiểu thuyết trứ danh Tam Quốc Chí, nhiều người chỉ thấy ở nhân vật này một tâm địa nham hiểm, trí trá, tàn bạo, với mưu đồ tranh đoạt quyền lực…\n\nTác giả Hàn Chung Lượng đã khắc họa công phu cuộc đời của nhân vật kiệt xuất này, khởi đầu từ gia thế, bối cảnh lịch sử, các mưu thần, võ tướng phò tá, các đối thủ, đồng minh, cho đến những bóng hồng tài sắc đã góp phần tạo nên gương mặt lịch sử lừng lẫy này.\n\nBên cạnh những dữ kiện lịch sử, tác giả còn vận dụng nhiều kiến thức văn hóa để lý giải vai trò của Tào Tháo, một nhà chính trị lỗi lạc, nhà quân sự tài ba và thi nhân xuất sắc trong lịch sử Trung Quốc.', '2023_04_07_16_24_56_1-390x510_2910246c38494fe69a18f389533e74ef_medium.jpg'),
('DS024', 'Tập Văn Họa Kỷ Niệm Nguyễn Du (Bản giấy dó)', 2020, 1000000.00, 'TG016', 'TL002', 'NXB002', 'Giới thiệu sách Tập Văn Họa Kỷ Niệm Nguyễn Du (Bản giấy dó)\nMột tuyển tập những dòng ngâm, bình, vịnh hay nhất về truyện Kiều và nàng Kiều\n11 bức tranh của 11 họa sĩ Đại học Đông Dương tên tuổi nhất nửa đầu thế kỷ XX\nTẬP VĂN HOẠ KỶ NIỆM NGUYỄN DU được xuất bản lần đầu tiên tại Hà Nội vào năm 1942 đã gây một tiếng vang lớn trong cả diễn đàn phê bình văn học và hội họa thời điểm bấy giờ. Điều này không chỉ bởi những giá trị quan trọng của Truyện Kiều trong lịch sử ngôn ngữ, văn hóa của dân tộc mà nó còn gắn với lịch sử phát triển của nghệ thuật minh họa và in khắc sách tại Việt Nam. Đây là tác phẩm duy nhất, tính đến thời điểm hiện tại có thể hội tụ được đầy đủ mười một họa sĩ tên tuổi nhất của Việt Nam trong nửa đầu thế kỷ XX như Nguyễn Đô Cung, Tô Ngọc Vân, Nguyễn Gia Trí, Trần Văn Cần, Lê Văn Đệ, Tôn Thất Đào, Nguyễn Tường Lân, Lương Xuân Nhị, Lưu Văn Sin, Pham Hậu và Nguyễn Văn Tỵ.\n\nVới mong muốn là cùng bạn đọc cảm thụ và chiêm ngưỡng lại những giá trị nhân văn, mỹ thuật mà những danh nhân, họa sĩ tài danh đã cống hiến cho dân tộc, MaiHaBooks kết hợp cùng NXB Thế Giới trân trọng tái bản lần đầu Tập văn họa Kỷ niệm Nguyễn Du vào đúng dịp tưởng nhớ 200 năm ngày mất của đại thi hào dân tộc (1820 – 2020).', 'tap-van-hoa-ky-niem-nguyen-du_d84377dcb9994eb3bd1d5c778b1f59d1_medium.jpg'),
('DS025', 'Mùa Gặt Mới - Số 2', 2021, 2500000.00, 'TG012', 'TL002', 'NXB007', 'MANG NGUỒN SỐNG MỚI TỪ THẾ KỶ CŨ\n\nĐã từ lâu, phong vị tết với việc đất nước đang trong quá trình hòa nhập và phát triển khiến cho sự thuần khiết đang dần phai nhạt. Hiểu được rằng đang nắm giữ trong tay những tư liệu quý – thứ lưu trữ trong đó là cái sức sống khi Tết đến một cách rất Thuần Việt, chúng tôi quyết định ra mắt bộ 2 cuốn Mùa Gặt Mới.\n\nXuân Mới – Mùa Gặt Mới, những suy nghĩ đầy giản dị mà cũng rất quen thuộc của người dân nước Việt, và từ những bình dị ấy mà tạo thành cái bản sắc riêng biệt. Cái “nguồn sống” chúng tôi muốn mang đến cũng vậy, đơn giản là phong vị Việt Nam, và chứa trong đó là những dòng tản văn của của những cây bút gạo cội như: Nguyễn Tuân, Thế Hưng, Nguyễn Bính, Đinh Hùng,… với sự đa dạng sắc màu cuộc sống pha trộn cùng cái cảm xúc Tết – cái cảm xúc đầy hỗn tạp của thời đại – bởi chứa trong đó là háo hức, là lo âu, là….hỗn độn.\n\nĐây là lần đầu tiên tái bản của cuốn Mùa Gặt Mới sau nhiều năm “biến mất”, và để nâng niu cái quý của nội dung, cái hiếm của số lượng, cái thuần Việt của cuốn sách, Trường Phương cũng để những dòng chữ ấy nằm trong chất liệu giấy dó thủ công – thứ giấy đặc trưng mang bản sắc của người Việt.\n\nHơn nữa 2 cuốn sách được đặt trong một hộp sơn mài truyền thống, thếp lên chiếc hộp đấy là chất liệu bạc để tôn vinh tính dân tộc, hay cũng là để tăng thêm tính giá trị cho 2 cuốn sách bởi: “Được Bạc Thì Sang”. Ở trong hộp chứa trong là hai tập Mùa Gặt Mới là hai bức tranh đầy giản dị mà giàu ý nghĩa, đi kèm hai chữ “Xuân Về” – “Lại Sống” như cho thấy cái bản sắc dân tộc cũng như phong vị tết vẫn đang tuần hoàn cũng như ngày một trỗi dậy và phát triển, và cái “chất riêng” của người Việt ấy sẽ luôn được người Việt gìn giữ, để từ đó cho thấy rằng chúng ta Hòa Nhập nhưng không Hòa Tan. Phong Vị Tết – Hồn Dân Tộc sẽ luôn được bảo tồn và phát huy.\n\nBên cạnh đấy, để giúp độc giả dễ mường tượng ra được cái “nguồn sống”, chúng tôi gửi kèm cuốn sách là những bức tranh phụ bản.', '9786043396744_4ab3680cc463407988c03decbb3b22d1_medium.jpg'),
('DS026', 'Mùa Gặt Mới - Số 1', 2020, 2500000.00, 'TG012', 'TL002', 'NXB007', NULL, 'so_1_536e2e0ae3d54fe8a89e560cdeb5bf2e_medium.jpg'),
('DS027', 'Đường Vào Tình Sử (Bản Đặc Biệt)', 2022, 800000.00, 'TG023', 'TL002', 'NXB008', 'Sinh (1920-1967) là một nhà thơ Việt Nam thời tiền chiến. Ngoài việc ký tên thật Đinh Hùng, ông còn dùng bút hiệu Thần Đăng khi làm thơ châm biếm và Hoài Điệp Thứ Lang khi viết tiểu thuyết. Tên tuổi của Đinh Hùng đương thời ông phần nào được kiểm chứng bởi thực tế ông là một trong số ít văn nghệ sĩ có mặt trong cuốn \"Mười khuôn mặt văn nghệ\" (xuất bản lần thứ nhất năm 1970) của Tạ Tỵ, bên cạnh một số tên tuổi lẫy lừng của văn học nghệ thuật Việt Nam trong thế kỷ 20 như Văn Cao, Nguyễn Tuân, Nguyễn Bính, hay Vũ Hoàng Chương.', 'duong-vao-tinh-su-ban-dac-biet_d96996649f134168b55595ad20ecf33c_medium.jpg'),
('DS028', 'Nghệ Thuật Trang Trí Bắc Kỳ - Bìa giả da', 2019, 550000.00, 'TG012', 'TL002', 'NXB008', 'Nghệ Thuật luôn là một thứ vô tận, và trong hàng vạn những khía cạnh, chủ đề nghệ thuật, thì Nghệ Thuật Trang Trí cũng là một dạng đặc biệt.\nỞ Bắc Kỳ, trong lịch sử đã có những dấu ấn riêng biệt trong cách trang trí, và đây cũng là một khía cạnh được rất nhiều người quan tâm cũng như khảo cứu.\nBởi vậy, với những tài liệu từ chính những người thợ thủ công cung cấp, và kèm đó là sự quan sát một cách miệt mài từ lịch sử cho đến giai đoạn đương thời của những người viết sách, thêm vào đấy là những thông tin mang tính huyền thoại và lịch sử, liên quan đến từng lĩnh vực nghệ thuật của Bắc Kỳ của ông Hoàng Trọng Phu, Tổng đốc Hà Đông - con của nguyên Nhiếp chính vương, một người yêu nghệ thuật khai sáng, người bảo vệ các giá trị truyền thống của nghệ thuật ở Bắc Kỳ, thì cuốn Nghệ Thuật Trang Trí Bắc Kỳ ra đời như một lời giới thiệu đến những người yêu nghệ thuật Viễn Đông, đồng thời là một lời tri ân đối với các nghệ nhân ở Bắc Kỳ', 'nghe-thuat-trang-tri-bac-ky-bia-gia-da_e52eec11fbae4456bdd6abb56035c50a_medium.png'),
('DS029', 'Hệ Thống Cơ Quan Giám Sát Triều Nguyễn (1802 - 1885) Từ Thiết Chế, Định Chế Đến Thực Tiễn (Bìa Cứng)', 2013, 235000.00, 'TG012', 'TL002', 'NXB008', 'Sau nhiều biến cố thăng trầm, đến năm 1802 Nguyễn Ánh giành được chiến thắng trước nhà Tây Sơn lập ra vương triều Nguyễn và mở đầu giai đoạn trị và kéo dài 143 năm của triều đại quân chủ cuối cùng ở Việt Nam. Trong thời gian trị vì, 4 vị vua đầu của triều Nguyễn, từ Gia Long, Minh Mạng đến Thiệu Trị, Tự Đức đã dày công xây dựng bộ máy nhà nước quân chủ tập quyền vững mạnh. Kết quả, so với các triều đại quân chủ Việt Nam trước đó, trong hơn 8 thập niên đầu, triều Nguyễn đã xây dựng được bộ máy nhà nước khá hoàn chỉnh từ trung ương đến địa phương. Bộ máy nhà nước của triều Nguyễn được xây dựng trên cơ sở kế thừa có chọn lọc các bộ máy nhà nước quân chủ Việt Nam trước đó và nhà Thanh (Trung Quốc) đương thời. Trải qua quá trình hoạt động, bộ máy nhà nước của triều Nguyễn thời kỳ độc lập tự chủ (1802-1885) đã có những đóng góp nhất định. Một trong những nguyên nhân góp phần giúp cho triều Nguyễn có được những đóng góp trên đó là triều đại này đã xây dựng, vận hành một hệ thống cơ quan giám sát khá hoàn chỉnh và hiệu quả.\n\nTrong quá trình ra đời, tồn tại và phát triển, các cơ quan giám sát của triều Nguyễn như: Viện Đô sát, Lục khoa và Giám sát ngư sử của 16 đạo đã có những đóng góp lớn, góp phần làm trong sạch bộ máy nhà nước từ trung ương đến địa phương cũng như ổn định xã hội và phần nào đảm bảo quyền, lợi ích của dân chúng.', '2__55761_1df171585bab4c91b57ab15dd8c74c14_medium.jpg'),
('DS030', 'HOẠT ĐỘNG CHẾ TẠO VÀ QUẢN LÝ SỬ DỤNG VŨ KHÍ DƯỚI TRIỀU NGUYỄN GIAI ĐOẠN 1802-1883 ( Bìa Cứng)', 2020, 215000.00, 'TG012', 'TL002', 'NXB008', 'Triều Nguyễn là vương triều cuối cùng của chế độ quân chủ Việt Nam gắn liền với nhiều biến cố quan trọng của lịch sử dân tộc. Trong 143 năm tồn tại (1802-1945), vương triều Nguyễn cũng đã có nhiều đóng góp quan trọng đối với sự phát triển của đất nước, đặc biệt là giai đoạn trị vì của Gia Long, Minh Mệnh, Thiệu Trị và Tự Đức từ năm 1802 đến năm 1883.\n\nTrong giai đoạn trị vì của các vị vua kể trên, một trong những điều nổi bật làm được là thống nhất cũng như khẳng định được đất nước cũng như có nhiều chính sách phát triển văn hóa của đất nước.\n\nGiai đoạn lịch sử này cũng ghi nhận những thách thức to lớn đặt ra đối với vương triều Nguyễn. Trong đó, việc đối diện với nguy cơ bị xâm lược bởi thực dân phương Tây là thách thức lớn nhất. Đứng trước mối đe dọa đó, các hoàng đế triều Nguyễn đều có ý thức bảo vệ quốc gia và nỗ lực 10 tìm kiếm đường hướng giải quyết với mong muốn ngăn chặn, đẩy lùi nguy cơ xâm lược từ thực dân phương Tây cũng như giữ vững trật tự xã hội. Nhưng cuối cùng những nỗ lực trên chưa đạt được hiệu quả như kì vọng. Điều đó đã khiến cho Việt Nam bị rơi vào tay thực dân Pháp vào cuối thế kỉ XIX.', 'hoat-dong-che-tao-bc_a8b829a964894bca97804591c307433b_medium.png'),
('DS031', 'Chính Sách Tôn Giáo Thời Tự Đức (1848-1883)- Bìa cứng', 2020, 235000.00, 'TG012', 'TL002', 'NXB008', 'Chính Sách Tôn Giáo Thời Tự Đức (1848-1883)\n\nChính sách tôn giáo dưới triều Nguyễn, đặc biệt dưới thời Tự Đức là giai đoạn để lại những dấu ấn sâu sắc, có vị trí quan trọng trong chính sách đối với tôn giáo thời phong kiến ở Việt Nam. Có thể nói, triều Nguyễn thực sự làm chủ và hoàn thiện chế độ trong khoảng bốn triều vua đầu. Những đường hướng chính của chính sách quản lý xã hội nói chung, chính sách tôn giáo nói riêng của triều Nguyễn đã cơ bản được hình thành và phát triển ở giai đoạn này.\n\nThời Tự Đức là tâm điểm đáng chú ý nhất khi nghiên cứu về chính sách tôn giáo triều Nguyễn, đây là giai đoạn hết sức phức tạp, triều đình phải đối phó với thực dân phương Tây cũng như tôn giáo do họ mang tới. Nghiên cứu chính sách tôn giáo dưới thời Tự Đức sẽ góp phần làm sáng tỏ câu hỏi dưới triều vua này đã giải quyết vấn đề tôn giáo như thế nào, đâu là những cố gắng cần ghi nhận và nguyên nhân nào dẫn đến những thất bại trong chính sách tôn giáo, những hệ quả xã hội và những bài học kinh nghiệm cần rút ra.\n\nNhững năm gần đây, hoạt động tôn giáo, tín ngưỡng trở nên đa dạng, phong phú, thu hút đông đảo quần chúng tham gia. Tuy nhiên, vấn đề tôn giáo ở nước ta vẫn tiềm ẩn những yếu tố phức tạp, có lúc và có nơi trở thành điểm nóng. Thực tiễn sôi động đó đòi hỏi nhận thức về tôn giáo phải luôn đổi mới cho phù hợp với thời đại. Vì vậy, việc nhìn nhận và đánh giá lại những tác động và ảnh hưởng của chính sách tôn giáo thời Tự Đức dưới cái nhìn đổi mới để hiểu được một phần lịch sử của chính sách tôn giáo, những kinh nghiệm và bài học từ chính sách đó đối với cuộc sống hôm nay là một việc làm cần thiết.\n\nTừ đó, cuốn “CHÍNH SÁCH TÔN GIÁO THỜI TỰ ĐỨC (1848-1883)” ra đời như là một sự bổ sung cần thiết cho việc bổ sung, cải cách những chính sách tôn giáo để áp dụng với đương thời. Hiện cuốn sách đã có mặt tại cửa hàng, hãy nhanh tay liên hệ để sở hữu nhé.\n\n ', 'chinh-sach-ton-giao-thoi-tu-duc_fe0a204cc6f4404c95f55e054b4e0131_medium.png');
INSERT INTO `dausach` (`madausach`, `tensach`, `namxuatban`, `dongia`, `matacgia`, `matheloai`, `manxb`, `mota`, `anhbia`) VALUES
('DS032', 'Lược Khảo Binh Chế Việt Nam Qua Các Thời Đại', 2022, 215000.00, 'TG012', 'TL002', 'NXB008', 'Cuốn Lược khảo binh chế Việt Nam qua các thời đại được cụ Tiên Đàm Nguyễn Tường Phượng biên soạn từ trước năm 1945 từ việc khảo cứu các tư liệu cổ và từ lời kể của những võ quan triều Nguyễn. Trong cuốn sách là những tư liệu quý nghiên cứu về chế độ quân sự, vũ khí, sơ đồ chiến thuật (trận đồ, trận pháp), chế độ thi tuyển võ quan trong lịch sử quân sự Việt Nam. Bên cạnh đó, Tiên Đàm còn khảo cứu một số trận đánh nổi tiếng trong lịch sử Việt Nam. Đây là tài liệu thực sự có giá trị khi nghiên cứu nhiều mặt: lịch sử, văn hóa, pháp luật, giáo dục, thể dục thể\n\nCuốn Lược khảo binh chế Việt Nam qua các thời đại là cuốn sách có số phận đặc biệt. Sách vốn được Tiên Đàm Nguyễn Tường Phượng cho ấn hành lần đầu vào năm 1946. Nhưng chưa kịp phát hành thì Toàn quốc kháng chiến diễn ra, sách bị thiêu hủy trong cơn binh lửa. Tiên Đàm tản cư về Thái Bình cũng không còn bản thảo. Năm 1950, khi hồi cư về Hà Nội, Tiên Đàm may mắn tìm lại được một bản thảo do ông chủ hiệu sách Thăng Long lưu giữ. Sách được in lại nhà xuất bản Ngày Mai và lần đầu tiên đến tay bạn đọc (nhưng là lần in thứ hai).\n\nSau một thời gian dài, nhờ sự cố gắng sưu tầm tư liệu và biên tập, chúng tôi cho in lại lần thứ ba cuốn Lược khảo binh chế Việt Nam qua các thời đại. 70 năm sau, một số địa danh trong bản in năm 1950 đã thay đổi. Nhiều kết quả nghiên cứu mới về lịch sử binh chế Việt Nam được công bố. Tuy nhiên, để tôn trọng tính lịch sử của văn bản, chúng tôi vẫn giữ nguyên những kết quả nghiên cứu và đánh giá của ông. Chúng tôi chỉ biên tập lại những lỗi chính tả và chú thích những địa danh mới cho phù hợp với hiện tại.', '2ae559fe8bda3405f49a877ba968afe0_12797ac2aa494822858411c3d07c4dc4_medium.jpg'),
('DS033', 'Chữ, Văn Quốc Ngữ - Bìa Cứng', 2020, 175000.00, 'TG012', 'TL002', 'NXB008', 'Chúng ta đều thống nhất rằng chữ Quốc Ngữ là thứ chữ mà các giáo sĩ phương Tây cùng rất nhiều người Việt dùng chữ Latinh để ghi âm cách phát âm của người Việt. Ban đầu, chữ Quốc Ngữ chỉ nằm trong mục đích truyền giáo, nhưng sau này, chữ Quốc Ngữ được người Việt chấp nhận.\n\nTừ đó, đóng góp và ảnh hưởng của chữ Quốc Ngữ với xã hội đã là thứ đồng hành không thể tách rời với lịch sử. Với điều đó, hiểu được tầm quan trọng, nhiều nhà nghiên cứu đã nghiên cứu, đề cập về lịch sử hình thành và truyền bá.', '2022_06_21_14_40_50_1-390x510__00541_image2_800_big_5afc0b671ecb45ae9fd62a37a70b2a7f_medium.jpg'),
('DS034', 'Binh Pháp Tinh Hoa - Bìa Cứng', 2018, 175000.00, 'TG012', 'TL002', 'NXB008', 'Luận giải 13 Thiên Binh Pháp Tôn Võ Tử - Đối chiếu các nguyên lý hành binh và các trận đánh lớn của lịch sử Đông - Tây hiện đại và cận đại.\n\nBinh Pháp Tinh Hoa là cuốn sách có giá trị ứng dụng rộng rãi không chỉ trong quân đội, kinh doanh, thể thao mà còn trong nhiều lĩnh vực khác của cuộc sống. Nó phù hợp cho những người đòi hỏi phải có kỹ năng hoạch định chiến lược, quản lý và lãnh đạo ở khắp nơi trên thế giới. Thậm chí những chỉ dẫn khôn ngoan trong việc hẹn hò hay trong các mối quan hệ cũng được đúc kết trong cuốn sách binh pháp cổ của Tôn Tử.', 'binh---phap---tinh---hoa---bia---cung_d7154f688f5541078d55be40136ea7cd_medium.png'),
('DS035', 'Việt Kiều Tại Các Xứ Lân Bang - Bìa Cứng', 2015, 299000.00, 'TG012', 'TL002', 'NXB008', 'Số người Việt trên đã sang lập nghiệp tại các lân bang khi nào, cuộc sống của họ ra sao, được tổ chức như thế nào? Có lẽ từ trước đến nay, ít ai kể cả chính quyền thật sự quan tâm tới số phận cũng như sứ mạng của các Việt kiều ấy. Thật là bất công và thiệt thòi lớn lao cho các Việt kiều ấy nói riêng và cho thế lực của quốc gia ta nói chung. Tương lai thế đứng của Việt Nam về mọi phương diện tại các lân bang sẽ một phần lớn trông cậy vào sự đóng góp của các Việt kiều.\n\nViệt Kiều Tại Các Xứ Lân Bang được khảo cứu dưới khía cạnh địa lý, nhân văn hơn là sử học', '3b16440891b2549a78869c6ae9ae27a5_5cefe110925d449bb868b9b9cd8b53bc_medium.jpg'),
('DS036', 'Yên Bái Đêm Đỏ Lửa _ Bìa Cứng', 2012, 319000.00, 'TG012', 'TL002', 'NXB008', 'Đêm 9 rạng ngày 10/2/1930, Khởi nghĩa Yên Bái bùng nổ do Việt Nam Quốc dân đảng lãnh đạo. Cuộc khởi nghĩa không chỉ đánh thức lòng yêu nước của toàn dân tộc mà còn làm rung chuyển nước Phá Cuộc khởi nghĩa này đã được một sĩ quan quân đội Pháp ghi chép lại qua cuốn sách: YÊN BÁI ĐÊM ĐỎ LỬA (La nuit rouge de Yen Bai)\n\nKhởi nghĩa Yên Bái là một cuộc nổi dậy bằng vũ trang bùng phát tại Yên Bái do Việt Nam Quốc dân Đảng tổ chức và lãnh đạo nhằm đánh chiếm một số tỉnh và thành phố trọng yếu của Bắc Kì vào đêm mùng 9, rạng ngày 10 tháng 2 năm 1930. Dù nhanh chóng bị thất bại do nhiều nguyên nhân cả chủ quan và khách quan nhưng cuộc khởi nghĩa Yên Bái đã góp phần cổ vũ lòng yêu nước, ý chí căm thù của dân tộc ta đối với bè lũ cướp nước và tay sai.\n\nCuốn sách YÊN BÁI ĐÊM ĐỎ LỬA (La nuit rouge de Yen Bai) của tác giả có bút danh Bốn Mắt, một sĩ quan quân đội Pháp, đã phân tích nguyên nhân dẫn đến vụ bạo động này. Ông đề cập chủ yếu đến những nguyên nhân chủ quan từ phía quân đội Pháp và trách nhiệm của nhà cầm quyền Đông Dương thời đó. Với cái nhìn sắc sảo, khách quan, với đầu óc quan sát tỉ mỉ, cụ thể, cộng với sự phân tích mang tính khoa học của một nhà quân sự, tác giả đã đưa ra những nhận định tổng quan về tình hình Bắc Kì lúc đó. Những phân tích và đánh giá của ông khá bao quát, từ vị trí địa lí các vùng miền, từ tâm tính, đặc điểm của con người đến những giá trị đạo đức và tinh thần dân tộc.', '858d76ac8747bbc24c6f770cfad71363_f303947077c84385b8c4bafab42224e0_medium.jpg'),
('DS037', 'Hát Văn và âm nhạc hát văn', 2018, 219000.00, 'TG012', 'TL002', 'NXB008', NULL, '4c7ac45a0e6e315b9c5a0c3b11dbfc96_967b8076d01b4bf1a56c30523fa68ed6_medium.jpg'),
('DS038', 'Lịch sử hình thành và phát triển ngôn ngữ', 2020, 385000.00, 'TG012', 'TL002', 'NXB009', NULL, '9786043945096_8143d14a09ee4e66935eaaa8da6d69aa_medium.jpg'),
('DS039', 'Vụ Án Truyện Kiều', 2018, 89000.00, 'TG012', 'TL002', 'NXB009', NULL, '9786043839609_fd7813cc53c342338c6c27a16456e031_medium.jpg'),
('DS040', 'Thà có một con chim trong tay', 2015, 89000.00, 'TG012', 'TL002', 'NXB008', NULL, '9786043826180_0efaeede468945a4956a09bd50bb67d6_medium.jpg'),
('DS041', 'Yên Bái Đêm Đỏ Lửa', 2010, 250000.00, 'TG012', 'TL002', 'NXB009', NULL, '9786043640137_87e16eb4414d4a528f07f762b015036f_medium.jpg'),
('DS042', 'Hoạt động chế tạo và quản lý sử dụng vũ khí dưới triều Nguyễn giai đoạn 1802-1883 (Mềm)', 2020, 155000.00, 'TG024', 'TL002', 'NXB008', NULL, 'he-tao-va-quan-ly-su-dung-vu-khi-duoi-trieu-nguyen-giai-doan-1802-1883_01b5f31e0f3a4c01bc40168c3b4c9774_medium.jpg'),
('DS043', 'Khâm thiên giám triều Nguyễn giai đoạn 1802-1883: Nghiên cứu so sánh với khâm thiên giám triều Thanh (Trung Quốc)(Mềm)', 2016, 165000.00, 'TG025', 'TL002', 'NXB009', NULL, 'kham-thien-giam-trieu-nguyen-giai-doan-1802-1883_06a8d4bb37d04db6a05187c23fbcbbf1_medium.jpg'),
('DS044', 'Hệ thống cơ quan giám sát triều Nguyễn (1802-1885): từ thiết chế, định chế đến thực tiễn (Mềm)', 2013, 165000.00, 'TG019', 'TL002', 'NXB009', NULL, '9786043085877_2a3bbd88b77e4710b0bd80af3edfbbf5_medium.jpg'),
('DS045', 'Cùng Cha Tới Auschwits', 2022, 225000.00, 'TG024', 'TL002', 'NXB010', 'Đây là một câu chuyện có thật về gia đình và sự sống sót của họ trong thời kỳ Holocaust (Diệt chủng người Do Thái), dựa trên nhật ký và hồi ký của một cặp cha con Gustav và Fritz Kleinmann, cựu tù nhân trong các trại tử thần của Đức Quốc xã.\n\nCuốn sách bắt đầu vào năm 1938, khi Gustav, một thợ bọc vải người Do Thái đến từ Vienna và con trai ông là Fritz, 16 tuổi, bị mật vụ Gestapo bắt và đưa đến Buchenwald, một trại tập trung khét tiếng ở Đức. Họ cố gắng luôn ở bên nhau, cùng chịu đựng những điều kiện tàn bạo, lao động cưỡng bức, đói khát, đánh đập và luôn có nguy cơ tử vong bất cứ lúc nào. Họ cũng chứng kiến ​​những hành động tàn bạo do Đức Quốc xã và những kẻ cộng tác với chúng gây ra, chẳng hạn như giết người hàng loạt, thí nghiệm y tế vô nhân tính và tra tấn.\n\nNăm 1942, Gustav được chọn để chuyển đến Auschwitz, trại tử thần khét tiếng nhất, nơi ông được cho là sẽ chết. Fritz, người rất yêu cha mình, quyết định đi theo ông, bất chấp rủi ro và lời khuyên của bạn bè. Họ lên cùng một chuyến tàu và đến Auschwitz, tại nơi đó, họ bị chia cắt bởi quá trình tuyển chọn. Gustav được gửi đến trại chính, trong khi Fritz được gửi đến trại Monowitz gần đó, còn được gọi là Auschwitz III.\n\nTrong hai năm tiếp theo, họ phải vật lộn để tồn tại và liên lạc với nhau thông qua những lá thư gửi lậu và những chuyến thăm thỉnh thoảng xảy ra. Họ cũng hình thành mối quan hệ với các tù nhân khác, một số người đã giúp đỡ họ hoặc phản bội họ. Họ đã chứng kiến sự khủng khiếp của phòng hơi ngạt, lò hỏa táng, nạn đói và bệnh tật.\n\nNăm 1945, khi quân Đồng minh tiến công đến gần, Đức Quốc xã sơ tán các trại tập trung. Gustav và Fritz nằm trong số hàng nghìn người đàn ông phải bước đi qua tuyết và giá lạnh, hầu như không có thức ăn hoặc nước uống và dưới làn đạn liên tục. Nhiều người đã chết trên đường đi, nhưng Gustav và Fritz cố gắng sống sót và ở bên nhau. Cuối cùng, họ được quân đội Liên Xô giải phóng gần biên giới Séc.\n\n Cuốn sách kết thúc bằng phần kết ngắn gọn, mô tả số phận của những thành viên khác trong gia đình Kleinmann (vợ Tini, con gái cả Edith, con gái thứ Herta, em trai út Kurt), những người cũng bị Đức Quốc xã đàn áp.\n\nCuốn sách là một câu chuyện cảm động và mạnh mẽ về mối quan hệ giữa người cha và con trai cũng như lòng dũng cảm và sự kiên cường của họ khi đối mặt với nỗi kinh hoàng không thể tưởng tượng được. Nó cũng là lời tri ân tới hàng triệu nạn nhân và những người sống sót sau thảm họa Holocaust, đồng thời là lời nhắc nhở về tầm quan trọng của việc ghi nhớ và đúc rút các bài học từ lịch sử.', 'bia_1_9ae046a49b9f4ccebadfac97dbb2c210_master.png'),
('DS046', 'Thoát Khỏi Địa Ngục Khmer Đỏ - Hồi Ký Của Một Người Còn Sống', 2024, 73000.00, 'TG025', 'TL002', 'NXB011', 'Thoát Khỏi Địa Ngục Khmer Đỏ - Hồi Ký Của Một Người Còn Sống\n\nCuốn sách Thoát khỏi địa ngục Khmer đỏ – Hồi ký của một người còn sống là cuốn hồi ký đẫm nước mắt viết về những năm tháng kiệt quệ cả về thể xác lẫn tinh thần mà Denise Affonço đã phải trải qua. Bằng giọng văn ý nhị nhưng rõ ràng, tác giả đã kể lại khá chi tiết, chân thực và thành công một giai đoạn đặc biệt và phi thường của cuộc đời mình, những mất mát và khổ đau mà bà đã trải qua trong những năm bị đày ải dưới sự kìm kẹp của Khmer đỏ cũng như trong thời gian 11 tháng sau khi bộ đội Việt Nam vào giải phóng nhân dân Campuchia.\n\nCuốn sách với lời văn sinh động, chân thực qua lời kể của một người đã từng trải qua những năm tháng địa ngục ở Campuchia sẽ giúp những thế hệ đi sau hiểu hơn, từ đó không lãng quên một giai đoạn tang thương của người Campuchia dưới tội ác diệt chủng của chính quyền Khmer đỏ, cũng như tôn vinh những đóng góp to lớn của bộ đội tình nguyện Việt Nam trong sự nghiệp quốc tế cao cả, giúp giải phóng đất nước Campuchia, giúp người dân Campuchia thoát khỏi họa diệt chủng, nhưng hiện đang bị một số thế ực tìm cách phủ nhận.\n\nTác giả cuốn sách là người mang quốc tịch Pháp, làm việc tại Đại sứ quán Pháp ở Campuchia trước khi quân Khmer đỏ chiếm Thủ đô Phnom Penh nên dù rất biết ơn bộ đội Việt Nam đã giải phóng bà và người dân Campuchia khỏi tay quân Khmer đỏ, nhưng bà vẫn có một số quan điểm, cách nhìn nhận khác với quan điểm chính thống của Việt Nam về chủ nghĩa xã hội, chủ nghĩa cộng sản. Tôn trọng ý kiến của tác giả trong bối cảnh lịch sử cụ thể khi đó, chúng tôi cố gắng giữ ý kiến của tác giả và khẳng định đó là ý kiến riêng của tác giả, không phản ánh quan điểm của Nhà xuất bản Chính trị quốc gia Sự thật.', '8935279163403_7db9ad0beba54074806072de0d332eec_master.jpg'),
('DS047', 'Thần Thoại Hy Lạp Trọn Bộ 2 Tập (Bìa Cứng)', 2018, 219000.00, 'TG023', 'TL003', 'NXB011', 'Thần thoại Hy Lạp, một di sản văn hóa của nhân dân Hy Lạp, từ lâu đã trở thành một giá trị phổ biến vô cùng quý báu của gia tài văn hóa nhân loại. Thật vậy, hiếm có thần thoại của dân tộc nào trên thế giới lại luôn luôn được tái sinh như thần thoại Hy Lạp, lại luôn luôn có mặt trong đời sống hằng ngày như thần thoại Hy Lạp.\n\nCuốn sách trọn bộ \"Thần thoại Hy Lạp\" này là một kho tàng văn hóa Hy Lạp thu nhỏ mang đến cho bạn những giá trị tinh thần quý báu, rất đáng lưu giữ và trân trọng. Đó là miền đất văn hóa của tâm linh, của khát vọng, của ước mơ hoài bão chân chính, nơi sức mạnh mang ý nghĩa nhân bản sẽ chiến thắng bạo tàn, nơi cái đẹp được tôn vinh và khẳng định.\n\nNói như người biên soạn Nguyễn Văn Khỏa trong lời giới thiệu sách: \"ường như Thần thoại Hy Lạp vẫn đang hàng ngày hàng giờ, nhắn nhủ loài người chúng ta: \"Hãy sống nhân ái và cao thượng hơn nữa! Hãy sống tốt đẹp hơn nữa, yêu công lý và trọng danh dự hơn nữa! Hãy lập chiến công vì dân vì nước nhiều hơn nữa! Hãy sống trung thực dũng cảm và hiểu biết hơn nữa!\". Bởi vì thần thoại là nhân loại của tuổi thơ hồn nhiên và trong sáng, ngây thơ vụng dại song tràn đầy tin yêu; hiểu biết chưa nhiều, giản đơn song thông minh một cách ngộ nghĩnh và tràn đầy ước mơ đẹp đẽ, tràn đầy khát vọng táo bạo.\"\n\nChính những điều đó đã làm nên sức sống kỳ diệu, vượt mọi không gian, thời gian để Thần thoại Hy Lạp luôn trường tồn cùng nhân loại.\n\nThần thoại Hy Lạp là những truyện thần thoại của người Hy Lạp, bao gồm các truyền thuyết về các vị nam thần, nữ thần và các vị anh hùng của người Hy Lạp. Ban đầu, thần thoại Hy Lạp là những câu chuyện thơ truyền khẩu qua nhiều thế hệ. Các câu chuyện đó tồn tại đến ngày nay là nhờ các ghi chép về các câu chuyện truyền miệng nói trên, đôi khi chúng được bổ sung thêm các lời giải thích về các ý nghĩa biểu tượng hoặc các hàm ý khác có thể là hiện đại hoặc cổ điển. Nhiệm vụ của các nhà nghiên cứu là tìm ra những ý nghĩa ban đầu được ẩn dấu trên các hình vẽ trên các bình gốm sứ, các bức họa, hoặc đằng sau những nghi lễ tôn giáo còn tồn tại đến ngày nay.\n\nTrong các truyền thuyết, câu chuyện và trường ca, tất cả các vị thần của Hy Lạp cổ đại đều được miêu tả giống như hình dáng của con người, ngoại trừ một số sinh vật nửa người nửa thú như các nhân sư, số còn lại đều có nguồn gốc từ vùng Cận Đông và vùng Thổ Nhĩ Kỳ. Các vị thần Hy Lạp có thể sinh con nhưng trẻ mãi không già, không bị thương tổn, không ốm đau, có thể trở nên tàng hình, có thể di chuyển rất nhanh và có thể dùng người là phương tiện truyền đạt ý tưởng của họ mà người đó có thể biết hoặc không biết. Mỗi vị thần có một hình dáng, một nguồn gốc, một sở thích, một cá tính và một lĩnh vực chuyên môn mà họ quản lý; tuy nhiên, việc miêu tả các thần thường xuất phát từ các dị bản khác nhau nên không phải lúc nào cũng ăn khớp với nhau. Khi các vị thần được vinh danh trong thơ ca hoặc khi cầu nguyện thì họ được coi như là một ý nghĩa tổng hợp gồm tên và trách nhiệm của các vị để phân biệt với các hình ảnh khác của các thần. Trách nhiệm của một vị thần có thể phản ánh một khía cạnh đặc biệt về vai trò của vị thần đó, ví dụ, Apollo, vị thần thơ ca là tên dành cho thần Apollo, được coi là người bảo trợ cho nghệ thuật: thơ, ca, nhạc, họa; người cầm đầu các tiên nữ thơ ca muse. Nhưng trách nhiệm của một vị thần cũng có thể dùng để phân biệt một khía cạnh đặc biệt nào đó của một vị thần.\n\nTrong các truyện thần thoại Hy Lạp, các vị thần được miêu tả là những người thuộc cùng một gia đình đa thế hệ. Vị thần già nhất tạo ra thế giới, nhưng các vị thần trẻ hơn đã thay thế các vị thần già. Mười hai vị thần trên đỉnh Olympus là các vị thần quen thuộc nhất với tôn giáo Hy Lạp và nghệ thuật Hy Lạp và được miêu tả trong các sử thi có hình dáng của con người trong \"Thời đại của các anh hùng\". Đó là các bài học mà tổ tiên người Hy Lạp phải học để có được các kỹ năng cần thiết, lòng kính sợ thần thánh, đề cao đức hạnh và trừng phạt tội lỗi. Các vị thần nửa người, nửa thần được gọi là các \"anh hùng\" và cho đến khi thiết lập được thể chế dân chủ, các hậu duệ người Hy Lạp xây dựng trên cơ sở của tổ tiên.', '856939087335_554732de4a374e9ea39e8c920eb5ecea_medium.jpg'),
('DS048', 'Truyện Thạch Yêu', 2020, 116000.00, 'TG026', 'TL003', 'NXB010', 'Trong sự nghiệp kéo dài gần năm thập niên, Ingri và Edgar d’Aulaire đã chấp bút và minh họa gần ba mươi cuốn sách, nhận được nhiều lời khen ngợi và giải thưởng. Truyện Thạch Yêu được xuất bản lần đầu năm 1972, cũng nằm trong số những cuốn sách được ngợi ca đó. Truyện Thạch Yêu kết hợp những truyện kể hấp dẫn từ văn học dân gian Bắc Âu với một lữ khách lang thang miền thần thoại dẫn dắt ta đến với những sinh vật lông lá. Chúng ta biết thêm về thạch yêu rừng, thạch yêu núi, thạch yêu sống ở chân cầu – nhà của chúng, lối sống của chúng, thậm chí cả số đầu của chúng. Chúng ta gặp ba sinh vật chia nhau một con mắt duy nhất, và những cô gái bị nguyền rủa cứ nói là nhả ra cóc nhái. Nhưng không có gì quá đáng sợ ở đây: Những bức tranh in thạch bản trông như được vẽ tay thoát lên một vẻ ấm áp biến tất cả những sinh vật đó từ đáng sợ thành hài hước.\n\n- Time Out New York Kids\n\n-------------\n\nĐủ các loài thạch yêu - thạch yêu núi, thạch yêu rừng, thạch yêu sống dưới nước và thạch yêu sống dưới cầu, những thạch yêu xù xì, lôi thôi, đáng ghét, khó quên, và luôn luôn ngốc nghếch đến không tưởng - lấp đầy những trang sách Truyện Thạch Yêu của vợ chồng d’Aulaire, cuốn sách được minh họa đẹp mắt và thú vị cực kỳ, đồng hành cùng Những vị thần Bắc Âu.\n\nTrong cuốn sách này, cặp vợ chồng Ingri và Edgar Parin d’Aulaire đã khám phá mặt tối của thần thoại Bắc Âu, thế giới ban đêm ở đó thạch yêu bày mưu tính kế và sinh hoạt theo những lối kỳ quái và thú vị nhất, đi săn trẻ em hay cãi cọ với nhau trên cánh đồng việt quất. Những thạch yêu nhiều đầu, những thạch yêu kẹp đầu dưới nách, và những thạch yêu chỉ có một con mắt dùng chung, cùng với những mụ vợ thạch yêu mà cái mũi đỏ dài của các mụ rất hợp dùng để khuấy súp, chúng lang thang khắp miền núi non xứ Bắc Âu vào ban đêm rồi đến ngày thì ngủ trong những hang chất đầy bạc vàng.\n\nVới tài năng vô song của một người kể chuyện và một họa sĩ minh họa, vợ chồng d’Aulaires đang thổi hồn vào thế giới thần thoại Bắc Âu lạ lùng và kỳ diệu.', 'p92022mtruyen_th___ch_yeu_01_e73e7a6234fa47efaf5c2823955efb25_medium.jpg'),
('DS049', 'Rilla Dưới Mái Nhà Bên Ánh Lửa', 2022, 200000.00, 'TG027', 'TL003', 'NXB009', '\"Chưa tròn mười lăm, Rilla ùa vào đời với niềm háo hức vẹn nguyên. Mỗi ngày với cô đều đáng để trông đợi, tương lai phía trước trải ra huy hoàng mời gọi. Nhưng, bóng đen của cuộc Thế Chiến thứ nhất lừ lừ kéo đến, lần lượt cuốn đi những người Rilla yêu thương nhất, phủ sầu lo sợ hãi lên mỗi buổi bình minh. Cô con gái út vô tư lự của Anne tóc đỏ ngày nào đã buộc phải trưởng thành quá sớm.\n\nLấy bối cảnh chiến tranh nặng nề, đau thương và mất mát, cuốn cuối cùng của series về Anne tóc đỏ dường như không tràn đầy niềm hy vọng, nét tươi vui và vẻ đẹp nên thơ trong sáng như những tác phẩm trước. Nhưng Rilla dưới mái nhà Bên Ánh Lửa, xứng đáng hơn hết thảy, vẫn là cuốn sách “dành cho tất cả những ai từng yêu thích Anne tóc đỏ dưới Chái Nhà Xanh”, vì sự sâu sắc, thấm thía và một niềm hy vọng kiểu khác: niềm tin về một thế giới tốt đẹp hơn, một hạnh phúc được người ta chiến đấu, hy sinh để giành lấy.', 'p94953mrilla_duoi_mai_nha_ben_anh_lua_01_2b6289475163404a88b5da9e7add091d_medium.jpg'),
('DS050', 'Giết Con Chim Nhại (Tái Bản 2019)', 2018, 145000.00, 'TG028', 'TL003', 'NXB010', 'Nào, hãy mở cuốn sách này ra. Bạn phải làm quen ngay với bố Atticus của hai anh em - Jem và Scout, ông bố luật sư có một cách riêng, để những đứa trẻ của mình cứng cáp và vững vàng hơn khi đón nhận những bức xúc không sao hiểu nổi trong cuộc sống. Bạn sẽ nhớ rất lâu người đàn ông thích trốn trong nhà Boo Radley, kẻ bị đám đông coi là lập dị đã chọn một cách rất riêng để gửi những món quà nhỏ cho Jem và Scout, và khi chúng lâm nguy, đã đột nhiên xuất hiện để che chở. Và tất nhiên, bạn không thể bỏ qua anh chàng Tom Robinson, kẻ bị kết án tử hình vì tội hãm hiếp một cô gái da trắng, sự thật thà và suy nghĩ quá đỗi đơn giản của anh lại dẫn đến một cái kết hết sức đau lòng, chỉ vì lý do anh là một người da đen.\n\nCho dù được kể dưới góc nhìn của một cô bé, cuốn sách Giết con chim nhạikhông né tránh bất kỳ vấn đề nào, gai góc hay lớn lao, sâu xa hay phức tạp: nạn phân biệt chủng tộc, những định kiến khắt khe, sự trọng nam khinh nữ… Góc nhìn trẻ thơ là một dấu ấn đậm nét và cũng là đặc sắc trong Giết con chim nhại. Trong sáng, hồn nhiên và đầy cảm xúc, những câu chuyện tưởng như chẳng có gì to tát gieo vào người đọc hạt mầm yêu thương.\n\nGần 50 năm từ ngày đầu ra mắt, Giết con chim nhại, tác phẩm đầu tay và cũng là cuối cùng của nữ nhà văn Mỹ Harper Lee vẫn đầy sức hút với độc giả ở nhiều lứa tuổi. Thông điệp yêu thương trải khắp các chương sách là một trong những lý do khiến Giết con chim nhại giữ sức sống lâu bền của mình trong trái tim độc giả ở nhiều quốc gia, nhiều thế hệ. Những độc giả nhí tìm cho mình các trò nghịch ngợm và cách nhìn dí dỏm về thế giới xung quanh. Người lớn lại tìm ra điều thú vị sâu xa trong tình cha con nhà Atticus, và đặc biệt là tình người trong cuộc sống, như bé Scout quả quyết nói “em nghĩ chỉ có một hạng người. Đó là người”.', 'p94902mimage_180771_538aa219d5604ec1af896cf9efb511f7_medium.jpg'),
('DS051', 'Cửa Hàng Ma Quái', 2024, 349000.00, 'TG029', 'TL003', 'NXB005', 'Needful Things – Cửa hàng ma quái là câu chuyện diễn ra tại thị trấn Castle Rock. Câu chuyện bắt đầu vào một ngày nọ, khi một người đàn ông tên Leland Gaunt tới thị trấn và mở một cửa hàng mang tên: Những Thứ Cần Thiết. Nơi đây bán những món đồ được khách tới mua yêu thích nhưng họ cũng phải trả giá không chỉ bằng tiền… mà bằng một trò chơi khăm do chính Gaunt đề xuất. Từ đây thị trấn vốn dĩ bình yên bị xáo trộn bởi những sự kiện kỳ lạ, thậm chí mang phần máu me, bạo lực.\n\nCác nhân vật trong thị trấn nhỏ này đều bị sập bẫy bởi chính ham muốn sở hữu của bản thân mình. Nhưng đây không chỉ đơn thuần là một bài học về đạo đức. Câu chuyện có đầy đủ những đặc điểm xuất sắc thường có trong các tiểu thuyết của bậc thầy viết truyện kinh dị Stephen King: Yếu tố li kỳ, tiết tấu nhanh, những pha hành động nghẹt thở, gay cấn, óc hài hước châm biếm về xã hội Mỹ đương thời, những nhân vật đặc trưng tiêu biểu của mỗi lớp tính cách, sự đấu tranh giữa cái Thiện và cái Ác trong mỗi con người.\n\nNeedful Things – Cửa hàng ma quái được xem là một trong những tiểu thuyết hay nhất trong sự nghiệp của Stephen King.', 'ma_9e38e85878204ab8af03e84984a53aae_master.png'),
('DS052', 'Lịch Sử Các Lý Thuyết Truyền Thông - Histoire Des Théories De La Communication', 2024, 175000.00, 'TG030', 'TL003', 'NXB008', 'Lịch Sử Các Lý Thuyết Truyền Thông - Histoire Des Théories De La Communication\n\nCuốn sách \"Lịch Sử Các Lý Thuyết Truyền Thông\" của Armand Mattelart và Michèle Mattelart khám phá sự phát triển của lĩnh vực truyền thông và những ý nghĩa đa dạng mà nó mang lại. Từ các lĩnh vực triết học, lịch sử, địa lý, tâm lý học, đến kinh tế học và chính trị học, cuốn sách này giúp bạn hiểu rõ hơn về truyền thông trong bối cảnh lịch sử và các quan điểm khác nhau.\n\nHiểu rõ sự phát triển và bùng nổ của lĩnh vực truyền thông.\n\nKhám phá các quan điểm và trường phái khác nhau trong lý thuyết truyền thông.\n\nTìm hiểu về sự đa dạng và căng thẳng trong nghiên cứu lịch sử của lý thuyết truyền thông.\n\nArmand Mattelart và Michèle Mattelart là hai tác giả nổi tiếng với sự đóng góp của mình trong lĩnh vực truyền thông. Cuốn sách này là một trong những công trình tiêu biểu của hai tác giả, giúp đưa ra cái nhìn tổng quan về lịch sử và lý thuyết truyền thông.', '8935280501508_12c78c778c0c440aad2eb37e53ba6a5b_master.jpg'),
('DS053', 'Vươn Lên Từ Đáy - Hành Trình Tái Hiện Giấc Mơ Mỹ', 2024, 215000.00, 'TG031', 'TL003', 'NXB001', 'Vươn Lên Từ Đáy - Hành Trình Tái Hiện Giấc Mơ Mỹ\n\nChúng ta nợ nhau điều gì? Làm sao chúng ta truyền động lực, tài năng, thậm chí nỗi đau, vào những thứ còn ý nghĩa hơn cả thành công cá nhân? Và trách nhiệm của chúng ta ở nơi chúng ta sống, làm việc, và vui chơi là gì? Đây chính là những câu hỏi mà Howard Schultz luôn mang bên mình từ khi lớn lên ở khu nhà Brooklyn và trong khi xây dựng Starbucks từ 11 cửa hàng thành một trong những thương hiệu biểu tượng của thế giới.\n\nTrong quyển sách này, Schultz tìm câu trả lời qua hai câu chuyện đan xen. Một câu chuyện cho thấy thời nhỏ đầy mâu thuẫn của anh - cả những trải nghiệm anh chưa từng tiết lộ - chính là động lực để anh trở thành người đầu tiên trong nhà tốt nghiệp đại học, rồi xây dựng nên loại công ty mà cha anh, công nhân tầng lớp lao động, chưa từng có cơ hội làm việc: một doanh nghiệp cố gắng cân bằng giữa lợi nhận và tính nhân văn.\n\nCâu chuyện còn lại đưa ra cái nhìn phía sau hậu trường về những nỗ lực phi thường của Schultz nhằm thách thức những quan niệm cũ về vai trò của doanh nghiệp trong xã hội. Từ bảo hiểm y tế và miễn học phí đại học cho nhân viên bán thời gian đến những sáng kiến đầy tranh cãi về sắc tộc và người nhập cư. Schultz cùng đội ngũ của mình đã giải quyết những vấn đề xã hội với cùng sự sáng tạo và táo bạo như cách họ làm để thay đổi nhận thức của thế giới về cách thưởng thức cà phê.\n\nQuyển sách một phần là hồi ký, một phần là bản kế hoạch chi tiết về trách nhiệm chung, và một phần như minh chứng rằng con người bình thường có thể làm những điều phi thường. Tâm điểm của quyển sách chính là bản mô tả lạc quan, đầy cảm hứng về những gì xảy ra khi chúng ta dám đứng lên, lên tiếng và chung tay vì những mục đích còn cao cả hơn chính bản thân chúng ta.', '8934974201106_7211232daf0443aeb60ca8161c6fcef5_master.jpg'),
('DS054', 'Người Chết Biết Điều Gì', 2024, 175000.00, 'TG032', 'TL003', 'NXB005', 'Người Chết Biết Điều Gì\n\nĐỪNG ĐỌC CUỐN SÁCH NÀY KHI BẠN ĐANG DÙNG BỮA!\n\nĐằng sau cánh cửa im lìm là một cái xác đang phân hủy, thối rữa và bốc mùi ngai ngái. Những vết cắn nham nhở của lũ chuột ngập ngụa trên đống nội tạng bầy nhầy. Cẳng tay, cẳng chân được đựng trong túi du lịch và vết xăm mình là dấu hiệu duy nhất để nhận dạng danh tính, nhưng... chúng không thuộc về cùng một người.\n\nNGƯỜI CHẾT BIẾT ĐIỀU GÌ gồm 18 câu chuyện dựa trên những sự kiện có thật trong cuộc đời Barbara với tư cách một điều tra viên pháp y. Trong suốt 23 năm làm việc tại Văn phòng Giám định Y khoa Thành phố New York (OCME), bà đã điều tra hơn 5.500 trường hợp tử vong, trong đó có 680 vụ giết người. Cuốn hồi ký kể lại quá trình điều tra các vụ giết người hàng loạt lớn nhất nước Mỹ và vụ khủng bố ngày 11/9/2001, nơi bà và các đồng nghiệp đã phải căng não làm việc để nhận diện 21.900 bộ phận cơ thể của các nạn nhân.', 'b_a-1-ng_i-ch_t-bi_t-_i_u-g_-1_04ecfa96a8c54b16aa53420e206d9546_master.jpg'),
('DS055', 'Beyond The Story - 10 Year Record Of BTS', 2023, 589000.00, 'TG033', 'TL003', 'NXB002', '10-Year Recond Of BTS - Beyond The Story\n\nCUỐN SÁCH CHÍNH THỨC ĐẦU TIÊN được phát hành nhân dịp kỷ niệm mười năm thành lập của BTS.\n\nNhững câu chuyện vượt xa những gì bạn đã biết về BTS.\n\nBTS, viết tắt của “Bangtan Sonyeondan” hoặc “Beyond the Scene”, là một nhóm nhạc nam từng được đề cử giải Grammy đến từ Hàn Quốc đã chiếm được cảm tình của hàng triệu người hâm mộ trên toàn cầu kể từ khi ra mắt vào tháng 6 năm 2013. Các thành viên của BTS gồm có RM, Jin, SUGA, j-hope, Jimin, V và Jung Kook. Họ đã được đón nhận trên toàn thế giới, vượt qua mọi quốc gia, chủng tộc, ngôn ngữ, giới tính và thế hệ bằng âm nhạc, những màn trình diễn cuồng nhiệt và mối liên hệ chặt chẽ của họ với người hâm mộ. Họ luôn tìm kiếm những lối đi mới mẻ để đáp lại tình yêu mà họ nhận được thông qua âm nhạc của mình.\n\nGiờ đây, trong cuốn sách đặc biệt này, ghi chép chính thức về BTS và tuổi trẻ của nhóm, “The Most Beautiful Moment on Life” (khoảnh khắc rực rỡ nhất trong cuộc đời) do nhà phê bình âm nhạc/văn hóa đại chúng Myeongseok Kang chắp bút trong vòng hơn 3 năm và việc phỏng vấn được thực hiện trong hai năm. BTS đã chia sẻ những câu chuyện cá nhân, hậu trường và hành trình của nhóm cho đến thời điểm hiện tại.', 'sp3_11_96dd3b417173463cbab8fe6cc2c2998f_master.jpg'),
('DS056', 'CR7 - Hành trình lên đỉnh thế giới - Phiên bản 2023 ', 2023, 278400.00, 'TG034', 'TL003', 'NXB007', 'Sách - CR7 - Hành trình lên đỉnh thế giới\n\n\n\nTrong thế giới bóng đá, siêu sao Cristiano Ronaldo vốn vẫn được biết đến với những màn trình diễn phi thường trên sân và một ngôi sao thực sự bên ngoài sân cỏ trong suốt hơn một thập kỷ vừa qua. Và đến thời điểm hiện tại, ngôi sao người Bồ Đào Nha vẫn đang chứng minh mình vẫn còn đang ở trên đỉnh của thế giới\n\n“CR7 – Hành trình lên đỉnh thế giới” - Cuốn sách là câu chuyện kể về cuộc đời của siêu sao người Bồ Đào Nha với biết bao thăng trầm và biến cố trên chặng đường bước lên đỉnh cao sự nghiệp. \n\n\n\nTừ một cậu nhóc bị lũ bạn ở trường chê cười vì cái chất giọng Madeira quê mùa cho đến siêu sao của bóng đá thế giới với 5 danh hiệu Champions League, 5 giải thưởng Quả bóng vàng cùng vô số nhưng danh hiệu lớn nhỏ trong suốt 18 năm qua. Đó là cả một hành trình dài với đầy rẫy những chông gai và thử thách. \n\n\n\nCuốn sách là món quà thực sự ý nghĩa, giúp những ai đã, đang và sẽ dành tình cảm cho anh chàng CR7 hiểu hơn về cuộc đời của con người luôn nỗ lực hết mình để hướng tới sự hoàn hảo ấy. Biết bao điều bí ẩn cả trong lẫn ngoài sân cỏ về cuộc đời của CR7 mà không phải ai cũng biết sẽ lần đầu tiên được tiết lộ trong cuốn sách này. ', '0b036713badb4f6a24144eff7a2f6143.jpg'),
('DS057', 'Who? Chuyện Kể Về Danh Nhân Thế Giới: Lionel Messi ', 2023, 61000.00, 'TG012', 'TL003', 'NXB012', 'Who? Chuyện Kể Về Danh Nhân Thế Giới: Lionel Messi Lionel Messi - Từ một cậu bé thấp còi trở thành siêu sao trong lịch sử bóng đá thế giới! Thuở bé, Lionel Messi rất yêu thích đá bóng. Tuy nhiên, do mắc chứng thiếu hụt hoóc môn tăng trưởng nên thân hình cậu bé thấp nhỏ, còi cọc so với các bạn cùng trang lứa. Bằng tình yêu cháy bỏng dành cho trái bóng, Messi quyết tâm khắc phục nhược điểm thể hình để trở thành cầu thủ xuất sắc nhất với năm lần đoạt Quả Bóng Vàng thế giới! Bộ tranh truyện WHO? Chuyện kể về danh nhân thế giới - người bạn thân thiết của thiếu nhi. Những câu chuyện về cuộc đời và sự nghiệp vĩ đại của các danh nhân sẽ đem đến cho các em những ước mơ và hoài bão cao đẹp!', '28ed80689efdd3d985fb94f1283b0f6a.jpg'),
('DS058', 'Tự truyện David Beckham - Góc cạnh đời tôi', 2022, 210000.00, 'TG035', 'TL003', 'NXB007', 'Tự Truyện David Beckham - Góc Cạnh Đời Tôi\n\nTại sao David Beckham lại được yêu mến và hâm mộ cuồng nhiệt đến như vậy? Bởi khả năng chơi bóng trên cả tuyệt vời? Bởi vẻ ngoài quý tộc? Bởi niềm đam mê bóng đá đã tạo cảm hứng cho bao thế hệ?\n\nCâu trả lời là tất cả những yếu tố trên, Beckham là một thần tượng, là tấm gương cho sự nỗ lực kiên cường, can đảm vượt qua khó khăn để rồi tạo ra những khoảnh khắc say đắm lòng người\n\nỞ những nơi mà anh đi qua - những thành phố hoa lệ và hào nhoáng bậc nhất thế giới: Madrid, Milan, Los Angeles cho đến Paris, Beckham luôn luôn tỏa sáng, anh đơn giản là không thể thay thế\n\nSự nghiệp bóng đá của Becks là cả một câu chuyện dài mà không phải cầu thủ nào cũng có được – đắng cay, thất bại, gục ngã, thành công, vinh quang… Bằng tài năng, sự chuyên nghiệp và niềm đam mê bóng đá đến cháy bỏng, Beckham đã chinh phục cả thế giới trong suốt 3 thập kỉ.', '7dd5799dd382ba022683adea64abe800.jpg'),
('DS059', 'Tự truyện \"VOI RỪNG\" Drogba - Cầu thủ vĩ đại bậc nhất Chelsea ', 2023, 200000.00, 'TG036', 'TL003', 'NXB013', 'Cuốn sách này, tôi xin được dành tặng cho người hâm mộ ở những đội bóng tôi từng khoác áo, cho bố mẹ tôi, vợ và các con.\n\nKhông có họ, sẽ không có Didier Drogba.\n\n\n\nLời tri ân\n\nTừ tận đáy lòng, tôi muốn gửi lời cảm ơn đặc biệt đến những HLV tôi đã từng làm việc cùng. Họ là những người giúp đỡ tôi trở thành một Didier Drogba như tất cả chúng ta đã thấy. Nhưng trên hết, tôi muốn tri ân tất cả những người đã ảnh hưởng đến tôi, luôn thôi thúc, động viên tôi trở thành người đàn ông như ngày hôm nay. Tôi chẳng thể liệt kê tên của từng người đâu, vì như vậy tôi phải viết thêm 1 cuốn sách nữa mất.\n\nTôi cũng muốn cảm ơn đến những người đồng đội. Tôi từng làm việc với rất nhiều ngôi sao, và thật may mắn khi có cơ hội làm bạn với nhiều người trong số họ.\n\nCảm ơn bố mẹ vì đã cho tôi được học hành tử tế. Cảm ơn người chú của tôi, ông Michel Goba, vì nhờ có ông, tôi đã có một giấc mơ và biến\n\nnó trở thành sự thật.\n\nCảm ơn những HLV đã đồng hành cùng tôi, từ khi tôi còn ở Levallois Perret. Cảm ơn Marc Westerloppe và Alain Pascalou, vì đã mang đến cho tôi thách thức thú vị trong 4 năm đầu tiên ở một đội bóng chuyên nghiệp thực sự. Cảm ơn Guy Lacombe, người đã tin tưởng tuyệt đối vào tôi.\n\nCảm ơn José, người đặc biệt, cùng với Roman, người mà tôi từng nói rằng đã thay đổi cuộc đời của tôi và gia đình, là người tôi sẽ luôn mang ơn suốt cuộc đời.', 'vn-11134207-7ras8-m3hblq7cmo4o6d.jpg'),
('DS060', 'Jurgen Klopp - Thổi bùng huyên náo - Chiến lược gia hàng đầu của bóng đá đương đại', 2022, 130000.00, 'TG037', 'TL003', 'NXB007', 'Sách - Sách - Jurgen Klopp - Thổi bùng huyên náo - Chiến lược gia hàng đầu của bóng đá đương đại\n\n\n\nLà một cổ động viên lâu năm của Liverpool, Jurgen Klopp với tôi như một vị cứu tinh. Mối lương duyên của ông với Liverpool thật đặc biệt, nhất là cách mà ông biến đội bóng này trở thành một trong những tập thể khó đánh bại bậc nhất tại châu Âu, với phong cách chơi bóng như muốn thiêu rụi đối phương bằng ngọn lửa của lòng quả cảm và đam mê chiến thắng.\n\nKlopp sinh ra là để dành cho The Kop. Như một định mệnh không thể chối bỏ. Dưới bàn tay của Klopp, Anfield tìm lại được âm lượng vốn có, các cầu thủ Liverpool giờ bước vào mỗi trận đấu trong tâm thế của những kẻ đi chinh phục. Klopp khiến Liverpool vĩ đại trở lại. Klopp khơi lại nỗi khát khao vẫn đang cháy âm ỉ bên trong mỗi cổ động viên Liverpool.\n\n“Jurgen Klopp – Thổi bùng huyên náo” là một lát cắt sống động về cuộc đời đầy thăng trầm của vị huấn luyện viên sinh ra ở vùng Rừng Đen. Từ khi là một cậu bé được rèn giũa dưới thiết quân luật của người cha, đến một chàng thanh niên đôi mươi đầy hoài bão và cuồng nộ, tới hình ảnh một gã trung niên lăn lộn trong gian khó lúc khởi sự nghiệp huấn luyện. Gập cuốn sách lại, tôi hiểu được lý do tại sao Klopp lại đang trên đường trở thành chiến lược gia hay nhất đương đại.\n\nKlopp chính là nét đặc biệt đắt giá của Premier League, giải đấu quy tụ nhiều huấn luyện viên hàng đầu nhất thế giới. Truyền thông săn đón ở ông những phát biểu cá tính và nụ cười toe toét, các Liverpudlians chờ đợi những màn ăn mừng giơ nắm đấm vào không khí đầy cảm xúc, còn các cầu thủ Liverpool muốn nhìn thấy người đàn ông với bộ râu rậm và chiếc mũ lưỡi trai bên đường piste, để tự nhủ rằng họ không bao giờ được bỏ cuộc.\n\nKlopp chính là câu trả lời cho những con tim Kopites tan vỡ, khi Steven Gerrard trượt dài theo giấc mơ của hàng triệu CĐV áo đỏ vào năm 2014. Có ông, người Liverpool sẽ chẳng bao giờ phải bước đi một mình, nhưng họ vẫn sẽ hát vang “You’ll Never Walk Alone”, như một lời khẳng định cho đế chế vĩ đại mà Klopp đang gây dựng ở vùng Merseyside.\n\nKlopp gọi mình là “Người bình thường”, người ta gọi ông với cái tên thân mật Klopp, còn với tôi, ông đã là một Scouser chính hiệu rồi!\n\n-- Nhà báo Lại Văn Sâm --', 'e0061cf7eda9fda8b33092a143e436d8.jpg'),
('DS061', 'Messi Vs. Ronaldo - Sự Đối Đầu Của Hai Cầu Thủ Vĩ Đại Và Kỷ Nguyên Tái Tạo Bóng Đá Thế Giới', 2023, 145000.00, 'TG012', 'TL003', 'NXB001', 'Joshua Robinson và Jonathan Clegg kết hợp những câu chuyện về Messi và Ronaldo thành một sử thi về kỷ nguyên hiện đại của bóng đá, trình bày chi tiết cách thức mà cuộc đua tranh giữa họ đã làm biến đổi cả bóng đá và ngành kinh doanh bóng đá quốc tế, một lần và mãi mãi. Dựa trên những tài liệu gốc và nhiều năm đưa tin trên các tờ báo, cuốn sách này tổng hợp vô số dữ kiện và câu chuyện liên quan tới hai danh thủ. Các tác giả như thể đi sâu vào phòng thay đồ và phòng họp ban lãnh đạo của các câu lạc bộ - những nơi hình thành nên các huyền thoại bóng đá thế giới, và tiết lộ cho độc giả những tình tiết hấp dẫn cả trong và ngoài sân cỏ. Từ những khởi đầu khác nhau tới những bước đi cũng vô cùng khác biệt trong sự nghiệp, hai siêu sao Argentina và Bồ Đào Nha có những cách thành công rất riêng, cùng với những chi tiết đôi khi thực sự thái quá liên quan đến mỗi người. Kết hợp cùng nhau, câu chuyện về họ chính là biểu tượng cho sự tăng trưởng chóng mặt của bóng đá thế giới. Ngoài ra, nó cũng cho thấy cái cách mà truyền thông mạng xã hội đã biến đổi triệt để quyền lực của những ngôi sao thể thao. Cuối cùng, mong muốn khai thác triệt để khoản đầu tư hàng tỷ đô la vào Messi và Ronaldo đã làm thay đổi vài câu lạc bộ hàng đầu châu Âu như Barcelona, Real Madrid và Manchester United.\n\nWorld Cup 2022 có lẽ là giải vô địch thế giới cuối cùng với cả Messi và Ronaldo. Do đó, cuốn sách này cũng dành nhiều trang đánh giá về di sản của họ cho bóng đá, cũng như phân tích những ảnh hưởng mà tài năng của họ tạo ra, cả tích cực lẫn tiêu cực. Hơn cả việc đơn thuần liệt kê những thành tích của hai siêu sao, đây thực sự là một “tiểu sử của cuộc đua tranh” vốn đã trở thành một lăng kính quan trọng để hiểu về quá khứ, hiện tại và tương lai của bóng đá thế giới.', 'vn-11134201-7r98o-logb4yg6xiuv8c.jpg'),
('DS062', 'Andrés Iniesta the artist - Khi bóng đá là nghệ thuật ', 2023, 150000.00, 'TG038', 'TL003', 'NXB007', 'Có một điều khá hoang đường trong thời đại truyền thông như bây giờ, đó là sự tồn tại của những cầu thủ không có antifan. Vậy mà Andrés Iniesta lại là một người nằm trong cõi hoang đường đó. Chàng trai ấy sở hữu tài năng xuất chúng, nhân cách hiền hậu, những phát ngôn chuẩn mực, và đặc biệt là những bàn thắng tuy không nhiều nhưng bùng nổ đất trời. Thế rồi, ta rời khỏi những dòng tìm kiếm trên Google, rời khỏi thảm cỏ xanh mướt, và thời khắc ta lật cuốn sách “Andrés Iniesta - Khi bóng đá là nghệ thuật”, thì điều đập vào mắt ta là một thế giới khác: thế giới nội tâm đầy những hỷ nộ ái ố, cắn rứt, đau khổ của con người ngỡ rằng có tất cả ấy. Đó là một thế giới nghệ thuật mang tên Andrés Iniesta mà như anh bảo “Tôi dành cho bạn những gì bạn chưa biết…” ', '51119dedb23e3b6209ab129fde8cdb38.jpg'),
('DS063', 'Dưỡng Nhan Đánh Tan Lão Hóa ', 2025, 148000.00, 'TG039', 'TL004', 'NXB014', 'Dưỡng Nhan Đánh Tan Lão Hóa\n\nMuốn duy trì nhan sắc tươi trẻ và cơ thể khỏe mạnh, việc chăm sóc không chỉ dừng lại ở bên ngoài mà cần dưỡng từ bên trong. “Dưỡng nhan đánh tan lão hóa” hướng dẫn chi tiết cách chăm sóc cơ thể và làn da, từ việc điều dưỡng ngũ tạng, khí huyết, xoa bóp huyệt vị, điều tiết cảm xúc, đến rèn luyện thể chất.\n\nCuốn sách giúp bạn phòng tránh các dấu hiệu lão hóa như da sạm, nám, tàn nhang, nếp nhăn, đau lưng, loãng xương, thoái hóa đốt sống… đồng thời mang lại cơ thể khỏe mạnh, khí huyết đầy đủ và làn da tươi trẻ rạng rỡ.\n\nSách hướng dẫn bạn làm thế nào để:\n\n- Dưỡng nhan và trẻ hóa cơ thể từ bên trong\n\n- Phòng ngừa các dấu hiệu lão hóa trên da và cơ thể\n\n- Nhận biết tình trạng khí huyết qua các dấu hiệu cơ thể, đặc biệt là bàn tay và đầu ngón tay\n\n- Kết hợp ăn uống, tập luyện, xoa bóp và điều tiết cảm xúc để duy trì sức khỏe\n\n- Xây dựng thói quen chăm sóc toàn diện, vừa đẹp vừa khỏe\n\nNội dung nổi bật của sách:\n\n- Phương pháp chăm sóc cơ thể toàn diện từ bên trong\n\n- Hướng dẫn nhận biết khí huyết qua các dấu hiệu cơ thể\n\n- Cách phòng chống lão hóa thông qua ăn uống và rèn luyện\n\n- Ngôn từ dễ hiểu, phù hợp cho phụ nữ ở mọi độ tuổi\n\nSách dành cho ai:\n\n- Phụ nữ muốn giữ nhan sắc tươi trẻ lâu dài\n\n- Người quan tâm đến sức khỏe tổng thể và phòng chống lão hóa\n\n- Những ai muốn kết hợp chăm sóc từ bên trong và bên ngoài\n\n- Người muốn tìm hiểu về khí huyết, điều dưỡng cơ thể và tập luyện', '8935074137906_05703475ae514cbcb3578db19bdd7339_master.jpg'),
('DS064', 'Khỏe Đẹp Từ Gốc', 2025, 499000.00, 'TG040', 'TL004', 'NXB015', 'Khỏe Đẹp Từ Gốc\n\nBí quyết giảm cân & làm chậm quá trình lão hóa thuần tự nhiên\n\n“Khỏe đẹp từ gốc” là hành trình chân thật và truyền cảm hứng của TS. Nguyễn Thu Hương – người phụ nữ từng thành công rực rỡ trong lĩnh vực truyền thông, từng trải qua những biến cố sức khỏe nghiêm trọng, và đã tìm lại chính mình nhờ vào việc thay đổi lối sống.\n\nCuốn sách được viết nên từ trải nghiệm thực tế, kết hợp với kiến thức y sinh học hiện đại, cùng kết quả thực nghiệm từ chương trình huấn luyện “SBody - Khỏe đẹp từ gốc” - nơi hàng trăm học viên đã trải qua những thay đổi tích cực về thể chất, tinh thần và thậm chí là “tuổi sinh học”.\n\nKhông đơn thuần là những lời khuyên, cuốn sách đưa ra các khái niệm khoa học nền tảng như: tuổi sinh học, hệ vi sinh đường ruột, ty thể, telomere, giấc ngủ, dinh dưỡng và stress - được trình bày dễ hiểu, gần gũi và tính ứng dụng cao vào thực tế sống.\n\nNHỮNG GIÁ TRỊ CỐT LÕI ĐÁNG GHI NHỚ\n\n- Thay vì chống lại lão hóa, hãy học cách đồng hành với tự nhiên bằng nội lực sinh học của chính mình.\n\n- Chúng ta không già đi vì số tuổi, mà vì lối sống sai lệch đang đẩy nhanh tốc độ lão hóa.\n\n- Cơ thể có thể được “tái thiết” từ gốc nếu bạn thay đổi: thói quen ăn uống – vận động – ngủ nghỉ – cảm xúc.\n\n- Giảm cân an toàn, làm chậm lão hóa và gia tăng năng lượng hoàn toàn có thể đạt được mà không cần dùng thuốc, nhịn ăn khắc nghiệt hay can thiệp thẩm mỹ.\n\nĐỐI TƯỢNG ĐỌC PHÙ HỢP\n\n- Phụ nữ sau tuổi 30–35, bắt đầu quan tâm đến sức khỏe bền vững và vóc dáng.\n\n- Người từng trải qua biến cố sức khỏe, đang tìm kiếm một “cách sống mới” lành mạnh hơn.\n\n- Doanh nhân, người làm việc căng thẳng cần cải thiện năng lượng và sức bền.\n\n- Bất kỳ ai muốn chủ động “trẻ hóa tuổi sinh học” và tái thiết cuộc sống từ thể chất đến tinh thần.\n\nĐây không chỉ là một cuốn sách về sức khỏe, mà là lời đánh thức cho những ai đang bỏ quên chính mình trên hành trình bận rộn. Hãy để “Khỏe đẹp từ gốc” trở thành kim chỉ nam cho bạn bắt đầu hành trình sống khỏe – sống đẹp – sống tỉnh thức.', 'kh_e_p_t_g_c_-_b_a_1_a41f68018e264b6d97dc89b4c103f686_medium.jpg'),
('DS065', 'Cẩm Nang Độ Dáng Tại Nhà', 2025, 299000.00, 'TG041', 'TL004', 'NXB002', 'Cẩm Nang Độ Dáng Tại Nhà\n\nBí quyết chinh phục bờ mông trái đào, thân hình săn chắc chỉ với các bài tập đơn giản và nguyên tắc ăn uống dễ áp dụng cho chị em bận rộn.\n\nQuyển sách này sẽ mang đến cho bạn:\n\n- Câu chuyện “lột xác” từ một cô gái “cò Hương” tự ti thành một huấn luyện viên thể hình truyền cảm hứng cho chị em phụ nữ\n\n- Kiến thức ai-cũng-cần-biết trước khi bắt đầu tập luyện\n\n- Hiểu biết cơ bản về dinh dưỡng giúp bạn ăn uống lành mạnh\n\n- Thực đơn và một số bài tập dành cho người cần tăng cân và giảm cân\n\n- Các bài tập hiệu quả giúp bạn có vòng 3 căng tròn và vòng 2 săn chắc.\n\nVới những câu chuyện tràn đầy năng lượng của chính tác giả, nội dung kiến thức dễ hiểu, dễ áp dụng, quyển sách sẽ truyền cho bạn nguồn động lực dồi dào để đứng lên, bắt đầu hành trình chinh phục thân hình trong mơ.', '9786047754601_de40e10bbdce41beab899f01e020779e_medium.jpg'),
('DS066', 'Đi Bộ Giảm Cân', 2024, 128000.00, 'TG042', 'TL004', 'NXB014', 'Đi Bộ Giảm Cân\n\nBẠN CŨNG CÓ THỂ TRỞ THÀNH SIÊU MẪU\n\nLàm sao để giảm cân được hiệu quả mà không áp lực? Siêu mẫu Kim Sarah, bậc thầy về giảm cân, cuối cùng đã tìm thấy câu trả lời sau nhiều thử nghiệm và sai lầm. Bí quyết giữ gìn vóc dáng của cô chính là ĐI BỘ GIẢM CÂN.\n\nĐể giúp bạn giảm cân thành công, sách cung cấp cho bạn:\n\nCông thức đi bộ giảm cân thành công\n\nCách chuẩn bị và các tư thế cơ bản\n\nChương trình giảm 5kg trong 8 tuần\n\nLiệu pháp ăn kiêng giúp bạn giảm cân thành công', '2024_11_13_16_47_48_1-390x510_9b947e38763e4860bd7ff2e534479e0c_medium.jpg'),
('DS067', 'Đẹp Đỉnh Cao Chao Đao Thế Giới', 2025, 105000.00, 'TG043', 'TL004', 'NXB014', 'Lời vàng từ những chị đại không chỉ nhắm đến nữ giới, mà nó phù hợp cho mọi thành phần độc giả, thuộc đủ mọi giới và xu hướng tính dục khác nhau. Trong ĐẸP ĐỈNH CAO CHAO ĐAO THẾ GIỚI, chúng ta sẽ được lắng nghe những lời vàng, những bài học xương máu từ những chị em từng trải của các thập niên trước, hay thậm chí từ những “tổ mẫu” của hàng thế kỷ trước. Qua đó, độc giả thấy được niềm kiêu hãnh và nỗ lực đấu tranh để phái nữ trở thành một giới tính ngang hàng với phái nam về mọi phương diện.\n\nMột số chủ đề mà các chị đại đề cập là:\n\nChuyện chia tay, hẹn hò, tình dục, cưới hỏi, điều kiện tiên quyết để có nụ hôn đầu hoàn hảo.\n\nNhững câu trích sắc sảo, thông thái và những mẩu chuyện sâu sắc từ chính cuộc đời của những chị em thành danh đi trước.\n\nNhững chủ đề chưa bao giờ hết nóng hổi: Kích cỡ là quan trọng; Khóc thành sông cùng những bộ phim tình cảm lãng mạn; Phụ nữ và cà phê đậm đặc…', 'image_223605_0724a78798da45d8b7805bbda0d0bfad_medium.jpg'),
('DS068', 'Green Smoothies - Giảm Cân, Làm Đẹp Da, Tăng Cường Sức Đề Kháng Với 7 Ngày Uống Sinh Tố Xanh', 2024, 159000.00, 'TG044', 'TL004', 'NXB002', '7 ngày uống sinh tố xanh giúp bạn giảm cân, giảm mỡ bụng, làm đẹp da, thanh lọc cơ thể, tăng cường sức đề kháng cho hệ miễn dịch, giúp cải thiện hệ tiêu hóa, hình thành thói quen ăn uống lành mạnh phòng chống bệnh tật.\n\nCuốn sách cung cấp 3 phương pháp uống sinh tố để bạn đạt được mục tiêu mong muốn:\n\n• Giảm 1.5-3kg trong vòng 7 ngày mà không cần tập luyện khi áp dụng phương pháp một ngày uống 3 ly sinh tố.\n• Giảm từ 1-2kg trong vòng 7 ngày bằng phương pháp 2 – uống 2 ly sinh tố xanh mỗi ngày và 1 bữa ăn chính.\n• Giữ cân và hình thành thói quen ăn uống tốt, cải thiện sức khỏe, tăng cường hệ miễn dịch bằng phương pháp 3 – mỗi ngày một ly sinh tố xanh.\nKhông chỉ mang đến cho bạn những công thức, phương pháp khoa học giúp bạn giảm cân, cuốn sách này còn chia sẻ danh sách mua sắm cũng như bí quyết giúp bạn có một làn da sáng mịn, cải thiện sức khỏe và hình thành một thói quen ăn uống tốt.', 'image_195509_1_37298_5417b41da6bf43678cffd0d03e3ca451_medium.jpg'),
('DS069', 'Những Lá Thư Cha Gửi Con', 2025, 179000.00, 'TG045', 'TL004', 'NXB005', 'Những Lá Thư Cha Gửi Con\n\nCuốn sách là một bản tuyên ngôn sâu sắc về nghĩa vụ, phẩm chất và trách nhiệm làm cha trong thời đại mới, nơi nuôi dạy con không chỉ là đảm bảo “đủ đầy” mà còn là sự hiện diện, tử tế, kỷ luật cảm xúc và tấm gương nhân cách.\n\nTập hợp hơn 40 lá thư và bài sẻ chia từ các người cha trên khắp tầng lớp xã hội: doanh nhân, luật sư, lãnh đạo, bác sĩ, vận động viên, nhà điều hành cấp cao. Cuốn sách mở ra một cái nhìn đa chiều, thực tế và chân thành về hành trình nuôi dưỡng một con người trưởng thành.\n\nCuốn sách này mang đến điều gì?\n\n- Những giá trị nền tảng mà một người cha cần trao truyền cho con: kỷ luật, chính trực, nhân ái, sức mạnh nội tâm.\n\n- Góc nhìn sâu sắc về tầm ảnh hưởng vô hình nhưng dài lâu của người cha trong quá trình hình thành nhân cách trẻ.\n\n- Bài học từ các người cha đã đi qua thành công, thất bại, va chạm và rút ra trí tuệ để đồng hành cùng con cái.\n\n- Một lộ trình trưởng thành cho chính người làm cha: không đòi hỏi sự hoàn hảo, mà là sự hiện diện và nhất quán.\n\n- Những trải nghiệm thực tế từ những người cha thuộc nhiều thế hệ, nhiều ngành nghề tạo nên bức tranh đa dạng nhưng thống nhất về một tình yêu có trách nhiệm.\n\nCuốn sách dành cho ai?\n\n- Những người đàn ông chuẩn bị làm cha mong muốn bước vào hành trình này với nhận thức vững vàng.\n\n- Các ông bố trẻ đang loay hoay giữa kỳ vọng, trách nhiệm và áp lực nuôi dạy con thời hiện đại.\n\n- Những gia đình muốn nuôi dưỡng mối quan hệ cha con sâu sắc, tôn trọng, đồng hành thay vì áp đặt.\n\n- Các nhà giáo dục, nhà tâm lý, cố vấn gia đình cần một tư liệu thực tế và nhân văn về vai trò người cha.\n\n- Những người mẹ muốn thấu hiểu hành trình nội tâm của người đàn ông trong sứ mệnh làm cha.', 'nhung-la-thu-cha-gui-con-01_d57e94e65ae942fa9239626e0b759c80_medium.jpg'),
('DS070', 'Một Đứa Trẻ Giàu Tâm Hồn Sẻ Trưởng Thành Như Thế Nào - Cẩm Nang Giúp Cha Mẹ Nuôi Con Hạnh Phúc', 2025, 279000.00, 'TG046', 'TL004', 'NXB004', 'Một Đứa Trẻ Giàu Tâm Hồn Sẻ Trưởng Thành Như Thế Nào - Cẩm Nang Giúp Cha Mẹ Nuôi Con Hạnh Phúc\n\n\"Cha mẹ tốt là người biết cách mở rộng trái tim mình để đón nhận tâm hồn con.\" Dù thời gian bên con có ngắn ngủi đến đâu, hãy khiến nó trở nên trọn vẹn! Cuốn sách giúp nuôi dưỡng tình yêu sâu sắc và tinh thần vững vàng trong trẻ.\n\nNhững nghiên cứu trong lĩnh vực tâm lý và giáo dục trẻ nhỏ cho thấy hạnh phúc của cha mẹ có mối liên hệ sâu sắc với hạnh phúc của con cái. Cuốn sách này giúp cha mẹ thấu hiểu rằng \"việc nuôi dạy con không phải là nỗ lực để hoàn thiện đứa trẻ, mà là hành trình hoàn thiện chính bản thân mình”. Đây là cuốn cẩm nang quý giá cho những ai muốn nuôi dạy con bằng tình yêu thương, sự thấu cảm và sự hiện diện trọn vẹn.\n\n- Kwon Eun-jin (Giáo sư Khoa Tâm lý học Phát triển, Đại học Sư phạm Quốc gia Seoul)\n\nCuốn sách này chứa đựng những lời khuyên thực tế giúp cha mẹ vượt qua lo lắng, tự ti và áp lực so sánh, từ đó xây dựng mối quan hệ tin tưởng và an toàn với con. Tác giả nhấn mạnh rằng cha mẹ không cần phải hoàn hảo, chỉ cần biết lắng nghe và thấu hiểu để cùng con lớn lên trong niềm tin và yêu thương.\n\n- Han Soo-jin (Giáo sư Khoa Tư vấn Tâm lý, Đại học nữ sinh Ewha)', '8935280920637_e4246711791d4119972b2c19648927c8_medium.jpg');
INSERT INTO `dausach` (`madausach`, `tensach`, `namxuatban`, `dongia`, `matacgia`, `matheloai`, `manxb`, `mota`, `anhbia`) VALUES
('DS071', 'Con Tha Thứ Rồi, Ba Mẹ Ơi! - I Forgive You, Mom And Dad!', 2025, 149000.00, 'TG047', 'TL004', 'NXB004', 'Con Tha Thứ Rồi, Ba Mẹ Ơi! - I Forgive You, Mom And Dad!\n\nNếu có một cuốn sách có thể khiến bạn dừng lại, hít một hơi thật sâu và thì thầm: “Giá như mình đọc nó sớm hơn…” thì đó chắc chắn là “Con tha thứ rồi, ba mẹ ơi!” – một cuốn sách song ngữ Việt – Anh, được in màu tuyệt đẹp với những minh họa đầy cảm xúc, khiến mỗi trang sách như một cánh cửa mở vào thế giới nội tâm của trẻ.\n\nĐây không phải một cuốn sách dạy làm cha mẹ kiểu khuôn mẫu. Không có phán xét, không dọa nạt tội lỗi, không đòi hỏi bạn phải trở nên hoàn hảo. Cuốn sách này giống như một bàn tay dịu dàng đặt lên vai bạn, và nói: “Ba mẹ à, mình có thể bắt đầu lại.”\n\nTừng trang sách là tiếng nói thật thà của một đứa trẻ tiếng nói mà rất nhiều người lớn chưa từng được nghe, hoặc đã quên mất rằng chính mình cũng từng mang trong lòng. Những lời thì thầm bé nhỏ ấy chạm vào những ký ức tưởng như đã ngủ quên: khoảnh khắc ta bị so sánh, bị mắng trước mặt người khác, bị bỏ quên trong sự bận rộn của người lớn, hay chỉ mong một cái ôm thay vì một câu gắt gỏng.\n\nVà rồi, bằng một sự tinh tế lạ kỳ, cuốn sách dẫn dắt người đọc bước ra khỏi những “vết thương không lời” để chạm đến vùng sáng của sự thấu hiểu nơi cha mẹ dần nhận ra rằng đằng sau mỗi hành vi tưởng như “khó chịu” của con là một nhu cầu chưa được nói thành lời; rằng con không hư, con chỉ chưa biết cách diễn đạt; rằng con không cần cha mẹ phải hoàn hảo, mà chỉ cần một trái tim thật lòng; và rằng tha thứ không phải là quên đi, mà là cơ hội để cả gia đình cùng học lại cách yêu thương, ấm áp và đúng cách hơn.\n\nNhững gợi ý thực hành trong sách đơn giản đến bất ngờ nhưng lại có sức mạnh chuyển hóa sâu sắc: một ánh mắt chú ý, 10 phút lắng nghe thật sự, một lời xin lỗi đúng lúc, hay chỉ là một cái ôm khi con không thể diễn đạt cảm xúc.\n\n“Con tha thứ rồi, ba mẹ ơi!” là tấm gương giúp bạn nhìn thấy đứa trẻ bên trong mình và cả đứa trẻ thật sự đứng trước mặt bạn mỗi ngày. Cuốn sách khiến bạn muốn ôm con mình hơn một chút, nói nhẹ nhàng hơn một chút, và yêu thương có ý thức hơn rất nhiều.\n\nNếu bạn từng mang những tổn thương tuổi thơ, hoặc từng vô tình làm con đau lòng, thì đây là hành trình chữa lành dành riêng cho bạn. Và nếu bạn đang làm cha mẹ trong thời đại bận rộn này, cuốn sách song ngữ – minh họa đầy cảm xúc này chính là thứ bạn nhất định phải có trên kệ sách của mình.', 'bia_con-tha-thu-roi-ba-me-oi_bia-1_2a5dbd6a223d428d80110e44825d8ceb_medium.jpg'),
('DS072', 'Thịnh Vượng Gia Tộc - Phương Thức Bảo Tồn Và Chuyển Giao Gia Sản Con Người, Trí Tuệ Và Tài Chính Qua Các Thế Hệ', 2025, 199000.00, 'TG048', 'TL004', 'NXB005', 'Cuốn sách này chắt lọc nội dung cốt lõi từ các cuốn sách trước của chúng tôi: Thịnh vượng gia đình (Family Wealth, 1997 và 2004), Gia đình: Khế ước giữa các thế hệ (Family: The Compact Among Generations, 2007), Chu trình của ban tặng (Cycle of the Gift, 2014), Tiếng nói của thế hệ đang lên (Voice of the Rising Generation, 2015) và Quỹ tín thác gia đình (Family Trusts, 2016). Nó cũng chứa những hiểu biết sâu sắc mà chúng tôi từng chia sẻ trên hàng loạt bài báo, sách trắng, bài đăng trên blog, cũng như trong những dịp thuyết trình trước hàng trăm khán giả. Chúng tôi đánh giá cao tất cả những điều mà mình học hỏi được từ nhiều độc giả đã nhận xét, bình luận về sách vở, bài viết trên blog và cả những ai đã tham dự các sự kiện trên.\n\nMỗi chương trong cuốn sách Thịnh vượng gia tộc đều gắn kết với các chương khác thông qua những chủ đề và khái niệm; đồng thời mỗi chương cũng có thể đọc tách rời. Các bạn có thể thoải mái chọn đọc bất kỳ chương nào, dựa trên chủ điểm có ý nghĩa quan trọng nhất với bạn và gia đình.\n\nTrong Thịnh vượng gia tộc, chúng tôi tìm cách trình bày những ý tưởng và thực hành có ý nghĩa lâu dài. Đó là những quan điểm sâu sắc và những hành động có thể tạo nên sự khác biệt tích cực thực sự cho đời sống của các gia đình trong dài hạn.  \n\nDưới đây là một vài nguyên tắc dài hạn đó, sẽ được phản ánh đầy đủ hơn trong các chương sách:\n\nMục đích không phải là “làm vô hiệu câu tục ngữ ‘không ai giàu ba họ, không ai khó ba đời’ để nhằm giữ gìn vốn tài chính trong gia đình. Vốn tài chính là quan trọng. Thế nhưng việc viện dẫn câu tục ngữ này có thể khiến nhiều độc giả nghĩ rằng chúng tôi dành sự chú ý cho vốn định tính chỉ gắn với vốn tài chính. Ngược lại mới là đúng.\n\nTuy chủ đề của chúng ta là về thịnh vượng “gia đình”, nhưng sự thịnh vượng này dựa trên các thành viên gia đình riêng lẻ. Mối quan hệ giữa họ có ý nghĩa trọng yếu, song sự vững mạnh của mối quan hệ này cũng như gia đình phụ thuộc vào sự vững mạnh của mỗi cá nhân liên quan.\n\nTrong khi “việc quản trị” (ra quyết định chung) có ý nghĩa quan trọng và đôi lúc bị bỏ qua, thì việc nhấn mạnh quá nhiều vào quản trị có thể dẫn đến tình trạng áp đặt “những kiểu cách” lên gia đình và làm cho nó không thể “hoạt động” được. Đôi khi các nhà tư vấn có thể trao cho gia đình bản dự thảo gia quy, việc làm này dễ dàng hơn so với việc giúp họ chung sống tốt đẹp. Thế nhưng việc soạn thảo gia quy, cho dù nó có được chấp nhận, cũng cần phục vụ được cho việc chung sống trong gia đình.', '8936219420037_34471be4c98144bb805894c78a2c0ff4_medium.jpg'),
('DS073', 'Chuyện Trò Cùng Con - Gieo Yêu Thương, Gặt Niềm Vui', 2025, 80000.00, 'TG049', 'TL004', 'NXB016', 'Chuyện Trò Cùng Con - Gieo Yêu Thương, Gặt Niềm Vui\n\nLà cha mẹ, chắc hẳn ai trong chúng ta cũng thường cảm thấy đau đầu bởi rõ ràng muốn thể hiện sự quan tâm dành cho con cái, thế nhưng nói một hồi lại thành những lời răn đe đầy tính sách vở và xa cách; mục đích ban đầu là muốn giúp con thay đổi những thói quen xấu, nhưng cứ hễ bắt đầu lại thành ra những lời mắng mỏ, trách cứ… Vậy vấn đề nằm ở đâu?', '8936238100712_5104ffe310ee4a2190ced07aec797664_medium.jpg'),
('DS074', 'Cha Mẹ Gen Y - Đồng Hành Cùng Con, Bắt Đầu Từ Việc Hiểu Chính Mình', 2025, 115000.00, 'TG050', 'TL004', 'NXB004', 'Cha Mẹ Gen Y - Đồng Hành Cùng Con, Bắt Đầu Từ Việc Hiểu Chính Mình\n\nXin chào mừng bạn đến với “Cha mẹ thế hệ Y”.\n\nChúng ta là những bậc cha mẹ thuộc thế hệ Y, một thế hệ đặc biệt sinh ra trong giai đoạn giao thoa giữa xã hội cũ và xã hội mới. Chúng ta lớn lên khi Internet mới bắt đầu xuất hiện, và trưởng thành cùng với sự bùng nổ của thông tin. Chúng ta là những nhân chứng lịch sử đã chứng kiến thế giới chuyển mình thành \"thế giới phẳng\" chỉ sau một đêm, và mỗi ngày thức dậy là một lần phải học cách thích ứng với những điều mới mẻ.\n\nThế hệ cha mẹ của chúng ta, những người lớn lên trong thời kỳ thiếu thốn, quen với việc \"không có\". Ngược lại, con cái của chúng ta lại sinh ra trong một thế giới \"có mọi thứ\", nơi smartphone và cuộc sống trực tuyến là một phần không thể tách rời. Chúng ta đứng ở giữa, như một cây cầu nối liền hai thế giới hoàn toàn khác biệt. Chính vị trí \"mắc kẹt\" này mang đến cho chúng ta không ít hoang mang, loay hoay và cả những mâu thuẫn nội tâm: vừa muốn thay đổi để tiếp nhận cái mới, lại vừa muốn giữ gìn những giá trị cũ đã gắn bó với mình.\n\nCuốn sách này không phải là một cẩm nang với những công thức tuyệt đối, mà là một lời tâm sự, một hành trình được ghi lại bởi một người mẹ cũng đang bước đi trên con đường đầy mới lạ và háo hức ấy. Đây là tập hợp những quan sát, những bài học được rút ra từ chính sự trưởng thành của bản thân và của con cái. Đó là hành trình chúng ta học cách chuyển đổi từ lối giáo dục \"áp đặt – phục tùng\" sang \"trao đổi – lắng nghe\", học cách quan sát con và quan sát chính mình để tìm ra gốc rễ của vấn đề.\n\nHành trình làm cha mẹ của thế hệ chúng ta đòi hỏi sự kiên nhẫn, chăm chỉ và một nhận thức luôn sẵn sàng thay đổi. Chúng ta sẽ cùng nhau tìm câu trả lời cho những trăn trở chung: Làm sao để trang bị cho con những kỹ năng cần thiết trong một thế giới bất định? Làm sao để giữ gìn những giá trị cốt lõi mà không ngại bước tiếp để chinh phục những đỉnh cao mới? Và quan trọng nhất, làm sao để trở thành những người đồng hành, cùng con trưởng thành trên con đường mà chính chúng ta cũng đang vừa đi vừa học hỏi?\n\nHy vọng rằng, cuốn sách này sẽ là một người bạn, một sự sẻ chia để bạn cảm thấy không đơn độc trên hành trình đầy ý nghĩa này.', 'cha-m_-gen-y_bia-1_3337e517d61a4a37b668fffb5f3808fb_medium.jpg'),
('DS075', 'Bé Khỏe Lại Ngay Đánh Ngay Bệnh Vặt', 2024, 189000.00, 'TG051', 'TL004', 'NXB002', 'Bé Khỏe Lại Ngay Đánh Ngay Bệnh Vặt\n\nKhi con trẻ đau ốm, cha mẹ nào cũng sốt ruột và nhiều khi bối rối không biết nên xử lý ra sao. Thực tế, những chứng bệnh thường gặp như cảm lạnh, ho, sốt, phát ban, rôm sảy… nếu được chăm sóc và điều trị đúng cách từ sớm sẽ giúp bé nhanh chóng hồi phục, tránh biến chứng và giảm thiểu dùng thuốc tây không cần thiết. Cuốn sách Bé khỏe lại ngay, đánh bay bệnh vặt chính là cẩm nang hữu ích, mang đến cho các gia đình những phương pháp y học cổ truyền đơn giản, an toàn và hiệu quả để xử lý bệnh thường gặp ở trẻ.\n\nTác giả Phạm Thánh Hoa đã dành hơn 20 năm nghiên cứu và thực hành lâm sàng, kết hợp tinh hoa từ các trước tác kinh điển như Tố Vấn, Linh Khu, Thương Hàn Luận, Kim Quỹ Yếu Lược cùng với kinh nghiệm thực tế để xây dựng hệ thống phương pháp toàn diện, dễ hiểu và dễ áp dụng. Điều đặc biệt là sách phân loại bệnh theo từng cơ quan phổi, tỳ vị, tim gan, thận, ngoài da, ngũ quan… giúp cha mẹ dễ dàng tra cứu và lựa chọn phương pháp phù hợp.\n\nTừ những trang đầu, bạn đọc sẽ thấy các hướng dẫn cụ thể khi trẻ bị cảm lạnh kèm sốt: từ cách nấu nước hành gừng để uống, ngâm chân với lá tía tô, xoa bóp các huyệt như Ngoại lao cung, Nhất oa phong, Thiên hà thủy… cho đến việc che thóp, dán rốn bằng Hoắc hương chính khí thủy hay tắm bằng thảo dược. Mỗi phương pháp đều đi kèm công dụng, chỉ định, lưu ý an toàn và mức độ dễ thực hiện tại nhà. Nhờ vậy, cha mẹ vừa có thể xử lý nhanh tình trạng bệnh, vừa an tâm khi biết rằng các phương pháp đều dựa trên nền tảng y học cổ truyền đáng tin cậy.\n\nKhông chỉ hữu ích với phụ huynh, cuốn sách còn là tài liệu quý giá cho các bác sĩ trẻ, sinh viên y học cổ truyền hay bất kỳ ai quan tâm đến chăm sóc sức khỏe tự nhiên. Với văn phong mạch lạc, hình ảnh minh họa chi tiết, Bé khỏe lại ngay, đánh bay bệnh vặt đã biến lý thuyết phức tạp thành những chỉ dẫn gần gũi, dễ tiếp cận. Đây không chỉ là một cuốn sách chữa bệnh, mà còn là một người bạn đồng hành cùng gia đình trên hành trình nuôi dưỡng, bảo vệ và nâng cao sức khỏe cho con trẻ.', '8935280920200_7c15538506eb4d64882049fb1c542f71_medium.jpg'),
('DS076', 'Con Tôi Bị Trầm Cảm, Nhưng Tôi Cứ Tưởng Con Chỉ Không Vui', 2025, 205000.00, 'TG052', 'TL004', 'NXB005', 'Con Tôi Bị Trầm Cảm, Nhưng Tôi Cứ Tưởng Con Chỉ Không Vui\n\nNhiều bậc cha mẹ không tin rằng con mình mắc trầm cảm, họ nghĩ con mình chỉ đang buồn bã vì cảm thấy quá áp lực. Trên thực tế, trầm cảm ở thanh thiếu niên không đơn thuần là tâm trạng thoáng qua mà là một tình trạng nghiêm trọng, ảnh hưởng sâu sắc đến cảm xúc, tư duy và hành vi của trẻ. Tuy nhiên, do thiếu kiến thức và hiểu biết trực quan, nhiều phụ huynh vô tình bỏ lỡ những dấu hiệu cảnh báo quan trọng.\n\nCuốn sách Con tôi bị trầm cảm, nhưng tôi cứ tưởng con chỉ không vui sẽ giúp cha mẹ nhận diện và phân biệt trầm cảm với những cảm xúc tiêu cực thông thường. Bên cạnh đó, sách còn cung cấp những hướng dẫn thiết thực: Làm sao để tương tác đúng cách mà không khiến con thêm tổn thương? Làm thế nào để thực sự thấu hiểu và đồng hành cùng con? Có những phương pháp nào giúp trẻ hồi phục? Nếu con tự làm tổn thương bản thân hoặc thậm chí có suy nghĩ tự tử, cha mẹ nên làm gì?\n\nKhông chỉ dừng lại ở việc nhận diện và xử lý, cuốn sách còn đi sâu vào cách phòng ngừa và hạn chế tái phát trầm cảm, giúp trẻ từng bước lấy lại sự cân bằng trong cuộc sống. Với những kiến thức khoa học và lời khuyên thực tế, đây sẽ là cẩm nang không thể thiếu dành cho mọi phụ huynh muốn bảo vệ sức khỏe tinh thần của con mình.', '8935235245754_9d9147bbabe6405983f66aa7ff059011_medium.jpg'),
('DS077', 'Rèn Luyện Não Bộ Cho Trẻ Thông Qua Trò Chơi Trí Tuệ', 2024, 159000.00, 'TG053', 'TL004', 'NXB013', 'Rèn Luyện Não Bộ Cho Trẻ Thông Qua Trò Chơi Trí Tuệ\n\nCuốn sách tập trung vào 12 tháng đầu đời của trẻ – giai đoạn não bộ phát triển nhanh nhất và nhạy cảm nhất. Mỗi trang sách đều gợi ý cho cha mẹ những trò chơi trí tuệ được thiết kế khoa học, đơn giản nhưng hiệu quả, gắn liền với từng cột mốc vận động:\n\n- 0–3 tháng: trò chơi với bàn tay, ngón chân, gương mặt… giúp kích thích giác quan và sự nhận biết.\n\n- 3–6 tháng: các trò chơi “ú òa”, “cù lét” kết hợp với thơ vần, giúp con rèn luyện phản xạ, ghi nhớ âm thanh, phát triển cảm xúc tích cực.\n\n- 6–9 tháng: trò chơi ngồi, lăn bóng, gõ nhịp… hỗ trợ khả năng vận động thô và sự phối hợp tay – mắt.\n\n- 9–12 tháng: trò chơi tập đứng, vỗ tay theo nhạc, xếp chồng khối… khơi gợi sự tò mò và kỹ năng giải quyết vấn đề.\n\nĐiểm nổi bật là mọi trò chơi đều gắn liền với những bài thơ ngắn dễ nhớ. Nhờ tiết tấu lặp lại, cha mẹ dễ dàng ngân nga cùng con, biến giờ chơi thành khoảnh khắc gắn kết tình cảm và nuôi dưỡng ngôn ngữ tự nhiên.', '-bo-cho-tre-thong-qua-tro-choi-tri-tue---full-----t5-2025---outline-02_d15dbbfee5d34bafa5447359d0ec5edc_medium.jpg'),
('DS078', 'Trò Chơi Tiến Hóa - Darwin’s Game - Tập 1 + Tập 2 (Bộ 2 Tập)', 2025, 139000.00, 'TG054', 'TL005', 'NXB006', 'Bộ Manga - Trò Chơi Tiến Hóa - Darwin’s Game - Tập 1 + Tập 2 (Bộ 2 Tập)\n\nBộ truyện tranh về đề tài sinh tồn – hành động cực kì đình đám “Darwin’s Game” đã chính thức ra mắt độc giả Việt Nam với 2 tập đầu đầy gay cấn, đừng bỏ lỡ!\n\nTập 1:\n\nNỗi bất an ngày một lớn dần...!\n\nAnh chàng Kaname vô tình khởi động một ứng dụng lạ mang tên “Darwin’s Game”. Ngay sau đó, một gã đàn ông mặc bộ đồ gấu trúc bất ngờ xuất hiện giữa đời thực và lao vào tấn công cậu…!?\n\nTrò chơi định mệnh chính thức bắt đầu!\n\nTập 2:\n\n“Sigil của cậu là gì?”\n\nBị cuốn vào một trò chơi bí ẩn, chàng trai Kaname quyết định tìm kiếm manh mối và chạm trán với “Nữ vương bất bại” Shuka.\n\nNăng lực đặc biệt của Kaname được kích hoạt! Luật chơi dần dần hé lộ!\n\nCâu chuyện giật gân đen tối đang từng bước nuốt chửng hiện thực, kéo tất cả vào một trận chiến không lối thoát!\n\nBộ truyện xuất sắc đạt các thành tích ấn tượng như:\n\n- Bán ra hơn 10 TRIỆU bản trên toàn cầu (tính đến 2024)\n\n- Đã chuyển thể anime và có thêm spin-off novel\n\n- Được mua bản quyền tại Pháp, Trung và nay là Việt Nam', '0444c3d83e12407ca6e357520648e918_bbf9382b26534f2e91513e494d35de93_medium.png'),
('DS079', 'Bộ Manga - Gấu Trúc Đi Lạc - Tập 1 + Tập 2 (Bộ 2 Tập)', 2024, 160000.00, 'TG055', 'TL005', 'NXB007', 'Bộ Manga - Gấu Trúc Đi Lạc - Tập 1 + Tập 2 (Bộ 2 Tập)\n\nMải mê cày game đến khuya, Ume - một nữ sinh trung học bình thường, chạy đến trường bằng cả tính mạng vì sợ bị trễ học. Trên đường đi, ánh mắt cô lỡ va phải một anh chàng đẹp trai ở trên cầu, và cùng lúc đó một tên côn đồ mải nhìn điện thoại đã đụng trúng người cô. Cô cứ tưởng mình sẽ bị tên côn đồ xạc cho một trận tơi bời, ai dè hắn ta lại quay sang… mắng anh đẹp trai?! Trong lúc cô đang khó hiểu thì người kia đã “xử đẹp” tên côn đồ và lạnh lùng bỏ đi mất. Vậy là cô đã được anh đẹp trai nọ cứu một mạng?!\n\nNhưng tình huống tệ nhất đã xảy đến ngay sau đó, khi một sự cố ngoài ý muốn xảy ra khiến cô bị ngã từ trên cầu xuống. Không biết bằng một phép màu nào, lúc tỉnh dậy sau cú ngã chấn động, cô lại thấy mình đang nằm trên giường của anh đẹp trai vừa gặp, còn bị anh chàng “trần như nhộng” đó nắm đầu?! Cô bàng hoàng nhận ra mình đã biến thành một chú gấu trúc bông từ lúc nào chẳng hay, và chủ nhân của chú gấu bông đó chính là anh chàng đẹp trai đã cứu cô, tên là Kaede.\n\nMất sạch mọi ký ức trước kia, không biết cách trở về, thậm chí còn không nhớ nổi tên mình, cô đành phải ở lại nhà của Kaede dưới hình dạng con thú nhồi bông kỳ lạ. Ume phải làm sao để được trở về hình dáng thật, và liệu nhân duyên trời định giữa cô và Kaede có ẩn chứa bí mật gì đằng sau hay không?', '1-_12__7f3f1a335bf7462f8225e9c6ffec8d6a_medium.jpg'),
('DS080', 'Studio Cabana - Bản Tình Ca Cho Em - Tập 4', 2025, 89000.00, 'TG056', 'TL005', 'NXB013', 'Studio Cabana - Bản Tình Ca Cho Em - Tập 4\n\nĐêm hội mùa hè đã góp phần thúc đẩy mối quan hệ của Yukari và Yusuke thêm phần tốt đẹp. Yusuke đã quyết định không mang sửa chiếc điện thoại đang hỏng của mình để cắt đứt liên lạc với Haruki.\n\n“Chỉ là... Tôi đã mệt mỏi rồi...”\n\nSau một khoảng thời gian không thể liên lạc với Yusuke, Haruki cuối cùng cũng nhận ra có gì đó bất ổn nơi trái tim mình.', 'tbph4_24237914a9274cbaa18fe8671624ed83_medium.jpg'),
('DS081', 'Studio Cabana - Bản Tình Ca Cho Em - Tập 3', 2025, 83000.00, 'TG056', 'TL005', 'NXB013', 'Studio Cabana - Bản Tình Ca Cho Em - Tập 3\n\nTrong lúc Yusuke còn đang vật lộn với mối quan hệ bế tắc nơi Haruki, thì mặt khác tình yêu Yukari dành cho cậu đang ngày một lớn hơn.\n\nTôi muốn nhiều hơn hai chữ “Bạn bè”...\n\nKhi tình cảm của cả hai dần ổn định, Yukari đã mời Yusuke đến lễ hội mùa hè và dùng hết can đảm để nắm chặt lấy đôi bàn tay cậu...', 'tbph1_7_1967e12b53c04d17877cb9270dc62a8d_medium.jpg'),
('DS082', '\n Vui Được Ngày Nào Hay Ngày Nấy \nVui Được Ngày Nào Hay Ngày Nấy', 2024, 195000.00, 'TG057', 'TL005', 'NXB007', 'Vui Được Ngày Nào Hay Ngày Nấy\n\nCuốn truyện tranh nạp năng lượng tích cực cho mình mỗi ngày!\n\nNếu bạn đang tìm một cuốn sách khiến mình vừa đọc vừa bật cười như đang tám chuyện cùng hội bạn thân, thì “VUI ĐƯỢC NGÀY NÀO HAY NGÀY NẤY” chính là lựa chọn hoàn hảo.\n\nTác giả Tiểu Lam và những người bạn - người đứng sau loạt truyện tranh xinh xắn, hài hước và cực kỳ “bắt trúng tim đen” độc giả lại tiếp tục mang đến tuyển tập những mẩu chuyện ngắn đầy dí dỏm về cuộc sống thường ngày. Từ việc vì sao phải uống nhiều nước, nếu không dọn rác mỗi ngày sẽ ra sao, cho đến nỗi khổ muôn thuở mang tên “bán mình cho tư bản”, tất cả đều được kể lại bằng nét vẽ tinh nghịch và giọng kể duyên dáng đặc trưng.\n\nĐiều tuyệt nhất trong cuốn sách này là tác giả đã biến mọi thứ tưởng chừng khô khan – kể cả kiến thức khoa học – thành câu chuyện siêu dễ hiểu, vừa đáng yêu vừa sâu sắc. Mỗi trang truyện như một liều vitamin vui vẻ, khiến người đọc mỉm cười nhẹ nhõm giữa guồng quay bận rộn.\n\n“Vui được ngày nào hay ngày nấy” không chỉ là cuốn truyện để giải trí, mà còn là lời nhắc nhỏ: Cuộc sống có thể mệt, nhưng niềm vui vẫn luôn ở quanh ta, chỉ cần ta chọn mỉm cười.', 'vui_d__c_ng_y_n_o_hay_ng_y__y_4ab6341ff9af4f0b90737a741789c00c_medium.jpg'),
('DS083', 'Cuộc Nổi Dậy Của Cô Nàng Mọt Sách', 2025, 98000.00, 'TG058', 'TL005', 'NXB007', 'Một nữ sinh viên không may qua đời đã được tái sinh thành con gái của một binh sĩ. Nhưng than ôi, vốn là mọt sách chính hiệu, cô nàng lại đến một thế giới có tỷ lệ biết chữ thấp và khan hiếm sách vở. Không có sách thì phải làm sao đây? Thì tự làm ra chứ sao! Với ước mơ trở thành thủ thư và được sống trong “biển sách”, cô bắt đầu từ việc cơ bản nhất – tự làm ra sách. Tác phẩm này là thế giới biblia-fantasy do mọt sách tạo ra vì mọt sách!', 'motsach-adv_c8e93efc38c44f3b9deffb89e0ad94c6_medium.png'),
('DS084', '13 Giờ Sáng - Khung Giờ Vô Thực Cho Những Câu Chuyện Bất Thường', 2025, 88000.00, 'TG059', 'TL005', 'NXB007', '13 Giờ Sáng - Khung Giờ Vô Thực Cho Những Câu Chuyện Bất Thường\n\nTrong cuộc sống hằng ngày vẫn luôn tồn tại những câu chuyện kì lạ xảy ra xung quanh ta. Những hình ảnh thoáng qua mà ta bắt gặp khi đi trên đường, những giấc mơ không đầu không cuối, những gương mặt ta gặp gỡ hằng ngày… tất cả tưởng chừng như vô thưởng vô phạt nhưng lại ẩn giấu những chuyện bất thường. “13 giờ sáng” là tuyển tập ghi lại một phần những câu chuyện kì bí, không thể lý giải bằng lẽ thường đó.\n\nTiếng mèo kêu giữa đêm là chuyện bình thường hay báo hiệu cho điều lạ thường nào đó?\n\nTại sao mỗi khi nhìn thấy ma-nơ-canh, nhiều người lại có cảm giác khó chịu đến rùng mình?\n\nLiệu người ở cùng phòng trọ với bạn có thật sự là người mà bạn vẫn gặp hằng ngày?\n\nVà con chó đội nón mê có thật sự đem tới tai ương như người ta đồn thổi hay là một điều kì bí chưa ai biết đến?\n\nMời bạn cùng đón đọc “13 giờ sáng” với 17 mẩu truyện ngắn mang đến những cung bậc cảm xúc khác nhau, từ hài hước, bí ẩn, rùng rợn cho đến kinh ngạc và cảm động.', '_nh-ttph-2---13-gi_-s_ng_225460a2a47e4824a80442b92db494d4_medium.jpg'),
('DS085', 'Tanukoi - Mùa Xuân Của Tanuki - Tập 2', 2025, 71000.00, 'TG060', 'TL005', 'NXB007', 'Tanukoi - Mùa Xuân Của Tanuki - Tập 2\n\nTanukoi - Mùa xuân của Tanuki 2 - Thiếu nữ và Tanuki\n\nYuzu, cô nữ sinh hậu đậu sống yên bình nơi thị trấn hẻo lánh ba mặt giáp núi rừng, dần nhận ra rằng tình cảm mình dành cho Akane - cậu bạn đẹp trai cô mới quen gần đây, đã vượt xa những rung động ban đầu. Sau nhiều lần gặp gỡ, cả hai cuối cùng cũng có buổi hẹn hò đầu tiên đầy ngọt ngào. Thế nhưng, một tình huống khẩn cấp đã xảy ra! Tiếng còi xe bất ngờ khiến Akane giật mình hiện nguyên hình, hóa ra đó chính là chú tanuki mà Yuzu từng che mưa cho trong ngày mưa lúc trước.\n\nChẳng những không sợ hãi, mà Yuzu còn xúc động trước tình cảm khăng khít giữa các thành viên trong gia đình kỳ lạ của Akane. Nghe theo trái tim mách bảo, cô bé mạnh mẽ khẳng định tình cảm của mình dành cho Akane, dù cho cậu không phải người bình thường đi chăng nữa. Cùng lúc ấy, Yuzu đang đứng trước ngưỡng cửa đại học và nỗi băn khoăn về tương lai, trong khi Akane vẫn chưa thể thích nghi hoàn toàn với cuộc sống loài người để đồng hành cùng cô.\n\nGiữa những trắc trở ấy, liệu câu chuyện tình “gà bông” giữa thiếu nữ và Tanuki có thể đơm hoa kết trái? Hãy cùng đón đọc “Tanukoi - Mùa xuân của Tanuki” tập 2 để tiếp tục theo dõi hành trình yêu đương vừa trong trẻo vừa lạ lùng của họ nhé!', '_nh-ttph-1---tanukoi-2_c0b052668ffa4980a0bfdbb55d7aef1a_medium.jpg'),
('DS086', 'Bộ Manga - Sa Vào Lưới Tình Với Shiina: Tập 1 - 3 (Bộ 3 Tập)', 2025, 258000.00, 'TG061', 'TL005', 'NXB007', 'Bộ Manga - Sa Vào Lưới Tình Với Shiina: Tập 1 - 3 (Bộ 3 Tập)\n\nShiina chưa bao giờ nghĩ cuộc đời mình là một cuốn tiểu thuyết lãng mạn, mà nó giống một đường đua marathon trả nợ không hồi kết hơn. Sau sự ra đi của cha mẹ, gánh nặng chăm sóc một bầy em nhỏ và khoản nợ khổng lồ đã đè nặng lên vai cô chị cả. Cô phải cật lực làm việc không ngừng nghỉ, bán mình cho tư bản tại một công ty sản xuất game lớn để kiếm tiền.\n\nNiềm an ủi hiếm hoi của Shiina chính là chàng idol đẹp trai hoàn hảo trong game điện thoại mà cô cày cuốc ngày đêm. Và tình cờ làm sao, ở nơi cô làm việc lại xuất hiện một người đàn ông cũng đẹp trai không kém, chính là Giám đốc điều hành của cô, đồng thời còn là người đã tạo ra trò chơi ấy.\n\nMọi chuyện thay đổi khi vận rủi ập đến. Sếp tình cờ phát hiện ra bí mật động trời của Shiina: cô lén lút làm thêm công việc khác ngoài giờ, một điều bị cấm tuyệt đối trong hợp đồng. Thay vì đối mặt với kết cục bi thảm là bị đuổi việc và mất đi nguồn thu nhập chính, Shiina lại nhận được một đề nghị không tưởng:\n\n\"Tôi sẽ giữ kín bí mật này. Đổi lại, cô phải trở thành người giúp việc riêng cho tôi.\"\n\nBị đẩy vào một mối quan hệ bất đắc dĩ, Shiina phải bước vào thế giới riêng của vị giám đốc bí ẩn. Cô sẽ phải vừa che giấu thân phận, vừa đối phó với những tình huống dở khóc dở cười, và quan trọng hơn cả, làm cách nào cô có thể giữ trái tim mình không rung động trước người sếp \"tai quái\" này – người lại có những nét giống với idol trong game của cô một cách đáng kinh ngạc? Liệu đây sẽ là khởi đầu của một câu chuyện tình yêu hay chỉ là một giao kèo đầy rắc rối.', 'z7147353759741_d898e1b78fb668f3e7b05d94a67df463_e22c0c6897984ab4a9e7a562f77aca2f_medium.jpg'),
('DS087', 'Ánh Sao Bên Tôi - Tập 3', 2025, 175000.00, 'TG062', 'TL005', 'NXB010', 'Ánh Sao Bên Tôi - Tập 3\n\nĐôi bạn thân Tư Nam - Tử Tinh từ nhỏ đã dính nhau như sam nay lại ngượng ngập tránh mặt bởi những cảm xúc kỳ lạ ập đến. Cô bạn Tinh Tinh chẳng biết phải làm sao nên chỉ còn cách... “né” người bạn thân từ thuở nhỏ, dù trong lòng thì vẫn nhớ cậu ấy không ngừng.\n\n“Cậu ấy chưa bao giờ nhắc đến từ “thích” trước mặt tớ mà…”\n\nNhận thấy tín hiệu bất ổn của hai người, cô bạn thân Quách Lâm Lâm đành phải hóa thân thành quân sư tình yêu - nỗ lực kéo hai kẻ ngốc dễ thương ấy lại gần nhau. Đồng thời, đúng lúc đó, một nhân vật mới xuất hiện, khơi dậy những ký ức cũ và bí mật trong quá khứ của Lâm Lâm…\n\nLiệu đứng trước những rung động đầu đời, họ sẽ đối mặt với cảm xúc thật của mình ra sao?\n\nÁnh Sao Bên Tôi – Tập 3 sẽ là chuyến tàu cảm xúc nhẹ nhàng nhưng đầy xao xuyến, nơi tuổi trẻ, tình bạn và tình yêu hòa vào nhau như bầu trời đầy sao.', 'z7115361916121_5de75eeaaf3498cef8ddcf3d9abd6cae_813b9af4f57d4046b6f643f08f8af192_medium.jpg'),
('DS088', 'Thị Trấn Hoa Mười Giờ - Tập 1', 2023, 75000.00, 'TG063', 'TL005', 'NXB010', 'Truyện tranh Thị Trấn Hoa Mười Giờ tập 1', 'image_223631_4e39ab4d37494885b4623f1c5d27707b_medium.jpg'),
('DS089', 'Thị Trấn Hoa Mười Giờ - Tập 2', 2023, 75000.00, 'TG063', 'TL005', 'NXB010', 'Truyện tranh Thị Trấn Hoa Mười Giờ tập 2', '22458eb1cbb90384e3fdc876eabf8ec9_48b559fa9eb4424a94347f886a7cc5aa_medium.jpg'),
('DS090', 'Thị Trấn Hoa Mười Giờ - Tập 3', 2023, 80000.00, 'TG063', 'TL005', 'NXB010', 'Truyện tranh Thị Trấn Hoa Mười Giờ tập 3', 'z3825076506886_c56b80d01335e3353dd3c6c9723b73d1_5f8ee1af29414aa28b9fb484f087a851_medium.jpg'),
('DS091', 'Thị Trấn Hoa Mười Giờ - Tập 4', 2024, 80000.00, 'TG063', 'TL005', 'NXB010', 'Truyện tranh Thị Trấn Hoa Mười Giờ tập 4', 'tthmg4_biatruoc_636e25a42dd44c52a244a939d615c072_medium.jpg'),
('DS092', 'Vạn Nhân Ký - Noãn', 2023, 120000.00, 'TG064', 'TL005', 'NXB016', 'Tiểu thuyết/Truyện tranh Vạn Nhân Ký', 'z6401500381656_476cf6fb6d7de9f300ef530d45affba6_b484932e98d74a1193c3a54cdfdf6cea_medium.jpg'),
('DS093', 'Cú Mèo', 2022, 95000.00, 'TG063', 'TL005', 'NXB010', 'Truyện tranh Cú Mèo của tác giả Phan', 'image_200739_df52fd9a666e4ffda5dd551fdb9dbd7a_medium.jpg'),
('DS094', 'Lột Được Vỏ Chanh Mở Được Tiệm Nail', 2023, 115000.00, 'TG065', 'TL005', 'NXB010', '17 câu chuyện vượt gian khó và vận hành tiệm nail thành công', '976000181590c58ee7161a87ce46ac3c_13464cb74c7646408146a6f368b9c20f_medium.jpg'),
('DS095', 'Making Comics - Sáng Tác Truyện Tranh', 2024, 185000.00, 'TG066', 'TL005', 'NXB001', 'Bí quyết chinh phục comic, manga và graphic novel', '20250420-makingcomics-bia-taygam5cm-bleed2mm-v2-1-1746239187672_1fe4e620960e475b88613b08535f0e93_medium.jpg'),
('DS096', 'Cuộc Sống Nhiệm Màu Của Mèo Trắng - Tập 1', 2023, 85000.00, 'TG063', 'TL005', 'NXB010', 'Truyện tranh: Cuộc sống nhiệm màu của Mèo Trắng tập 1', '2023_09_18_16_46_05_1-390x510_f5fabc3fc88849e29b92c90253ecb452_medium.jpg'),
('DS097', 'Cuộc Sống Nhiệm Màu Của Mèo Trắng - Tập 2', 2023, 85000.00, 'TG063', 'TL005', 'NXB010', 'Truyện tranh: Cuộc sống nhiệm màu của Mèo Trắng tập 2', '9786043267402_1d2aef4868754aa7a713390554d71972_medium.jpg'),
('DS098', 'Cuốn Sách Này Dành Cho Bạn', 2022, 95000.00, 'TG067', 'TL005', 'NXB010', 'Hy vọng bạn cảm thấy lên tinh thần đôi chút', 'z3825076502989_f8f6dd0044220d7f18020fa082aa8ea9_e079e12609b34f388e24e575049a55ba_medium.jpg'),
('DS099', 'Ế Có Khi Nào?', 2022, 80000.00, 'TG068', 'TL005', 'NXB010', 'Truyện tranh hài hước Ế có khi nào? - Tác giả Sói Ăn Chay', 'image_200743_1_ed4dbc0ea17a41b1a0936dfc335d68f8_medium.jpg'),
('DS100', 'Gửi Em', 2021, 90000.00, 'TG069', 'TL005', 'NXB010', 'Tác phẩm Gửi Em - Tác giả Lê Thư', '9786049799013_03ad8de11cd44abda1f509d57091aae1_medium.jpg'),
('DS101', 'Origami - Động Vật Trên Cạn (Land Animals)', 2020, 120000.00, 'TG070', 'TL005', 'NXB010', 'Hướng dẫn gấp giấy nghệ thuật Origami chủ đề động vật trên cạn', 'image_200736_624871e74d704dbe8d5330a9dba14be6_medium.jpg'),
('DS102', 'Bạn Là Ai Và Làm Thế Nào Để Sống Tốt Hơn', 2023, 120000.00, 'TG071', 'TL006', 'NXB001', 'Loại tính cách giúp bạn nhận ra mình và sống tốt hơn', 'p90555mban_la_ai_va_lam_the_nao_01_52140f0f709f4d4ca73e4e8a3aa85052_medium.jpg'),
('DS103', 'Không Sợ Hãi (Be Fearless)', 2022, 135000.00, 'TG072', 'TL006', 'NXB002', 'Năm nguyên tắc kiến tạo một cuộc đời phi thường và ý nghĩa', 'p90612mscan0001_03f514261bb04b57b0c9b7602660964e_medium.jpg'),
('DS104', 'Làm Chủ Bình Minh Sống Đời Xuất Chúng', 2021, 150000.00, 'TG073', 'TL006', 'NXB001', 'Câu lạc bộ 5 giờ sáng (The 5 AM Club)', 'p91284mlam_chu_020545354c9a40eba052bc3195fad6d2_medium.jpg'),
('DS105', '10 Bước Đến Thành Công (The Success Factor)', 2020, 95000.00, 'TG074', 'TL006', 'NXB004', 'Làm chủ bí quyết tư duy của người chiến thắng', 'upload_0e076f4989be4e7fb9b0f5b5402b9068_medium.jpg'),
('DS106', 'Tất Cả Chỉ Tại Cái Đầu', 2022, 110000.00, 'TG075', 'TL006', 'NXB005', 'Cẩm nang để cầm cương những cảm xúc bất kham', 'p91366mtat_ca_chi_tai_cai_dau_01_6805018173ea42eca86386b53b650666_medium.jpg'),
('DS107', 'Quy Tắc 5 Giây', 2023, 85000.00, 'TG076', 'TL006', 'NXB010', '50 thói quen tư duy giúp vươn lên làm người vượt trội', '8935074127150_617b9f59e3a6428c985f89fc04191083_medium.jpg'),
('DS108', 'Ứng Biến Giữa Đời Vạn Biến (Go With It)', 2021, 105000.00, 'TG077', 'TL006', 'NXB010', 'Chấp nhận điều không mong đợi để thay đổi cuộc đời', 'p91491mung_bien_giua_doi_van_bien_1595906917_b020fb654f1c4bbb9340520d50155058_medium.jpg'),
('DS109', 'Tuổi Già Thanh Thản (Aging For Beginners)', 2020, 125000.00, 'TG078', 'TL006', 'NXB008', 'Thiền định chiêm nghiệm cho cuộc sống viên mãn', 'p91494mtuoi_gia_thanh_than_xp_01_1592447182_d7ccd3e0d67648c69b42bf51ece43a97_medium.jpg'),
('DS110', 'Buông Để Được (Let Go)', 2021, 115000.00, 'TG079', 'TL006', 'NXB005', 'Sống tự do không gánh nặng', 'p91495mbuong_de_duoc_xp_01_1592444720_2ab30f17b1e44085aa02c3c94c19fb09_medium.jpg'),
('DS111', 'Nói Hay Là Phải Hài', 2023, 90000.00, 'TG080', 'TL006', 'NXB012', 'Kỹ năng giao tiếp và ứng xử hài hước', '8935074131164_23f1bbb7b18d467f9362d6bf83b251ab_medium.jpg'),
('DS112', 'Dám Bị Ghét', 2022, 115000.00, 'TG081', 'TL006', 'NXB010', 'The International Bestseller - Bài học từ tâm lý học Adler', 'yho27ekd_d5401f04b61e4476a5c57c3fe13d572d_medium.jpg'),
('DS113', 'Trở Thành Người Đáng Giá Nhất', 2023, 145000.00, 'TG082', 'TL006', 'NXB001', 'Những kinh nghiệm đắt giá để trở thành người được chọn từ giảng đường đến thương trường', 'tro-thanh-nguoi-dang-gia-nhat---bia-1_b86e0643f4ab48a3963379c54dd402e2_medium.jpg'),
('DS114', 'Tác Động Tinh Gọn', 2022, 135000.00, 'TG083', 'TL006', 'NXB001', 'Cách đổi mới để mang lại lợi ích xã hội lớn hơn', 'tac-dong-tinh-gon---bia-1_dd57ff74b0514df9b37ea388be08e64e_medium.jpg'),
('DS115', 'Người Nam Châm - Bí Mật Của Luật Hấp Dẫn', 2021, 85000.00, 'TG084', 'TL006', 'NXB005', 'Khám phá bí mật của Luật hấp dẫn để thành công', 'nguoi-nam-cham-_tb-2025_--bia-1_e907a33d266c435ca692b459b2cb5d76_medium.jpg'),
('DS116', 'Càng Dịu Dàng, Càng Đắt Giá (The Worth Of Gentleness)', 2023, 95000.00, 'TG085', 'TL006', 'NXB005', 'Tuyển tập trích dẫn song ngữ Việt - Anh truyền cảm hứng', 'bia-1---cang-diu-dang-cang-dat-gia_9efda0f6c4c84721a84702062d9ab74d_medium.jpg'),
('DS117', 'Sự Dung Dị Của Ngôn Từ', 2022, 110000.00, 'TG086', 'TL006', 'NXB010', 'Nghệ thuật giao tiếp tinh tế và chữa lành', '2_19_41_d644c49a31454836ae70bf12462b2340_medium.jpg'),
('DS118', 'Sức Mạnh Của Kỷ Luật Bản Thân', 2023, 125000.00, 'TG087', 'TL006', 'NXB005', 'Duy trì động lực, kỷ luật và nâng cao khả năng kiểm soát cảm xúc', 'b_a-1---s_c-m_nh-c_a-k_-lu_t-b_n-th_n_fff2a086cc8543f18c8e7e926d5f96fe_medium.jpg'),
('DS119', 'Bậc Thầy Quản Lý Thời Gian', 2023, 130000.00, 'TG088', 'TL006', 'NXB012', 'Chiến lược để có nhiều thời gian hơn người khác gấp 10 lần', 'b_a-1_b_c-th_y-qu_n-l_-th_i-gian_7f4d339f18fa43e6aebc0087db943988_medium.jpg'),
('DS120', 'Làm Việc Với Người Khó Tính', 2021, 90000.00, 'TG089', 'TL006', 'NXB014', 'Biến thù địch thành thân hữu nơi công sở', '8935073103100_f905bc207afc462ba4d7cf009b3e76ab_medium.jpg'),
('DS121', 'Cờ Đỏ Cờ Xanh (Red Flags Green Flags)', 2024, 155000.00, 'TG090', 'TL006', 'NXB005', 'Tâm lý học hiện đại giải mã drama cuộc sống', '8935235246874_669db2f52b53439babf6c2879f85c76b_medium.jpg'),
('DS122', '1500 Từ Vựng Dành Cho Kỳ Thi Năng Lực Nhật Ngữ N4', 2022, 120000.00, 'TG091', 'TL007', 'NXB005', 'Sách học 1500 từ vựng luyện thi tiếng Nhật N4', 'n4_e73a500d9f0c4616af73c3849dd1cba9_medium.jpg'),
('DS123', 'Nihongo So-matome N4 Kanji - Từ Vựng', 2021, 130000.00, 'TG092', 'TL007', 'NXB005', 'Luyện thi năng lực Nhật ngữ N4 Kanji và Từ vựng', 'han_a5b83f1018d04c4f80503f8a0c5812a8_medium.jpg'),
('DS124', '10 Phút Tự Học Tiếng Anh Mỗi Ngày', 2023, 85000.00, 'TG093', 'TL007', 'NXB007', 'Sách tự học tiếng Anh giao tiếp mỗi ngày', '8935236434287_923eda4e91434a0293edd2bb53fdad7c_medium.jpg'),
('DS125', 'Chém Tiếng Anh Không Cần Động Não', 2023, 150000.00, 'TG094', 'TL007', 'NXB005', 'Học tiếng Anh giao tiếp thực chiến cùng BINO', 'chemta-bino_bia1_436d844dd64c4fa39b8eb7c7b920c13a_medium.jpg'),
('DS126', 'Tự Học Đàm Thoại Tiếng Anh Công Sở', 2022, 90000.00, 'TG095', 'TL007', 'NXB006', 'Sách đàm thoại tiếng Anh dành cho người đi làm', 'image_241326_e5a7dd93d3d94468829a8c0a61588499_medium.jpg'),
('DS127', 'Tự Học Tiếng Anh Trong 24 Ngày', 2021, 80000.00, 'TG096', 'TL007', 'NXB014', 'Cẩm nang bỏ túi cho người mới bắt đầu học tiếng Anh', '8935074132215_99b9609e4b124ea6955f1f7129642a2e_medium.jpg'),
('DS128', 'Tiếng Hàn Tổng Hợp Dành Cho Người Việt Nam - Sách Bài Tập 1', 2022, 110000.00, 'TG097', 'TL007', 'NXB007', 'Sách bài tập tiếng Hàn tổng hợp sơ cấp 1', 'sach-tieng-han-tong-hop-danh-cho-nguoi-viet-nam-sach-bai-tap-so-cap-1_e6f98d8457c04712a18a7f269d9c7113_medium.jpg'),
('DS129', 'Thuyết Trình Tiếng Anh', 2023, 95000.00, 'TG098', 'TL007', 'NXB005', 'Kỹ năng thuyết trình tiếng Anh hiệu quả', '43_159ad1c825844fbbbc518a4989915052_medium.jpg'),
('DS130', 'IELTS Writing Journey Elevate To Band 8.0', 2024, 180000.00, 'TG099', 'TL007', 'NXB005', 'Hướng dẫn tự học IELTS Writing đạt band 8.0', '8794069304781_1_7462fcbf72814588beccce549a200c9a_medium.jpg'),
('DS131', 'Tự Học Ngữ Pháp Tiếng Anh Cơ Bản', 2023, 105000.00, 'TG100', 'TL007', 'NXB006', 'Sách hướng dẫn tự học ngữ pháp tiếng Anh hiệu quả', 'untitled__78__2d45f64e5e15451f935b1b84bb86f614_medium.jpg'),
('DS132', 'Chiêm Tinh Học - Ứng Dụng Trong Sự Nghiệp Và Tình Yêu', 2022, 135000.00, 'TG101', 'TL007', 'NXB005', 'Hướng dẫn ứng dụng chiêm tinh học vào phát triển sự nghiệp và thấu hiểu tình cảm.', 'p97251m8935246933459_55ec2629361647d2825ad72ad6c0b028_medium.jpg'),
('DS133', 'Người Giàu Có Nhất Thành Babylon', 2023, 98000.00, 'TG102', 'TL007', 'NXB001', 'Cuốn sách kinh điển về bí quyết làm giàu, tiết kiệm và quản lý tài chính cá nhân.', 'p97362m8935246937525_1_1da106c981c24a4d9571e8fc9eb88e3a_medium.jpg'),
('DS134', 'Từ Điển Song Ngữ Qua Tranh Cho Bé (Hộp 6 Cuốn)', 2024, 250000.00, 'TG103', 'TL007', 'NXB012', 'Bộ từ điển bằng tranh song ngữ Anh - Việt sinh động dành cho trẻ từ 0-6 tuổi.', 'p97044m8935246936115_f42f24b9f8a74ce8a944f7857ed81ac9_medium.jpg'),
('DS135', 'Go For No! Đập Tan Nỗi Sợ Bị Từ Chối', 2021, 115000.00, 'TG104', 'TL007', 'NXB004', 'Chiến lược thay đổi tư duy, vượt qua sự từ chối để vươn tới thành công trong bán hàng.', 'p97211m8935246936795_42d0a469099a43ccb665e385c53c891b_medium.jpg'),
('DS136', 'Chinh Phục Công Thức Viết Đoạn Văn Và Bài Văn Nghị Luận Văn Học', 2023, 120000.00, 'TG105', 'TL008', 'NXB007', 'Sách dành cho học sinh Trung học phổ thông ôn luyện viết văn nghị luận.', 'b1_fcb5f9a04bcd4968af60d1d5eacaa4c9_medium.png'),
('DS137', 'Bộ Đề Kiểm Tra Tiếng Việt Lớp 2 - Tập 1', 2023, 65000.00, 'TG106', 'TL008', 'NXB007', 'Tài liệu ôn tập và kiểm tra môn Tiếng Việt dành cho học sinh Lớp 2 tập 1.', '2023_11_30_16_30_38_1-390x510_4acd7e25039448e79d6b9ccb5ea9be20_medium.jpg'),
('DS138', 'Bộ Đề Kiểm Tra Tiếng Việt Lớp 1 - Tập 2', 2023, 65000.00, 'TG106', 'TL008', 'NXB007', 'Tài liệu ôn tập và kiểm tra môn Tiếng Việt dành cho học sinh Lớp 1 tập 2.', '2023_10_10_16_33_53_1-390x510_65a62d24ab154eeda6862a421b5f4751_medium.jpg'),
('DS139', 'A Holistic Approach To IELTS Writing', 2022, 185000.00, 'TG107', 'TL008', 'NXB007', 'Hướng dẫn sử dụng từ ngữ, lập luận, và cấu trúc câu IELTS Writing hiệu quả.', 'p91726mielts_a936112425ed400da4d24ba3fd58a8dc_medium.jpg'),
('DS140', '50 Đề Luyện Thi Tốt Nghiệp THPT 2026 Ngữ Văn', 2024, 135000.00, 'TG108', 'TL008', 'NXB007', '100% đề luyện thi đều có lời giải và video hướng dẫn chi tiết theo chương trình GDPT mới.', 'b_a-1-ng_-v_n_9c96e85f57294b49beb339763c460356_medium.jpg'),
('DS141', '30 Đề Luyện Thi Tốt Nghiệp THPT 2026 Tiếng Anh', 2024, 115000.00, 'TG109', 'TL008', 'NXB007', 'Bám sát cấu trúc đề thi Tiếng Anh THPT 2026 chương trình mới.', '_nh-1_9_8d66739b73df4d879796001561ff0493_medium.jpg'),
('DS142', '30 Đề Luyện Thi Tốt Nghiệp THPT 2026 Môn Toán', 2024, 125000.00, 'TG110', 'TL008', 'NXB007', 'Bám sát cấu trúc đề thi Toán THPT 2026 chương trình mới, có lời giải chi tiết.', '_nh-2_10_a289ca4875834a9981a48e3844059b04_medium.jpg'),
('DS143', '30 Đề Luyện Thi Tốt Nghiệp THPT 2026 Lịch Sử', 2024, 110000.00, 'TG111', 'TL008', 'NXB007', 'Bám sát cấu trúc đề thi Lịch Sử THPT 2026 chương trình mới.', 'bia-1-l_ch-s__9dfc24a4bbf54a99ac691d08fdb35d7f_medium.jpg'),
('DS144', '30 Đề Luyện Thi Tốt Nghiệp THPT 2026 Hoá Học', 2024, 120000.00, 'TG112', 'TL008', 'NXB007', 'Bộ 30 đề thi môn Hoá Học bám sát cấu trúc GDPT 2018.', 'b_a-1-ho_-h_c_0153cf0da8a94116b14c6c87e2afa2ce_medium.jpg'),
('DS145', '30 Đề Luyện Thi Tốt Nghiệp THPT 2026 Địa Lí', 2024, 110000.00, 'TG113', 'TL008', 'NXB007', 'Tài liệu ôn luyện thi THPT 2026 môn Địa Lí theo chương trình mới.', 'bia-1-_a-l__eb3c409112e74878a0361edaa48c32f5_medium.jpg'),
('DS146', 'Bộ Sách The Secret - Bí Mật (4 Cuốn)', 2023, 450000.00, 'TG114', 'TL001', 'NXB002', 'Bộ sách nổi tiếng The Secret: Phép Màu, Bí Mật, Sức Mạnh, Người Hùng.', 'screen_shot_2025-01-11_at_2.07.06_pm_e474b83ce92f4dee88f87581115e9039_medium.png'),
('DS147', 'Chiến Lược Phân Phối Hàng Hóa Tuyệt Vời', 2022, 135000.00, 'TG115', 'TL001', 'NXB004', 'Học hỏi kinh nghiệm từ những doanh nghiệp hàng đầu thế giới như Amazon, Nitori, Zara...', '8935074132130_e7d3387f09144bf48eee7a2b56ab420c_medium.jpg'),
('DS148', '6 Bí Quyết Quản Lý Dự Án Hiệu Quả', 2023, 120000.00, 'TG116', 'TL001', 'NXB004', 'Cách thực hiện dự án thành công, đúng thời gian, đủ ngân sách. Nguồn sách từ Bloomsbury Publishing.', '8935074134837_ff5d4c53a550463e875805db11e00cfc_medium.jpg'),
('DS149', 'Nhà Lãnh Đạo Không Chức Danh', 2021, 95000.00, 'TG073', 'TL001', 'NXB001', 'Câu chuyện về thành công thực sự trong kinh doanh và cuộc sống từ Robin Sharma.', 'nha_lanh_6c0e82e8d5134e568268981b963ae5a0_medium.jpg'),
('DS150', 'Nghịch Lý CIO', 2022, 145000.00, 'TG117', 'TL001', 'NXB004', 'Hòa giải các mâu thuẫn của lãnh đạo IT trong doanh nghiệp hiện đại.', '8935074134431_7f07c29f0e0c40a4a10957cda72729fd_medium.jpg'),
('DS151', 'Nghệ Thuật Quản Lý Nhân Sự', 2024, 155000.00, 'TG118', 'TL001', 'NXB008', 'Cẩm nang và nghệ thuật quản lý nhân sự hiệu quả dành cho những nhà quản lý.', '17_f83fb1064e18465aaf2593ac86380ada_medium.jpg'),
('DS152', 'MBA Bằng Hình (The Visual MBA)', 2021, 185000.00, 'TG119', 'TL001', 'NXB005', 'Trọn bộ 2 năm kiến thức quản trị kinh doanh qua trực quan sinh động.', 'p97611mmba_b___ng_h__nh____nh_7ee76e26293c40cfb5dd4cfa24ad82a8_medium.jpg'),
('DS153', 'Kỷ Nguyên Phẫn Nộ (The Age of Outrage)', 2024, 175000.00, 'TG120', 'TL001', 'NXB002', 'Bí kíp hạ nhiệt làn sóng phẫn nộ của công chúng trong thế giới phân cực.', '8935235245280_348b73f2403645f88fabab62b6925ab5_medium.jpg'),
('DS154', 'Quản Lý Hiệu Quả Nguồn Nhân Lực', 2023, 110000.00, 'TG121', 'TL001', 'NXB004', 'Tài liệu hướng dẫn (How-to) về quản lý và phát triển hiệu quả nguồn nhân lực.', 'quan-ly-hieu-qua-nguon-nhan-luc_d6d52864aee347aca04d9fff3f703a7b_medium.jpg'),
('DS155', 'Giữ Người Bằng Tâm - Dẫn Dắt Bằng Tầm', 2023, 160000.00, 'TG122', 'TL001', 'NXB004', 'Gắn kết đội nhóm, cho kết quả vượt trội cùng chuyên gia thung lũng Silicon.', '_nh-b_a-1_3_0757e87b90fe44949c322d5ff1dedbae_medium.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `docgia`
--

CREATE TABLE `docgia` (
  `madocgia` varchar(50) NOT NULL,
  `hodocgia` varchar(100) DEFAULT NULL,
  `tendocgia` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `sdt` varchar(20) DEFAULT NULL,
  `ngaysinh` date DEFAULT NULL,
  `diachi` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `nhacungcap`
--

CREATE TABLE `nhacungcap` (
  `mancc` varchar(50) NOT NULL,
  `tenncc` varchar(255) DEFAULT NULL,
  `diachincc` varchar(255) DEFAULT NULL,
  `sdt` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `nhacungcap`
--

INSERT INTO `nhacungcap` (`mancc`, `tenncc`, `diachincc`, `sdt`, `email`) VALUES
('NCC_FAHASA', 'Công ty Cổ phần Phát hành Sách TP.HCM - FAHASA', '60-62 Lê Lợi, Quận 1, TP.HCM', '1900636467', 'info@fahasa.com.vn'),
('NCC_NHANAM', 'Công ty Cổ phần Văn hóa và Truyền thông Nhã Nam', '59 Đỗ Quang, Trung Hòa, Cầu Giấy, Hà Nội', '02435146205', 'info@nhanam.vn'),
('NCC_PHUONGNAM', 'Công ty Cổ phần Văn hóa Phương Nam', '940 Đường 3/2, Phường 15, Quận 11, TP.HCM', '19006656', 'hotro@phuongnambook.com'),
('NCC_SAIGONBOOKS', 'Công ty Cổ phần Sách Sài Gòn (Saigon Books)', '97 Nguyễn Bỉnh Khiêm, Đa Kao, Quận 1, TP.HCM', '02862822666', 'contact@saigonbooks.vn'),
('NCC_THAIHA', 'Công ty Cổ phần Sách Thái Hà (ThaiHaBooks)', '119 C3, Tập thể Mai Động, Hoàng Mai, Hà Nội', '02437533909', 'info@thaihabooks.com'),
('NCC_TRE', 'Nhà xuất bản Trẻ', '161 Lý Chính Thắng, Phường Võ Thị Sáu, Quận 3, TP.HCM', '02839316237', 'tre@nxbtre.com.vn');

-- --------------------------------------------------------

--
-- Table structure for table `nhanvien`
--

CREATE TABLE `nhanvien` (
  `manv` varchar(50) NOT NULL,
  `honv` varchar(100) DEFAULT NULL,
  `tennv` varchar(100) DEFAULT NULL,
  `gioitinh` varchar(10) DEFAULT NULL,
  `sdt` varchar(20) DEFAULT NULL,
  `ngaysinh` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `nhaxuatban`
--

CREATE TABLE `nhaxuatban` (
  `manxb` varchar(50) NOT NULL,
  `tennxb` varchar(255) DEFAULT NULL,
  `diachi` varchar(255) DEFAULT NULL,
  `sdt` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `nhaxuatban`
--

INSERT INTO `nhaxuatban` (`manxb`, `tennxb`, `diachi`, `sdt`, `email`) VALUES
('NXB001', 'NXB Trẻ', '161 Lý Chính Thắng, Võ Thị Sáu, Quận 3, TP.HCM', '02839316237', 'tre@nxbtre.com.vn'),
('NXB002', 'NXB Thế Giới', '55 Quang Trung, Hà Nội', '02439434730', 'info@nxbkimdong.com.vn'),
('NXB003', 'NXB Thông Tấn', '11 Trần Hưng Đạo, Hoàn Kiếm, Hà Nội', '02439331149', 'vnanet@vnanet.vn'),
('NXB004', 'NXB Công Thương', 'Hà Nội', '02400000000', 'info@nxbcongthuong.vn'),
('NXB005', 'NXB Dân Trí', 'Hà Nội', '02400000001', 'info@nxbdantri.vn'),
('NXB006', 'NXB Hồng Đức', '65 Tràng Thi, Hoàn Kiếm, Hà Nội', '02439260024', 'info@nxbhongduc.vn'),
('NXB007', 'NXB Hà Nội', '4 Tống Duy Tân, Hoàn Kiếm, Hà Nội', '02438252916', 'info@nxbhanoi.com.vn'),
('NXB008', 'NXB Khoa Học Xã Hội', '26 Lý Thường Kiệt, Hoàn Kiếm, Hà Nội', '02438252916', 'nxbkhxh@vass.gov.vn'),
('NXB009', 'NXB Hội Nhà Văn', '65 Nguyễn Du, Hai Bà Trưng, Hà Nội', '02439439472', 'nxbhoinhavan@gmail.com'),
('NXB010', 'NXB Văn Học', '18 Nguyễn Trường Tộ, Ba Đình, Hà Nội', '02438438266', 'nxbvanhoc@gmail.com'),
('NXB011', 'NXB Chính Trị Quốc Gia Sự Thật', '6/86 Duy Tân, Cầu Giấy, Hà Nội', '02437910000', 'info@nxbctqg.org.vn'),
('NXB012', 'NXB Kim Đồng', '55 Quang Trung, Hai Bà Trưng, Hà Nội', '02439434730', 'info@nxbkimdong.com.vn'),
('NXB013', 'NXB Lao Động', '175 Giảng Võ, Đống Đa, Hà Nội', '02438515380', 'nxblaodong@gmail.com'),
('NXB014', 'NXB Thanh Hóa', '19 Phan Chu Trinh, TP Thanh Hóa', '02373852945', 'nxbthanhhoa@gmail.com'),
('NXB015', 'NXB Tri Thức', '53 Nguyễn Du, Hai Bà Trưng, Hà Nội', '02439439562', 'info@nxbtrithuc.com.vn'),
('NXB016', 'NXB Phụ Nữ Việt Nam', '39 Hàng Chuối, Hai Bà Trưng, Hà Nội', '02439710717', 'nxbphunu@nxbphunu.vn');

-- --------------------------------------------------------

--
-- Table structure for table `nhomquyen`
--

CREATE TABLE `nhomquyen` (
  `manhomquyen` varchar(50) NOT NULL,
  `tennhomquyen` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `phieumuon`
--

CREATE TABLE `phieumuon` (
  `mamuon` varchar(50) NOT NULL,
  `ngaymuon` datetime DEFAULT NULL,
  `ngayhethan` datetime DEFAULT NULL,
  `manv` varchar(50) DEFAULT NULL,
  `madocgia` varchar(50) DEFAULT NULL,
  `trangthai` varchar(50) DEFAULT NULL,
  `ghichu` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `phieunhap`
--

CREATE TABLE `phieunhap` (
  `maphieunhap` varchar(50) NOT NULL,
  `thoigiantao` datetime DEFAULT NULL,
  `tongtien` decimal(10,2) DEFAULT NULL,
  `manv` varchar(50) DEFAULT NULL,
  `mancc` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `phieuphat`
--

CREATE TABLE `phieuphat` (
  `maphat` varchar(50) NOT NULL,
  `madocgia` varchar(50) DEFAULT NULL,
  `matra` varchar(50) DEFAULT NULL,
  `ngaylap` datetime DEFAULT NULL,
  `tongtienphat` decimal(10,2) DEFAULT NULL,
  `trangthai` varchar(50) DEFAULT NULL,
  `ghichu` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `phieutra`
--

CREATE TABLE `phieutra` (
  `matra` varchar(50) NOT NULL,
  `mamuon` varchar(50) DEFAULT NULL,
  `ngaytra` datetime DEFAULT NULL,
  `manv` varchar(50) DEFAULT NULL,
  `tongtienphat` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tacgia`
--

CREATE TABLE `tacgia` (
  `matacgia` varchar(50) NOT NULL,
  `tentacgia` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tacgia`
--

INSERT INTO `tacgia` (`matacgia`, `tentacgia`) VALUES
('TG001', 'Bách Tùng'),
('TG002', '	\nLeader Thanh'),
('TG003', ' Joe Vitale'),
('TG004', 'Andrea Fryrear'),
('TG005', 'Khôi'),
('TG006', 'Richard Moore'),
('TG007', ' Matt Johnson PhD, Prince Ghuman'),
('TG008', 'Dan White'),
('TG009', ' Lydia Michael'),
('TG010', 'Inamori Kazuo'),
('TG011', 'Jeb Blount, Anthony Iannarino'),
('TG012', 'Nhiều tác giả'),
('TG013', 'Aaron Ross, Marylou Tyler'),
('TG014', 'Dan S. Kennedy'),
('TG015', ' Russell Brunson'),
('TG016', ' Joseph F. Hair, Jr., Dana E. Harrison, Haya Ajjan'),
('TG017', 'Allan J. Kimmel'),
('TG018', 'Mary Roach'),
('TG019', 'Alex Goldfayn'),
('TG020', 'Bình Nguyễn, Việt Anh Nori'),
('TG021', 'Marcel Gaultier'),
('TG022', 'Hàn Chung Lượng'),
('TG023', ' Đinh Hùng'),
('TG024', 'Jeremy Dronfield'),
('TG025', 'Denise Affonco'),
('TG026', 'Edgar parin D\'aulaire, Ingri'),
('TG027', 'Lucy Maud Montgomery'),
('TG028', 'Harper Lee'),
('TG029', 'Stephen King'),
('TG030', ' Armand Mattelart, Michèle Mattelart'),
('TG031', 'Howard Schultz'),
('TG032', 'Barbara Butcher'),
('TG033', 'BTS, Myeongseok Kang'),
('TG034', 'Guillem Balague'),
('TG035', 'David Beckham & Tom Watt'),
('TG036', 'Debbie Beckrman'),
('TG037', 'Raphael Honigstein'),
('TG038', 'Andrés Iniesta'),
('TG039', 'Thẩm Ninh'),
('TG040', 'TS Nguyễn Thu Hương'),
('TG041', 'HƯƠNG BÙI'),
('TG042', 'Kim Sarah'),
('TG043', ' Becca Anderson'),
('TG044', 'Emma Phạm'),
('TG045', 'Craig Kessler'),
('TG046', 'Park Soyuong'),
('TG047', 'Nguyễn Ngọc Ái Quỳnh'),
('TG048', 'James E. Hughes, Jr. Susan E. Massenzio,  Keith Whitaker'),
('TG049', 'Lý Tĩnh'),
('TG050', ' Vũ Thị Huệ'),
('TG051', 'Bác Sĩ, Thạc Sĩ Phạm Thánh Hoa'),
('TG052', 'Dương Ý'),
('TG053', 'Toshiyuki Shiomi, Eiko Kuryu'),
('TG054', 'FLIPFLOPs'),
('TG055', '	\nNomomarino'),
('TG056', '	\nUma Agri'),
('TG057', 'Tiểu Lam Và Những Người Bạn'),
('TG058', 'Suzuka'),
('TG059', 'Lương Minh Quang'),
('TG060', '	\nUraroji'),
('TG061', 'Ameko'),
('TG062', 'Khuyển Nhất'),
('TG063', 'Phan'),
('TG064', 'Linh Thạch'),
('TG065', 'Lâm Bảo Thi'),
('TG066', 'Scott McCloud'),
('TG067', 'Worry Lines'),
('TG068', 'Sói Ăn Chay'),
('TG069', 'Lê Thư'),
('TG070', 'Nguyễn Tú Tuấn'),
('TG071', 'Gretchen Rubin'),
('TG072', 'Jean Case'),
('TG073', 'Robin Sharma'),
('TG074', 'John Leach'),
('TG075', 'Rae Earl'),
('TG076', 'Takuya Senda'),
('TG077', 'Karen Hough'),
('TG078', 'Ezra Bayda & Elizabeth Hamilton'),
('TG079', 'Pat Flynn'),
('TG080', 'Lý Thế Cường'),
('TG081', 'Kishimi Ichiro & Koga Fumitake'),
('TG082', 'Tiến sĩ Lê Nguyễn Hồng Phương'),
('TG083', 'Ann Mei Chang & Eric Ries'),
('TG084', 'Jack Canfield & D.D. Watkins'),
('TG085', 'Mr. Q'),
('TG086', 'Ki Ju Lee'),
('TG087', 'Damon Zahariades'),
('TG088', 'Liễu Nhất Thu'),
('TG089', 'Mike Leibling'),
('TG090', 'Tiến sĩ Ali Fenwick'),
('TG091', 'ARC ACADEMY'),
('TG092', 'Hitoko Sasaki, Noriko Matsumoto'),
('TG093', 'Nguyễn Thị Thu Huế'),
('TG094', 'BINO'),
('TG095', 'Trí Thức Việt'),
('TG096', 'Nguyễn Đại'),
('TG097', 'Cho Hang Rok, Lee Ji Yeong'),
('TG098', 'Nguyễn Phương Anh'),
('TG099', 'Bùi Thành Việt'),
('TG100', 'Nhung Đỗ, Giang Vi'),
('TG101', 'Louise Edington'),
('TG102', 'George S. Clason'),
('TG103', 'Nhóm tác giả Thu Khai'),
('TG104', 'Richard Fenton & Andrea Waltz'),
('TG105', 'PGS. TS Thái Phan Vàng Anh'),
('TG106', 'ThS. Lê Nguyệt & Trần Thanh Hà'),
('TG107', 'Vũ Hải'),
('TG108', 'TS. Hoàng Thị Thanh Huyền'),
('TG109', 'ThS. Hoàng Nhật Linh'),
('TG110', 'ThS. Đào Hữu Trọng'),
('TG111', 'Hồ Như Hiển & Trần Mơ'),
('TG112', 'TS. Nguyễn Đình Nguyên'),
('TG113', 'Nguyễn Thị Hồng Châu & Nguyễn Thị Hải Yến'),
('TG114', 'Rhonda Byrne'),
('TG115', 'Ryoichi Kakui'),
('TG116', 'Bloomsbury Publishing'),
('TG117', 'Martha Heller'),
('TG118', 'Lê Tiến Thành'),
('TG119', 'Jason Barron'),
('TG120', 'Karthik Ramanna'),
('TG121', 'ThaiHaBooks'),
('TG122', 'Russ Laraway');

-- --------------------------------------------------------

--
-- Table structure for table `taikhoan`
--

CREATE TABLE `taikhoan` (
  `tendangnhap` varchar(50) NOT NULL,
  `matkhau` varchar(255) DEFAULT NULL,
  `manhomquyen` varchar(50) DEFAULT NULL,
  `manv` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `theloai`
--

CREATE TABLE `theloai` (
  `matheloai` varchar(50) NOT NULL,
  `tentheloai` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `theloai`
--

INSERT INTO `theloai` (`matheloai`, `tentheloai`) VALUES
('TL001', 'Kinh Tế'),
('TL002', 'Văn Học Trong Nước'),
('TL003', 'Văn Học Nước Ngoài'),
('TL004', 'Thưởng Thức Đời Sống'),
('TL005', 'Thiếu Nhi'),
('TL006', 'Phát Triển Bản Thân'),
('TL007', 'Tin Học Ngoại Ngữ'),
('TL008', 'Giáo Khoa - Giáo Trình');

-- --------------------------------------------------------

--
-- Table structure for table `vitri`
--

CREATE TABLE `vitri` (
  `mavitri` varchar(50) NOT NULL,
  `khuvuc` varchar(100) DEFAULT NULL,
  `ke` varchar(50) DEFAULT NULL,
  `mota` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vitri`
--

INSERT INTO `vitri` (`mavitri`, `khuvuc`, `ke`, `mota`) VALUES
('VT001', 'Tầng 1', 'Kệ A1', 'Khu vực văn học'),
('VT002', 'Tầng 1', 'Kệ B2', 'Khu vực trinh thám'),
('VT003', 'Tầng 2', 'Kệ C1', 'Khu vực kỹ năng');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ctphieumuon`
--
ALTER TABLE `ctphieumuon`
  ADD PRIMARY KEY (`mamuon`,`macuonsach`),
  ADD KEY `fk_ctpm_cuonsach` (`macuonsach`);

--
-- Indexes for table `ctphieunhap`
--
ALTER TABLE `ctphieunhap`
  ADD PRIMARY KEY (`maphieunhap`,`madausach`),
  ADD KEY `fk_ctpn_dausach` (`madausach`);

--
-- Indexes for table `ctphieuphat`
--
ALTER TABLE `ctphieuphat`
  ADD PRIMARY KEY (`maphat`,`macuonsach`),
  ADD KEY `fk_ctpp_cuonsach` (`macuonsach`);

--
-- Indexes for table `ctphieutra`
--
ALTER TABLE `ctphieutra`
  ADD PRIMARY KEY (`matra`,`macuonsach`,`maphat`),
  ADD KEY `fk_ctpt_cuonsach` (`macuonsach`);

--
-- Indexes for table `ctquyen`
--
ALTER TABLE `ctquyen`
  ADD PRIMARY KEY (`manhomquyen`,`machucnang`),
  ADD KEY `fk_ctq_chucnang` (`machucnang`);

--
-- Indexes for table `cuonsach`
--
ALTER TABLE `cuonsach`
  ADD PRIMARY KEY (`macuonsach`),
  ADD KEY `fk_cuonsach_dausach` (`madausach`),
  ADD KEY `fk_cuonsach_vitri` (`mavitri`);

--
-- Indexes for table `danhmucchucnang`
--
ALTER TABLE `danhmucchucnang`
  ADD PRIMARY KEY (`machucnang`);

--
-- Indexes for table `dausach`
--
ALTER TABLE `dausach`
  ADD PRIMARY KEY (`madausach`),
  ADD KEY `fk_dausach_tacgia` (`matacgia`),
  ADD KEY `fk_dausach_theloai` (`matheloai`),
  ADD KEY `fk_dausach_nxb` (`manxb`);

--
-- Indexes for table `docgia`
--
ALTER TABLE `docgia`
  ADD PRIMARY KEY (`madocgia`);

--
-- Indexes for table `nhacungcap`
--
ALTER TABLE `nhacungcap`
  ADD PRIMARY KEY (`mancc`);

--
-- Indexes for table `nhanvien`
--
ALTER TABLE `nhanvien`
  ADD PRIMARY KEY (`manv`);

--
-- Indexes for table `nhaxuatban`
--
ALTER TABLE `nhaxuatban`
  ADD PRIMARY KEY (`manxb`);

--
-- Indexes for table `nhomquyen`
--
ALTER TABLE `nhomquyen`
  ADD PRIMARY KEY (`manhomquyen`);

--
-- Indexes for table `phieumuon`
--
ALTER TABLE `phieumuon`
  ADD PRIMARY KEY (`mamuon`),
  ADD KEY `fk_phieumuon_docgia` (`madocgia`),
  ADD KEY `fk_phieumuon_nhanvien` (`manv`);

--
-- Indexes for table `phieunhap`
--
ALTER TABLE `phieunhap`
  ADD PRIMARY KEY (`maphieunhap`),
  ADD KEY `fk_phieunhap_nhacungcap` (`mancc`),
  ADD KEY `fk_phieunhap_nhanvien` (`manv`);

--
-- Indexes for table `phieuphat`
--
ALTER TABLE `phieuphat`
  ADD PRIMARY KEY (`maphat`),
  ADD KEY `fk_phieuphat_docgia` (`madocgia`),
  ADD KEY `fk_phieuphat_phieutra` (`matra`);

--
-- Indexes for table `phieutra`
--
ALTER TABLE `phieutra`
  ADD PRIMARY KEY (`matra`),
  ADD KEY `fk_phieutra_phieumuon` (`mamuon`),
  ADD KEY `fk_phieutra_nhanvien` (`manv`);

--
-- Indexes for table `tacgia`
--
ALTER TABLE `tacgia`
  ADD PRIMARY KEY (`matacgia`);

--
-- Indexes for table `taikhoan`
--
ALTER TABLE `taikhoan`
  ADD PRIMARY KEY (`tendangnhap`),
  ADD KEY `fk_taikhoan_nhanvien` (`manv`),
  ADD KEY `fk_taikhoan_nhomquyen` (`manhomquyen`);

--
-- Indexes for table `theloai`
--
ALTER TABLE `theloai`
  ADD PRIMARY KEY (`matheloai`);

--
-- Indexes for table `vitri`
--
ALTER TABLE `vitri`
  ADD PRIMARY KEY (`mavitri`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `ctphieumuon`
--
ALTER TABLE `ctphieumuon`
  ADD CONSTRAINT `fk_ctpm_cuonsach` FOREIGN KEY (`macuonsach`) REFERENCES `cuonsach` (`macuonsach`),
  ADD CONSTRAINT `fk_ctpm_phieumuon` FOREIGN KEY (`mamuon`) REFERENCES `phieumuon` (`mamuon`);

--
-- Constraints for table `ctphieunhap`
--
ALTER TABLE `ctphieunhap`
  ADD CONSTRAINT `fk_ctpn_dausach` FOREIGN KEY (`madausach`) REFERENCES `dausach` (`madausach`),
  ADD CONSTRAINT `fk_ctpn_phieunhap` FOREIGN KEY (`maphieunhap`) REFERENCES `phieunhap` (`maphieunhap`);

--
-- Constraints for table `ctphieuphat`
--
ALTER TABLE `ctphieuphat`
  ADD CONSTRAINT `fk_ctpp_cuonsach` FOREIGN KEY (`macuonsach`) REFERENCES `cuonsach` (`macuonsach`),
  ADD CONSTRAINT `fk_ctpp_phieuphat` FOREIGN KEY (`maphat`) REFERENCES `phieuphat` (`maphat`);

--
-- Constraints for table `ctphieutra`
--
ALTER TABLE `ctphieutra`
  ADD CONSTRAINT `fk_ctpt_cuonsach` FOREIGN KEY (`macuonsach`) REFERENCES `cuonsach` (`macuonsach`),
  ADD CONSTRAINT `fk_ctpt_phieutra` FOREIGN KEY (`matra`) REFERENCES `phieutra` (`matra`);

--
-- Constraints for table `ctquyen`
--
ALTER TABLE `ctquyen`
  ADD CONSTRAINT `fk_ctq_chucnang` FOREIGN KEY (`machucnang`) REFERENCES `danhmucchucnang` (`machucnang`),
  ADD CONSTRAINT `fk_ctq_nhomquyen` FOREIGN KEY (`manhomquyen`) REFERENCES `nhomquyen` (`manhomquyen`);

--
-- Constraints for table `cuonsach`
--
ALTER TABLE `cuonsach`
  ADD CONSTRAINT `fk_cuonsach_dausach` FOREIGN KEY (`madausach`) REFERENCES `dausach` (`madausach`),
  ADD CONSTRAINT `fk_cuonsach_vitri` FOREIGN KEY (`mavitri`) REFERENCES `vitri` (`mavitri`);

--
-- Constraints for table `dausach`
--
ALTER TABLE `dausach`
  ADD CONSTRAINT `fk_dausach_nxb` FOREIGN KEY (`manxb`) REFERENCES `nhaxuatban` (`manxb`),
  ADD CONSTRAINT `fk_dausach_tacgia` FOREIGN KEY (`matacgia`) REFERENCES `tacgia` (`matacgia`),
  ADD CONSTRAINT `fk_dausach_theloai` FOREIGN KEY (`matheloai`) REFERENCES `theloai` (`matheloai`);

--
-- Constraints for table `phieumuon`
--
ALTER TABLE `phieumuon`
  ADD CONSTRAINT `fk_phieumuon_docgia` FOREIGN KEY (`madocgia`) REFERENCES `docgia` (`madocgia`),
  ADD CONSTRAINT `fk_phieumuon_nhanvien` FOREIGN KEY (`manv`) REFERENCES `nhanvien` (`manv`);

--
-- Constraints for table `phieunhap`
--
ALTER TABLE `phieunhap`
  ADD CONSTRAINT `fk_phieunhap_nhacungcap` FOREIGN KEY (`mancc`) REFERENCES `nhacungcap` (`mancc`),
  ADD CONSTRAINT `fk_phieunhap_nhanvien` FOREIGN KEY (`manv`) REFERENCES `nhanvien` (`manv`);

--
-- Constraints for table `phieuphat`
--
ALTER TABLE `phieuphat`
  ADD CONSTRAINT `fk_phieuphat_docgia` FOREIGN KEY (`madocgia`) REFERENCES `docgia` (`madocgia`),
  ADD CONSTRAINT `fk_phieuphat_phieutra` FOREIGN KEY (`matra`) REFERENCES `phieutra` (`matra`);

--
-- Constraints for table `phieutra`
--
ALTER TABLE `phieutra`
  ADD CONSTRAINT `fk_phieutra_nhanvien` FOREIGN KEY (`manv`) REFERENCES `nhanvien` (`manv`),
  ADD CONSTRAINT `fk_phieutra_phieumuon` FOREIGN KEY (`mamuon`) REFERENCES `phieumuon` (`mamuon`);

--
-- Constraints for table `taikhoan`
--
ALTER TABLE `taikhoan`
  ADD CONSTRAINT `fk_taikhoan_nhanvien` FOREIGN KEY (`manv`) REFERENCES `nhanvien` (`manv`),
  ADD CONSTRAINT `fk_taikhoan_nhomquyen` FOREIGN KEY (`manhomquyen`) REFERENCES `nhomquyen` (`manhomquyen`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

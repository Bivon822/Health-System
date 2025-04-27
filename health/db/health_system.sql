-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 27, 2025 at 01:56 PM
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
-- Database: `health_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`) VALUES
(3, 'admin', '$2y$10$Y9FFrmMtXTjCuIWPdWL4MODhsdTmLq7QomepnDbghJJ8LskZH8mE2');

-- --------------------------------------------------------

--
-- Table structure for table `api_keys`
--

CREATE TABLE `api_keys` (
  `id` int(11) NOT NULL,
  `api_key` varchar(255) NOT NULL,
  `description` varchar(100) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `api_keys`
--

INSERT INTO `api_keys` (`id`, `api_key`, `description`, `is_active`, `created_at`) VALUES
(1, 'my_secure_key_001', 'Default access for external apps', 1, '2025-04-26 01:17:50');

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

CREATE TABLE `clients` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `age` int(3) NOT NULL,
  `gender` enum('Male','Female') NOT NULL,
  `contact` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `clients`
--

INSERT INTO `clients` (`id`, `name`, `age`, `gender`, `contact`, `email`, `password`, `created_at`) VALUES
(1, 'Mose Bivon', 12, 'Male', '0769716880', 'bivonmomanyi03@gmail.com', '$2y$10$jnXKu8Y9NAmezYh.DUFEl.RQohwh1s7OlKkg1oJQt.jFVGKzu59MO', '2025-04-25 13:23:06'),
(3, 'kevin mose', 25, 'Male', '0758349274', 'kevinatambo@gmail.com', '$2y$10$bGew7O/STm65KoINbuOM4euTQZRJCAH8kMKyIz2YaWXGvEyD/0UIS', '2025-04-25 13:35:30'),
(5, 'Peter Iroga', 25, 'Male', '0115062166', 'irogapeter4@gmail.com', '', '2025-04-26 03:23:39'),
(6, 'Moses Bonny', 24, 'Male', '0769716880', 'mosesb@gmail.com', '$2y$10$fxg/9c5XVdbHRLwTdG6Jcu9/C7vqjBP2Pv1YHFbSDUtcGXEuycTQy', '2025-04-26 03:38:38'),
(7, 'clinton', 24, 'Male', '0769716880', 'clinton@gmail.com', '12345', '2025-04-26 14:18:57');

-- --------------------------------------------------------

--
-- Table structure for table `enrollments`
--

CREATE TABLE `enrollments` (
  `id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `program_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `enrollments`
--

INSERT INTO `enrollments` (`id`, `client_id`, `program_id`) VALUES
(1, 1, 2),
(2, 3, 1),
(3, 3, 1),
(4, 1, 2),
(5, 1, 16),
(6, 1, 12),
(7, 5, 13),
(8, 1, 14),
(9, 3, 20),
(10, 3, 14);

-- --------------------------------------------------------

--
-- Table structure for table `programs`
--

CREATE TABLE `programs` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `programs`
--

INSERT INTO `programs` (`id`, `name`, `description`, `created_at`) VALUES
(1, 'Malaria Awareness and Education', 'Educates communities on malaria prevention, symptoms, and when to seek care.', '2025-04-25 21:27:55'),
(2, 'Malaria Awareness and Education', 'Educates communities on malaria prevention, symptoms, and when to seek care.', '2025-04-25 21:28:33'),
(12, 'Malaria Prevention and Awareness Program', 'Focuses on educating communities about malaria transmission, prevention methods like the use of insecticide-treated nets (ITNs), and promoting early diagnosis and treatment practices.\r\n', '2025-04-27 08:53:11'),
(13, ' Insecticide-Treated Nets (ITN) Distribution Campaign', 'Aims to distribute free or subsidized mosquito nets treated with insecticide to vulnerable populations, especially pregnant women and children under five years old, to reduce malaria infections.', '2025-04-27 08:55:43'),
(14, 'Indoor Residual Spraying (IRS) Program', 'Targets the spraying of insecticides inside homes to kill mosquitoes resting on walls, significantly reducing mosquito populations and malaria transmission rates.\r\n', '2025-04-27 08:56:20'),
(15, ' Malaria Diagnosis and Treatment Program', 'Provides free or affordable malaria diagnostic services (using Rapid Diagnostic Tests or microscopy) and treatment with effective antimalarial drugs like ACTs (Artemisinin-based Combination Therapy).', '2025-04-27 08:56:49'),
(16, ' 🌿 5. Malaria in Pregnancy (MIP) Intervention Program', 'Focuses on protecting pregnant women by providing intermittent preventive treatment in pregnancy (IPTp) and encouraging the use of ITNs to reduce the risks of maternal death, miscarriage, and low birth weight.', '2025-04-27 08:57:49'),
(17, 'Community Health Worker (CHW) Malaria Training Program', 'Trains community health workers to recognize malaria symptoms, perform diagnostic tests, provide basic treatment, and refer complicated cases to health facilities.', '2025-04-27 08:58:16'),
(18, 'Seasonal Malaria Chemoprevention (SMC) Program', 'Targets children aged 3–59 months in areas where malaria is seasonal by providing preventive antimalarial medicine during high transmission seasons (usually rainy periods).', '2025-04-27 08:58:48'),
(19, 'School Malaria Education Program', 'Introduces malaria education into school curriculums, training teachers and students to recognize malaria symptoms, promote prevention strategies, and act as community change agents.', '2025-04-27 08:59:19'),
(20, ' Malaria Surveillance and Data Management Program', 'Improves the collection, analysis, and use of malaria case data to help health authorities make informed decisions about resource allocation and targeted interventions.', '2025-04-27 09:00:14'),
(21, '10. Research and Innovation in Malaria Control Program', 'Encourages research institutions to innovate new malaria vaccines, better diagnostic tools, advanced mosquito control methods, and resistance monitoring to enhance malaria elimination efforts.', '2025-04-27 09:00:51');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `api_keys`
--
ALTER TABLE `api_keys`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `api_key` (`api_key`);

--
-- Indexes for table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `enrollments`
--
ALTER TABLE `enrollments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `client_id` (`client_id`),
  ADD KEY `program_id` (`program_id`);

--
-- Indexes for table `programs`
--
ALTER TABLE `programs`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `api_keys`
--
ALTER TABLE `api_keys`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `clients`
--
ALTER TABLE `clients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `enrollments`
--
ALTER TABLE `enrollments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `programs`
--
ALTER TABLE `programs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `enrollments`
--
ALTER TABLE `enrollments`
  ADD CONSTRAINT `enrollments_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `enrollments_ibfk_2` FOREIGN KEY (`program_id`) REFERENCES `programs` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 04, 2024 at 08:11 AM
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
-- Database: `tesys`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','tenant') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `firstname`, `lastname`, `email`, `phone`, `password`, `role`, `created_at`) VALUES
(1, 'Admin1', 'Jairo', 'Robiso', 'robiso@gmail.com', '12345678908', '$2y$10$FpBXNRJ1j40sbBHbBdIVfOq4vX9XUV.XZnJTADU/Tru/tolAl8Uga', 'admin', '2024-12-03 06:04:28'),
(2, 'Admin3', 'Abby', 'luci', 'luci@gmail.com', '12345678909', '$2y$10$zbGOypUnw0RrB0UytA/ImONWxbsUWCPZa97Rd.HlXj4ZP4sUqJEi.', 'admin', '2024-12-03 06:31:59');

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `id` int(11) NOT NULL,
  `announcement` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `announcements`
--

INSERT INTO `announcements` (`id`, `announcement`, `created_at`) VALUES
(1, 'hellow', '2024-12-01 08:50:46'),
(2, 'hellow', '2024-12-01 08:53:24'),
(3, 'hellow', '2024-12-01 08:54:42'),
(4, 'hellow', '2024-12-01 08:55:28'),
(5, 'hellow', '2024-12-01 08:56:39'),
(6, 'hellow', '2024-12-01 08:57:46'),
(7, 'hellow everyone ', '2024-12-01 08:58:02'),
(8, 'fff', '2024-12-01 09:00:40'),
(9, 'attention mag pasa na kayo', '2024-12-01 09:47:27'),
(10, 'can you be mine ', '2024-12-02 08:54:50');

-- --------------------------------------------------------

--
-- Table structure for table `maintenance_requests`
--

CREATE TABLE `maintenance_requests` (
  `id` int(11) NOT NULL,
  `tenant_name` varchar(255) NOT NULL,
  `unit_number` varchar(50) NOT NULL,
  `request_description` text NOT NULL,
  `category` varchar(100) DEFAULT NULL,
  `urgency` varchar(50) DEFAULT NULL,
  `request_status` varchar(50) DEFAULT 'Pending',
  `photos` text DEFAULT NULL,
  `date_requested` datetime DEFAULT current_timestamp(),
  `status` enum('Pending','Approved','Rejected') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `maintenance_requests`
--

INSERT INTO `maintenance_requests` (`id`, `tenant_name`, `unit_number`, `request_description`, `category`, `urgency`, `request_status`, `photos`, `date_requested`, `status`, `created_at`) VALUES
(6, 'jairorobiso', '12', 'aircon', 'Air Conditioning', 'High', 'Pending', 'uploads/674c1ee9df3f8_TeSys.drawio.png', '2024-12-01 16:31:37', 'Approved', '2024-12-02 13:58:15'),
(7, 'kassandra', '1002', 'luwag ang utak', 'Air Conditioning', 'High', 'Pending', 'uploads/674dc1ccf3483_part2.png', '2024-12-02 22:18:52', 'Approved', '2024-12-02 14:18:52'),
(9, 'jhane', '22222', 'wala', 'Electrical', 'Low', 'Pending', 'uploads/674edd76e2812_login page.png', '2024-12-03 18:29:10', 'Pending', '2024-12-03 10:29:10');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `message` text NOT NULL,
  `date_sent` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `tenant_id` int(11) DEFAULT NULL,
  `payment_date` date DEFAULT NULL,
  `amount_paid` decimal(10,2) DEFAULT NULL,
  `payment_status` enum('Paid','Unpaid') DEFAULT 'Unpaid'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `tenant_id`, `payment_date`, `amount_paid`, `payment_status`) VALUES
(1, NULL, '2024-12-02', 500.00, '');

-- --------------------------------------------------------

--
-- Table structure for table `renewal_requests`
--

CREATE TABLE `renewal_requests` (
  `request_id` int(11) NOT NULL,
  `id` int(11) DEFAULT NULL,
  `renewal_date` date DEFAULT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `request_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `username` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `renewal_requests`
--

INSERT INTO `renewal_requests` (`request_id`, `id`, `renewal_date`, `status`, `request_date`, `username`) VALUES
(1, NULL, '2024-04-02', 'pending', '2024-11-29 09:18:32', ''),
(2, NULL, '2024-04-02', 'pending', '2024-11-29 09:23:21', ''),
(3, NULL, '2024-04-02', 'pending', '2024-11-29 09:25:12', ''),
(4, NULL, '2024-05-02', 'pending', '2024-12-01 08:32:12', 'jairotenant'),
(5, NULL, '2024-12-01', 'pending', '2024-12-01 09:36:03', ''),
(110, NULL, '2024-12-01', 'pending', '2024-12-01 09:42:42', '');

-- --------------------------------------------------------

--
-- Table structure for table `spaces`
--

CREATE TABLE `spaces` (
  `id` int(11) NOT NULL,
  `space_name` varchar(100) NOT NULL,
  `tenant_id` int(11) DEFAULT NULL,
  `status` enum('Available','Occupied') DEFAULT 'Available',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `business_type` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `spaces`
--

INSERT INTO `spaces` (`id`, `space_name`, `tenant_id`, `status`, `created_at`, `updated_at`, `business_type`) VALUES
(11, 'appartment 223', NULL, 'Occupied', '2024-12-02 15:10:45', '2024-12-02 15:10:45', 'gagets');

-- --------------------------------------------------------

--
-- Table structure for table `tenants`
--

CREATE TABLE `tenants` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `lease_status` enum('Active','Expired','Pending') DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tenants`
--

INSERT INTO `tenants` (`id`, `username`, `firstname`, `lastname`, `email`, `phone`, `password`, `lease_status`) VALUES
(2, 'tenantjhane', 'kai', 'rivera', 'kai@gmail.com', '09874837268', '$2y$10$Wz9dZkJNpoANfPwJYC.jj.uChovAPxPQcZx.UvrvMt5MSyOBwmTXy', 'Pending'),
(11, 'jhane', 'jhane', 'ramos', 'jhane@gmail.com', '09882786722', '$2y$10$1QFf1VcNc6cA9.HEFqvjCOsIc8vD.UsHg5ouaIhk.r82YOYwnMmD2', 'Active'),
(12, 'jairotenant', 'jairo', 'robiso', 'robiso@gmail.com', '12345678912', '$2y$10$oKDD1fRcZ07Ru52P2SAhM.TD0qkzWRwILzFPIKaCviKUuW/fWGpea', 'Pending'),
(13, 'aning', 'sarah', 'dudas', 'dudas@gmail.com', '09128770015', '$2y$10$kYeuJBN5DoL48vTjPfQzc.BM516UhaMnBDW5BpIttlCIt6GemW54S', 'Pending'),
(14, 'cha', 'charlyn', 'miniano', 'cha@gmail.com', '12345678909', '$2y$10$jMMNAZm92faeCfYn8obiH.ieklPgupwlQuduip1X2fC/bc/fwBDeC', 'Pending'),
(15, 'sarahuto', 'dudas', 'sarah', 'sarah@gmail.com', '12345678909', '$2y$10$/MqtgZtKXqU0RxZ8oJogYeHDr7VHB3hdqr/6w6Vzxlqied0iBa4o.', 'Pending'),
(16, 'tenantreybin', 'reybin', 'magsino', 'magsino@gmail.com', '1234567890', '$2y$10$JVaaQhphR9U5cHSjvD.KeeuV7akhoiNTY92SwMrAUv1Yj6z052pvi', 'Pending'),
(17, 'karen', 'karen', 'dejocos', 'dejocos@gmail.com', '09926265229', '$2y$10$m20tkOPyyPHZ5j6A/msSN.N6XWqYwn3IeSfwczxXgaAg6R9dj3kPO', 'Pending'),
(18, 'carla', 'carla', 'carpenter', 'carl@gmail.com', '1234567890', '$2y$10$Hto4NE.MRQ7zy3LkNIWnLeW7EKORPGXJwuoH92yKiHvrLThPIfsFG', 'Pending'),
(19, 'zyrel', 'zy', 'landicho', 'zy@gmail.com', '12345678987', '$2y$10$50z2M9r2O7UPZ8NSs7ayleWiV6ZcNMiXTGukYW7LNLLBODCnG2T0a', 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `tenant_applications`
--

CREATE TABLE `tenant_applications` (
  `id` int(11) NOT NULL,
  `tenant_name` varchar(255) NOT NULL,
  `letter_of_intent` varchar(255) DEFAULT NULL,
  `business_profile` varchar(255) DEFAULT NULL,
  `business_registration` varchar(255) DEFAULT NULL,
  `valid_id` varchar(255) DEFAULT NULL,
  `bir_registration` varchar(255) DEFAULT NULL,
  `financial_statement` varchar(255) DEFAULT NULL,
  `application_sub` text NOT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tenant_applications`
--

INSERT INTO `tenant_applications` (`id`, `tenant_name`, `letter_of_intent`, `business_profile`, `business_registration`, `valid_id`, `bir_registration`, `financial_statement`, `application_sub`, `submitted_at`) VALUES
(1, '19', '?PNG\n\Z\n\0\0\0\nIHDR\0\0?\0\0?\0\0\0?|?\0\0\0sRGB\0???\0\0\0gAMA\0\0???a\0\0\0	pHYs\0\0?\0\0??o?d\0\0??IDATx^???]E?vk??????!08ww\'??k0?????k ??????v?\'w?;B??ݽ??T??ǥN????U?????\"\"\"\"\"\"\"?(?ߟS??@?*\"\"\"\"\"\"\n?BUDDDDDD\Z\n????????4\nUi(?\"\"\"\"\"\"?P(TEDDDDD??P???????HC?', '?PNG\r\n\Z\n\0\0\0\rIHDR\0\0?\0\0?\0\0\0?|?\0\0\0sRGB\0???\0\0\0gAMA\0\0???a\0\0\0	pHYs\0\0?\0\0??o?d\0\0??IDATx^???]E?vk??????!08ww\'??k0?????k ??????v?\'w?;B??ݽ??T??ǥN????U?????\"\"\"\"\"\"\"?(?ߟS??@?*\"\"\"\"\"\"\r?BUDDDDDD\Z\n????????4\nUi(?\"\"\"\"\"\"?P(TEDDDDD??P???????HC?', '?PNG\r\n\Z\n\0\0\0\rIHDR\0\0?\0\0?\0\0\0?|?\0\0\0sRGB\0???\0\0\0gAMA\0\0???a\0\0\0	pHYs\0\0?\0\0??o?d\0\0??IDATx^???]E?vk??????!08ww\'??k0?????k ??????v?\'w?;B??ݽ??T??ǥN????U?????\"\"\"\"\"\"\"?(?ߟS??@?*\"\"\"\"\"\"\r?BUDDDDDD\Z\n????????4\nUi(?\"\"\"\"\"\"?P(TEDDDDD??P???????HC?', '?PNG\r\n\Z\n\0\0\0\rIHDR\0\0?\0\0?\0\0\0?|?\0\0\0sRGB\0???\0\0\0gAMA\0\0???a\0\0\0	pHYs\0\0?\0\0??o?d\0\0??IDATx^???]E?vk??????!08ww\'??k0?????k ??????v?\'w?;B??ݽ??T??ǥN????U?????\"\"\"\"\"\"\"?(?ߟS??@?*\"\"\"\"\"\"\r?BUDDDDDD\Z\n????????4\nUi(?\"\"\"\"\"\"?P(TEDDDDD??P???????HC?', '?PNG\r\n\Z\n\0\0\0\rIHDR\0\0?\0\0?\0\0\0?|?\0\0\0sRGB\0???\0\0\0gAMA\0\0???a\0\0\0	pHYs\0\0?\0\0??o?d\0\0??IDATx^???]E?vk??????!08ww\'??k0?????k ??????v?\'w?;B??ݽ??T??ǥN????U?????\"\"\"\"\"\"\"?(?ߟS??@?*\"\"\"\"\"\"\r?BUDDDDDD\Z\n????????4\nUi(?\"\"\"\"\"\"?P(TEDDDDD??P???????HC?', '?PNG\r\n\Z\n\0\0\0\rIHDR\0\0?\0\0?\0\0\0?|?\0\0\0sRGB\0???\0\0\0gAMA\0\0???a\0\0\0	pHYs\0\0?\0\0??o?d\0\0??IDATx^???]E?vk??????!08ww\'??k0?????k ??????v?\'w?;B??ݽ??T??ǥN????U?????\"\"\"\"\"\"\"?(?ߟS??@?*\"\"\"\"\"\"\r?BUDDDDDD\Z\n????????4\nUi(?\"\"\"\"\"\"?P(TEDDDDD??P???????HC?', '2024-12-04 13:32:56', '2024-12-04 05:40:51'),
(3, '19', '?PNG\r\n\Z\n\0\0\0\rIHDR\0\0?\0\0?\0\0\0?|?\0\0\0sRGB\0???\0\0\0gAMA\0\0???a\0\0\0	pHYs\0\0?\0\0??o?d\0\0??IDATx^???]E?vk??????!08ww\'??k0?????k ??????v?\'w?;B??ݽ??T??ǥN????U?????\"\"\"\"\"\"\"?(?ߟS??@?*\"\"\"\"\"\"\r?BUDDDDDD\Z\n????????4\nUi(?\"\"\"\"\"\"?P(TEDDDDD??P???????HC?', '?PNG\r\n\Z\n\0\0\0\rIHDR\0\0?\0\0?\0\0\0?|?\0\0\0sRGB\0???\0\0\0gAMA\0\0???a\0\0\0	pHYs\0\0?\0\0??o?d\0\0??IDATx^???]E?vk??????!08ww\'??k0?????k ??????v?\'w?;B??ݽ??T??ǥN????U?????\"\"\"\"\"\"\"?(?ߟS??@?*\"\"\"\"\"\"\r?BUDDDDDD\Z\n????????4\nUi(?\"\"\"\"\"\"?P(TEDDDDD??P???????HC?', '?PNG\r\n\Z\n\0\0\0\rIHDR\0\0?\0\0?\0\0\0?|?\0\0\0sRGB\0???\0\0\0gAMA\0\0???a\0\0\0	pHYs\0\0?\0\0??o?d\0\0??IDATx^???]E?vk??????!08ww\'??k0?????k ??????v?\'w?;B??ݽ??T??ǥN????U?????\"\"\"\"\"\"\"?(?ߟS??@?*\"\"\"\"\"\"\r?BUDDDDDD\Z\n????????4\nUi(?\"\"\"\"\"\"?P(TEDDDDD??P???????HC?', '?PNG\r\n\Z\n\0\0\0\rIHDR\0\0?\0\0?\0\0\0?|?\0\0\0sRGB\0???\0\0\0gAMA\0\0???a\0\0\0	pHYs\0\0?\0\0??o?d\0\0??IDATx^???]E?vk??????!08ww\'??k0?????k ??????v?\'w?;B??ݽ??T??ǥN????U?????\"\"\"\"\"\"\"?(?ߟS??@?*\"\"\"\"\"\"\r?BUDDDDDD\Z\n????????4\nUi(?\"\"\"\"\"\"?P(TEDDDDD??P???????HC?', '?PNG\r\n\Z\n\0\0\0\rIHDR\0\0?\0\0?\0\0\0?|?\0\0\0sRGB\0???\0\0\0gAMA\0\0???a\0\0\0	pHYs\0\0?\0\0??o?d\0\0??IDATx^???]E?vk??????!08ww\'??k0?????k ??????v?\'w?;B??ݽ??T??ǥN????U?????\"\"\"\"\"\"\"?(?ߟS??@?*\"\"\"\"\"\"\r?BUDDDDDD\Z\n????????4\nUi(?\"\"\"\"\"\"?P(TEDDDDD??P???????HC?', '?PNG\r\n\Z\n\0\0\0\rIHDR\0\0?\0\0?\0\0\0?|?\0\0\0sRGB\0???\0\0\0gAMA\0\0???a\0\0\0	pHYs\0\0?\0\0??o?d\0\0??IDATx^???]E?vk??????!08ww\'??k0?????k ??????v?\'w?;B??ݽ??T??ǥN????U?????\"\"\"\"\"\"\"?(?ߟS??@?*\"\"\"\"\"\"\r?BUDDDDDD\Z\n????????4\nUi(?\"\"\"\"\"\"?P(TEDDDDD??P???????HC?', '2024-12-04 13:47:48', '2024-12-04 05:47:48');

-- --------------------------------------------------------

--
-- Table structure for table `tenant_documents`
--

CREATE TABLE `tenant_documents` (
  `id` int(11) NOT NULL,
  `tenant_id` int(11) NOT NULL,
  `document_name` varchar(255) DEFAULT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `maintenance_requests`
--
ALTER TABLE `maintenance_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tenant_id` (`tenant_id`);

--
-- Indexes for table `renewal_requests`
--
ALTER TABLE `renewal_requests`
  ADD PRIMARY KEY (`request_id`),
  ADD KEY `id` (`id`);

--
-- Indexes for table `spaces`
--
ALTER TABLE `spaces`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tenant_id` (`tenant_id`);

--
-- Indexes for table `tenants`
--
ALTER TABLE `tenants`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `tenant_applications`
--
ALTER TABLE `tenant_applications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tenant_documents`
--
ALTER TABLE `tenant_documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tenant_id` (`tenant_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `maintenance_requests`
--
ALTER TABLE `maintenance_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `renewal_requests`
--
ALTER TABLE `renewal_requests`
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=111;

--
-- AUTO_INCREMENT for table `spaces`
--
ALTER TABLE `spaces`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `tenants`
--
ALTER TABLE `tenants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `tenant_applications`
--
ALTER TABLE `tenant_applications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tenant_documents`
--
ALTER TABLE `tenant_documents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`);

--
-- Constraints for table `renewal_requests`
--
ALTER TABLE `renewal_requests`
  ADD CONSTRAINT `renewal_requests_ibfk_1` FOREIGN KEY (`id`) REFERENCES `tenants` (`id`);

--
-- Constraints for table `spaces`
--
ALTER TABLE `spaces`
  ADD CONSTRAINT `spaces_ibfk_1` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `tenant_documents`
--
ALTER TABLE `tenant_documents`
  ADD CONSTRAINT `tenant_documents_ibfk_1` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
CREATE TABLE tenant_information (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tradename VARCHAR(255),
    store_premises VARCHAR(255),
    store_location VARCHAR(255),
    ownership VARCHAR(255),
    company_name VARCHAR(255),
    business_address TEXT,
    tin VARCHAR(255),
    office_tel VARCHAR(255),
    tenant_representative VARCHAR(255),
    contact_person VARCHAR(255),
    position VARCHAR(255),
    contact_tel VARCHAR(255),
    mobile VARCHAR(255),
    email VARCHAR(255),
    prepared_by VARCHAR(255),
    submission_date DATETIME
);

-- ============================================================
-- Database: cmrwas_db
-- Campus Maintenance System (CMRWAS)
-- Compatible with XAMPP MySQL / MariaDB
-- ============================================================

CREATE DATABASE IF NOT EXISTS `cmrwas_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `cmrwas_db`;

SET FOREIGN_KEY_CHECKS = 0;

-- ------------------------------------------------------------
-- Table: users
-- ------------------------------------------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `full_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('admin','user') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Table: technicians
-- ------------------------------------------------------------
DROP TABLE IF EXISTS `technicians`;
CREATE TABLE `technicians` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `technician_code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `specialization` enum('Electrical','Plumbing','Carpentry','General Maintenance') COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('Available','Busy','Inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Available',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `technician_code` (`technician_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Table: equipment
-- ------------------------------------------------------------
DROP TABLE IF EXISTS `equipment`;
CREATE TABLE `equipment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `equipment_code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `equipment_name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `location` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `condition_status` enum('Good','Needs Repair','Damaged') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Good',
  `equipment_status` enum('Operational','Under Maintenance','Not Operational') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Operational',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `equipment_code` (`equipment_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Table: maintenance_requests
-- ------------------------------------------------------------
DROP TABLE IF EXISTS `maintenance_requests`;
CREATE TABLE `maintenance_requests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `request_code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `requester_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `location` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` enum('Electrical','Plumbing','Furniture','Cleaning','Air Conditioning','Other') COLLATE utf8mb4_unicode_ci NOT NULL,
  `priority` enum('Low','Medium','High') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Medium',
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('Pending','Approved','Rejected','In Progress','Completed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Pending',
  `technician_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `request_code` (`request_code`),
  KEY `fk_request_user` (`user_id`),
  KEY `fk_request_technician` (`technician_id`),
  CONSTRAINT `fk_request_technician` FOREIGN KEY (`technician_id`) REFERENCES `technicians` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_request_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- Table: transaction_history
-- ------------------------------------------------------------
DROP TABLE IF EXISTS `transaction_history`;
CREATE TABLE `transaction_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `request_id` int(11) DEFAULT NULL,
  `details` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_history_user` (`user_id`),
  KEY `fk_history_request` (`request_id`),
  CONSTRAINT `fk_history_request` FOREIGN KEY (`request_id`) REFERENCES `maintenance_requests` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_history_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- View: request_details
-- ------------------------------------------------------------
DROP VIEW IF EXISTS `request_details`;
CREATE VIEW `request_details` AS 
SELECT 
  `mr`.`id` AS `id`,
  `mr`.`request_code` AS `request_code`,
  `mr`.`user_id` AS `user_id`,
  `mr`.`requester_name` AS `requester_name`,
  `mr`.`location` AS `location`,
  `mr`.`category` AS `category`,
  `mr`.`priority` AS `priority`,
  `mr`.`description` AS `description`,
  `mr`.`status` AS `status`,
  `mr`.`technician_id` AS `technician_id`,
  `t`.`name` AS `technician`,
  `t`.`specialization` AS `specialization`,
  `u`.`username` AS `created_by`,
  `mr`.`created_at` AS `created_at`,
  `mr`.`updated_at` AS `updated_at` 
FROM `maintenance_requests` `mr`
LEFT JOIN `technicians` `t` ON (`mr`.`technician_id` = `t`.`id`)
LEFT JOIN `users` `u` ON (`mr`.`user_id` = `u`.`id`);

-- ------------------------------------------------------------
-- View: dashboard_summary
-- ------------------------------------------------------------
DROP VIEW IF EXISTS `dashboard_summary`;
CREATE VIEW `dashboard_summary` AS 
SELECT 
  count(0) AS `total_requests`,
  sum(case when `maintenance_requests`.`status` = 'Pending' then 1 else 0 end) AS `pending_requests`,
  sum(case when `maintenance_requests`.`status` = 'In Progress' then 1 else 0 end) AS `in_progress_requests`,
  sum(case when `maintenance_requests`.`status` = 'Completed' then 1 else 0 end) AS `completed_requests` 
FROM `maintenance_requests`;

-- ------------------------------------------------------------
-- Seed Data
-- ------------------------------------------------------------
INSERT INTO `users` (`id`, `full_name`, `username`, `email`, `password`, `role`, `status`) VALUES
(1, 'Administrator', 'admin', 'admin@cmrwas.com', 'admin123', 'admin', 'active'),
(2, 'Juan Dela Cruz', 'juan', 'juan@example.com', 'user123', 'user', 'active')
ON DUPLICATE KEY UPDATE `username`=`username`;

INSERT INTO `technicians` (`id`, `technician_code`, `name`, `specialization`, `contact`, `status`) VALUES
(1, 'TECH-001', 'Mark Reyes', 'Electrical', '09123456789', 'Available'),
(2, 'TECH-002', 'John Cruz', 'Plumbing', '09234567890', 'Busy'),
(3, 'TECH-003', 'Michael Santos', 'Carpentry', '09345678901', 'Available')
ON DUPLICATE KEY UPDATE `technician_code`=`technician_code`;

INSERT INTO `equipment` (`id`, `equipment_code`, `equipment_name`, `category`, `location`, `condition_status`, `equipment_status`) VALUES
(1, 'EQ-001', 'Air Conditioner', 'HVAC', 'Room 305', 'Good', 'Operational'),
(2, 'EQ-002', 'Ceiling Fan', 'Electrical', 'Room 201', 'Needs Repair', 'Under Maintenance'),
(3, 'EQ-003', 'Water Pump', 'Plumbing', 'Building A', 'Good', 'Operational')
ON DUPLICATE KEY UPDATE `equipment_code`=`equipment_code`;

INSERT INTO `maintenance_requests` (`id`, `request_code`, `user_id`, `requester_name`, `location`, `category`, `priority`, `description`, `status`, `technician_id`) VALUES
(1, 'MR-001', 2, 'Juan Dela Cruz', 'Room 201', 'Electrical', 'High', 'Broken ceiling light.', 'Pending', NULL),
(2, 'MR-002', 2, 'Juan Dela Cruz', 'Laboratory 1', 'Plumbing', 'Medium', 'Water leaking from sink.', 'Approved', 2),
(3, 'MR-003', 2, 'Juan Dela Cruz', 'Library', 'Furniture', 'Low', 'Broken chair.', 'In Progress', 3),
(4, 'MR-004', 2, 'Juan Dela Cruz', 'Room 305', 'Air Conditioning', 'High', 'Air conditioner not working.', 'Completed', 1)
ON DUPLICATE KEY UPDATE `request_code`=`request_code`;

SET FOREIGN_KEY_CHECKS = 1;

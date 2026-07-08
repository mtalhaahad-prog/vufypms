-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 06, 2026 at 01:23 AM
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
-- Database: `vufypms`
--

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `announcement_id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `body` text NOT NULL,
  `published_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `announcements`
--

INSERT INTO `announcements` (`announcement_id`, `admin_id`, `title`, `body`, `published_at`) VALUES
(1, 1, 'FYP First Deliverable Deadline', 'All teams must submit their first deliverable documentation by 30 April 2026. Late submissions will not be accepted.', '2026-05-10 11:08:27'),
(2, 1, 'New Project Detail', 'A new project assign sooner be ready', '2026-07-04 07:10:46');

-- --------------------------------------------------------

--
-- Table structure for table `documents`
--

CREATE TABLE `documents` (
  `document_id` int(11) NOT NULL,
  `team_id` int(11) NOT NULL,
  `milestone_id` int(11) DEFAULT NULL,
  `doc_type` enum('proposal','SRS','design','progress_report','final_report','other') NOT NULL,
  `file_path` varchar(300) NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `documents`
--

INSERT INTO `documents` (`document_id`, `team_id`, `milestone_id`, `doc_type`, `file_path`, `uploaded_at`) VALUES
(2, 1, 1, 'SRS', 'documents/1_SRS_1783077023_1_proposal_1783027292_Spring_2026_CS519_12929__2_.docx', '2026-07-03 18:10:23');

-- --------------------------------------------------------

--
-- Table structure for table `evaluations`
--

CREATE TABLE `evaluations` (
  `eval_id` int(11) NOT NULL,
  `team_id` int(11) NOT NULL,
  `supervisor_id` int(11) NOT NULL,
  `phase` enum('proposal_defense','progress_review','final_defense') NOT NULL,
  `marks` int(11) NOT NULL CHECK (`marks` between 0 and 100),
  `remarks` text DEFAULT NULL,
  `evaluated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `evaluations`
--

INSERT INTO `evaluations` (`eval_id`, `team_id`, `supervisor_id`, `phase`, `marks`, `remarks`, `evaluated_at`) VALUES
(1, 1, 1, 'proposal_defense', 85, 'Well structured proposal. Good understanding of the problem domain.', '2026-07-02 21:35:51');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `message_id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `team_id` int(11) DEFAULT NULL,
  `content` text NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `sent_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`message_id`, `sender_id`, `receiver_id`, `team_id`, `content`, `is_read`, `sent_at`) VALUES
(1, 3, 2, 1, 'hello', 1, '2026-07-03 04:27:53'),
(2, 2, 3, 1, 'Good work Talha! Please continue with the development phase.', 1, '2026-07-02 21:29:03'),
(3, 3, 2, 1, 'hsadahdhasdhashdasd', 1, '2026-07-03 18:08:53'),
(4, 2, 3, 1, 'hello', 1, '2026-07-04 06:45:07');

-- --------------------------------------------------------

--
-- Table structure for table `milestones`
--

CREATE TABLE `milestones` (
  `milestone_id` int(11) NOT NULL,
  `proposal_id` int(11) NOT NULL,
  `title` varchar(150) NOT NULL,
  `due_date` date NOT NULL,
  `status` enum('pending','in_progress','done') NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `milestones`
--

INSERT INTO `milestones` (`milestone_id`, `proposal_id`, `title`, `due_date`, `status`) VALUES
(1, 1, 'Requirement Analysis & First Deliverable', '2026-04-30', 'done'),
(2, 1, 'Proposal Approval', '2026-05-21', 'in_progress'),
(3, 1, 'Development Phase', '2026-06-30', 'in_progress'),
(4, 1, 'Testing & Bug Fixing', '2026-07-18', 'pending'),
(5, 1, 'Final Defense & Submission', '2026-07-31', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `presentations`
--

CREATE TABLE `presentations` (
  `presentation_id` int(11) NOT NULL,
  `team_id` int(11) NOT NULL,
  `phase` enum('proposal_defense','progress_review','final_defense') NOT NULL,
  `scheduled_at` datetime NOT NULL,
  `venue_or_link` varchar(255) DEFAULT NULL,
  `panel_info` varchar(255) DEFAULT NULL,
  `status` enum('scheduled','completed','cancelled') NOT NULL DEFAULT 'scheduled'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `presentations`
--

INSERT INTO `presentations` (`presentation_id`, `team_id`, `phase`, `scheduled_at`, `venue_or_link`, `panel_info`, `status`) VALUES
(1, 1, 'progress_review', '2026-07-15 10:00:00', 'https://teams.microsoft.com/meet/demo', 'Dr. Azeem Sarwar, Dr. Ali Khan', 'scheduled'),
(2, 1, 'final_defense', '2026-07-23 08:14:00', 'https://teams.microsoft.com/meet/demo', 'Dr. Talha Ahad', 'scheduled');

-- --------------------------------------------------------

--
-- Table structure for table `proposals`
--

CREATE TABLE `proposals` (
  `proposal_id` int(11) NOT NULL,
  `team_id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `abstract` text NOT NULL,
  `domain` varchar(100) NOT NULL,
  `tools` text DEFAULT NULL,
  `status` enum('pending','approved','rejected','revision') NOT NULL DEFAULT 'pending',
  `supervisor_remarks` text DEFAULT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `proposals`
--

INSERT INTO `proposals` (`proposal_id`, `team_id`, `title`, `abstract`, `domain`, `tools`, `status`, `supervisor_remarks`, `submitted_at`, `updated_at`) VALUES
(1, 1, 'Virtual University Final Year Project Management System (VUFYPMS)', 'A web-based application designed to automate and streamline the complete lifecycle of final year projects at Virtual University of Pakistan using Laravel framework. The application', 'Web Application', 'Laravel, MySQL, Bootstrap, jQuery, AJAX', 'pending', NULL, '2026-05-10 11:08:27', '2026-07-03 04:02:23');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `student_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `vuid` varchar(20) NOT NULL,
  `program` varchar(100) NOT NULL,
  `semester` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`student_id`, `user_id`, `vuid`, `program`, `semester`) VALUES
(1, 3, 'BC240401856', 'BS Computer Science', 8),
(2, 4, 'BC240401854', 'BS Software Engineering', 6),
(3, 6, 'BC240401853', 'BS Software Engineering', 8);

-- --------------------------------------------------------

--
-- Table structure for table `supervisors`
--

CREATE TABLE `supervisors` (
  `supervisor_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `department` varchar(100) NOT NULL,
  `max_teams` int(11) NOT NULL DEFAULT 5
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `supervisors`
--

INSERT INTO `supervisors` (`supervisor_id`, `user_id`, `department`, `max_teams`) VALUES
(1, 2, 'Computer Science', 10),
(2, 5, 'Information Technology', 3);

-- --------------------------------------------------------

--
-- Table structure for table `teams`
--

CREATE TABLE `teams` (
  `team_id` int(11) NOT NULL,
  `supervisor_id` int(11) DEFAULT NULL,
  `group_code` varchar(30) NOT NULL,
  `team_name` varchar(100) NOT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `teams`
--

INSERT INTO `teams` (`team_id`, `supervisor_id`, `group_code`, `team_name`, `status`, `created_at`) VALUES
(1, 2, 'S26PROJECTEB46B', 'Team VUFYPMS', 'active', '2026-05-10 11:08:27');

-- --------------------------------------------------------

--
-- Table structure for table `team_members`
--

CREATE TABLE `team_members` (
  `id` int(11) NOT NULL,
  `team_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `role` enum('leader','member') NOT NULL DEFAULT 'member',
  `joined_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `team_members`
--

INSERT INTO `team_members` (`id`, `team_id`, `student_id`, `role`, `joined_at`) VALUES
(1, 1, 1, 'leader', '2026-05-10 11:08:27'),
(2, 1, 2, 'member', '2026-07-02 21:09:47');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('student','supervisor','admin') NOT NULL DEFAULT 'student',
  `notification_prefs` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `email`, `password`, `role`, `notification_prefs`, `created_at`, `updated_at`) VALUES
(1, 'System Administrator', 'admin@vu.edu.pk', '$2y$12$Me5.Z7.LDgFQvBEHut/AmufFUcEuv4hz8YTP1sK5ARUdHfhI2loom', 'admin', NULL, '2026-05-10 11:08:26', '2026-07-02 20:54:37'),
(2, 'Hafiz Muhammad Azeem Sarwar', 'azeem.sarwar@vu.edu.pk', '$2y$12$Ba9LRC8l6NXV6IC46rRi7ujM04h8hlkFogOpDNn5ANrzfkOI0QGS6', 'supervisor', NULL, '2026-05-10 11:08:26', '2026-07-02 20:54:37'),
(3, 'Muhammad Talha Ahad', 'talha@vu.edu.pk', '$2y$12$9Y0z5FrDzjp5a3lr06oFVeFpqg2ZOv2TfvY1DzyHy8d57F2XU7HGO', 'student', NULL, '2026-05-10 11:08:26', '2026-07-02 20:54:37'),
(4, 'ahad', 'ahad@gmail.com', '$2y$12$OiPx0cHhk9ESCVWsy.m6tOvSUkQDCGntxIUyv6HIJ.TfDSI9VP9NC', 'student', NULL, '2026-06-26 04:43:23', '2026-06-26 04:43:23'),
(5, 'Talha', 'talha12@gmail.com', '$2y$12$PnzVU7TSgDkXjPE23uFHnu7ydZiLKTR6adhZreE1oJrRStKeBJyVy', 'supervisor', NULL, '2026-06-26 04:50:09', '2026-06-26 04:50:09'),
(6, 'Ayan Khan', 'Ayan@vu.edu.pk', '$2y$12$Wcj812FX.yueouzWyk4U1OnXFsIZQLOcm.0NVWciMvuG05hGgC/Jy', 'student', NULL, '2026-06-27 03:20:36', '2026-06-27 03:20:36');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`announcement_id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `documents`
--
ALTER TABLE `documents`
  ADD PRIMARY KEY (`document_id`),
  ADD KEY `team_id` (`team_id`),
  ADD KEY `milestone_id` (`milestone_id`);

--
-- Indexes for table `evaluations`
--
ALTER TABLE `evaluations`
  ADD PRIMARY KEY (`eval_id`),
  ADD KEY `team_id` (`team_id`),
  ADD KEY `supervisor_id` (`supervisor_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`message_id`),
  ADD KEY `sender_id` (`sender_id`),
  ADD KEY `receiver_id` (`receiver_id`),
  ADD KEY `team_id` (`team_id`);

--
-- Indexes for table `milestones`
--
ALTER TABLE `milestones`
  ADD PRIMARY KEY (`milestone_id`),
  ADD KEY `proposal_id` (`proposal_id`);

--
-- Indexes for table `presentations`
--
ALTER TABLE `presentations`
  ADD PRIMARY KEY (`presentation_id`),
  ADD KEY `team_id` (`team_id`);

--
-- Indexes for table `proposals`
--
ALTER TABLE `proposals`
  ADD PRIMARY KEY (`proposal_id`),
  ADD KEY `team_id` (`team_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`student_id`),
  ADD UNIQUE KEY `user_id` (`user_id`),
  ADD UNIQUE KEY `vuid` (`vuid`);

--
-- Indexes for table `supervisors`
--
ALTER TABLE `supervisors`
  ADD PRIMARY KEY (`supervisor_id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Indexes for table `teams`
--
ALTER TABLE `teams`
  ADD PRIMARY KEY (`team_id`),
  ADD UNIQUE KEY `group_code` (`group_code`),
  ADD KEY `supervisor_id` (`supervisor_id`);

--
-- Indexes for table `team_members`
--
ALTER TABLE `team_members`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_team_student` (`team_id`,`student_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `announcement_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `documents`
--
ALTER TABLE `documents`
  MODIFY `document_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `evaluations`
--
ALTER TABLE `evaluations`
  MODIFY `eval_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `milestones`
--
ALTER TABLE `milestones`
  MODIFY `milestone_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `presentations`
--
ALTER TABLE `presentations`
  MODIFY `presentation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `proposals`
--
ALTER TABLE `proposals`
  MODIFY `proposal_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `student_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `supervisors`
--
ALTER TABLE `supervisors`
  MODIFY `supervisor_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `teams`
--
ALTER TABLE `teams`
  MODIFY `team_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `team_members`
--
ALTER TABLE `team_members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `announcements`
--
ALTER TABLE `announcements`
  ADD CONSTRAINT `announcements_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `documents`
--
ALTER TABLE `documents`
  ADD CONSTRAINT `documents_ibfk_1` FOREIGN KEY (`team_id`) REFERENCES `teams` (`team_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `documents_ibfk_2` FOREIGN KEY (`milestone_id`) REFERENCES `milestones` (`milestone_id`) ON DELETE SET NULL;

--
-- Constraints for table `evaluations`
--
ALTER TABLE `evaluations`
  ADD CONSTRAINT `evaluations_ibfk_1` FOREIGN KEY (`team_id`) REFERENCES `teams` (`team_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `evaluations_ibfk_2` FOREIGN KEY (`supervisor_id`) REFERENCES `supervisors` (`supervisor_id`);

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_ibfk_3` FOREIGN KEY (`team_id`) REFERENCES `teams` (`team_id`) ON DELETE SET NULL;

--
-- Constraints for table `milestones`
--
ALTER TABLE `milestones`
  ADD CONSTRAINT `milestones_ibfk_1` FOREIGN KEY (`proposal_id`) REFERENCES `proposals` (`proposal_id`) ON DELETE CASCADE;

--
-- Constraints for table `presentations`
--
ALTER TABLE `presentations`
  ADD CONSTRAINT `presentations_ibfk_1` FOREIGN KEY (`team_id`) REFERENCES `teams` (`team_id`) ON DELETE CASCADE;

--
-- Constraints for table `proposals`
--
ALTER TABLE `proposals`
  ADD CONSTRAINT `proposals_ibfk_1` FOREIGN KEY (`team_id`) REFERENCES `teams` (`team_id`) ON DELETE CASCADE;

--
-- Constraints for table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `students_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `supervisors`
--
ALTER TABLE `supervisors`
  ADD CONSTRAINT `supervisors_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `teams`
--
ALTER TABLE `teams`
  ADD CONSTRAINT `teams_ibfk_1` FOREIGN KEY (`supervisor_id`) REFERENCES `supervisors` (`supervisor_id`);

--
-- Constraints for table `team_members`
--
ALTER TABLE `team_members`
  ADD CONSTRAINT `team_members_ibfk_1` FOREIGN KEY (`team_id`) REFERENCES `teams` (`team_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `team_members_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

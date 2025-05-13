-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 13. Mai 2025 um 07:40
-- Server-Version: 10.4.32-MariaDB
-- PHP-Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `social_owl`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `chats`
--

CREATE TABLE `chats` (
  `id` int(11) NOT NULL,
  `user1_id` int(11) NOT NULL,
  `user2_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `chats`
--

INSERT INTO `chats` (`id`, `user1_id`, `user2_id`, `created_at`) VALUES
(12, 8, 16, '2025-05-05 14:41:26'),
(13, 8, 17, '2025-05-05 14:41:27'),
(14, 8, 27, '2025-05-06 09:56:03');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

--
-- Daten für Tabelle `comments`
--

INSERT INTO `comments` (`id`, `post_id`, `user_id`, `content`, `created_at`) VALUES
(130, 190, 16, 'Das ist super!', '2025-05-05 10:15:09');

--
-- Trigger `comments`
--
DELIMITER $$
CREATE TRIGGER `after_comment_update` AFTER UPDATE ON `comments` FOR EACH ROW BEGIN
    INSERT INTO edit_log (type, item_id, user_id)
    VALUES ('comment', NEW.id, NEW.user_id);
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `before_comment_delete` BEFORE DELETE ON `comments` FOR EACH ROW BEGIN
    INSERT INTO deletion_log (type, item_id, user_id)
    VALUES ('comment', OLD.id, OLD.user_id);
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `comment_likes`
--

CREATE TABLE `comment_likes` (
  `id` int(11) NOT NULL,
  `comment_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

--
-- Daten für Tabelle `comment_likes`
--

INSERT INTO `comment_likes` (`id`, `comment_id`, `user_id`, `created_at`) VALUES
(114, 130, 16, '2025-05-05 10:15:12');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `deletion_log`
--

CREATE TABLE `deletion_log` (
  `id` int(11) NOT NULL,
  `type` enum('post','comment') NOT NULL,
  `item_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `timestamp` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `deletion_log`
--

INSERT INTO `deletion_log` (`id`, `type`, `item_id`, `user_id`, `timestamp`) VALUES
(42, 'post', 185, 8, '2025-04-30 06:23:25'),
(43, 'comment', 118, 8, '2025-04-30 06:23:29'),
(44, 'comment', 122, 8, '2025-04-30 06:23:30'),
(45, 'comment', 124, 8, '2025-05-02 09:31:05'),
(46, 'comment', 126, 16, '2025-05-05 12:02:27'),
(47, 'comment', 121, 16, '2025-05-05 12:03:52'),
(48, 'comment', 120, 16, '2025-05-05 12:03:54'),
(49, 'post', 187, 8, '2025-05-05 12:05:46'),
(50, 'comment', 127, 16, '2025-05-05 12:14:25'),
(51, 'comment', 128, 16, '2025-05-05 12:14:50'),
(52, 'comment', 129, 16, '2025-05-05 12:15:02'),
(53, 'comment', 125, 8, '2025-05-06 09:35:45'),
(54, 'post', 191, 8, '2025-05-06 09:36:02'),
(55, 'comment', 131, 8, '2025-05-06 09:36:24'),
(56, 'post', 179, 8, '2025-05-06 09:45:20'),
(57, 'post', 193, 8, '2025-05-08 07:56:18'),
(58, 'comment', 132, 8, '2025-05-08 10:41:21'),
(59, 'comment', 133, 8, '2025-05-08 10:55:21'),
(60, 'comment', 134, 8, '2025-05-08 11:06:58'),
(61, 'comment', 135, 8, '2025-05-08 12:16:27'),
(62, 'comment', 136, 8, '2025-05-08 12:19:43'),
(63, 'comment', 137, 8, '2025-05-08 12:19:50'),
(64, 'comment', 138, 8, '2025-05-08 12:20:04'),
(65, 'post', 194, 8, '2025-05-12 09:45:38'),
(66, 'comment', 139, 8, '2025-05-12 09:46:06');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `edit_log`
--

CREATE TABLE `edit_log` (
  `id` int(11) NOT NULL,
  `type` enum('post','comment') NOT NULL,
  `item_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `timestamp` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `edit_log`
--

INSERT INTO `edit_log` (`id`, `type`, `item_id`, `user_id`, `timestamp`) VALUES
(17, 'comment', 125, 8, '2025-05-06 09:35:15'),
(18, 'comment', 125, 8, '2025-05-06 09:35:40'),
(19, 'comment', 131, 8, '2025-05-06 09:36:20'),
(20, 'comment', 132, 8, '2025-05-08 10:32:42'),
(21, 'comment', 133, 8, '2025-05-08 10:55:11'),
(22, 'comment', 134, 8, '2025-05-08 11:02:04');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `followers`
--

CREATE TABLE `followers` (
  `follower_id` int(11) NOT NULL,
  `followed_id` int(11) NOT NULL,
  `followed_at` timestamp NULL DEFAULT current_timestamp(),
  `status` enum('pending','accepted','rejected') NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

--
-- Daten für Tabelle `followers`
--

INSERT INTO `followers` (`follower_id`, `followed_id`, `followed_at`, `status`) VALUES
(8, 16, '2025-05-05 07:15:53', 'accepted'),
(8, 17, '2025-05-07 12:57:44', 'pending'),
(8, 27, '2025-05-08 05:02:52', 'accepted'),
(8, 28, '2025-05-07 12:45:10', 'accepted'),
(8, 30, '2025-05-07 12:49:52', 'accepted'),
(16, 8, '2025-04-30 07:35:58', 'accepted'),
(17, 8, '2025-05-02 09:20:49', 'accepted'),
(27, 8, '2025-05-05 09:10:41', 'accepted');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `chat_id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `message` mediumtext DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `is_read` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `messages`
--

INSERT INTO `messages` (`id`, `chat_id`, `sender_id`, `message`, `created_at`, `is_read`) VALUES
(24, 12, 8, '8eiwMDslwwThb1xi56ImdA==', '2025-05-07 15:03:11', 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `type` enum('follow','comment','follow_request') NOT NULL,
  `content` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `post_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `type`, `content`, `is_read`, `created_at`, `post_id`) VALUES
(57, 17, 'comment', '@nico91 hat deinen Post kommentiert', 0, '2025-05-02 09:24:08', 190),
(58, 17, '', '@nico91 gefällt dein Post', 0, '2025-05-05 06:29:00', 190),
(63, 17, 'follow', '@andrea94 hat deine Anfrage angenommen', 0, '2025-05-05 09:01:30', NULL),
(67, 17, 'comment', '@andrea94 hat deinen Post kommentiert', 0, '2025-05-05 09:54:59', 190),
(68, 17, 'comment', '@andrea94 hat deinen Post kommentiert', 0, '2025-05-05 10:11:16', 190),
(69, 17, 'comment', '@andrea94 hat deinen Post kommentiert', 0, '2025-05-05 10:14:31', 190),
(70, 17, 'comment', '@andrea94 hat deinen Post kommentiert', 0, '2025-05-05 10:14:58', 190),
(71, 17, 'comment', '@andrea94 hat deinen Post kommentiert', 0, '2025-05-05 10:15:09', 190),
(73, 17, 'comment', '@nico91 hat deinen Post kommentiert', 0, '2025-05-06 07:36:12', 190),
(76, 17, '', '@nico91 gefällt dein Post', 0, '2025-05-06 07:45:39', 190),
(77, 17, '', '@nico91 gefällt dein Post', 0, '2025-05-06 07:45:41', 189),
(78, 28, 'follow_request', '@nico91 möchte dir folgen', 0, '2025-05-07 12:45:10', NULL),
(79, 30, 'follow_request', '@nico91 möchte dir folgen', 0, '2025-05-07 12:49:52', NULL),
(80, 17, 'follow_request', '@nico91 möchte dir folgen', 0, '2025-05-07 12:57:44', NULL),
(82, 17, 'comment', '@nico91 hat deinen Post kommentiert', 0, '2025-05-08 08:32:26', 190),
(88, 17, 'comment', '@nico91 hat deinen Post kommentiert', 0, '2025-05-08 10:20:01', 190),
(90, 17, 'comment', '@nico91 hat deinen Post kommentiert', 0, '2025-05-12 07:46:00', 190);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` text DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `video_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `posts`
--

INSERT INTO `posts` (`id`, `user_id`, `content`, `image_path`, `created_at`, `updated_at`, `video_path`) VALUES
(175, 16, 'Das ist ein Test', NULL, '2025-04-29 06:12:30', '2025-04-29 06:12:30', NULL),
(176, 16, 'Das ist ein Test \r\n#Hallo', NULL, '2025-04-29 07:16:41', '2025-04-29 07:16:41', NULL),
(180, 8, 'Das ist ein Post mit einem Bild❤️\r\n#Cool', 'media_68108568a342e7.24357583.png', '2025-04-29 07:53:12', '2025-04-29 11:53:44', NULL),
(186, 16, '#pupsi :D', NULL, '2025-04-29 20:28:18', '2025-04-29 20:28:18', NULL),
(188, 16, 'Hallo das ist ein Test', NULL, '2025-04-30 07:40:50', '2025-04-30 07:40:50', NULL),
(189, 17, 'Hallo :) Ich bin nun auch da #Mega', NULL, '2025-05-02 09:21:34', '2025-05-02 09:21:34', NULL),
(190, 17, '#Mega', NULL, '2025-05-02 09:21:46', '2025-05-02 09:21:46', NULL),
(192, 27, 'Das ist ein Test', NULL, '2025-05-07 13:12:36', '2025-05-07 13:12:36', NULL);

--
-- Trigger `posts`
--
DELIMITER $$
CREATE TRIGGER `after_post_update` AFTER UPDATE ON `posts` FOR EACH ROW BEGIN
    INSERT INTO edit_log (type, item_id, user_id)
    VALUES ('post', NEW.id, NEW.user_id);
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `before_post_delete` BEFORE DELETE ON `posts` FOR EACH ROW BEGIN
    INSERT INTO deletion_log (type, item_id, user_id)
    VALUES ('post', OLD.id, OLD.user_id);
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `post_likes`
--

CREATE TABLE `post_likes` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `post_likes`
--

INSERT INTO `post_likes` (`id`, `user_id`, `post_id`, `created_at`) VALUES
(66, 8, 176, '2025-04-29 07:41:55'),
(68, 16, 175, '2025-04-29 07:50:21'),
(70, 16, 180, '2025-04-29 07:53:41'),
(74, 8, 186, '2025-04-30 05:28:23'),
(75, 8, 188, '2025-04-30 07:41:12'),
(80, 8, 175, '2025-05-06 07:45:23'),
(82, 8, 189, '2025-05-06 07:45:41'),
(89, 8, 192, '2025-05-12 13:48:30'),
(91, 8, 190, '2025-05-13 05:24:48');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `lastname` varchar(255) DEFAULT NULL,
  `firstname` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_active` datetime DEFAULT NULL,
  `reset_token` varchar(255) DEFAULT NULL,
  `remember_token` varchar(255) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `profile_img` varchar(255) DEFAULT 'profil.png',
  `header_img` varchar(255) DEFAULT 'default_header.png',
  `reset_pin` varchar(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `lastname`, `firstname`, `created_at`, `last_active`, `reset_token`, `remember_token`, `bio`, `profile_img`, `header_img`, `reset_pin`) VALUES
(8, 'nico91', 'nico@example.com', '$2y$10$88EOUKIFgb2Dk/NspSdroeQB/yesLgMeN4BGB7e90GvqQHrF135Pe', 'Walter', 'Nico', '2025-03-27 10:10:08', '2025-05-13 07:39:05', 'c838e1321c096aa582dabbeb57d59329', '05ab8a0a252a2f02ca0aad245db291be', '💻 Programmierer mit ❤️', 'nico91_profile_1745562677.jpg', 'nico91_header_1745828984.jpg', NULL),
(16, 'andrea94', 'andrea@example.com', '$2y$10$lzg/h9BbMzVR5aJmx9jNJuMbFUayRSY5CwbYy4iAEdhJ2zAsKs5wi', 'Walter', 'Andrea', '2025-04-23 09:30:47', '2025-05-08 13:15:00', '6d586f0081a1e8b91dea940bb21e2383', NULL, 'Frontend Designerin 😍', 'andrea94_profile_1745487853.jpg', 'default_header.png', NULL),
(17, 'testuser', 'test@example.com', '$2y$10$C8xi6U4ttC5FU3unG/3/8.RtEPsVSGT3p71/fWLiXCRqZ/abNVi0W', 'Test', 'User', '2025-04-23 12:17:39', '2025-05-02 12:36:44', NULL, NULL, 'User für automatisierte Tests', 'profil.png', 'default_header.png', NULL),
(27, 'alexrahn', 'alex@example.com', '$2y$10$luL71FA8BTxinpTcnQ9RLesXAVbzQMteDf0AhQViipZEk.57d45fS', 'Rahn', 'Alexander', '2025-05-05 09:10:23', '2025-05-08 13:15:26', NULL, NULL, 'Hello', 'profil.png', 'default_header.png', NULL),
(28, 'georgde', 'georg@example.com', '$2y$10$Kw2GQY7sRyWTvxL.aDPDGO2eOt.4kXy5QGeEvWAT1kUxND3VF2CJG', 'Diesendorf', 'Georg', '2025-05-05 09:38:26', '2025-05-06 09:41:49', NULL, NULL, 'Fachinformatiker Systemintegration', 'profil.png', 'default_header.png', NULL),
(29, 'jowiegandhier', 'andreas@example.com', '$2y$10$fcWdEIRW19uSwXeirNdV3.OnwFYkMQiz26d0M2dXWkX.AJdFVz.Gu', 'Wiegand', 'Andreas', '2025-05-05 09:47:29', '2025-05-06 09:41:59', '7a87e4e921ae118837c706ed2539b0e0', NULL, '', 'profil.png', 'default_header.png', NULL),
(30, 'mustermann', 'max@example.com', '$2y$10$AZiDhC9BUAARO3OcJYkjvu9RgKjZe9CL173hI2RpJ7P8nbC.Gyjwi', 'Mustermann', 'Max', '2025-05-06 05:14:35', '2025-05-06 07:15:59', NULL, NULL, 'Ich bin ein Muster von einem Mann.', 'profil.png', 'default_header.png', NULL);

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `chats`
--
ALTER TABLE `chats`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_chat` (`user1_id`,`user2_id`);

--
-- Indizes für die Tabelle `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_comments_post` (`post_id`),
  ADD KEY `fk_comments_user` (`user_id`);

--
-- Indizes für die Tabelle `comment_likes`
--
ALTER TABLE `comment_likes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `comment_id` (`comment_id`,`user_id`),
  ADD UNIQUE KEY `unique_comment_like` (`comment_id`,`user_id`),
  ADD KEY `fk_commentlikes_user` (`user_id`);

--
-- Indizes für die Tabelle `deletion_log`
--
ALTER TABLE `deletion_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indizes für die Tabelle `edit_log`
--
ALTER TABLE `edit_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indizes für die Tabelle `followers`
--
ALTER TABLE `followers`
  ADD PRIMARY KEY (`follower_id`,`followed_id`),
  ADD KEY `idx_followed_at` (`followed_at`),
  ADD KEY `fk_followers_followed` (`followed_id`);

--
-- Indizes für die Tabelle `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `chat_id` (`chat_id`);

--
-- Indizes für die Tabelle `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `post_id` (`post_id`);

--
-- Indizes für die Tabelle `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_posts_user_id` (`user_id`);

--
-- Indizes für die Tabelle `post_likes`
--
ALTER TABLE `post_likes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`,`post_id`),
  ADD UNIQUE KEY `unique_like` (`user_id`,`post_id`),
  ADD KEY `fk_post_likes_post` (`post_id`);

--
-- Indizes für die Tabelle `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `remember_token` (`remember_token`),
  ADD KEY `idx_remember_token` (`remember_token`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `chats`
--
ALTER TABLE `chats`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT für Tabelle `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=140;

--
-- AUTO_INCREMENT für Tabelle `comment_likes`
--
ALTER TABLE `comment_likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=126;

--
-- AUTO_INCREMENT für Tabelle `deletion_log`
--
ALTER TABLE `deletion_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT für Tabelle `edit_log`
--
ALTER TABLE `edit_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT für Tabelle `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT für Tabelle `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=91;

--
-- AUTO_INCREMENT für Tabelle `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=195;

--
-- AUTO_INCREMENT für Tabelle `post_likes`
--
ALTER TABLE `post_likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=92;

--
-- AUTO_INCREMENT für Tabelle `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_comment_post` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_comment_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_comments_post` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_comments_post_id` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_comments_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_comments_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints der Tabelle `comment_likes`
--
ALTER TABLE `comment_likes`
  ADD CONSTRAINT `comment_likes_ibfk_1` FOREIGN KEY (`comment_id`) REFERENCES `comments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comment_likes_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_comment_like_comment` FOREIGN KEY (`comment_id`) REFERENCES `comments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_comment_like_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_comment_likes_comment` FOREIGN KEY (`comment_id`) REFERENCES `comments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_comment_likes_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_commentlikes_comment` FOREIGN KEY (`comment_id`) REFERENCES `comments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_commentlikes_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints der Tabelle `deletion_log`
--
ALTER TABLE `deletion_log`
  ADD CONSTRAINT `deletion_log_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints der Tabelle `edit_log`
--
ALTER TABLE `edit_log`
  ADD CONSTRAINT `edit_log_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints der Tabelle `followers`
--
ALTER TABLE `followers`
  ADD CONSTRAINT `fk_followed_user` FOREIGN KEY (`followed_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_follower_user` FOREIGN KEY (`follower_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_followers_followed` FOREIGN KEY (`followed_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_followers_follower` FOREIGN KEY (`follower_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `followers_ibfk_1` FOREIGN KEY (`follower_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `followers_ibfk_2` FOREIGN KEY (`followed_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints der Tabelle `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`chat_id`) REFERENCES `chats` (`id`) ON DELETE CASCADE;

--
-- Constraints der Tabelle `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `notifications_ibfk_2` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE;

--
-- Constraints der Tabelle `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `fk_posts_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_posts_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints der Tabelle `post_likes`
--
ALTER TABLE `post_likes`
  ADD CONSTRAINT `fk_post_likes_post` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_post_likes_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_postlikes_post` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_postlikes_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `post_likes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `post_likes_ibfk_2` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 05. Mai 2025 um 11:14
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
(8, 8, 17, '2025-04-30 13:26:12'),
(9, 8, 16, '2025-04-30 13:26:29');

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
(120, 179, 16, 'Cool', '2025-04-29 07:50:53'),
(121, 180, 16, 'Sehr nice 😎', '2025-04-29 07:53:59'),
(125, 190, 8, 'Das ist super', '2025-05-02 09:24:08');

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
(110, 120, 17, '2025-05-02 09:22:41'),
(111, 125, 17, '2025-05-02 09:24:23'),
(112, 125, 8, '2025-05-05 06:20:08');

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
(45, 'comment', 124, 8, '2025-05-02 09:31:05');

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
(8, 17, '2025-04-30 11:25:43', 'accepted'),
(16, 8, '2025-04-30 07:35:58', 'accepted'),
(17, 8, '2025-05-02 09:20:49', 'accepted'),
(17, 16, '2025-05-02 09:19:20', 'accepted'),
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
(18, 8, 8, '8eiwMDslwwThb1xi56ImdA==', '2025-05-02 11:08:24', 1),
(19, 8, 8, '03I6XYk8r5C3bgudrXqe4Q==', '2025-05-05 08:06:28', 0),
(20, 9, 8, '8eiwMDslwwThb1xi56ImdA==', '2025-05-05 11:00:47', 1),
(21, 9, 16, 'v6tHgNVsqT6l7DoaPMGwdw==', '2025-05-05 11:01:45', 1);

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
(65, 27, 'follow', '@nico91 hat deine Anfrage angenommen', 0, '2025-05-05 09:11:07', NULL);

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
(179, 8, 'Das ist nun geiler #Code', NULL, '2025-04-29 07:29:29', '2025-04-29 07:29:29', NULL),
(180, 8, 'Das ist ein Post mit einem Bild ❤️\r\n#Cool', 'media_68108568a342e7.24357583.png', '2025-04-29 07:53:12', '2025-04-29 11:53:44', NULL),
(186, 16, '#pupsi :D', NULL, '2025-04-29 20:28:18', '2025-04-29 20:28:18', NULL),
(187, 8, '#pupsi', NULL, '2025-04-30 07:25:21', '2025-04-30 07:25:21', NULL),
(188, 16, 'Hallo das ist ein Test', NULL, '2025-04-30 07:40:50', '2025-04-30 07:40:50', NULL),
(189, 17, 'Hallo :) Ich bin nun auch da #Mega', NULL, '2025-05-02 09:21:34', '2025-05-02 09:21:34', NULL),
(190, 17, '#Mega', NULL, '2025-05-02 09:21:46', '2025-05-02 09:21:46', NULL);

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
(67, 16, 179, '2025-04-29 07:42:12'),
(68, 16, 175, '2025-04-29 07:50:21'),
(70, 16, 180, '2025-04-29 07:53:41'),
(73, 8, 180, '2025-04-30 05:28:22'),
(74, 8, 186, '2025-04-30 05:28:23'),
(75, 8, 188, '2025-04-30 07:41:12'),
(77, 16, 187, '2025-04-30 08:00:33'),
(78, 8, 189, '2025-05-02 09:22:01'),
(79, 8, 190, '2025-05-05 06:29:00');

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
  `header_img` varchar(255) DEFAULT 'default_header.png'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `lastname`, `firstname`, `created_at`, `last_active`, `reset_token`, `remember_token`, `bio`, `profile_img`, `header_img`) VALUES
(8, 'nico91', 'nico@example.com', '$2y$10$ecmKZRFA1bfY7fYithUFOeBwVB/zbOG0N0ah532dJ3MJc59O0ZsOe', 'Walter', 'Nico', '2025-03-27 10:10:08', '2025-05-05 11:12:09', NULL, '05ab8a0a252a2f02ca0aad245db291be', '💻 Programmierer mit ❤️', 'nico91_profile_1745562677.jpg', 'nico91_header_1745828984.jpg'),
(16, 'andrea94', 'andrea@example.com', '$2y$10$C8xi6U4ttC5FU3unG/3/8.RtEPsVSGT3p71/fWLiXCRqZ/abNVi0W', 'Walter', 'Andrea', '2025-04-23 09:30:47', '2025-05-05 11:01:54', NULL, NULL, 'Frontend Designerin 😍', 'andrea94_profile_1745487853.jpg', 'default_header.png'),
(17, 'testuser', 'test@example.com', '$2y$10$C8xi6U4ttC5FU3unG/3/8.RtEPsVSGT3p71/fWLiXCRqZ/abNVi0W', 'Test', 'User', '2025-04-23 12:17:39', '2025-05-02 12:36:44', NULL, NULL, 'User für automatisierte Tests', 'profil.png', 'default_header.png'),
(27, 'alexrahn', 'alex@example.com', '$2y$10$luL71FA8BTxinpTcnQ9RLesXAVbzQMteDf0AhQViipZEk.57d45fS', 'Rahn', 'Alexander', '2025-05-05 09:10:23', '2025-05-05 11:11:55', NULL, NULL, 'Hello', 'profil.png', 'default_header.png');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT für Tabelle `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=126;

--
-- AUTO_INCREMENT für Tabelle `comment_likes`
--
ALTER TABLE `comment_likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=113;

--
-- AUTO_INCREMENT für Tabelle `deletion_log`
--
ALTER TABLE `deletion_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT für Tabelle `edit_log`
--
ALTER TABLE `edit_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT für Tabelle `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT für Tabelle `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT für Tabelle `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=191;

--
-- AUTO_INCREMENT für Tabelle `post_likes`
--
ALTER TABLE `post_likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- AUTO_INCREMENT für Tabelle `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

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

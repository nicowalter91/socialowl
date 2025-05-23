<?php
/**
 * API-Controller: Tabellen anlegen
 * Legt spezielle Log-Tabellen (z.B. für Lösch- und Edit-Logs) in der Datenbank an.
 * Antwortet mit JSON (success/message).
 */

require_once "../includes/config.php";
require_once '../../includes/connection.php';

try {
    // Tabellen erstellen
    $pdo->exec("CREATE TABLE IF NOT EXISTS `deletion_log` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `type` varchar(10) NOT NULL,
        `id` int(11) NOT NULL,
        `timestamp` datetime NOT NULL,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;");

    $pdo->exec("CREATE TABLE IF NOT EXISTS `edit_log` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `type` varchar(10) NOT NULL,
        `id` int(11) NOT NULL,
        `timestamp` datetime NOT NULL,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;");
    
    $pdo->exec("CREATE TABLE IF NOT EXISTS `follow_requests` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `requester_id` int(11) NOT NULL,
        `receiver_id` int(11) NOT NULL,
        `status` enum('pending', 'accepted', 'rejected') NOT NULL DEFAULT 'pending',
        `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
        `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`),
        UNIQUE KEY `unique_follow_request` (`requester_id`, `receiver_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;");

    echo json_encode([
        'success' => true,
        'message' => 'Tabellen erfolgreich erstellt'
    ]);
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Fehler beim Erstellen der Tabellen: ' . $e->getMessage()
    ]);
}
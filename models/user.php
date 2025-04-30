<?php
/**
 * Modellfunktionen für Benutzer (User)
 * Stellt Funktionen zum Laden und Initialisieren von Benutzerdaten bereit.
 */

/**
 * Holt alle Benutzerdaten aus der Datenbank anhand des Usernamens.
 *
 * @param string $username Benutzername
 * @return array|null Benutzerdaten als assoziatives Array oder null, falls nicht gefunden
 */
function fetchUserInfo($username) {
  global $conn;
  $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
  $stmt->bindParam(":username", $username);
  $stmt->execute();
  return $stmt->fetch(PDO::FETCH_ASSOC);
}

/**
 * Initialisiert die Session-Variablen für einen Nutzer.
 *
 * @param string $username Benutzername
 * @return array|null Die Benutzerdaten
 */
function initUserSession($username) {
  $user = fetchUserInfo($username);
  // Session-Variablen setzen (mit sinnvollen Defaults)
  $_SESSION["firstname"]   = $user["firstname"] ?? 'Unbekannt';
  $_SESSION["lastname"]    = $user["lastname"] ?? 'Unbekannt';
  $_SESSION["bio"]         = $user["bio"] ?? 'Noch keine Bio verfügbar';
  $_SESSION["follower"]    = $user["follower"] ?? 0;
  $_SESSION["following"]   = $user["following"] ?? 0;
  $_SESSION["profile_img"] = $user["profile_img"] ?? "./img/profil.png";
  $_SESSION["header_img"]  = $user["header_img"] ?? null;
  return $user;
}
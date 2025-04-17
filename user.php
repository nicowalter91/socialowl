<?php
function fetchUserInfo($username) {
  global $conn;

  $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
  $stmt->bindParam(":username", $username);
  $stmt->execute();
  return $stmt->fetch(PDO::FETCH_ASSOC);
}

function initUserSession($username) {
  $user = fetchUserInfo($username);

  $_SESSION["firstname"] = $user["firstname"] ?? 'Unbekannt';
  $_SESSION["lastname"]  = $user["lastname"] ?? 'Unbekannt';
  $_SESSION["bio"]       = $user["bio"] ?? 'Noch keine Bio verfügbar';
  $_SESSION["follower"]  = $user["follower"] ?? 0;
  $_SESSION["following"] = $user["following"] ?? 0;
  $_SESSION["profile_img"] = $user["profile_img"] ?? "./img/profil.png";
  $_SESSION["header_img"] = $user["header_img"] ?? null;

  return $user;
}


?>
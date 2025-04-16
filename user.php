// /includes/user.php
<?php
function fetchUserInfo($username) {
  global $conn;

  $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
  $stmt->bindParam(":username", $username);
  $stmt->execute();
  return $stmt->fetch(PDO::FETCH_ASSOC);
}

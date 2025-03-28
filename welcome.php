<?php
    require "connection.php";

    session_start();

    // Überprüfen, ob der Benutzer bereits angemeldet ist
    if (!isset($_SESSION["username"])) {
        
        // Wenn keine Session besteht, überprüfe das Cookie
        if (isset($_COOKIE["remember_token"])) {
            $remember_token = $_COOKIE["remember_token"];

            // Token in der Datenbank prüfen, um den Benutzernamen zu erhalten
            $stmt = $conn->prepare("SELECT username FROM users WHERE remember_token = :remember_token");
            $stmt->bindParam(":remember_token", $remember_token);
            $stmt->execute();
            $data = $stmt->fetch(PDO::FETCH_ASSOC);

            // Wenn ein Benutzer gefunden wird, die Session starten
            if ($data) {
                $_SESSION["username"] = $data["username"];
            } else {
                // Wenn kein Benutzer gefunden wird, abmelden
                setcookie("remember_token", "", time() - 3600); // Cookie löschen
            }
        }
        
        // Wenn keine gültige Session oder Cookie vorhanden ist, zum Login weiterleiten
        if (!isset($_SESSION["username"])) {
            header("Location: login.php");
            exit();
        }
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Social Owl</title>

    <!--Style-->
    <script src="https://kit.fontawesome.com/7cf2870798.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="shortcut icon" href="./img/Owl_logo.svg" type="image/x-icon">
    <link rel="stylesheet" href="./style.css">
</head>
<body>

  <!-- Navigation-Header -->
  <div class="nav" >
    <!-- Header-left -->
    <div class="nav-left">
      <img class="logo" src="./img/Owl_logo.svg" alt="Owl Logo">
      <div class="container-fluid">
    <form class="d-flex" role="search">
      <input class="form-control me-2" style="border-radius: 48px" type="search" placeholder="# Search" aria-label="Search">
    
    </form>
  </div>
    </div>
    <!-- Header-right -->
    <div class="nav-right">
    
      <div class="notification-container">
        <span class="icon"><i class="fa-solid fa-bell"></i></span>
        <span class="notification-badge">3</span>
      </div>
      <div class="dropdown">
                <a href="#" class="d-flex align-items-center link-body-emphasis text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="./img/profil.png" alt="" width="32" height="32" class="rounded-circle me-2">
                    <strong class="text-light"><?php echo $_SESSION["username"] ?></strong>
                </a>
                <ul class="dropdown-menu text-small shadow text-light">
                    <li><a class="dropdown-item" href="#"><i class="fa-solid fa-gear"></i> Settings</a></li>
                    <li><a class="dropdown-item" href="#"><i class="fa-solid fa-user"></i> Profile</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="./logout.php"><i class="fa-solid fa-right-from-bracket"></i> Sign out</a></li>
                </ul>
            </div>
    </div>
  </div>
  
  <!-- Grid-Layout -->
  <div class="parent">

    <!-- Sidebar-left -->
    <div class="left-top-sidebar">
      <div class="profile-top"></div>
      <div class="profile">
        <img class="profile-image" src="./img/profil.png" alt="Picture of a Man">
        <h3>Max Müller</h3>
        <p class="username">@<?php echo $_SESSION["username"] ?></p>
        <p class="bio" style="color: white; font-style: normal">🌍 Explorer of ideas</p>
      
      </div>

      <div class="stats">
        <div class="left-stats">
          <p style="color: #b7c8d2">Follower</p>
          <h3 style="color: white">7</h3>
      </div>
      <div class="right-stats">
        <p style="color: #b7c8d2">Following</p>
        <h3 style="color: white">35</h3>
      </div>
    </div>

    
    
    </div>
    <div class="left-down-sidebar">10</div>






    
    <!-- Main Content -->
    <div class="tweet-box">
      <div class="tweet-user">
        <img class="tweet-profile-image" src="./img/profil.png" alt="">
        <strong class="text-light tweet-box-name">@<?php echo $_SESSION["username"] ?></strong>
      </div>
      <div class="input-box">
        <input class="tweet-input-box" type="text" placeholder="What's happening?">
      </div>
      <div class="tweet-buttons-left">
      <label for="file-upload" class="btn btn-outline-light">+ Bild</label>
      <input type="file" id="file-upload" style="display: none;" />
      <label for="file-upload" class="btn btn-outline-light">+ Video</label>
      <input type="file" id="file-upload" style="display: none;" />
      
      <button type="button" class="btn btn-primary">Posten</button>
      </div>
      
    </div>
    <div class="searchbar">2</div>
    <div class="feed">
    
    </div>
    
  
  </div>


  <!-- Bootstrap 5 JS und Popper -->
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
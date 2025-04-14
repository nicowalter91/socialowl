<?php
require "connection.php";

session_start();

// Überprüfen, ob der Benutzer bereits angemeldet ist
if (!isset($_SESSION["username"])) {

    // Wenn keine Session besteht, überprüfe das Cookie
    if (isset($_COOKIE["remember_token"])) {
        $remember_token = $_COOKIE["remember_token"];

        // Erstes SQL-Statement: Überprüfe den remember_token und hole den Benutzernamen
        $stmt = $conn->prepare("SELECT username FROM users WHERE remember_token = :remember_token");
        $stmt->bindParam(":remember_token", $remember_token);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        // Wenn ein Benutzer gefunden wird, die Session starten
        if ($data) {
            $_SESSION["username"] = $data["username"];

            // Zweites SQL-Statement: Hole 'firstname' und 'lastname' aus der Datenbank
            $stmt2 = $conn->prepare("SELECT firstname, lastname FROM users WHERE username = :username");
            $stmt2->bindParam(":username", $_SESSION["username"]);
            $stmt2->execute();
            $data2 = $stmt2->fetch(PDO::FETCH_ASSOC);

            // Überprüfen, ob 'firstname' und 'lastname' vorhanden sind
            if ($data2) {
                $_SESSION["firstname"] = $data2["firstname"];
                $_SESSION["lastname"] = $data2["lastname"];
            } else {
                // Wenn keine Daten für Vorname und Nachname gefunden wurden
                $_SESSION["firstname"] = 'Unbekannt';
                $_SESSION["lastname"] = 'Unbekannt';
            }

        } else {
            // Wenn kein Benutzer mit diesem Token gefunden wurde, Cookie löschen
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
  <link rel="stylesheet" href="./css/bootstrap.min.css">
  <link rel="shortcut icon" href="./img/Owl_logo.svg" type="image/x-icon">
  <link rel="stylesheet" href="style.css">
</head>

<body>

  <!-- Navigation-Header -->
  <div class="nav">
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
        <ul class="dropdown-menu dropdown-menu-end">
          <li>
            <a class="dropdown-item" href="#"><i class="bi bi-person-circle me-2"></i>Profil</a>
          </li>
          <li>
            <a class="dropdown-item" href="settings.html"><i class="bi bi-gear me-2"></i>Einstellungen</a>
          </li>
          <li>
            <hr class="dropdown-divider" />
          </li>
          <li>
            <a class="dropdown-item text-danger" href="./logout.php"><i class="bi bi-box-arrow-right me-2"></i>Abmelden</a>
          </li>
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
        <h3><?php echo $_SESSION["firstname"] . " " . $_SESSION["lastname"]?></h3>
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
    <div class="left-down-sidebar">
      <div class="follow-suggestions bg-dark p-3 rounded">
        <h5 class="text-light mb-3">Benutzer zum Folgen</h5>

        <?php
        // Beispiel für das Abrufen von Benutzerdaten aus der Datenbank
        // $users = Abrufen von Benutzern, denen man folgen kann
        // Angenommen, es gibt eine Tabelle 'users' mit den Spalten 'profile_img', 'username'
        $users = [
          [
            'profile_img' => './img/profil.png', // Profilbild
            'username' => 'Max Müller'           // Benutzername
          ],
          [
            'profile_img' => './img/profil.png',
            'username' => 'Anna Schmidt'
          ],
          [
            'profile_img' => './img/profil.png',
            'username' => 'John Doe'
          ]
        ];

        // Durchlaufen der Benutzer und anzeigen der Vorschläge
        foreach ($users as $user) {
          echo '
                <div class="user-suggestion d-flex justify-content-between align-items-center bg-dark p-2 rounded mb-2">
                    <div class="d-flex align-items-center">
                        <!-- Profilbild des Benutzers -->
                        <img class="profile-image" src="' . $user['profile_img'] . '" alt="Profilbild" style="width: 40px; height: 40px; border-radius: 50%;">
                        <strong class="text-light ms-3">' . $user['username'] . '</strong>
                    </div>
                    <!-- Follow-Button -->
                    <button class="btn btn-primary btn-sm">Folgen</button>
                </div>
            ';
        }
        ?>
      </div>
    </div>








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
    <div class="searchbar"></div>
    <div class="feed">
  <div class="card">
    <div class="card-body bg-dark">
      <h5 class="card-title text-light">
        <!-- Profilbild und Benutzername -->
        <img class="tweet-profile-image" src="./img/profil.png" alt="">
        @<?php echo $_SESSION["username"]; ?>
      </h5>
      <p class="card-text text-light">
        <!-- Beispieltext für den Beitrag -->
        This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.
      </p>
      <p class="card-text text-light">
        <small class="text-body-secondary">Last updated 3 mins ago</small>
      </p>
    </div>

    <!-- Bild im Beitrag -->
    <img src="./img/Background.jpg" class="card-img-bottom" alt="...">

    <!-- Footer mit Like und Kommentar-Icons -->
    <div class="card-footer bg-dark d-flex justify-content-between">
      <div class="d-flex gap-1">
        <!-- Like-Button -->
        <button class="btn btn-light">
          <i class="bi bi-hand-thumbs-up"></i> <!-- Daumen hoch Icon -->
        </button>
        <!-- Kommentar-Button -->
        <button class="btn btn-light">
          <i class="bi bi-chat-left-text"></i> <!-- Kommentar Icon -->
        </button>
      </div>
    </div>

    <!-- Kommentarbereich -->
    <div class="card-body bg-dark">
      <?php
      // Beispiel für das Abrufen von Kommentaren aus der Datenbank
      // $comments = Abrufen der Kommentare aus der Datenbank (z.B. mit PDO)
      // Angenommen, es gibt eine Tabelle 'comments' mit den Spalten 'profile_img', 'username', 'comment'
      $comments = [
        [
          'profile_img' => './img/profil.png', // Profilbild
          'username' => '@Max Müller',          // Username
          'comment' => 'Das ist ein Kommentar!' // Kommentartext
        ],
        [
          'profile_img' => './img/profil.png',
          'username' => '@Anna Schmidt',
          'comment' => 'Ich stimme dir zu!'
        ]
      ];

      // Durchlaufen der Kommentare
      foreach ($comments as $comment) {
        echo '
          <div class="comment mb-3">
            <div class="d-flex align-items-center">
              <!-- Profilbild des Kommentators -->
              <img class="tweet-profile-image" src="' . $comment['profile_img'] . '" alt="" style="width: 30px; height: 30px;">
              <strong class="text-light ms-2">' . $comment['username'] . '</strong>
            </div>
            <!-- Kommentartext -->
            <p class="text-light mt-1">' . $comment['comment'] . '</p>
          </div>
        ';
      }
      ?>
    </div>

    <!-- Kommentar Eingabebereich -->
    <div class="card-body bg-dark">
      <div class="d-flex align-items-center">
        <!-- Profilbild des Nutzers -->
        <img class="tweet-profile-image" src="./img/profil.png" alt="" style="width: 30px; height: 30px;">
        
        <!-- Textfeld zur Eingabe des Kommentars -->
        <input type="text" class="form-control bg-dark text-light border-0 ms-2" placeholder="Deinen Kommentar eingeben...">

        <!-- Senden-Button -->
        <button class="btn btn-light ms-2">
          <i class="bi bi-send"></i> <!-- Senden Icon -->
        </button>
      </div>
    </div>

  </div>
</div>




  </div>


  <!-- Bootstrap 5 JS und Popper -->
  <script defer src="./js/bootstrap.bundle.min.js"></script>
</body>

</html>
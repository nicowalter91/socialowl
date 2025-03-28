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
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Social Owl</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
   
</head>
<body class="bg-light">

<nav class="navbar bg-body-tertiary">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">
      <img src="./img/Owl_logo.svg" alt="Logo" width="30" height="24" class="d-inline-block align-text-top">
     Social Owl
    </a>
    <form class="d-flex" role="search">
      <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
      <button class="btn btn-outline-success" type="submit">Search</button>
    </form>
  </div>
  
</nav>

    <!-- Container für Sidebar und Main Content -->
    <div class="container-fluid d-flex" style="margin-top: 16px;"> <!-- Abstand von der Navbar -->

        <!-- Sidebar -->
        <div class="d-flex flex-column p-3 bg-body-tertiary" style="width: 280px; height:100vh">
            <ul class="nav nav-pills flex-column mb-auto">
                <li class="nav-item">
                    <a href="#" class="nav-link active" aria-current="page">
                        <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#home"></use></svg>
                        Home
                    </a>
                </li>
                <li>
                    <a href="#" class="nav-link link-body-emphasis">
                        <svg class="bi pe-none me-2" width="16" height="16"><use xlink:href="#speedometer2"></use></svg>
                        Dashboard
                    </a>
                </li>
            </ul>
            <hr>
            <div class="dropdown">
                <a href="#" class="d-flex align-items-center link-body-emphasis text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="https://github.com/mdo.png" alt="" width="32" height="32" class="rounded-circle me-2">
                    <strong><?php echo $_SESSION["username"] ?></strong>
                </a>
                <ul class="dropdown-menu text-small shadow">
                    <li><a class="dropdown-item" href="#">New project...</a></li>
                    <li><a class="dropdown-item" href="#">Settings</a></li>
                    <li><a class="dropdown-item" href="#">Profile</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="./logout.php">Sign out</a></li>
                </ul>
            </div>
        </div>

        <!-- Main Content -->
        <main class="flex-fill p-4">
            <div class="container">

                <!-- Beitrag Erstellen -->
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h4>Neuen Beitrag erstellen</h4>
                    </div>
                    <form action="home.php" method="POST" class="card-body">
                        <div class="mb-3">
                            <textarea class="form-control" rows="3" placeholder="Was möchtest du teilen?" name="text" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="image-upload" class="form-label">Bild hochladen</label>
                            <input class="form-control" type="file" id="image-upload" name="image" accept="image/*">
                        </div>
                        <button type="submit" class="btn btn-primary mt-3" name="submit">Beitrag posten</button>
                    </form>
                </div>

                <!-- Beiträge anzeigen -->
                <div class="post">
                    <div class="card mb-4 shadow-sm">
                        <div class="card-header">
                            <h5>Neuester Beitrag</h5>
                        </div>
                        <div class="card-body">
                            <p>Hier steht der neueste Beitrag von deinen Freunden oder Gruppen.</p>
                            <img src="https://via.placeholder.com/600x400" class="img-fluid mb-3" alt="Beispielbild">
                            <div class="d-flex justify-content-between">
                                <button class="btn btn-outline-primary btn-sm">Gefällt mir</button>
                                <button class="btn btn-outline-secondary btn-sm">Kommentieren</button>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-4 shadow-sm">
                        <div class="card-header">
                            <h5>Älterer Beitrag</h5>
                        </div>
                        <div class="card-body">
                            <p>Ein weiterer Beitrag zum Lesen.</p>
                            <div class="d-flex justify-content-between">
                                <button class="btn btn-outline-primary btn-sm">Gefällt mir</button>
                                <button class="btn btn-outline-secondary btn-sm">Kommentieren</button>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </main>
    </div>

    <!-- Bootstrap 5 JS und Popper -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

</body>
</html>

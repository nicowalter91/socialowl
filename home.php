<?php
    require "connection.php";



?>


<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meine Social Media App</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
  
</head>
<body>

    <aside class="sidebar">
        <div class="mb-4">
            <h4>Hallo, 
            <?php
                session_start();
                echo $_SESSION["username"];
            ?>
            </h4>
        </div>
        <ul class="list-unstyled">
            <li><a href="profil.php">Profil</a></li>
            <li><a href="freunde.php">Freunde</a></li>
            <li><a href="gruppen.php">Gruppen</a></li>
        </ul>
        <hr class="text-white">
        <a href="login.php" class="text-white">Logout</a>
    </aside>

    <main class="newsfeed">
        <div class="container">
            

            <!-- Beitrag Erstellen -->
            <div class="post">
                <div class="post-header">
                    <h4>Neuen Beitrag erstellen</h4>
                </div>
                <form action="home.php" method="POST">
                    <div class="mb-3">
                        <textarea class="form-control" rows="3" placeholder="Was möchtest du teilen?" name="text" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="image-upload" class="form-label">Bild hochladen</label>
                        <input class="form-control" type="file" id="image-upload" name="image" accept="image/*">
                    </div>
                    <button type="submit" class="btn btn-primary" name="submit">Beitrag posten</button>
                </form>
            </div>

            <!-- Beiträge anzeigen -->
            <div class="post">
                <div class="post-header">
                    <h5>Neuester Beitrag</h5>
                </div>
                <div class="post-content">
                    <p>Hier steht der neueste Beitrag von deinen Freunden oder Gruppen.</p>
                    <!-- Beispielbild -->
                    <img src="https://via.placeholder.com/600x400" alt="Beispielbild">
                </div>
                <div class="post-actions">
                    <button class="btn btn-outline-primary btn-sm">Gefällt mir</button>
                    <button class="btn btn-outline-secondary btn-sm">Kommentieren</button>
                </div>
            </div>

            <div class="post">
                <div class="post-header">
                    <h5>Älterer Beitrag</h5>
                </div>
                <div class="post-content">
                    <p>Ein weiterer Beitrag zum Lesen.</p>
                </div>
                <div class="post-actions">
                    <button class="btn btn-outline-primary btn-sm">Gefällt mir</button>
                    <button class="btn btn-outline-secondary btn-sm">Kommentieren</button>
                </div>
            </div>

        </div>
    </main>

    <!-- Bootstrap 5 JS und Popper -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

</body>
</html>

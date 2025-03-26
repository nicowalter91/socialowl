<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meine Social Media App</title>
    <style>
        body {
            font-family: sans-serif;
            display: flex;
            margin: 0;
        }

        .sidebar {
            width: 200px;
            background-color: #f0f0f0;
            padding: 20px;
            box-sizing: border-box;
        }

        .newsfeed {
            flex-grow: 1;
            padding: 20px;
        }

        .post {
            border: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

    <aside class="sidebar">
        <h2>Menü</h2>
        <ul>
            <li><a href="profil.php">Profil</a></li>
            <li><a href="freunde.php">Freunde</a></li>
            <li><a href="gruppen.php">Gruppen</a></li>
        </ul>
        <hr>
        <a href="login.php">Logout</a>
    </aside>

    <main class="newsfeed">
        <h1>Newsfeed</h1>
        <div class="post">
            <h3>Neuester Beitrag</h3>
            <p>Hier steht der neueste Beitrag von deinen Freunden oder Gruppen.</p>
        </div>
        <div class="post">
            <h3>Älterer Beitrag</h3>
            <p>Ein weiterer Beitrag zum Lesen.</p>
        </div>
        
    </main>

</body>
</html>
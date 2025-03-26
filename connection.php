<?php
    // Datenbankverbindungsinformationen
    $servername = "localhost";
    $username = "root";
    $password = "root";
    $dbname = "social_owl";

    try {
        // PDO-Verbindung herstellen
        $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
        // Fehlerbehandlung im PDO-Modus aktivieren
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo "Verbindung fehlgeschlagen: " . $e->getMessage();
    }
?>

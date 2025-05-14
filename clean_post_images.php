<?php
/**
 * Bereinigt das assets/posts Verzeichnis
 * Entfernt alle Bilder, die nicht mehr in der Datenbank referenziert werden
 */
define('ROOT', dirname(__FILE__)); // Absoluter Pfad zum Projekt-Root
require_once ROOT . '/includes/config.php';
require_once ROOT . '/includes/connection.php';

// Header für bessere Lesbarkeit in der Konsole
echo "=================================================\n";
echo "   Bereinigung des assets/posts Verzeichnisses\n";
echo "=================================================\n\n";

// Verbindung zur Datenbank herstellen
$conn = getDatabaseConnection();

// Alle Bildpfade aus der Datenbank holen
$stmt = $conn->prepare("
    SELECT image_path, video_path 
    FROM posts 
    WHERE image_path IS NOT NULL OR video_path IS NOT NULL
");
$stmt->execute();
$dbFiles = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Alle referenzierten Dateien in ein Array sammeln
$referencedFiles = [];
foreach ($dbFiles as $file) {
    if (!empty($file['image_path'])) {
        $referencedFiles[] = $file['image_path'];
    }
    if (!empty($file['video_path'])) {
        $referencedFiles[] = $file['video_path'];
    }
}

// Verzeichnis mit allen vorhandenen Dateien scannen
$postsDir = POSTS;
$allFiles = scandir($postsDir);
$mediaFiles = array_diff($allFiles, ['.', '..']); // . und .. entfernen

// Statistik
$totalFiles = count($mediaFiles);
$referencedCount = count($referencedFiles);
$toDeleteCount = 0;
$deletedCount = 0;
$errorCount = 0;

echo "Gefundene Dateien: $totalFiles\n";
echo "Referenzierte Dateien in der Datenbank: $referencedCount\n\n";

// Prüfen, welche Dateien nicht referenziert werden
$unreferencedFiles = [];
foreach ($mediaFiles as $file) {
    if (!in_array($file, $referencedFiles)) {
        $unreferencedFiles[] = $file;
    }
}

$toDeleteCount = count($unreferencedFiles);
echo "Nicht referenzierte Dateien die gelöscht werden können: $toDeleteCount\n\n";

// Nicht referenzierte Dateien löschen
if ($toDeleteCount > 0) {
    echo "Starte Bereinigung...\n\n";
    
    foreach ($unreferencedFiles as $file) {
        $filePath = $postsDir . '/' . $file;
        
        echo "Lösche: $file ... ";
        
        if (unlink($filePath)) {
            echo "OK\n";
            $deletedCount++;
        } else {
            echo "FEHLER!\n";
            $errorCount++;
        }
    }
    
    echo "\nBereinigung abgeschlossen.\n";
    echo "Gelöschte Dateien: $deletedCount\n";
    
    if ($errorCount > 0) {
        echo "Fehler beim Löschen: $errorCount\n";
    }
} else {
    echo "Keine Dateien zu löschen. Das Verzeichnis ist bereits sauber.\n";
}

echo "\n=================================================\n";
echo "                Vorgang beendet\n";
echo "=================================================\n";

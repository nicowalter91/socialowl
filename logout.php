<?php
session_start();


session_unset();    // Entfernt alle Session-Variablen
session_destroy();  // Zerstört die Session
// Debugging: Zeige alle Session-Daten an
echo '<pre>';
print_r($_SESSION); // Gibt alle Session-Daten aus
echo '</pre>';

// Weiterleitung zur Login-Seite nach dem Logout
// Sheader("Location: login.php");
// exit();

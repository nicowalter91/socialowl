<?php
session_start();


session_unset();    // Entfernt alle Session-Variablen
session_destroy();  // Zerstört die Session



// Weiterleitung zur Login-Seite nach dem Logout
header("Location: login.php");
exit();

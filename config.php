<?php
// Datenbank-Konfiguration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'termin2praxis');

// Datenbankverbindung erstellen
function getDBConnection() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    if ($conn->connect_error) {
        die("Verbindung fehlgeschlagen: " . $conn->connect_error);
    }
    
    $conn->set_charset("utf8mb4");
    return $conn;
}

// Session starten
session_start();

// Hilfsfunktion: Prüfen ob Benutzer eingeloggt ist
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Hilfsfunktion: Prüfen ob Benutzer eine bestimmte Rolle hat
function hasRole($role) {
    return isset($_SESSION['role']) && $_SESSION['role'] === $role;
}

// Hilfsfunktion: Weiterleitung wenn nicht eingeloggt
function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: login.php");
        exit();
    }
}

// Hilfsfunktion: Weiterleitung wenn falsche Rolle
function requireRole($role) {
    requireLogin();
    if (!hasRole($role)) {
        header("Location: login.php");
        exit();
    }
}
?>

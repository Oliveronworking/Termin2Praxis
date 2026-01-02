<?php
// Passwort hashen für die Datenbank
$password = "password123";
$hash = password_hash($password, PASSWORD_DEFAULT);
echo "Hash für 'password123': " . $hash;
?>

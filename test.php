<?php
echo "TEST SEITE - Wenn Sie das sehen, funktioniert PHP!<br>";
echo "Aktueller Pfad: " . __FILE__ . "<br>";
echo "Session gestartet<br>";

session_start();
echo "Session ID: " . session_id() . "<br>";
echo "Session Daten: ";
print_r($_SESSION);
echo "<br><br>";

echo '<a href="index.php">Zur index.php</a><br>';
echo '<a href="login.php">Zur login.php</a><br>';
?>

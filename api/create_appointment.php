<?php
require_once '../config.php';
requireRole('arzt');

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $date = $_POST['date'] ?? '';
    $time = $_POST['time'] ?? '';
    
    if (empty($date) || empty($time)) {
        echo json_encode(['success' => false, 'message' => 'Datum und Uhrzeit sind erforderlich']);
        exit();
    }
    
    $conn = getDBConnection();
    
    // Prüfen ob Termin bereits existiert
    $stmt = $conn->prepare("SELECT id FROM appointments WHERE date = ? AND time = ?");
    $stmt->bind_param("ss", $date, $time);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'Dieser Termin existiert bereits']);
        $stmt->close();
        $conn->close();
        exit();
    }
    
    $stmt->close();
    
    // Termin erstellen
    $stmt = $conn->prepare("INSERT INTO appointments (date, time, status) VALUES (?, ?, 'frei')");
    $stmt->bind_param("ss", $date, $time);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Termin erfolgreich erstellt']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Fehler beim Erstellen des Termins']);
    }
    
    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Ungültige Anfrage']);
}
?>

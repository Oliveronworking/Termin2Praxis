<?php
require_once '../config.php';
requireRole('arzt');

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $appointment_id = $_POST['appointment_id'] ?? 0;
    $action = $_POST['action'] ?? 'delete'; // 'delete' oder 'reject'
    
    if (empty($appointment_id)) {
        echo json_encode(['success' => false, 'message' => 'Termin-ID fehlt']);
        exit();
    }
    
    $conn = getDBConnection();
    
    if ($action === 'reject') {
        // Termin ablehnen - Status auf 'abgelehnt' setzen
        $stmt = $conn->prepare("UPDATE appointments SET status = 'abgelehnt' WHERE id = ? AND status IN ('angefragt', 'bestätigt')");
        $stmt->bind_param("i", $appointment_id);
        
        if ($stmt->execute() && $stmt->affected_rows > 0) {
            echo json_encode(['success' => true, 'message' => 'Termin wurde abgelehnt. Patient wird benachrichtigt.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Termin konnte nicht abgelehnt werden.']);
        }
        $stmt->close();
    } else {
        // Termin komplett löschen
        $stmt = $conn->prepare("DELETE FROM appointments WHERE id = ?");
        $stmt->bind_param("i", $appointment_id);
        
        if ($stmt->execute() && $stmt->affected_rows > 0) {
            echo json_encode(['success' => true, 'message' => 'Termin wurde gelöscht.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Termin konnte nicht gelöscht werden.']);
        }
        $stmt->close();
    }
    
    $conn->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Ungültige Anfrage']);
}
?>

<?php
require_once '../config.php';

header('Content-Type: application/json');

// Prüfen, ob Benutzer eingeloggt ist
if (!isLoggedIn() || !hasRole('patient')) {
    $appointment_id = $_POST['appointment_id'] ?? 0;
    echo json_encode([
        'success' => false, 
        'message' => 'Bitte melden Sie sich an, um einen Termin zu buchen.',
        'redirect' => 'login.php?redirect=index.php&book_appointment=' . $appointment_id
    ]);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $appointment_id = $_POST['appointment_id'] ?? 0;
    $user_id = $_SESSION['user_id'];
    
    if (empty($appointment_id)) {
        echo json_encode(['success' => false, 'message' => 'Termin-ID fehlt']);
        exit();
    }
    
    $conn = getDBConnection();
    
    // Termin buchen (Status auf 'angefragt' ändern und user_id setzen)
    $stmt = $conn->prepare("UPDATE appointments SET status = 'angefragt', user_id = ? WHERE id = ? AND status = 'frei'");
    $stmt->bind_param("ii", $user_id, $appointment_id);
    
    if ($stmt->execute() && $stmt->affected_rows > 0) {
        echo json_encode(['success' => true, 'message' => 'Termin erfolgreich gebucht. Warten auf Bestätigung des Arztes.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Termin konnte nicht gebucht werden. Möglicherweise ist er nicht mehr verfügbar.']);
    }
    
    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Ungültige Anfrage']);
}
?>

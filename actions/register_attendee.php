<?php
session_start();
require '../includes/db.php';

// Ensure the response is JSON
header('Content-Type: application/json');

// Check if data is sent via POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'danger', 'message' => 'Invalid request method.']);
    exit();
}

// Validate inputs
$event_id = filter_input(INPUT_POST, 'event_id', FILTER_VALIDATE_INT);
$name = htmlspecialchars(trim($_POST['name'] ?? ''), ENT_QUOTES, 'UTF-8');
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$phone = htmlspecialchars(trim($_POST['phone'] ?? ''), ENT_QUOTES, 'UTF-8');

if (!$event_id || !$name || !$email) {
    echo json_encode(['status' => 'danger', 'message' => 'Please provide valid inputs.']);
    exit();
}

try {
    // Check if the event exists and fetch its max capacity
    $stmt = $pdo->prepare("SELECT max_capacity FROM events WHERE id = :id");
    $stmt->execute([':id' => $event_id]);
    $event = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$event) {
        echo json_encode(['status' => 'danger', 'message' => 'Selected event does not exist.']);
        exit();
    }

    // Check the current number of attendees
    $stmt = $pdo->prepare("SELECT COUNT(*) AS total_attendees FROM attendees WHERE event_id = :event_id");
    $stmt->execute([':event_id' => $event_id]);
    $attendee_count = $stmt->fetch(PDO::FETCH_ASSOC)['total_attendees'];

    if ($attendee_count >= $event['max_capacity']) {
        echo json_encode(['status' => 'danger', 'message' => 'Registration is full. Maximum capacity reached.']);
        exit();
    }

    // Insert attendee into the database
    $stmt = $pdo->prepare("
        INSERT INTO attendees (event_id, name, email, phone)
        VALUES (:event_id, :name, :email, :phone)
    ");
    $success = $stmt->execute([
        ':event_id' => $event_id,
        ':name' => $name,
        ':email' => $email,
        ':phone' => $phone,
    ]);

    if ($success) {
        echo json_encode(['status' => 'success', 'message' => 'Registration successful.']);
    } else {
        echo json_encode(['status' => 'danger', 'message' => 'Failed to register attendee.']);
    }
} catch (Exception $e) {
    echo json_encode(['status' => 'danger', 'message' => 'An error occurred: ' . $e->getMessage()]);
}
?>

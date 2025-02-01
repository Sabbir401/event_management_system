<?php
session_start();
require '../includes/db.php';

header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'danger', 'message' => 'Unauthorized access.']);
    exit();
}

// Check if user is admin
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT role FROM users WHERE id = :id");
$stmt->execute([':id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user['role'] !== 'admin') {
    echo json_encode(['status' => 'danger', 'message' => 'Access denied. Only admins can create events.']);
    exit();
}

// Validate inputs
$name = htmlspecialchars(trim($_POST['name'] ?? ''), ENT_QUOTES, 'UTF-8');
$description = htmlspecialchars(trim($_POST['description'] ?? ''), ENT_QUOTES, 'UTF-8');
$date = $_POST['date'] ?? '';
$time = $_POST['time'] ?? '';
$max_capacity = intval($_POST['max_capacity'] ?? 0);

if (empty($name) || empty($date) || empty($time) || $max_capacity <= 0) {
    echo json_encode(['status' => 'danger', 'message' => 'All fields are required, and max capacity must be a positive number.']);
    exit();
}

try {
    // Insert event into database
    $stmt = $pdo->prepare("
        INSERT INTO events (name, description, date, time, max_capacity, created_by) 
        VALUES (:name, :description, :date, :time, :max_capacity, :created_by)
    ");
    $success = $stmt->execute([
        ':name' => $name,
        ':description' => $description,
        ':date' => $date,
        ':time' => $time,
        ':max_capacity' => $max_capacity,
        ':created_by' => $_SESSION['user_id']
    ]);

    if ($success) {
        echo json_encode(['status' => 'success', 'message' => 'Event created successfully!']);
    } else {
        echo json_encode(['status' => 'danger', 'message' => 'Failed to create event.']);
    }
} catch (Exception $e) {
    echo json_encode(['status' => 'danger', 'message' => 'An error occurred: ' . $e->getMessage()]);
}
?>

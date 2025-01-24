<?php
session_start();
require '../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch statistics
$total_events_stmt = $pdo->prepare("SELECT COUNT(*) AS total FROM events WHERE created_by = :user_id");
$total_events_stmt->execute([':user_id' => $user_id]);
$total_events = $total_events_stmt->fetch(PDO::FETCH_ASSOC)['total'];

$total_attendees_stmt = $pdo->prepare("
    SELECT COUNT(*) AS total 
    FROM attendees 
    JOIN events ON attendees.event_id = events.id 
    WHERE events.created_by = :user_id
");
$total_attendees_stmt->execute([':user_id' => $user_id]);
$total_attendees = $total_attendees_stmt->fetch(PDO::FETCH_ASSOC)['total'];

$upcoming_events_stmt = $pdo->prepare("
    SELECT COUNT(*) AS total 
    FROM events 
    WHERE created_by = :user_id AND date >= CURDATE()
");
$upcoming_events_stmt->execute([':user_id' => $user_id]);
$upcoming_events = $upcoming_events_stmt->fetch(PDO::FETCH_ASSOC)['total'];

echo json_encode([
    'total_events' => $total_events,
    'total_attendees' => $total_attendees,
    'upcoming_events' => $upcoming_events
]);

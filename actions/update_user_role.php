<?php
session_start();
require '../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit();
}

// Check if the logged-in user is an admin
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT role FROM users WHERE id = :id");
$stmt->execute([':id' => $user_id]);
$admin = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$admin || $admin['role'] !== 'admin') {
    echo json_encode(['status' => 'error', 'message' => 'Access denied']);
    exit();
}

// Validate the incoming data
if (!isset($_POST['user_id']) || !isset($_POST['role'])) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid data']);
    exit();
}

$targetUserId = $_POST['user_id'];
$newRole = $_POST['role'];

if (!in_array($newRole, ['user', 'admin'])) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid role']);
    exit();
}

// Update the user's role
$stmtUpdate = $pdo->prepare("UPDATE users SET role = :role WHERE id = :id");
$stmtUpdate->execute([':role' => $newRole, ':id' => $targetUserId]);

if ($stmtUpdate->rowCount() > 0) {
    echo json_encode(['status' => 'success', 'message' => 'User role updated successfully']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to update user role']);
}

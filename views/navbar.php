<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../includes/db.php';

// Check user session and role
$user_id = $_SESSION['user_id'] ?? null;
$is_admin = false;

if ($user_id) {
    $stmt = $pdo->prepare("SELECT role FROM users WHERE id = :id");
    $stmt->execute([':id' => $user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    $is_admin = $user['role'] === 'admin';
}
?>

<nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
    <a class="navbar-brand" href="#">Event Management</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="dashboard.php">Dashboard</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="register_attendee.php">Attendee Registration</a>
            </li>
            <?php if ($is_admin): ?>
                <li class="nav-item">
                    <a class="nav-link" href="user_list.php">User List</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="create_event.php">Create Event</a>
                </li>
            <?php endif; ?>
        </ul>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="../actions/logout.php">Logout</a>
            </li>
        </ul>
    </div>
</nav>

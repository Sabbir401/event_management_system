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

// Get current page
$current_page = basename($_SERVER['PHP_SELF']);
?>

<head>
    <link rel="icon" href="../assets/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4 px-4">
    <a class="navbar-brand" href="#"><img src="../assets/logo.webp" width="40px" height="40px" alt=""></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link <?= $current_page == 'dashboard.php' ? 'active' : '' ?>" href="dashboard.php">Dashboard</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $current_page == 'register_attendee.php' ? 'active' : '' ?>" href="register_attendee.php">Attendee Registration</a>
            </li>
            <?php if ($is_admin): ?>
                <li class="nav-item">
                    <a class="nav-link <?= $current_page == 'user_list.php' ? 'active' : '' ?>" href="user_list.php">User List</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $current_page == 'create_event.php' ? 'active' : '' ?>" href="create_event.php">Create Event</a>
                </li>
            <?php endif; ?>
        </ul>
        <ul class="navbar-nav ms-auto">
            <li class="nav-item">
                <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#logoutModal">Logout</a>
            </li>
        </ul>
    </div>
</nav>

<!-- Logout Modal -->
<div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="logoutModalLabel">Confirm Logout</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to log out?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <a href="../actions/logout.php" class="btn btn-primary">Logout</a>
            </div>
        </div>
    </div>
</div>

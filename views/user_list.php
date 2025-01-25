<?php
session_start();
require '../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ./login.php");
    exit();
}


// Check if the logged-in user is an admin
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT role FROM users WHERE id = :id");
$stmt->execute([':id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user['role'] !== 'admin') {
    echo "Access denied. Only admins can view this page.";
    exit();
}
include './navbar.php';

// Fetch all users
$stmtUsers = $pdo->query("SELECT id, username, role FROM users ORDER BY username ASC");
$users = $stmtUsers->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <link rel="stylesheet" href="../assets/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h1>User Management</h1>
        <p>Only admin users can view this page.</p>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Role</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($user['username']); ?></td>
                        <td>
                            <select class="form-control role-dropdown" data-user-id="<?php echo $user['id']; ?>">
                                <option value="user" <?php echo $user['role'] === 'user' ? 'selected' : ''; ?>>User</option>
                                <option value="admin" <?php echo $user['role'] === 'admin' ? 'selected' : ''; ?>>Admin</option>
                            </select>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script>
        // Handle role change using AJAX
        $(document).on('change', '.role-dropdown', function() {
            const userId = $(this).data('user-id'); // Get user ID from the dropdown
            const newRole = $(this).val(); // Get the selected role

            $.ajax({
                url: '../actions/update_user_role.php', // Endpoint to update the role
                type: 'POST',
                data: {
                    user_id: userId,
                    role: newRole
                },
                success: function(response) {
                    const result = JSON.parse(response);
                    if (result.status === 'success') {
                        alert(result.message);
                    } else {
                        alert(result.message);
                    }
                },
                error: function() {
                    alert('An error occurred while updating the role.');
                }
            });
        });
    </script>
</body>
</html>

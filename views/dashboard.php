<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require '../includes/db.php';

include './navbar.php';

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT username, role FROM users WHERE id = :id");
$stmt->execute([':id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    session_destroy();
    header("Location: login.php");
    exit();
}

$is_admin = $user['role'] === 'admin';

// Fetch total events
$stmtTotalEvents = $pdo->prepare("SELECT COUNT(*) FROM events WHERE created_by = :user_id");
$stmtTotalEvents->execute([':user_id' => $user_id]);
$totalEvents = $stmtTotalEvents->fetchColumn();

// Fetch pending events (date in the past)
$stmtPendingEvents = $pdo->prepare("SELECT COUNT(*) FROM events WHERE created_by = :user_id AND date < CURDATE()");
$stmtPendingEvents->execute([':user_id' => $user_id]);
$pendingEvents = $stmtPendingEvents->fetchColumn();

// Fetch upcoming events (date in the future)
$stmtUpcomingEvents = $pdo->prepare("SELECT COUNT(*) FROM events WHERE created_by = :user_id AND date >= CURDATE()");
$stmtUpcomingEvents->execute([':user_id' => $user_id]);
$upcomingEvents = $stmtUpcomingEvents->fetchColumn();

// Fetch total users (admin only)
$totalUsers = 0;
if ($is_admin) {
    $stmtTotalUsers = $pdo->query("SELECT COUNT(*) FROM users");
    $totalUsers = $stmtTotalUsers->fetchColumn();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="../assets/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <div class="container">
        <h1 class="mt-5">Welcome, <?php echo htmlspecialchars($user['username']); ?>!</h1>
        <p>Your role: <strong><?php echo htmlspecialchars($user['role']); ?></strong></p>
        <hr>

        <!-- Dashboard Cards -->
        <div class="row text-center">
            <div class="col-md-3 p-1 mb-4">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h3>Total Events</h3>
                        <h4><?php echo $totalEvents; ?></h4>
                    </div>
                </div>
            </div>
            <div class="col-md-3 p-1 mb-4">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <h3>Pending Events</h3>
                        <h4><?php echo $pendingEvents; ?></h4>
                    </div>
                </div>
            </div>
            <div class="col-md-3 p-1 mb-4">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h3>Upcoming Events</h3>
                        <h4><?php echo $upcomingEvents; ?></h4>
                    </div>
                </div>
            </div>
            <?php if ($is_admin): ?>
                <div class="col-md-3 p-1 mb-4">
                    <div class="card bg-secondary text-white">
                        <div class="card-body">
                            <h3>Total Users</h3>
                            <h4><?php echo $totalUsers; ?></h4>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <?php if ($is_admin): ?>
            <p><a href="create_event.php" class="btn btn-success">Create New Event</a></p>
        <?php endif; ?>

        <h3>Events</h3>
        <div class="form-group">
            <label for="search">Search Events:</label>
            <input type="text" id="search" class="form-control" placeholder="Type to search events...">
        </div>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Event Name</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Capacity</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="event-table">
                <tr>
                    <td colspan="5" class="text-center">Loading events...</td>
                </tr>
            </tbody>
        </table>
        <div class="d-flex justify-content-end">
            <div id="pagination" class="text-center"></div>
        </div>
    </div>

    <script>
        $(document).on('click', '.delete-event', function() {
            const eventId = $(this).data('id');
            if (confirm('Are you sure you want to delete this event?')) {
                $.ajax({
                    url: '../actions/delete_event.php',
                    type: 'POST',
                    data: { id: eventId },
                    success: function(response) {
                        const result = JSON.parse(response);
                        if (result.status === 'success') {
                            alert(result.message);
                            fetchEvents();
                        } else {
                            alert(result.message);
                        }
                    },
                    error: function() {
                        alert('An error occurred while deleting the event.');
                    }
                });
            }
        });

        function fetchEvents(page = 1, query = '') {
            $.ajax({
                url: '../actions/fetch_events.php',
                type: 'GET',
                data: { page, query },
                success: function(response) {
                    const result = JSON.parse(response);
                    $('#event-table').html(result.eventsHtml);
                    $('#pagination').html(result.paginationHtml);
                },
                error: function() {
                    alert('Error occurred while fetching events.');
                }
            });
        }

        $('#search').on('input', function() {
            const query = $(this).val();
            fetchEvents(1, query);
        });

        $(document).on('click', '.pagination a', function(e) {
            e.preventDefault();
            const page = $(this).data('page');
            const query = $('#search').val();
            fetchEvents(page, query);
        });

        $(document).ready(function() {
            fetchEvents();
        });
    </script>
</body>

</html>

<?php
session_start();
require '../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit();
}

if (!isset($_GET['event_id']) || !is_numeric($_GET['event_id'])) {
    echo "Invalid event ID.";
    exit();
}

$event_id = $_GET['event_id'];
$user_id = $_SESSION['user_id'];

// Fetch event details
$stmtEvent = $pdo->prepare("SELECT name, date, max_capacity FROM events WHERE id = :event_id");
$stmtEvent->execute([':event_id' => $event_id]);
$event = $stmtEvent->fetch(PDO::FETCH_ASSOC);

if (!$event) {
    echo "Event not found.";
    exit();
}

// Fetch attendees for the event
$stmtAttendees = $pdo->prepare("SELECT name, email FROM attendees WHERE event_id = :event_id");
$stmtAttendees->execute([':event_id' => $event_id]);
$attendees = $stmtAttendees->fetchAll(PDO::FETCH_ASSOC);

// Total attendees count
$totalAttendees = count($attendees);

// Render the event and attendee details
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Attendees</title>
    <link rel="stylesheet" href="../assets/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Event: <?php echo htmlspecialchars($event['name']); ?></h1>
        <p>Date: <?php echo htmlspecialchars($event['date']); ?></p>
        <p>Capacity: <?php echo $totalAttendees . '/' . htmlspecialchars($event['max_capacity']); ?></p>
        <hr>
        <h3>Attendees</h3>
        <?php if ($totalAttendees > 0): ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($attendees as $attendee): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($attendee['name']); ?></td>
                            <td><?php echo htmlspecialchars($attendee['email']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No attendees for this event.</p>
        <?php endif; ?>
        <a href="../views/dashboard.php" class="btn btn-primary">Back to Dashboard</a>
    </div>
</body>
</html>

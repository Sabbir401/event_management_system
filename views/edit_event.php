<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require '../includes/db.php';
include './navbar.php';

// Check if event ID is provided
if (!isset($_GET['id'])) {
    $_SESSION['error'] = 'No event ID provided!';
    header('Location: dashboard.php');
    exit();
}

$event_id = intval($_GET['id']);
$user_id = $_SESSION['user_id'];

// Fetch event details
$stmt = $pdo->prepare("SELECT * FROM events WHERE id = :id AND created_by = :user_id");
$stmt->execute([':id' => $event_id, ':user_id' => $user_id]);
$event = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$event) {
    $_SESSION['error'] = 'Event not found!';
    header('Location: dashboard.php');
    exit();
}

// Update event details
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $date = $_POST['date'];
    $time = $_POST['time'];
    $max_capacity = intval($_POST['max_capacity']);

    $errors = [];

    if (empty($name)) $errors[] = 'Event name is required.';
    if (empty($date)) $errors[] = 'Event date is required.';
    if (empty($time)) $errors[] = 'Event time is required.';
    if ($max_capacity <= 0) $errors[] = 'Max capacity must be a positive number.';

    if (empty($errors)) {
        $update_stmt = $pdo->prepare("
            UPDATE events 
            SET name = :name, description = :description, date = :date, time = :time, max_capacity = :max_capacity
            WHERE id = :id AND created_by = :user_id
        ");
        $update_stmt->execute([
            ':name' => $name,
            ':description' => $description,
            ':date' => $date,
            ':time' => $time,
            ':max_capacity' => $max_capacity,
            ':id' => $event_id,
            ':user_id' => $user_id
        ]);

        $_SESSION['success'] = 'Event updated successfully!';
        header('Location: dashboard.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Event</title>
    <link rel="stylesheet" href="../assets/bootstrap.min.css">
</head>

<body>
    <section class="vh-100">
        <div class="mask d-flex align-items-center h-100 gradient-custom-3">
            <div class="container h-100">
                <div class="row d-flex justify-content-center align-items-center h-100">
                    <div class="col-12 col-md-9 col-lg-7 col-xl-6">
                        <div class="card" style="border-radius: 15px;">
                            <div class="card-body p-5">
                                <h2 class="text-uppercase text-center mb-5">Edit Event</h2>

                                <?php if (!empty($errors)): ?>
                                    <div class="alert alert-danger">
                                        <ul>
                                            <?php foreach ($errors as $error): ?>
                                                <li><?php echo htmlspecialchars($error); ?></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                <?php endif; ?>

                                <form action="" method="POST">
                                    <div class="form-group">
                                        <label for="name">Event Name</label>
                                        <input type="text" class="form-control" id="name" name="name"
                                            value="<?php echo htmlspecialchars($event['name']); ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="description">Description</label>
                                        <textarea class="form-control" id="description" name="description"><?php echo htmlspecialchars($event['description']); ?></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="date">Date</label>
                                        <input type="date" class="form-control" id="date" name="date"
                                            value="<?php echo htmlspecialchars($event['date']); ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="time">Time</label>
                                        <input type="time" class="form-control" id="time" name="time"
                                            value="<?php echo htmlspecialchars($event['time']); ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="max_capacity">Max Capacity</label>
                                        <input type="number" class="form-control" id="max_capacity" name="max_capacity"
                                            value="<?php echo htmlspecialchars($event['max_capacity']); ?>" required>
                                    </div>
                                    <button type="submit" class="btn btn-success">Update Event</button>
                                    <a href="dashboard.php" class="btn btn-secondary">Cancel</a>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>

</html>
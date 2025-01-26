<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require '../includes/db.php';
include './navbar.php';

// Check if the logged-in user is an admin
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT role FROM users WHERE id = :id");
$stmt->execute([':id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user['role'] !== 'admin') {
    echo "Access denied. Only admins can view this page.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate inputs
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
        // Save to database
        $stmt = $pdo->prepare("
            INSERT INTO events (name, description, date, time, max_capacity, created_by) 
            VALUES (:name, :description, :date, :time, :max_capacity, :created_by)
        ");
        $stmt->execute([
            ':name' => $name,
            ':description' => $description,
            ':date' => $date,
            ':time' => $time,
            ':max_capacity' => $max_capacity,
            ':created_by' => $_SESSION['user_id']
        ]);

        $_SESSION['success'] = 'Event created successfully!';
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
    <title>Create Event</title>
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
                                <h2 class="text-uppercase text-center mb-5">Create Event</h2>

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
                                            value="<?php echo isset($name) ? htmlspecialchars($name) : ''; ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="description">Description</label>
                                        <textarea class="form-control" id="description" name="description"><?php echo isset($description) ? htmlspecialchars($description) : ''; ?></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="date">Date</label>
                                        <input type="date" class="form-control" id="date" name="date"
                                            value="<?php echo isset($date) ? htmlspecialchars($date) : ''; ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="time">Time</label>
                                        <input type="time" class="form-control" id="time" name="time"
                                            value="<?php echo isset($time) ? htmlspecialchars($time) : ''; ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="max_capacity">Max Capacity</label>
                                        <input type="number" class="form-control" id="max_capacity" name="max_capacity"
                                            value="<?php echo isset($max_capacity) ? htmlspecialchars($max_capacity) : ''; ?>" required>
                                    </div>
                                    <button type="submit" class="btn btn-success">Create Event</button>
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
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

                                <div id="message"></div> <!-- Message container -->

                                <form id="create-event-form">
                                    <div class="form-group">
                                        <label for="name">Event Name</label>
                                        <input type="text" class="form-control" id="name" name="name" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="description">Description</label>
                                        <textarea class="form-control" id="description" name="description"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="date">Date</label>
                                        <input type="date" class="form-control" id="date" name="date" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="time">Time</label>
                                        <input type="time" class="form-control" id="time" name="time" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="max_capacity">Max Capacity</label>
                                        <input type="number" class="form-control" id="max_capacity" name="max_capacity" required>
                                    </div>
                                    <div class="d-flex justify-content-center">
                                        <button type="submit" class="btn btn-success">Create Event</button>
                                        <a href="dashboard.php" class="btn btn-secondary ml-2">Cancel</a>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $('#create-event-form').on('submit', function(e) {
            e.preventDefault();

            $.ajax({
                url: '../actions/create_event_action.php', 
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    try {
                        if (response && response.status) {
                            $('#message').html(
                                `<div class="alert alert-${response.status}">${response.message}</div>`
                            );
                            if (response.status === 'success') {
                                $('#create-event-form')[0].reset(); // Reset form on success
                            }
                        } else {
                            $('#message').html('<div class="alert alert-danger">Unexpected response format.</div>');
                        }
                    } catch (error) {
                        $('#message').html('<div class="alert alert-danger">Error processing server response.</div>');
                    }
                },
                error: function(xhr, status, error) {
                    $('#message').html('<div class="alert alert-danger">An error occurred while processing your request.</div>');
                    console.error('AJAX Error:', error);
                }
            });
        });
    </script>

</body>

</html>

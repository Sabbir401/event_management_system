<?php
require '../includes/db.php';
include './navbar.php';

// Fetch events for the dropdown
$stmt = $pdo->query("SELECT id, name FROM events ORDER BY name ASC");
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Attendee</title>
    <link rel="stylesheet" href="../assets/bootstrap.min.css">
</head>

<body>
    <div class="container">
        <h1 class="mt-5">Attendee Registration</h1>
        <form id="registration-form">
            <div class="form-group">
                <label for="event">Select Event</label>
                <select name="event_id" id="event" class="form-control" required>
                    <option value="" disabled selected>Select an event</option>
                    <?php foreach ($events as $event): ?>
                        <option value="<?php echo $event['id']; ?>">
                            <?php echo htmlspecialchars($event['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" name="email" id="email" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="text" name="phone" id="phone" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary">Register</button>
        </form>
        <div id="message" class="mt-3"></div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
     $('#registration-form').on('submit', function (e) {
    e.preventDefault();

    $.ajax({
        url: '../actions/register_attendee.php',
        type: 'POST',
        data: $(this).serialize(),
        success: function (response) {
            try {
                if (response && response.status) {
                    $('#message').html(
                        `<div class="alert alert-${response.status}">${response.message}</div>`
                    );
                    if (response.status === 'success') {
                        $('#registration-form')[0].reset(); // Reset form on success
                    }
                } else {
                    $('#message').html('<div class="alert alert-danger">Unexpected response format.</div>');
                }
            } catch (error) {
                $('#message').html('<div class="alert alert-danger">Error processing server response.</div>');
            }
        },
        error: function (xhr, status, error) {
            $('#message').html('<div class="alert alert-danger">An error occurred while processing your request.</div>');
            console.error('AJAX Error:', error);
        }
    });
});

    </script>
</body>

</html>
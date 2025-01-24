<?php
session_start();
require '../includes/db.php';

// Ensure the user is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403); // Forbidden if not admin
    echo "Unauthorized access.";
    exit();
}

// Validate the event_id
$event_id = filter_input(INPUT_GET, 'event_id', FILTER_VALIDATE_INT);

if (!$event_id) {
    http_response_code(400); // Bad Request if no valid event_id
    echo "Invalid event ID.";
    exit();
}

try {
    // Fetch the event name (optional for the filename)
    $stmt = $pdo->prepare("SELECT name FROM events WHERE id = :event_id");
    $stmt->execute([':event_id' => $event_id]);
    $event = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$event) {
        http_response_code(404); // Not Found if event doesn't exist
        echo "Event not found.";
        exit();
    }

    $event_name = $event['name'];

    // Fetch the attendees for the event
    $stmt = $pdo->prepare("SELECT name, email, phone FROM attendees WHERE event_id = :event_id");
    $stmt->execute([':event_id' => $event_id]);
    $attendees = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($attendees)) {
        echo "No attendees found for this event.";
        exit();
    }

    // Set headers to force CSV download
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="attendees_' . $event_name . '.csv"');

    // Open output stream
    $output = fopen('php://output', 'w');

    // Add the CSV column headers
    fputcsv($output, ['Name', 'Email', 'Phone']);

    // Add attendees' data to the CSV
    foreach ($attendees as $attendee) {
        fputcsv($output, $attendee);
    }

    // Close the output stream
    fclose($output);

} catch (Exception $e) {
    http_response_code(500); // Internal Server Error
    echo "An error occurred: " . $e->getMessage();
}
?>

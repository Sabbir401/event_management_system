<?php
session_start();
require '../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit();
}

$user_id = $_SESSION['user_id'];

// Check if the user is an admin
$stmt = $pdo->prepare("SELECT role FROM users WHERE id = :id");
$stmt->execute([':id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$is_admin = $user['role'] === 'admin';

// Pagination variables
$perPage = 5;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $perPage;
$query = isset($_GET['query']) ? trim($_GET['query']) : '';

// Fetch events with pagination and search
$sql = "SELECT e.*, 
               (SELECT COUNT(*) FROM attendees WHERE event_id = e.id) AS total_attendees 
        FROM events e 
        WHERE created_by = :user_id";
$params = [':user_id' => $user_id];

if (!empty($query)) {
    $sql .= " AND e.name LIKE :query";
    $params[':query'] = '%' . $query . '%';
}

$sql .= " LIMIT $offset, $perPage";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);

$events = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch total events count for pagination
$totalQuery = "SELECT COUNT(*) FROM events WHERE created_by = :user_id";
$totalParams = [':user_id' => $user_id];

if (!empty($query)) {
    $totalQuery .= " AND name LIKE :query";
    $totalParams[':query'] = '%' . $query . '%';
}

$stmtTotal = $pdo->prepare($totalQuery);
$stmtTotal->execute($totalParams);
$totalEvents = $stmtTotal->fetchColumn();

$totalPages = ceil($totalEvents / $perPage);

// Build the events HTML
if (empty($events)) {
    $eventsHtml = '<tr><td colspan="5" class="text-center">No events found.</td></tr>';
} else {
    $eventsHtml = '';
    foreach ($events as $event) {
        $capacity = $event['total_attendees'] . '/' . $event['max_capacity'];

        if ($is_admin) {
            $actions = "<a href='../actions/view_attendees.php?event_id=" . $event['id'] . "' class='btn btn-info btn-sm'>View Attendees</a>";
            $actions .= " <a href='edit_event.php?id=" . $event['id'] . "' class='btn btn-warning btn-sm'>Edit</a>";
            $actions .= " <a href='javascript:void(0)' class='btn btn-danger btn-sm delete-event' data-id='" . $event['id'] . "'>Delete</a>";
            $actions .= " <a href='../actions/download_attendees.php?event_id=" . $event['id'] . "' class='btn btn-secondary btn-sm'>Download CSV</a>";
        } else {
            $actions = "<span class='text-muted'>No actions available</span>";
        }

        $eventsHtml .= "<tr>
                            <td>" . htmlspecialchars($event['name']) . "</td>
                            <td>" . htmlspecialchars($event['date']) . "</td>
                            <td>" . htmlspecialchars($event['time']) . "</td>
                            <td>$capacity</td>
                            <td>$actions</td>
                        </tr>";
    }
}

// Build pagination links
$paginationHtml = '<div class="pagination">';
for ($i = 1; $i <= $totalPages; $i++) {
    $activeClass = ($i == $page) ? 'active' : '';
    $paginationHtml .= "<a href='javascript:void(0)' class='btn btn-info btn-sm m-1 $activeClass' data-page='$i'>$i</a> ";
}
$paginationHtml .= '</div>';

echo json_encode(['eventsHtml' => $eventsHtml, 'paginationHtml' => $paginationHtml]);

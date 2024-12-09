<?php
session_start();
require_once 'connection.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

try {
    $roomUsage = $db->query("
        SELECT 
            r.room_name, 
            COUNT(b.id) AS total_bookings,
            SUM(TIMESTAMPDIFF(MINUTE, b.booking_start, b.booking_end)) AS total_minutes_used
        FROM rooms r
        LEFT JOIN bookings b ON r.id = b.room_id
        GROUP BY r.id
        ORDER BY total_bookings DESC
    ")->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <title>Room Usage Report</title>
</head>
<body>
<div class="container mt-5">
    <h3>Room Usage Report</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Room Name</th>
                <th>Total Bookings</th>
                <th>Total Usage (Minutes)</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($roomUsage as $usage): ?>
                <tr>
                    <td><?= htmlspecialchars($usage['room_name']) ?></td>
                    <td><?= $usage['total_bookings'] ?></td>
                    <td><?= $usage['total_minutes_used'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>

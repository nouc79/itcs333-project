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
    <style>
        body {
            background-image: url('room4.jpg'); /* Background image */
            background-size: cover; /* Cover entire screen */
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            height: 100vh;
            margin: 0;
        }

        table {
            border-collapse: separate; /* Enables rounded corners */
            border-spacing: 0; /* Removes extra spacing between cells */
            border-radius: 15px; /* Round table edges */
            overflow: hidden; /* Ensures rounded edges apply correctly */
            width: 100%; /* Full width table */
            background-color: rgba(255, 255, 255, 0.9); /* Slightly transparent background */
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2); /* Subtle shadow for better visibility */
        }

        th, td {
            text-align: center; /* Center align text */
            padding: 12px; /* Add some padding */
            border-right: 1px solid #ddd; /* Vertical lines between columns */
        }

        th:last-child, td:last-child {
            border-right: none; /* Remove border for the last column */
        }

        th {
            font-weight: bold; /* Bold header text */
            background-color: #4d77a3; /* Header background color */
            color: white; /* White text for headers */
        }

        tr:nth-child(even) {
            background-color: #f9f9f9; /* Alternate row color */
        }

        tr:hover {
            background-color: #f1f1f1; /* Highlight row on hover */
        }

        h3 {
            text-align: center; /* Center the heading */
            margin-bottom: 20px; /* Add space below heading */
            font-weight: bold;
        }

        .container {
            margin-top: 30px; /* Space from top */
        }
    </style>
</head>
<body>
<div class="container">
    <h3>Room Usage Report</h3>
    <table class="table">
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

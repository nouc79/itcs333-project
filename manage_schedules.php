<?php
session_start();
require_once 'connection.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

try {
    $rooms = $db->query("SELECT id, room_name FROM rooms")->fetchAll(PDO::FETCH_ASSOC);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['add_schedule'])) {
            $stmt = $db->prepare("INSERT INTO room_schedules (room_id, start_time, end_time, is_available) VALUES (:room_id, :start_time, :end_time, :is_available)");
            $stmt->execute([
                'room_id' => $_POST['room_id'],
                'start_time' => $_POST['start_time'],
                'end_time' => $_POST['end_time'],
                'is_available' => isset($_POST['is_available']) ? 1 : 0
            ]);
        } elseif (isset($_POST['delete_schedule'])) {
            $stmt = $db->prepare("DELETE FROM room_schedules WHERE id = :id");
            $stmt->execute(['id' => $_POST['schedule_id']]);
        }
    }

    $schedules = $db->query("
        SELECT rs.id, rs.start_time, rs.end_time, rs.is_available, r.room_name
        FROM room_schedules rs
        JOIN rooms r ON rs.room_id = r.id
        ORDER BY rs.start_time ASC
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
    <title>Manage Schedules</title>
    <style>
        body {
            background-image: url('room4.jpg'); /* Image in the same folder */
            background-size: cover; /* Ensure the image covers the entire screen */
            background-position: center; /* Center the image */
            background-repeat: no-repeat; /* Prevent repeating the image */
            background-attachment: fixed; /* Make the background stay fixed during scroll */
            height: 100vh; /* Full viewport height */
            margin: 0;
        }

        .form-label {
            font-weight: bold; /* Make labels bold */
        }

        table {
            border-collapse: separate; /* Allows for rounded borders */
            border-spacing: 0; /* Remove extra spacing between cells */
            border-radius: 10px; /* Round the edges of the table */
            overflow: hidden; /* Ensure the border radius applies properly */
            width: 100%; /* Full width table */
            background-color: rgba(255, 255, 255, 0.9); /* Slightly transparent background for readability */
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2); /* Add subtle shadow */
        }

        th, td {
            text-align: center; /* Center align text */
            padding: 12px; /* Add some padding */
        }

        th {
            font-weight: bold; /* Bold font for headers */
            background-color: #4d77a3; /* Subtle header background */
            color: white; /* White text for headers */
        }

        tr:nth-child(even) {
            background-color: #f9f9f9; /* Alternate row color */
        }

        tr:hover {
            background-color: #f1f1f1; /* Highlight row on hover */
        }

        .btn-sm {
            font-size: 12px; /* Smaller buttons */
            padding: 5px 10px; /* Adjust padding */
        }

        .container {
            margin-top: 30px;
        }

        h3, h4 {
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<div class="container">
    <h3>Manage Room Schedules</h3>

    <!-- Add New Schedule Form -->
    <form method="POST" class="mb-4">
        <div class="mb-3">
            <label for="room_id" class="form-label">Room</label>
            <select name="room_id" id="room_id" class="form-select" required>
                <option value="">Select a room</option>
                <?php foreach ($rooms as $room): ?>
                    <option value="<?= $room['id'] ?>"><?= htmlspecialchars($room['room_name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="start_time" class="form-label">Start Time</label>
            <input type="datetime-local" name="start_time" id="start_time" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="end_time" class="form-label">End Time</label>
            <input type="datetime-local" name="end_time" id="end_time" class="form-control" required>
        </div>
        <div class="form-check mb-3">
            <input type="checkbox" name="is_available" id="is_available" class="form-check-input" checked>
            <label for="is_available" class="form-check-label">Available</label>
        </div>
        <button type="submit" name="add_schedule" class="btn btn-success">Add Schedule</button>
    </form>

    <!-- Existing Schedules -->
    <h4>Existing Schedules</h4>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Room Name</th>
                <th>Start Time</th>
                <th>End Time</th>
                <th>Available</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($schedules as $schedule): ?>
                <tr>
                    <td><?= $schedule['id'] ?></td>
                    <td><?= htmlspecialchars($schedule['room_name']) ?></td>
                    <td><?= $schedule['start_time'] ?></td>
                    <td><?= $schedule['end_time'] ?></td>
                    <td><?= $schedule['is_available'] ? 'Yes' : 'No' ?></td>
                    <td>
                        <!-- Delete Schedule -->
                        <form method="POST" class="d-inline">
                            <input type="hidden" name="schedule_id" value="<?= $schedule['id'] ?>">
                            <button type="submit" name="delete_schedule" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>



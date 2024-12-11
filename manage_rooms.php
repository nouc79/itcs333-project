<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

require 'connection.php';
include 'header.php';

// Handle room actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_room'])) {
        $stmt = $pdo->prepare("INSERT INTO rooms (room_name, capacity, description, equipment) VALUES (:room_name, :capacity, :description, :equipment)");
        $stmt->execute([
            'room_name' => $_POST['room_name'],
            'capacity' => $_POST['capacity'],
            'description' => $_POST['description'],
            'equipment' => $_POST['equipment']
        ]);
    } elseif (isset($_POST['delete_room'])) {
        $stmt = $pdo->prepare("DELETE FROM rooms WHERE id = :id");
        $stmt->execute(['id' => $_POST['room_id']]);
    }
}

// Fetch all rooms
$stmt = $pdo->query("SELECT * FROM rooms");
$rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Rooms</title>
    <style>
        body {
            background-image: url('room4.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            color: white; /* Adjust text color for contrast */
        }
        .container {
            background: rgba(0, 0, 0, 0.8); /* Optional: Add a dark overlay */
            padding: 20px;
            border-radius: 8px;
        }
        table {
            background: white;
            color: black;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Manage Rooms</h1>
        <form method="POST" class="mt-4">
            <div class="mb-3">
                <label for="room_name" class="form-label">Room Name</label>
                <input type="text" name="room_name" id="room_name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="capacity" class="form-label">Capacity</label>
                <input type="number" name="capacity" id="capacity" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" id="description" class="form-control"></textarea>
            </div>
            <div class="mb-3">
                <label for="equipment" class="form-label">Equipment</label>
                <input type="text" name="equipment" id="equipment" class="form-control">
            </div>
            <button type="submit" name="add_room" class="btn btn-success">Add Room</button>
        </form>

        <h2 class="mt-5">Existing Rooms</h2>
        <table class="table mt-3">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Room Name</th>
                    <th>Capacity</th>
                    <th>Description</th>
                    <th>Equipment</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rooms as $room): ?>
                    <tr>
                        <td><?= htmlspecialchars($room['id']) ?></td>
                        <td><?= htmlspecialchars($room['room_name']) ?></td>
                        <td><?= htmlspecialchars($room['capacity']) ?></td>
                        <td><?= htmlspecialchars($room['description']) ?></td>
                        <td><?= htmlspecialchars($room['equipment']) ?></td>
                        <td>
                            <form method="POST" class="d-inline">
                                <input type="hidden" name="room_id" value="<?= $room['id'] ?>">
                                <button type="submit" name="delete_room" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

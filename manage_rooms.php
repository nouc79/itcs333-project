<?php
session_start();
require_once 'connection.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['add_room'])) {
            $stmt = $db->prepare("INSERT INTO rooms (room_name, capacity, description, equipment) VALUES (:room_name, :capacity, :description, :equipment)");
            $stmt->execute([
                'room_name' => $_POST['room_name'],
                'capacity' => $_POST['capacity'],
                'description' => $_POST['description'],
                'equipment' => $_POST['equipment']
            ]);
        } elseif (isset($_POST['delete_room'])) {
            $stmt = $db->prepare("DELETE FROM rooms WHERE id = :id");
            $stmt->execute(['id' => $_POST['room_id']]);
        } elseif (isset($_POST['edit_room'])) {
            $stmt = $db->prepare("UPDATE rooms SET room_name = :room_name, capacity = :capacity, description = :description, equipment = :equipment WHERE id = :id");
            $stmt->execute([
                'room_name' => $_POST['room_name'],
                'capacity' => $_POST['capacity'],
                'description' => $_POST['description'],
                'equipment' => $_POST['equipment'],
                'id' => $_POST['room_id']
            ]);
        }
    }

    $rooms = $db->query("SELECT * FROM rooms")->fetchAll(PDO::FETCH_ASSOC);
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
    <title>Manage Rooms</title>
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
        font-weight: bold; /* Make form labels bold */
        font-size: 16px; /* Slightly increase the size for better visibility */
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
        background-color: #4d77a3; /* Add a subtle background for headers */
        color: white; /* White text for headers */
    }

    td:nth-child(2),
    td:nth-child(3),
    td:nth-child(4),
    td:nth-child(5) {
        font-weight: bold; /* Bold for Room Name, Description, Capacity, and Equipment */
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
        <h3>Manage Rooms</h3>

        <!-- Add New Room Form -->
        <form method="POST" class="mb-4">
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
        <input type="text" name="equipment" id="equipment" class="form-control" placeholder="E.g., Projector, Whiteboard">
    </div>
    <button type="submit" name="add_room" class="btn btn-success">Add Room</button>
</form>


        <h4>Existing Rooms</h4>
        <table class="table">
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
                    <td><?= $room['id'] ?></td>
                    <td><?= htmlspecialchars($room['room_name']) ?></td>
                    <td><?= $room['capacity'] ?></td>
                    <td><?= htmlspecialchars($room['description']) ?></td>
                    <td><?= htmlspecialchars($room['equipment']) ?></td>
                    <td>
                        <!-- Edit Room Button -->
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                            data-bs-target="#editRoomModal<?= $room['id'] ?>">Edit</button>
                        <form method="POST" class="d-inline">
                            <input type="hidden" name="room_id" value="<?= $room['id'] ?>">
                            <button type="submit" name="delete_room" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>

                <!-- Edit Modal -->
                <div class="modal fade" id="editRoomModal<?= $room['id'] ?>" tabindex="-1"
                    aria-labelledby="editRoomModalLabel<?= $room['id'] ?>" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form method="POST">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editRoomModalLabel<?= $room['id'] ?>">Edit Room</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" name="room_id" value="<?= $room['id'] ?>">
                                    <div class="mb-3">
                                        <label for="room_name_<?= $room['id'] ?>" class="form-label">Room Name</label>
                                        <input type="text" name="room_name" id="room_name_<?= $room['id'] ?>"
                                            class="form-control" value="<?= htmlspecialchars($room['room_name']) ?>"
                                            required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="capacity_<?= $room['id'] ?>" class="form-label">Capacity</label>
                                        <input type="number" name="capacity" id="capacity_<?= $room['id'] ?>"
                                            class="form-control" value="<?= $room['capacity'] ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="description_<?= $room['id'] ?>"
                                            class="form-label">Description</label>
                                        <textarea name="description" id="description_<?= $room['id'] ?>"
                                            class="form-control"><?= htmlspecialchars($room['description']) ?></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="equipment_<?= $room['id'] ?>" class="form-label">Equipment</label>
                                        <input type="text" name="equipment" id="equipment_<?= $room['id'] ?>"
                                            class="form-control" value="<?= htmlspecialchars($room['equipment']) ?>">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Close</button>
                                    <button type="submit" name="edit_room" class="btn btn-primary">Save Changes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

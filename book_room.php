<?php 
session_start();
require 'connection.php';
include 'header.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit();
}

// Get the room ID from the URL
$room_id = $_GET['id'] ?? null;

if (!$room_id) {
    die("Error: Room ID not provided.");
}

// Fetch room details
$stmt = $pdo->prepare("SELECT * FROM rooms WHERE id = :id");
$stmt->execute(['id' => $room_id]);
$room = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$room) {
    die("Error: Room not found.");
}

// Handle booking form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];

    // Insert booking into the database
    try {
        $stmt = $pdo->prepare("INSERT INTO bookings (user_id, room_id, booking_start, booking_end) VALUES (:user_id, :room_id, :start_time, :end_time)");
        $stmt->execute([
            'user_id' => $_SESSION['user_id'], // Ensure user_id is set in the session
            'room_id' => $room_id,
            'start_time' => $start_time,
            'end_time' => $end_time
        ]);

        // Redirect to user dashboard after successful booking
        header("Location: user_dashboard.php");
        exit();
    } catch (PDOException $e) {
        die("Error booking room: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Room</title>
    <style>
        body {
            background: url('room4.jpg') no-repeat center center fixed;
            background-size: cover;
            color: white;
            font-family: Arial, sans-serif;
        }
        .container {
            background: rgba(0, 0, 0, 0.7); /* Semi-transparent background */
            padding: 30px;
            border-radius: 10px;
            max-width: 400px;
            margin: 50px auto;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        label {
            font-weight: bold;
            margin-top: 10px;
        }
        input {
            background: white;
            color: black;
            border: none;
            border-radius: 5px;
            padding: 10px;
            width: 100%;
            margin-bottom: 20px;
        }
        button {
            background-color: #15455f; /* Button color */
            border: none;
            color: white;
            padding: 10px;
            font-size: 16px;
            width: 100%;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #123a4e; /* Slightly darker shade on hover */
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Book <?= htmlspecialchars($room['room_name']) ?></h2>
        <p class="text-center"><?= htmlspecialchars($room['description']) ?></p>
        <form method="POST">
            <div>
                <label for="start_time">Start Time</label>
                <input type="datetime-local" id="start_time" name="start_time" required>
            </div>
            <div>
                <label for="end_time">End Time</label>
                <input type="datetime-local" id="end_time" name="end_time" required>
            </div>
            <button type="submit">Confirm Booking</button>
        </form>
    </div>
</body>
</html>

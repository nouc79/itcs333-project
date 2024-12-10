<?php
session_start();
require 'connection.php';
include 'header.php';

// Get the room ID from the URL
$room_id = $_GET['id'];

// Fetch room details
$stmt = $pdo->prepare("SELECT * FROM rooms WHERE id = :id");
$stmt->execute(['id' => $room_id]);
$room = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$room) {
    die("Room not found.");
}

// Handle booking form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];

    // Insert booking into the database
    try {
        $stmt = $pdo->prepare("INSERT INTO bookings (user_id, room_id, booking_start, booking_end) VALUES (:user_id, :room_id, :start_time, :end_time)");
        $stmt->execute([
            'user_id' => $_SESSION['user_id'],
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

<div class="container mt-5">
    <h2 class="text-center">Book <?= htmlspecialchars($room['room_name']) ?></h2>
    <p class="text-center"><?= htmlspecialchars($room['description']) ?></p>
    <form method="POST">
        <div class="mb-3">
            <label for="start_time" class="form-label">Start Time</label>
            <input type="datetime-local" id="start_time" name="start_time" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="end_time" class="form-label">End Time</label>
            <input type="datetime-local" id="end_time" name="end_time" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Confirm Booking</button>
    </form>
</div>

<?php include 'footer.php'; ?>

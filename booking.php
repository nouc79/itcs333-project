<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require 'connection.php';
include 'header.php';

// Fetch available rooms
$rooms = [];
try {
    $stmt = $pdo->query("SELECT id, room_name FROM rooms");
    $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching rooms: " . $e->getMessage());
}

// Get pre-selected room
$selected_room = isset($_GET['room_id']) ? intval($_GET['room_id']) : null;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $room_id = $_POST['room_id'];
    $booking_start = $_POST['booking_start'];
    $booking_end = $_POST['booking_end'];

    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }

    // Validate booking times
    if (strtotime($booking_start) >= strtotime($booking_end)) {
        $error = "End time must be after start time.";
    } else {
        // Check for booking conflicts
        try {
            $stmt = $pdo->prepare("
                SELECT * FROM bookings 
                WHERE room_id = :room_id 
                AND ((booking_start BETWEEN :start AND :end) 
                OR (booking_end BETWEEN :start AND :end) 
                OR (:start BETWEEN booking_start AND booking_end))
            ");
            $stmt->execute([
                'room_id' => $room_id,
                'start' => $booking_start,
                'end' => $booking_end
            ]);
            $conflict = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($conflict) {
                $error = "The selected time conflicts with an existing booking.";
            } else {
                // Insert the booking
                $stmt = $pdo->prepare("
                    INSERT INTO bookings (user_id, room_id, booking_start, booking_end)
                    VALUES (:user_id, :room_id, :start, :end)
                ");
                $stmt->execute([
                    'user_id' => $_SESSION['user_id'],
                    'room_id' => $room_id,
                    'start' => $booking_start,
                    'end' => $booking_end
                ]);
                $success = "Booking successful!";
            }
        } catch (PDOException $e) {
            die("Error processing booking: " . $e->getMessage());
        }
    }
}
?>

<div class="container mt-5">
    <h1 class="text-center">Book a Room</h1>
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <?php if (!empty($success)): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form method="POST" class="mt-4">
        <div class="mb-3">
            <label for="room_id" class="form-label">Select Room</label>
            <select name="room_id" id="room_id" class="form-select" required>
                <option value="">Choose a room</option>
                <?php foreach ($rooms as $room): ?>
                    <option value="<?= $room['id'] ?>" <?= $room['id'] == $selected_room ? 'selected' : '' ?>>
                        <?= htmlspecialchars($room['room_name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="booking_start" class="form-label">Start Time</label>
            <input type="datetime-local" name="booking_start" id="booking_start" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="booking_end" class="form-label">End Time</label>
            <input type="datetime-local" name="booking_end" id="booking_end" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Book Now</button>
    </form>
</div>

<?php include 'footer.php'; ?>


<?php
session_start();
require_once 'connection.php';

if (!isset($_SESSION['user_id'])) {
    die("User not logged in.");
}

$user_id = $_SESSION['user_id']; 

try {

    $upcomingBookings = $db->prepare("
        SELECT r.room_name, b.booking_start, b.booking_end 
        FROM bookings b 
        JOIN rooms r ON b.room_id = r.id 
        WHERE b.user_id = :user_id AND b.booking_start > NOW()
        ORDER BY b.booking_start ASC
    ");
    $upcomingBookings->execute(['user_id' => $user_id]);

    $pastBookings = $db->prepare("
        SELECT r.room_name, b.booking_start, b.booking_end 
        FROM bookings b 
        JOIN rooms r ON b.room_id = r.id 
        WHERE b.user_id = :user_id AND b.booking_start <= NOW()
        ORDER BY b.booking_start DESC
    ");
    $pastBookings->execute(['user_id' => $user_id]);
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
    <title>User Dashboard</title>
</head>
<body>
<div class="container mt-5">
    <h3>Your Bookings</h3>

    <h4>Upcoming Bookings</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Room Name</th>
                <th>Start Time</th>
                <th>End Time</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($upcomingBookings->rowCount() > 0): ?>
                <?php foreach ($upcomingBookings as $booking): ?>
                    <tr>
                        <td><?= htmlspecialchars($booking['room_name']) ?></td>
                        <td><?= $booking['booking_start'] ?></td>
                        <td><?= $booking['booking_end'] ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3" class="text-center">No upcoming bookings.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <h4>Past Bookings</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Room Name</th>
                <th>Start Time</th>
                <th>End Time</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($pastBookings->rowCount() > 0): ?>
                <?php foreach ($pastBookings as $booking): ?>
                    <tr>
                        <td><?= htmlspecialchars($booking['room_name']) ?></td>
                        <td><?= $booking['booking_start'] ?></td>
                        <td><?= $booking['booking_end'] ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3" class="text-center">No past bookings.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>

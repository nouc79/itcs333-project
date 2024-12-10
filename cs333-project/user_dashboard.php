<?php
session_start();
require 'connection.php';
include 'header.php';

// Redirect if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch user bookings
try {
    $stmt = $pdo->prepare("SELECT b.id, b.booking_start, b.booking_end, r.room_name 
                           FROM bookings b 
                           JOIN rooms r ON b.room_id = r.id 
                           WHERE b.user_id = :user_id
                           ORDER BY b.booking_start DESC");
    $stmt->execute(['user_id' => $_SESSION['user_id']]);
    $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>

<div class="container mt-5">
    <div class="text-center">
        <h1 class="mb-4">My Bookings</h1>
        <p class="lead">Manage your room bookings easily from here.</p>
    </div>
    <div class="row mt-4">
        <?php if (empty($bookings)): ?>
            <div class="col-12 text-center">
                <p class="text-muted">You have no bookings at the moment.</p>
                <a href="rooms.php" class="btn btn-primary">Book a Room</a>
            </div>
        <?php else: ?>
            <div class="col-12">
                <table class="table table-bordered shadow-sm">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Room</th>
                            <th>Start Time</th>
                            <th>End Time</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($bookings as $index => $booking): ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td><?= htmlspecialchars($booking['room_name']) ?></td>
                                <td><?= htmlspecialchars($booking['booking_start']) ?></td>
                                <td><?= htmlspecialchars($booking['booking_end']) ?></td>
                                <td>
                                    <form method="POST" action="cancel_booking.php">
                                        <input type="hidden" name="booking_id" value="<?= $booking['id'] ?>">
                                        <button type="submit" class="btn btn-danger btn-sm">Cancel Booking</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'footer.php'; ?>

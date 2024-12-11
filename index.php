<?php
session_start();
require 'connection.php';
include 'header.php';

// Fetch rooms from the database
$stmt = $pdo->query("SELECT * FROM rooms");
$rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mt-5">
    <div class="text-center">
        <?php if (isset($_SESSION['user_id'])): ?>
            <h2 class="mb-4">Welcome, <?= htmlspecialchars($_SESSION['user_name']) ?>!</h2>
        <?php else: ?>
            <h2 class="mb-4">Welcome to the Room Booking System</h2>
            <p class="lead">Please <a href="login.php">log in</a> to book a room.</p>
        <?php endif; ?>
    </div>

    <h2 class="text-center">Available Rooms</h2>
    <div class="row mt-4">
        <?php foreach ($rooms as $room): ?>
            <div class="col-md-4 mb-4">
                <div class="card">
                    <img src="class2.jpg" class="card-img-top" alt="Room Image">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($room['room_name']) ?></h5>
                        <p class="card-text">Capacity: <?= htmlspecialchars($room['capacity']) ?></p>
                        <p class="card-text"><?= htmlspecialchars($room['description']) ?></p>
                        
                        <!-- Book this room button -->
                        <a href="book_room.php?id=<?= $room['id'] ?>" class="btn btn-success">Book this Room</a>

                        <!-- Comment link -->
                        <a href="comments.php?room_id=<?= $room['id'] ?>" class="btn btn-info">Leave a Comment</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include 'footer.php'; ?>

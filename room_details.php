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

// Fetch comments for the room
$stmt = $pdo->prepare("SELECT c.comment, u.name AS user_name 
                       FROM comments c 
                       JOIN users u ON c.user_id = u.id 
                       WHERE c.room_id = :room_id
                       ORDER BY c.created_at DESC");
$stmt->execute(['room_id' => $room_id]);
$comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mt-5">
    <h2 class="text-center"><?= htmlspecialchars($room['room_name']) ?></h2>
    <p class="lead"><?= htmlspecialchars($room['description']) ?></p>
    <h4>Comments</h4>

    <?php if (empty($comments)): ?>
        <p>No comments yet. Be the first to comment!</p>
    <?php else: ?>
        <ul class="list-group">
            <?php foreach ($comments as $comment): ?>
                <li class="list-group-item">
                    <strong><?= htmlspecialchars($comment['user_name']) ?>:</strong>
                    <p><?= htmlspecialchars($comment['comment']) ?></p>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <a href="rooms.php" class="btn btn-secondary mt-4">Back to Rooms</a>
</div>

<?php include 'footer.php'; ?>

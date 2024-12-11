<?php
session_start();
require 'connection.php';
include 'header.php';

// Redirect if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get the room ID from the URL
$room_id = $_GET['room_id'];

// Fetch room details
$stmt = $pdo->prepare("SELECT * FROM rooms WHERE id = :id");
$stmt->execute(['id' => $room_id]);
$room = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$room) {
    die("Room not found.");
}

// Fetch comments for the room
$stmt = $pdo->prepare("SELECT c.comment, u.name AS user_name, c.created_at 
                       FROM comments c 
                       JOIN users u ON c.user_id = u.id 
                       WHERE c.room_id = :room_id
                       ORDER BY c.created_at DESC");
$stmt->execute(['room_id' => $room_id]);
$comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle comment form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $comment = $_POST['comment'];

    // Insert comment into the database
    try {
        $stmt = $pdo->prepare("INSERT INTO comments (user_id, room_id, comment) VALUES (:user_id, :room_id, :comment)");
        $stmt->execute([
            'user_id' => $_SESSION['user_id'],
            'room_id' => $room_id,
            'comment' => $comment
        ]);

        // Redirect back to the room details page after successful comment
        header("Location: comments.php?room_id=" . $room_id);
        exit();
    } catch (PDOException $e) {
        die("Error submitting comment: " . $e->getMessage());
    }
}
?>

<div class="container mt-5">
    <h2 class="text-center"><?= htmlspecialchars($room['room_name']) ?></h2>
    <p class="lead text-center"><?= htmlspecialchars($room['description']) ?></p>

    <!-- Existing Comments Section -->
    <h4>Comments</h4>
    <?php if (empty($comments)): ?>
        <p>No comments yet. Be the first to comment!</p>
    <?php else: ?>
        <ul class="list-group">
            <?php foreach ($comments as $comment): ?>
                <li class="list-group-item">
                    <strong><?= htmlspecialchars($comment['user_name']) ?>:</strong>
                    <p><?= htmlspecialchars($comment['comment']) ?></p>
                    <small class="text-muted"><?= date('F j, Y, g:i a', strtotime($comment['created_at'])) ?></small>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <!-- Comment Form -->
    <h4 class="mt-4">Add a Comment</h4>
    <form method="POST">
        <div class="mb-3">
            <label for="comment" class="form-label">Your Comment</label>
            <textarea name="comment" id="comment" class="form-control" rows="5" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary w-100">Submit Comment</button>
    </form>

    <a href="rooms.php" class="btn btn-secondary mt-4">Back to Rooms</a>
</div>

<?php include 'footer.php'; ?>

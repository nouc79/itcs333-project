<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

require 'connection.php';
include 'header.php';

try {
    // Fetch all comments
    $stmt = $pdo->query("
        SELECT 
            c.id AS comment_id,
            c.comment,
            c.response,
            r.room_name,
            u.name AS user_name,
            c.created_at
        FROM comments c
        JOIN rooms r ON c.room_id = r.id
        JOIN users u ON c.user_id = u.id
        ORDER BY c.created_at DESC
    ");
    $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}

// Handle response submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment_id'], $_POST['response'])) {
    $comment_id = $_POST['comment_id'];
    $response = $_POST['response'];

    try {
        $stmt = $pdo->prepare("UPDATE comments SET response = :response WHERE id = :id");
        $stmt->execute(['response' => $response, 'id' => $comment_id]);
        $success = "Response submitted successfully!";
        header("Location: manage_comments.php");
        exit();
    } catch (PDOException $e) {
        die("Database error: " . $e->getMessage());
    }
}
?>

<div class="container mt-5">
    <div class="text-center">
        <h1>Manage Comments</h1>
        <p class="lead">View and respond to user comments.</p>
    </div>

    <?php if (!empty($success)): ?>
        <div class="alert alert-success text-center"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <div class="row mt-4">
        <div class="col-12">
            <table class="table table-bordered shadow-sm">
                <thead class="table-light">
                    <tr>
                        <th>User</th>
                        <th>Room</th>
                        <th>Comment</th>
                        <th>Response</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($comments as $comment): ?>
                        <tr>
                            <td><?= htmlspecialchars($comment['user_name']) ?></td>
                            <td><?= htmlspecialchars($comment['room_name']) ?></td>
                            <td><?= htmlspecialchars($comment['comment']) ?></td>
                            <td><?= htmlspecialchars($comment['response'] ?: 'No response yet') ?></td>
                            <td>
                                <form method="POST">
                                    <input type="hidden" name="comment_id" value="<?= $comment['comment_id'] ?>">
                                    <textarea name="response" class="form-control mb-2" required><?= htmlspecialchars($comment['response']) ?></textarea>
                                    <button type="submit" class="btn btn-primary btn-sm">Submit Response</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>


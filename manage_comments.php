<?php
session_start();
require_once 'db.php';

// if (!isset($_SESSION['admin_id'])) {
//     header("Location: admin_login.php");
//     exit;
// }

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $stmt = $pdo->prepare("
        UPDATE notifications 
        SET is_read = 1 
        WHERE admin_id = :admin_id
    ");
    $stmt->execute(['admin_id' => $_SESSION['admin_id']]);
}


$stmt = $pdo->query("
    SELECT c.id, c.comment, c.response, c.created_at, r.room_name, u.name AS user_name
    FROM comments c
    JOIN rooms r ON c.room_id = r.id
    JOIN users u ON c.user_id = u.id
    ORDER BY c.created_at DESC
");
$comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_response'])) {
    $comment_id = $_POST['comment_id'];
    $response = $_POST['response'];

    try {
        $stmt = $pdo->prepare("
            UPDATE comments 
            SET response = :response 
            WHERE id = :id
        ");
        $stmt->execute(['response' => $response, 'id' => $comment_id]);
        $success = "Response submitted successfully!";
    } catch (PDOException $e) {
        die("Database error: " . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <title>Manage Comments</title>
</head>
<body>
<div class="container mt-5">
    <h3>Manage Comments</h3>
    <?php if (!empty($success)): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>
    <table class="table">
        <thead>
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
                <td><?= htmlspecialchars($comment['response']) ?></td>
                <td>
                    <form method="POST">
                        <input type="hidden" name="comment_id" value="<?= $comment['id'] ?>">
                        <textarea name="response" class="form-control mb-2"><?= htmlspecialchars($comment['response']) ?></textarea>
                        <button type="submit" name="submit_response" class="btn btn-primary btn-sm">Submit Response</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>

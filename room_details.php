<?php
include 'includes/db.php';

// Check if the room ID is provided
if (!isset($_GET['id'])) {
    die("Room ID is required.");
}

$id = (int)$_GET['id'];

// Fetch room details from the database
$sql = "SELECT * FROM rooms WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['id' => $id]);
$room = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$room) {
    die("Room not found.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room Details - <?= htmlspecialchars($room['room_number']) ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">

</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Room <?= htmlspecialchars($room['room_number']) ?></h1>
        <p><strong>Floor:</strong> <?= htmlspecialchars($room['floor']) ?></p>
        <p><strong>Section:</strong> <?= htmlspecialchars($room['section']) ?></p>
        <p><strong>Capacity:</strong> <?= htmlspecialchars($room['capacity']) ?></p>
        <p><strong>Equipment:</strong> <?= htmlspecialchars($room['equipment']) ?></p>
        <p><strong>Available Timeslots:</strong> <?= htmlspecialchars($room['available_timeslots']) ?></p>
        <a href="index.php" class="btn btn-secondary">Back to Room List</a>
    </div>
</body>
</html>

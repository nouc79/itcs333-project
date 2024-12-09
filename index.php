<?php
include 'includes/db.php';

// Fetch all rooms from the database
$sql = "SELECT * FROM rooms";
$stmt = $pdo->query($sql);
$rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room Borowsing - UOB</title>
   
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Room Browsing - UOB</h1>
        <div class="row">
            <?php foreach ($rooms as $room): ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Room <?= htmlspecialchars($room['room_number']) ?></h5>
                            <p class="card-text">Floor: <?= htmlspecialchars($room['floor']) ?></p>
                            <p class="card-text">Section: <?= htmlspecialchars($room['section']) ?></p>
                            <p class="card-text">Capacity: <?= htmlspecialchars($room['capacity']) ?></p>
                            <a href="room_details.php?id=<?= $room['id'] ?>" class="btn btn-primary">View Details</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>



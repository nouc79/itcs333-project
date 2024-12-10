<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

require 'connection.php';
include 'header.php';

try {
    // Fetch room usage data
    $stmt = $pdo->query("
        SELECT 
            r.room_name, 
            COUNT(b.id) AS total_bookings,
            SUM(TIMESTAMPDIFF(MINUTE, b.booking_start, b.booking_end)) AS total_minutes_used
        FROM rooms r
        LEFT JOIN bookings b ON r.id = b.room_id
        GROUP BY r.id
        ORDER BY r.room_name ASC
    ");
    $roomUsage = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>

<div class="container mt-5">
    <div class="text-center">
        <h1>Room Usage Report</h1>
        <p class="lead">Overview of room bookings and usage.</p>
    </div>
    <div class="row mt-4">
        <div class="col-12">
            <table class="table table-bordered shadow-sm">
                <thead class="table-light">
                    <tr>
                        <th>Room Name</th>
                        <th>Total Bookings</th>
                        <th>Total Usage (Minutes)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($roomUsage as $usage): ?>
                        <tr>
                            <td><?= htmlspecialchars($usage['room_name']) ?></td>
                            <td><?= $usage['total_bookings'] ?: 0 ?></td>
                            <td><?= $usage['total_minutes_used'] ?: 0 ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>

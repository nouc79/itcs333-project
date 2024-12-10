<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

require 'connection.php';
include 'header.php';

// Fetch admin name from session
$admin_name = $_SESSION['admin_name'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <!-- Welcome Message -->
    <div class="text-center">
        <h1>Welcome, Admin <?= htmlspecialchars($admin_name) ?>!</h1>
        <p class="lead">Use the tools below to manage the system.</p>
    </div>

    <!-- Dashboard Sections -->
    <div class="row mt-4">
        <!-- Manage Rooms -->
        <div class="col-md-6 mb-4">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Manage Rooms</h5>
                    <p class="card-text">Add, edit, or delete rooms.</p>
                    <a href="manage_rooms.php" class="btn btn-primary">Manage Rooms</a>
                </div>
            </div>
        </div>

        <!-- Manage Comments -->
        <div class="col-md-6 mb-4">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Manage Comments</h5>
                    <p class="card-text">View and respond to user comments.</p>
                    <a href="manage_comments.php" class="btn btn-primary">Manage Comments</a>
                </div>
            </div>
        </div>

        <!-- Room Usage -->
        <div class="col-md-6 mb-4">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Room Usage</h5>
                    <p class="card-text">View analytics and reports.</p>
                    <a href="room_usage.php" class="btn btn-primary">View Room Usage</a>
                </div>
            </div>
        </div>

    </div>
</div>

<?php include 'footer.php'; ?>
</body>
</html>

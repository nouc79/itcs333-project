<?php
session_start();
require_once 'connection.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    die("User not logged in.");
}

// Get the logged-in user ID
$user_id = $_SESSION['user_id'];

try {
    // Fetch upcoming bookings
    $upcomingBookings = $db->prepare("
        SELECT r.room_name, b.booking_start, b.booking_end 
        FROM bookings b 
        JOIN rooms r ON b.room_id = r.id 
        WHERE b.user_id = :user_id AND b.booking_start > NOW()
        ORDER BY b.booking_start ASC
    ");
    $upcomingBookings->execute(['user_id' => $user_id]);

    // Fetch past bookings
    $pastBookings = $db->prepare("
        SELECT r.room_name, b.booking_start, b.booking_end 
        FROM bookings b 
        JOIN rooms r ON b.room_id = r.id 
        WHERE b.user_id = :user_id AND b.booking_start <= NOW()
        ORDER BY b.booking_start DESC
    ");
    $pastBookings->execute(['user_id' => $user_id]);
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <title>User Dashboard</title>
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 50px;
        }
        h3, h4 {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            background-color: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
<div class="container">
    

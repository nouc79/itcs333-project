<?php
session_start();
require 'connection.php';

// Redirect if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check if the booking ID is provided
if (isset($_POST['booking_id'])) {
    $booking_id = $_POST['booking_id'];

    try {
        // Delete the booking from the database
        $stmt = $pdo->prepare("DELETE FROM bookings WHERE id = :id AND user_id = :user_id");
        $stmt->execute(['id' => $booking_id, 'user_id' => $_SESSION['user_id']]);

        // Redirect to the user dashboard after cancellation
        header("Location: user_dashboard.php");
        exit();
    } catch (PDOException $e) {
        die("Error canceling booking: " . $e->getMessage());
    }
} else {
    // If no booking ID is provided, redirect back to dashboard
    header("Location: user_dashboard.php");
    exit();
}
?>
<?php 
// Database configuration
$host = 'localhost';
$dbname = 'uob_room_booking'; // Correct database name
$username = 'root';
$password = ''; // Your MySQL password, if set

<?php
try {
    $pdo = new PDO("mysql:host=localhost;dbname=uob_room_booking", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>

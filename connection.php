<?php
$host = 'localhost';          
$dbname = 'itcollege';  
$username = 'root';           
$password = '';              

try {
    $db = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Enable error reporting
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}


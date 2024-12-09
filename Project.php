<?php
session_start();

// Database connection 
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'user';

// Connect to the database
$conn = new mysqli($host, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Select the database
$conn->select_db($dbname);
// feedback
$message = "";

// Handle user registration
if (isset($_POST['register'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validate email for UoB domains
    if (!preg_match("/@stu\.uob\.edu\.bh$/", $email) && !preg_match("/@uob\.edu\.bh$/", $email)) {
        $message = "Please use a valid UoB email.";
    } else {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert into the database
        $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $hashed_password);

        if ($stmt->execute()) {
            $message = "Registration successful!";
        } else {
            $message = "Error: " . $stmt->error;
        }
    }
}

// Handle user login
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if user exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            header("Location: profile.php");
        } else {
            $message = "Invalid password!";
        }
    } else {
        $message = "User not found!";
    }
}

// Fetch user profile if logged in
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Student Project - User System</title>
    <link rel="stylesheet" href="design.css">
</head>
<body>
<div class="container">
    <h2>User System</h2>
    <div><?php echo $message; ?></div>

    <?php if (!isset($_SESSION['user_id'])): ?>
    <!-- Registration Form -->
    <form method="POST" action="">
        <h3>Register</h3>
        <input type="text" name="name" placeholder="Full Name" required>
        <input type="email" name="email" placeholder="University Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" name="register">Register</button>
    </form>

    <!-- Login Form -->
    <form method="POST" action="">
        <h3>Login</h3>
        <input type="email" name="email" placeholder="University Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" name="login">Login</button>
    </form>
    <?php else: ?>
    <!-- Profile Section -->
    <h3>Welcome, <?php echo $user['name']; ?></h3>
    <p>Email: <?php echo $user['email']; ?></p>
    <a href="logout.php">Logout</a>
    <?php endif; ?>
</div>
</body>
</html>
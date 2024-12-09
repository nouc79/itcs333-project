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
    // Handle profile picture upload
if (isset($_POST['upload_picture']) && isset($_SESSION['user_id']))
    $user_id = $_SESSION['user_id'];

    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
        $target_dir = "uploads/";
        $file_name = basename($_FILES["profile_picture"]["name"]);
        $target_file = $target_dir . uniqid() . "_" . $file_name;
        $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Validate file type (allow only jpg, jpeg, png, gif)
        $allowed_types = ["jpg", "jpeg", "png", "gif"];
        if (in_array($file_type, $allowed_types)) {
            // Move uploaded file to target directory
            if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
                // Update profile picture in the database
                $stmt = $conn->prepare("UPDATE users SET profile_picture = ? WHERE id = ?");
                $stmt->bind_param("si", $target_file, $user_id);
                if ($stmt->execute()) {
                    $message = "Profile picture uploaded successfully!";
                } else {
                    $message = "Error updating profile picture in database.";
                }
            } else {
                $message = "Error uploading the file.";
            }
        } else {
            $message = "Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed.";
        }
    } else {
        $message = "No file uploaded or file upload error.";
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

    <!-- Profile Picture Display -->
    <div class="profile-picture">
        <?php if (!empty($user['profile_picture'])): ?>
            <img src="<?php echo $user['profile_picture']; ?>" alt="Profile Picture" width="150" height="150">
        <?php else: ?>
            <img src="default-profile.png" alt="Default Profile Picture" width="150" height="150">
        <?php endif; ?>
    </div>

    <!-- Profile Picture Upload Form -->
    <form method="POST" action="" enctype="multipart/form-data">
        <label for="profile_picture">Upload Profile Picture:</label>
        <input type="file" name="profile_picture" id="profile_picture" accept="image/*">
        <button type="submit" name="upload_picture">Upload</button>
    </form>

    <a href="logout.php">Logout</a>
    <?php endif; ?>
</div>
</body>
</html>

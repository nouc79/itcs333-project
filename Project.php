<?php
session_start();

// Database connection
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'your_database';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = '';

// Handle registration
if (isset($_POST['register'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Email validation for UoB email domains
    if (!preg_match("/@stu\.uob\.edu\.bh$/", $email) && !preg_match("/@uob\.edu\.bh$/", $email)) {
        $message = "Invalid email address. Please use a UoB email address.";
    } else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert user into database
        $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $hashedPassword);

        if ($stmt->execute()) {
            $message = "Registration successful!";
        } else {
            $message = "Error: " . $stmt->error;
        }
    }
}

// Handle login
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if email exists in the database
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
            header("Location: profile.php"); // Redirect to profile page after successful login
            exit();
        } else {
            $message = "Invalid password!";
        }
    } else {
        $message = "No account found with that email!";
    }
}

// User Profile Management
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    // Update user profile (name, email)
    if (isset($_POST['update_profile'])) {
        $name = $_POST['name'];
        $email = $_POST['email'];

        // Update profile in database
        $stmt = $conn->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
        $stmt->bind_param("ssi", $name, $email, $user_id);

        if ($stmt->execute()) {
            $message = "Profile updated successfully!";
        } else {
            $message = "Error: " . $stmt->error;
        }
    }

    // Handle profile picture upload
    if (isset($_POST['upload_picture'])) {
        if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
            $upload_dir = 'uploads/';
            $file_name = $_FILES['profile_picture']['name'];
            $file_tmp = $_FILES['profile_picture']['tmp_name'];
            $file_path = $upload_dir . basename($file_name);

            // Check if the file is an image
            $image_info = getimagesize($file_tmp);
            if ($image_info !== false) {
                if (move_uploaded_file($file_tmp, $file_path)) {
                    // Update profile picture in the database
                    $stmt = $conn->prepare("UPDATE users SET profile_picture = ? WHERE id = ?");
                    $stmt->bind_param("si", $file_path, $user_id);
                    if ($stmt->execute()) {
                        $message = "Profile picture updated successfully!";
                    } else {
                        $message = "Error updating profile picture!";
                    }
                } else {
                    $message = "Failed to upload picture.";
                }
            } else {
                $message = "Please upload a valid image file.";
            }
        }
    }
} else {
    $message = "Please log in to access your profile.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="design.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration, Login & Profile Management</title>
</head>
<body>
    <div class="form-container">
        <h2>User Registration, Login & Profile Management</h2>
        <div class="message"><?php echo $message; ?></div>

        <!-- Registration Form -->
        <?php if (!isset($_SESSION['user_id'])): ?>
        <h3>Register</h3>
        <form method="post" action="">
            <input type="text" name="name" placeholder="Name" required>
            <input type="email" name="email" placeholder="University Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="register">Register</button>
        </form>

        <hr>

        <!-- Login Form -->
        <h3>Login</h3>
        <form method="post" action="">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="login">Login</button>
        </form>
        <?php endif; ?>

        <!-- User Profile Section -->
        <?php if (isset($_SESSION['user_id'])): ?>
        <h3>Edit Profile</h3>
        <form method="post" action="" enctype="multipart/form-data">
            <input type="text" name="name" value="<?php echo $user['name']; ?>" placeholder="Name" required>
            <input type="email" name="email" value="<?php echo $user['email']; ?>" placeholder="Email" required>
            <button type="submit" name="update_profile">Update Profile</button>
        </form>

        <hr>

        <!-- Profile Picture Upload -->
        <h3>Change Profile Picture</h3>
        <form method="post" action="" enctype="multipart/form-data">
            <input type="file" name="profile_picture" accept="image/*" required>
            <button type="submit" name="upload_picture">Upload Picture</button>
        </form>

        <div class="profile-picture">
            <img src="<?php echo isset($user['profile_picture']) ? $user['profile_picture'] : 'default-profile.png'; ?>" alt="Profile Picture" width="150">
        </div>
        <?php endif; ?>
    </div>
</body>
</html>

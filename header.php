<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room Booking System</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background: url('room4.jpg') no-repeat center center fixed;
            background-size: cover;
            color: black; /* Default text color */
        }
        .navbar {
            background-color: rgba(0, 0, 0, 0.8); /* Slight transparency for the navbar */
        }
        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
        }
        h1, h2 {
            color: black; /* Set text color to black */
        }
        h1 {
            font-size: 2rem; /* Smaller size for "Welcome" heading */
        }
        h2 {
            font-size: 1.5rem; /* Smaller size for "Available Rooms" heading */
        }
        .container {
            background-color: #f1efdb; /* Set background color */
            border-radius: 15px; /* Round edges */
            padding: 20px; /* Add some padding */
            margin-top: 20px; /* Add margin to separate from navbar */
        }
    </style>
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark">
            <div class="container-fluid d-flex justify-content-between align-items-center">
                <div class="d-none d-lg-flex flex-grow-1"></div> <!-- Empty space for centering -->
                <a class="navbar-brand mx-auto" href="index.php">Room Booking</a>
                <div class="collapse navbar-collapse flex-grow-1 d-flex justify-content-end" id="navbarNav">
                    <ul class="navbar-nav">
                        <?php if (isset($_SESSION['admin_id'])): ?>
                            <li class="nav-item"><a class="nav-link" href="admin_dashboard.php">Dashboard</a></li>
                            <li class="nav-item"><a class="nav-link" href="manage_rooms.php">Manage Rooms</a></li>
                            <li class="nav-item"><a class="nav-link" href="manage_comments.php">Manage Comments</a></li>
                            <li class="nav-item"><a class="nav-link" href="room_usage.php">Room Usage</a></li>
                            <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
                        <?php elseif (isset($_SESSION['user_id'])): ?>
                            <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                            <li class="nav-item"><a class="nav-link" href="user_dashboard.php">My Bookings</a></li>
                            <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
                        <?php else: ?>
                            <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
                            <li class="nav-item"><a class="nav-link" href="register.php">Register</a></li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
</body>
</html>
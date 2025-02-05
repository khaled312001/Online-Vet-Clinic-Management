<?php
session_start();
include '../backend/database.php'; // Database connection

if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: /VetDirect/public/login.php?error=unauthorized');
    exit();
}

// Fetch counts for dashboard
$query_users = "SELECT COUNT(*) AS count FROM users WHERE role = 'user'";
$query_doctors = "SELECT COUNT(*) AS count FROM users WHERE role = 'doctor'";
$query_appointments = "SELECT COUNT(*) AS count FROM appointments";
$query_emergencies = "SELECT COUNT(*) AS count FROM emergencies WHERE status = 'pending'";

$result_users = $conn->query($query_users)->fetch_assoc();
$result_doctors = $conn->query($query_doctors)->fetch_assoc();
$result_appointments = $conn->query($query_appointments)->fetch_assoc();
$result_emergencies = $conn->query($query_emergencies)->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../public/css/style.css">
    <style>
        /* Profile Section Styling */
.profile-section {
    max-width: 600px;
    margin: 30px auto;
    background: #ffffff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    text-align: center;
}

.profile-section h2 {
    color: #4CAF50;
    font-size: 2rem;
    margin-bottom: 15px;
}

.profile-section p {
    font-size: 1.2rem;
    color: #333;
    margin: 10px 0;
    padding: 8px;
    background: #f4f4f4;
    border-radius: 5px;
}

.profile-section strong {
    color: #4CAF50;
    font-weight: bold;
}

    </style>
</head>
<body>
    <header>
        <h1>Admin Dashboard</h1>
        <nav>
           <a href="../public/index.php">Home Page</a>
                        <a href="dashboard.php">Dashboard</a>

            <a href="manage_doctors.php">Manage Doctors</a>
            <a href="manage_users.php">Manage Users</a>
            <a href="reports.php">Reports</a>
                        <a href="../backend/logout.php">Logout</a>

        </nav>
    </header>

    <main>
        <h2>Admin Overview</h2>
        <div class="dashboard">
            <div class="box">
                <h3>Total Users</h3>
                <p><?= $result_users['count'] ?></p>
            </div>
            <div class="box">
                <h3>Total Doctors</h3>
                <p><?= $result_doctors['count'] ?></p>
            </div>
            <div class="box">
                <h3>Appointments</h3>
                <p><?= $result_appointments['count'] ?></p>
            </div>
            <div class="box">
                <h3>Pending Emergencies</h3>
                <p><?= $result_emergencies['count'] ?></p>
            </div>
        </div>
    </main>

    <footer>
        <p>&copy; 2025 Veterinary Platform. All Rights Reserved.</p>
    </footer>
</body>
</html>

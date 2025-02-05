<?php
session_start();
include '../backend/database.php'; // Database connection

// Ensure the user is logged in as an admin
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: /VetDirect/public/login.php?error=unauthorized');
    exit();
}

// Fetch appointments data from correct tables
$query = "SELECT a.id, p.name AS pet_name, u.name AS doctor_name, a.date, a.time
          FROM appointments a
          JOIN pets p ON a.pet_id = p.id
          JOIN users u ON a.doctor_id = u.id
          WHERE u.role = 'doctor'";  // Ensure only doctors are fetched
          
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Reports</title>
    <link rel="stylesheet" href="../public/css/style.css">
</head>
<body>
    <header>
        <h1>Reports</h1>
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
        <h2>Appointment Reports</h2>
        <table class="styled-table">
            <thead>
                <tr>
                    <th>Appointment ID</th>
                    <th>Pet Name</th>
                    <th>Doctor Name</th>
                    <th>Date</th>
                    <th>Time</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) : ?>
                    <tr>
                        <td><?= htmlspecialchars($row['id']) ?></td>
                        <td><?= htmlspecialchars($row['pet_name']) ?></td>
                        <td><?= htmlspecialchars($row['doctor_name']) ?></td>
                        <td><?= htmlspecialchars($row['date']) ?></td>
                        <td><?= htmlspecialchars($row['time']) ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </main>
    <footer>
        <p>&copy; 2025 Veterinary Platform</p>
    </footer>
</body>
</html>

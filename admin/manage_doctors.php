<?php
session_start();
include '../backend/database.php'; // Database connection

// Ensure the user is logged in as an admin
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: /VetDirect/public/login.php?error=unauthorized');
    exit();
}

// Fetch doctors from users table where role = 'doctor'
$result = $conn->query("SELECT id, name, email, phone FROM users WHERE role = 'doctor'");

// Handle doctor deletion
if (isset($_GET['delete_id'])) {
    $doctor_id = $_GET['delete_id'];
    $conn->query("DELETE FROM users WHERE id = $doctor_id AND role = 'doctor'");
    header('Location: manage_doctors.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Doctors</title>
    <link rel="stylesheet" href="../public/css/style.css">
</head>
<body>
    <header>
        <h1>Manage Doctors</h1>
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
        <h2>Doctors List</h2>
        <table class="styled-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) : ?>
                    <tr>
                        <td><?= htmlspecialchars($row['id']) ?></td>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= htmlspecialchars($row['email']) ?></td>
                        <td><?= htmlspecialchars($row['phone'] ?? 'N/A') ?></td>
                        <td>
                            <a href="manage_doctors.php?delete_id=<?= $row['id'] ?>" class="btn-delete" onclick="return confirm('Are you sure you want to delete this doctor?')">Delete</a>
                        </td>
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

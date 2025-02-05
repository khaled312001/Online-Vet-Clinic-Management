<?php
session_start();


include '../backend/database.php'; // Database connection

// Fetch users
$result = $conn->query("SELECT * FROM users");

// Handle user deletion
if (isset($_GET['delete_id'])) {
    $user_id = $_GET['delete_id'];
    $conn->query("DELETE FROM users WHERE id = $user_id");
    header('Location: manage_users.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Users</title>
    <link rel="stylesheet" href="../public/css/style.css">
</head>
<body>
    <header>
        <h1>Manage Users</h1>
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
        <h2>Users List</h2>
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
                        <td><?= htmlspecialchars($row['phone']) ?></td>
                        <td>
                            <a href="manage_users.php?delete_id=<?= $row['id'] ?>" class="btn-delete" onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
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

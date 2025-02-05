<?php
session_start();
if (!isset($_SESSION['user_logged_in']) || !isset($_SESSION['user_id'])) {
    header('Location: ../public/login.php?error=not_logged_in');
    exit();
}

include '../backend/database.php'; // Database connection

$user_id = $_SESSION['user_id'];

// Fetch pets
$query = "SELECT * FROM pets WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User - My Pets</title>
    <link rel="stylesheet" href="../public/css/style.css">
</head>
<body>
    <header>
        <h1>My Pets</h1>
        <nav>
            <a href="../public/index.php">Home Page</a>
            <a href="profile.php">Profile</a>
            <a href="pets.php">My Pets</a>
            <a href="appointments.php">Appointments</a>
            <a href="emergency.php">Emergency</a>
            <a href="forum.php">Forum</a>
            <a href="../backend/logout.php">Logout</a>
        </nav>
    </header>
    <main>
        <section>
            <h2>Pet Details</h2>
            <?php if ($result->num_rows > 0): ?>
                <table class="styled-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Breed</th>
                            <th>Age</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['name'] ?? 'Unknown') ?></td>
                                <td><?= htmlspecialchars($row['breed'] ?? 'Unknown') ?></td>
                                <td><?= htmlspecialchars($row['age'] ?? 'N/A') ?></td>
                                <td>
                                    <a href="appointments.php?pet_id=<?= htmlspecialchars($row['id']) ?>">Book Appointment</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>You currently have no pets listed. <a href="add_pet.php">Add a new pet</a>.</p>
            <?php endif; ?>
        </section>
    </main>
    <footer>
        <p>&copy; 2025 Veterinary Platform. All Rights Reserved.</p>
    </footer>
</body>
</html>

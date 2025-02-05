<?php
session_start();
include '../backend/database.php'; // Database connection

// Ensure the user is logged in as a doctor
if (!isset($_SESSION['user_logged_in']) || !isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'doctor') {
    header('Location: ../public/login.html?error=unauthorized');
    exit();
}

$doctor_id = $_SESSION['user_id']; // Store logged-in doctor's ID

// Fetch appointments assigned to the logged-in doctor
$query = "SELECT a.id, p.name AS pet_name, u.name AS owner_name, a.date, a.time, a.status 
          FROM appointments a
          JOIN pets p ON a.pet_id = p.id
          JOIN users u ON p.user_id = u.id
          WHERE a.doctor_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $doctor_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor - Appointments</title>
    <link rel="stylesheet" href="../public/css/style.css">
    <style>
        .status-confirmed {
    color: green;
    font-weight: bold;
}

.status-pending {
    color: orange;
    font-weight: bold;
}

.confirm-btn {
    background-color: #4CAF50;
    color: white;
    padding: 5px 10px;
    border: none;
    cursor: pointer;
    font-size: 14px;
}

.confirm-btn:hover {
    background-color: #45a049;
}

    </style>
</head>
<body>
    <header>
        <h1>Appointments</h1>
        <nav>
            <a href="../public/index.php">Home Page</a>
            <a href="dashboard.php">Dashboard</a>
            <a href="appointments.php">Appointments</a>
            <a href="consultations.php">Consultations</a>
            <a href="prescriptions.php">Prescriptions</a>
            <a href="../backend/logout.php">Logout</a>
        </nav>
    </header>
    <section>
        <h2>Upcoming Appointments</h2>
        <?php if ($result->num_rows > 0): ?>
        <table class="styled-table">
            <tr>
                <th>Pet Name</th>
                <th>Owner Name</th>
                <th>Date</th>
                <th>Time</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()) : ?>
                <tr>
                    <td><?= htmlspecialchars($row['pet_name']) ?></td>
                    <td><?= htmlspecialchars($row['owner_name']) ?></td>
                    <td><?= htmlspecialchars($row['date']) ?></td>
                    <td><?= htmlspecialchars($row['time']) ?></td>
                    <td class="<?= ($row['status'] === 'confirmed') ? 'status-confirmed' : 'status-pending' ?>">
                        <?= htmlspecialchars(ucfirst($row['status'])) ?>
                    </td>
                    <td>
                        <?php if ($row['status'] === 'pending'): ?>
                            <form action="confirm_appointment.php" method="POST" style="display:inline;">
                                <input type="hidden" name="appointment_id" value="<?= $row['id'] ?>">
                                <button type="submit" class="confirm-btn">Confirm</button>
                            </form>
                       
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
        <?php else: ?>
            <p>No appointments assigned to you.</p>
        <?php endif; ?>
    </section>
</body>
</html>

<?php
session_start();
include '../backend/database.php'; // Database connection

// Ensure the user is logged in as a doctor
if (!isset($_SESSION['user_logged_in']) || !isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'doctor') {
    header('Location: ../public/login.php?error=unauthorized');
    exit();
}

$doctor_id = $_SESSION['user_id']; // Doctor's ID is stored in `user_id`

// Fetch appointment stats
$stmt = $conn->prepare("SELECT COUNT(*) AS count FROM appointments WHERE doctor_id = ?");
$stmt->bind_param('i', $doctor_id);
$stmt->execute();
$appointments_count = $stmt->get_result()->fetch_assoc()['count'] ?? 0;

// Fetch consultation stats
$stmt = $conn->prepare("SELECT COUNT(*) AS count FROM consultations WHERE doctor_id = ?");
$stmt->bind_param('i', $doctor_id);
$stmt->execute();
$consultations_count = $stmt->get_result()->fetch_assoc()['count'] ?? 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor - Dashboard</title>
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
        <h1>Dashboard</h1>
        <nav>
            <a href="../public/index.php">Home Page</a>
            <a href="dashboard.php">Dashboard</a>
            <a href="appointments.php">Appointments</a>
            <a href="consultations.php">Consultations</a>
            <a href="prescriptions.php">Prescriptions</a>
            <a href="../backend/logout.php">Logout</a>
        </nav>
    </header>
    <section class="profile-section">
    <h2>Welcome, Doctor!</h2>
        <p>You have <strong><?= htmlspecialchars($appointments_count) ?></strong> upcoming appointments.</p>
        <p>You have completed <strong><?= htmlspecialchars($consultations_count) ?></strong> consultations.</p>
    </section>
</body>
</html>

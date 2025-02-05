<?php
session_start();
include '../backend/database.php';

// Check if the user is logged in
if (!isset($_SESSION['user_logged_in']) || !isset($_SESSION['user_id'])) {
    header('Location: ../public/login.php?error=not_logged_in');
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user details
$query = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    header('Location: ../public/login.php?error=user_not_found');
    exit();
}

// Assign default values for nullable fields
$name = htmlspecialchars($user['name'] ?? 'Unknown');
$email = htmlspecialchars($user['email'] ?? 'Unknown');
$phone = htmlspecialchars($user['phone'] ?? 'Not provided');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile</title>
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
        <h1>My Profile</h1>
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
        <section class="profile-section">
            <h2>Profile Information</h2>
            <p><strong>Name:</strong> <?= $name ?></p>
            <p><strong>Email:</strong> <?= $email ?></p>
            <p><strong>Phone:</strong> <?= $phone ?></p>
        </section>
    </main>
    <footer>
        <p>&copy; 2025 Veterinary Platform. All Rights Reserved.</p>
    </footer>
</body>
</html>

<?php
session_start();
if (!isset($_SESSION['user_logged_in']) || !isset($_SESSION['user_id'])) {
    header('Location: ../public/login.php?error=not_logged_in');
    exit();
}

include '../backend/database.php'; // Database connection

// Handle emergency request submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message = $_POST['message'];
    $user_id = $_SESSION['user_id'];

    // Insert the emergency request into the database
    $query = "INSERT INTO emergencies (user_id, message, date) VALUES (?, ?, NOW())";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('is', $user_id, $message);

    if ($stmt->execute()) {
        $success = true;
    } else {
        $error = "An error occurred while submitting your emergency request. Please try again.";
    }
}

// Fetch user's emergency requests
$query = "SELECT message, date FROM emergencies WHERE user_id = ? ORDER BY date DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User - Emergency</title>
    <link rel="stylesheet" href="../public/css/style.css">
    <style>
        .success-message {
            color: green;
            font-weight: bold;
            margin-top: 10px;
        }
        .error-message {
            color: red;
            font-weight: bold;
            margin-top: 10px;
        }
        .emergency-history {
            margin-top: 20px;
        }
        .emergency-history table {
            width: 100%;
            border-collapse: collapse;
        }
        .emergency-history th, .emergency-history td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .emergency-history th {
            color: green  ;
            background-color: #f4f4f4;
        }
    </style>
</head>
<body>
    <header>
        <h1>Emergency Services</h1>
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
            <h2>Request Emergency Help</h2>
            <form action="" method="POST">
                <label for="message">Describe Your Emergency:</label>
                <textarea id="message" name="message" rows="4" required></textarea>
                <button type="submit">Submit Request</button>
            </form>
            <?php if (isset($success) && $success): ?>
                <p class="success-message">Your emergency request has been submitted successfully.</p>
            <?php elseif (isset($error)): ?>
                <p class="error-message"><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>
        </section>
        <section class="emergency-history">
            <h2>Your Emergency Requests</h2>
            <?php if ($result->num_rows > 0): ?>
                <table class="styled-table">
                    <thead>
                        <tr>
                            <th>Message</th>
                            <th>Date Submitted</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['message']) ?></td>
                                <td><?= htmlspecialchars($row['date']) ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>You have not submitted any emergency requests yet.</p>
            <?php endif; ?>
        </section>
    </main>
    <footer>
        <p>&copy; 2025 Veterinary Platform. All Rights Reserved.</p>
    </footer>
</body>
</html>

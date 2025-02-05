<?php
session_start();
include '../backend/database.php'; // Database connection

// Ensure doctor is logged in
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_role'] !== 'doctor') {
    header('Location: ../public/login.php?error=unauthorized');
    exit();
}

$doctor_id = $_SESSION['user_id']; // Fetch doctor ID from session

// Fetch past consultations
$query = "SELECT c.id, p.name AS pet_name, u.name AS owner_name, c.date, c.notes, c.reply
          FROM consultations c
          JOIN appointments a ON c.appointment_id = a.id
          JOIN pets p ON a.pet_id = p.id
          JOIN users u ON p.user_id = u.id
          WHERE c.doctor_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $doctor_id);
$stmt->execute();
$result = $stmt->get_result();

// Handle new consultation submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['appointment_id'], $_POST['notes'])) {
    $appointment_id = $_POST['appointment_id'];
    $notes = $_POST['notes'];

    $query = "INSERT INTO consultations (doctor_id, appointment_id, notes, date) VALUES (?, ?, ?, NOW())";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('iis', $doctor_id, $appointment_id, $notes);
    $stmt->execute();
    
    header('Location: consultations.php?success=consultation_added');
    exit();
}

// Handle doctor reply submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['consultation_id'], $_POST['reply'])) {
    $consultation_id = $_POST['consultation_id'];
    $reply = $_POST['reply'];

    $query = "UPDATE consultations SET reply = ? WHERE id = ? AND doctor_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('sii', $reply, $consultation_id, $doctor_id);
    $stmt->execute();
    
    header('Location: consultations.php?success=reply_added');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor - Consultations</title>
    <link rel="stylesheet" href="../public/css/style.css">
</head>
<body>
    <header>
        <h1>Consultations</h1>
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
        <h2>Consultation History</h2>
        <table>
            <thead>
                <tr>
                    <th>Pet Name</th>
                    <th>Owner Name</th>
                    <th>Date</th>
                    <th>Doctor Notes</th>
                    <th>Doctor Reply</th>
                    <th>Reply</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) : ?>
                    <tr>
                        <td><?= htmlspecialchars($row['pet_name']) ?></td>
                        <td><?= htmlspecialchars($row['owner_name']) ?></td>
                        <td><?= htmlspecialchars($row['date']) ?></td>
                        <td><?= htmlspecialchars($row['notes']) ?></td>
                        <td><?= htmlspecialchars($row['reply'] ?: 'No reply yet') ?></td>
                        <td>
                            <form action="" method="POST">
                                <input type="hidden" name="consultation_id" value="<?= $row['id'] ?>">
                                <textarea name="reply" placeholder="Reply to this consultation..." required></textarea>
                                <button type="submit">Submit Reply</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </section>

    
</body>
</html>

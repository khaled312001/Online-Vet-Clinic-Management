<?php
session_start();
include '../backend/database.php'; // Database connection

// Ensure the user is logged in as a doctor
if (!isset($_SESSION['user_logged_in']) || !isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'doctor') {
    header('Location: ../public/login.html?error=unauthorized');
    exit();
}

$doctor_id = $_SESSION['user_id']; // Get doctor ID from session

// Fetch prescriptions created by the doctor
$query = "SELECT pr.id, p.name AS pet_name, u.name AS owner_name, pr.medicine, pr.dosage, pr.date 
          FROM prescriptions pr
          JOIN pets p ON pr.pet_id = p.id
          JOIN users u ON p.user_id = u.id
          WHERE pr.doctor_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $doctor_id);
$stmt->execute();
$result = $stmt->get_result();

// Fetch all pets for dropdown selection
$pets_query = "SELECT p.id, p.name, u.name AS owner_name FROM pets p JOIN users u ON p.user_id = u.id";
$pets_result = $conn->query($pets_query);

// Handle new prescription submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['pet_id'], $_POST['medicine'], $_POST['dosage'])) {
        header('Location: prescriptions.php?error=missing_data');
        exit();
    }

    $pet_id = $_POST['pet_id'];
    $medicine = $_POST['medicine'];
    $dosage = $_POST['dosage'];

    $query = "INSERT INTO prescriptions (doctor_id, pet_id, medicine, dosage, date) VALUES (?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('iiss', $doctor_id, $pet_id, $medicine, $dosage);
    $stmt->execute();
    
    header('Location: prescriptions.php?success=added');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor - Prescriptions</title>
    <link rel="stylesheet" href="../public/css/style.css">
</head>
<body>
    <header>
        <h1>Prescriptions</h1>
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
        <h2>Prescription History</h2>
        <?php if ($result->num_rows > 0): ?>
        <table class="styled-table">
            <tr>
                <th>Pet Name</th>
                <th>Owner Name</th>
                <th>Medicine</th>
                <th>Dosage</th>
                <th>Date</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()) : ?>
                <tr>
                    <td><?= htmlspecialchars($row['pet_name']) ?></td>
                    <td><?= htmlspecialchars($row['owner_name']) ?></td>
                    <td><?= htmlspecialchars($row['medicine']) ?></td>
                    <td><?= htmlspecialchars($row['dosage']) ?></td>
                    <td><?= htmlspecialchars($row['date']) ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
        <?php else: ?>
            <p>No prescriptions found.</p>
        <?php endif; ?>
    </section>

    <section>
        <h2>Create New Prescription</h2>
        <form action="" method="POST">
            <label for="pet_id">Select Pet:</label>
            <select id="pet_id" name="pet_id" required>
                <option value="">-- Select a Pet --</option>
                <?php while ($pet = $pets_result->fetch_assoc()): ?>
                    <option value="<?= $pet['id'] ?>">
                        <?= htmlspecialchars($pet['name']) ?> (Owner: <?= htmlspecialchars($pet['owner_name']) ?>)
                    </option>
                <?php endwhile; ?>
            </select>
            
            <label for="medicine">Medicine:</label>
            <input type="text" id="medicine" name="medicine" required>
            
            <label for="dosage">Dosage:</label>
            <input type="text" id="dosage" name="dosage" required>
            
            <button type="submit">Add Prescription</button>
        </form>
    </section>
</body>
</html>

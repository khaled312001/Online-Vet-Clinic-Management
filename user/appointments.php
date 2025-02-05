<?php
session_start();
if (!isset($_SESSION['user_logged_in']) || !isset($_SESSION['user_id'])) {
    header('Location: ../public/login.php?error=not_logged_in');
    exit();
}

include '../backend/database.php'; // Database connection

$user_id = $_SESSION['user_id'];

// Fetch all appointments for the logged-in user
$appointments_query = "SELECT a.id, p.name AS pet_name, d.name AS doctor_name, a.date, a.time, a.status
                       FROM appointments a
                       JOIN pets p ON a.pet_id = p.id
                       JOIN users d ON a.doctor_id = d.id
                       WHERE p.user_id = ?";
$stmt = $conn->prepare($appointments_query);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$appointments_result = $stmt->get_result();

// Fetch pets for the logged-in user
$pets_query = "SELECT id, name FROM pets WHERE user_id = ?";
$pets_stmt = $conn->prepare($pets_query);
$pets_stmt->bind_param('i', $user_id);
$pets_stmt->execute();
$pets_result = $pets_stmt->get_result();

// Fetch all doctors
$doctors_query = "SELECT id, name FROM users WHERE role = 'doctor'";
$doctors_result = $conn->query($doctors_query);

// Handle form submission to book a new appointment
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pet_id = $_POST['pet_id'];
    $doctor_id = $_POST['doctor_id'];
    $date = $_POST['date'];
    $time = $_POST['time'];

    // Insert new appointment into the database
    $insert_query = "INSERT INTO appointments (pet_id, doctor_id, date, time, status) VALUES (?, ?, ?, ?, 'pending')";
    $insert_stmt = $conn->prepare($insert_query);
    $insert_stmt->bind_param('iiss', $pet_id, $doctor_id, $date, $time);

    if ($insert_stmt->execute()) {
        header('Location: appointments.php?success=appointment_booked');
        exit();
    } else {
        $error_message = "Error booking appointment. Please try again.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User - Appointments</title>
    <link rel="stylesheet" href="../public/css/style.css">
    <style>
        .status-pending {
            color: orange;
            font-weight: bold;
        }
        .status-confirmed {
            color: green;
            font-weight: bold;
        }
        .status-canceled {
            color: red;
            font-weight: bold;
        }
    </style>
    <script>
        function showAppointmentForm() {
            document.getElementById('appointment-form').style.display = 'block';
        }
    </script>
</head>
<body>
    <header>
        <h1>My Appointments</h1>
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
            <h2>Select Details to Book an Appointment</h2>
            <button onclick="showAppointmentForm()">Book Appointment</button>
            <form id="appointment-form" action="appointments.php" method="POST" style="display: none;">
                <label for="pet_id">Choose a Pet:</label>
                <select id="pet_id" name="pet_id" required>
                    <?php while ($pet = $pets_result->fetch_assoc()): ?>
                        <option value="<?= htmlspecialchars($pet['id']) ?>"><?= htmlspecialchars($pet['name']) ?></option>
                    <?php endwhile; ?>
                </select>

                <label for="doctor_id">Choose a Doctor:</label>
                <select id="doctor_id" name="doctor_id" required>
                    <?php while ($doctor = $doctors_result->fetch_assoc()): ?>
                        <option value="<?= htmlspecialchars($doctor['id']) ?>"><?= htmlspecialchars($doctor['name']) ?></option>
                    <?php endwhile; ?>
                </select>

                <label for="date">Appointment Date:</label>
                <input type="date" id="date" name="date" required>

                <label for="time">Appointment Time:</label>
                <input type="time" id="time" name="time" required>

                <button type="submit">Confirm Appointment</button>
            </form>
        </section>
        <section>
            <h2>My Appointments</h2>
            <?php if ($appointments_result->num_rows > 0): ?>
                <table class="styled-table">
                    <thead>
                        <tr>
                            <th>Pet Name</th>
                            <th>Doctor Name</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($appointment = $appointments_result->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($appointment['pet_name']) ?></td>
                                <td><?= htmlspecialchars($appointment['doctor_name']) ?></td>
                                <td><?= htmlspecialchars($appointment['date']) ?></td>
                                <td><?= htmlspecialchars($appointment['time']) ?></td>
                                <td class="<?= 'status-' . strtolower($appointment['status']) ?>">
                                    <?= htmlspecialchars(ucfirst($appointment['status'])) ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>You have no appointments at the moment. <a href="#appointment-form" onclick="showAppointmentForm()">Book one now</a>.</p>
            <?php endif; ?>
        </section>
    </main>
    <footer>
        <p>&copy; 2025 Veterinary Platform. All Rights Reserved.</p>
    </footer>
</body>
</html>

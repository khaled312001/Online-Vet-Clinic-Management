<?php
session_start();
if (!isset($_SESSION['user_logged_in']) || !isset($_SESSION['user_id'])) {
    header('Location: ../public/login.php?error=not_logged_in');
    exit();
}

include '../backend/database.php'; // Database connection

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $name = $_POST['name'];
    $breed = $_POST['breed'];
    $type = $_POST['type'];
    $age = $_POST['age'];
    $health_status = $_POST['health_status'];

    // Insert pet details into the database
    $query = "INSERT INTO pets (user_id, name, breed, type, age, health_status) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('isssis', $user_id, $name, $breed, $type, $age, $health_status);

    if ($stmt->execute()) {
        header('Location: pets.php?success=pet_added');
        exit();
    } else {
        $error = "Failed to add pet. Please try again.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add a New Pet</title>
    <link rel="stylesheet" href="../public/css/style.css">
</head>
<body>
    <header>
        <h1>Add a New Pet</h1>
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
            <h2>Enter Pet Details</h2>
            <?php if (isset($error)): ?>
                <p class="error"><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>
            <form action="add_pet.php" method="POST">
                <label for="name">Pet Name:</label>
                <input type="text" id="name" name="name" required>

                <label for="breed">Breed:</label>
                <input type="text" id="breed" name="breed" required>

                <label for="type">Type:</label>
                <select id="type" name="type" required>
                    <option value="dog">Dog</option>
                    <option value="cat">Cat</option>
                    <option value="bird">Bird</option>
                    <option value="other">Other</option>
                </select>

                <label for="age">Age (in years):</label>
                <input type="number" id="age" name="age" min="0" required>

                <label for="health_status">Health Status:</label>
                <textarea id="health_status" name="health_status" required></textarea>

                <button type="submit">Add Pet</button>
            </form>
        </section>
    </main>
    <footer>
        <p>&copy; 2025 Veterinary Platform. All Rights Reserved.</p>
    </footer>
</body>
</html>

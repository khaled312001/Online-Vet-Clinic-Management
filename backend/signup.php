<?php
include 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $role = trim($_POST['role']); // Role: 'user' or 'doctor'

    // Validate role (only allow 'user' or 'doctor')
    if (!in_array($role, ['user', 'doctor'])) {
        header('Location: ../public/signup.php?error=invalid_role');
        exit();
    }

    // Check if email already exists
    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        header('Location: ../public/signup.php?error=email_exists');
        exit();
    }

    // ✅ **Hash password using SHA-256**
    $hashed_password = hash('sha256', $password);

    // Insert new user into the database
    $query = "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ssss', $name, $email, $hashed_password, $role);

    if ($stmt->execute()) {
        // Redirect user based on role
        if ($role === 'doctor') {
            header('Location: ../doctor/dashboard.php?success=signup');
        } else {
            header('Location: ../public/login.php?success=signup');
        }
        exit();
    } else {
        header('Location: ../public/signup.php?error=signup_failed');
        exit();
    }
}
?>

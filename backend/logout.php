<?php
session_start();
session_unset();  // Unset all session variables
session_destroy(); // Destroy the session

// Redirect to login page with a logout success message
header("Location: ../public/login.php?message=logged_out");
exit();
?>

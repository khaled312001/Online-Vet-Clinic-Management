<?php
include 'database.php';

// Fetch pets that need vaccinations
$query = "SELECT p.name AS pet_name, u.name AS owner_name, v.due_date 
          FROM vaccinations v
          JOIN pets p ON v.pet_id = p.id
          JOIN users u ON p.owner_id = u.id
          WHERE v.due_date <= CURDATE()";
$result = $conn->query($query);

while ($row = $result->fetch_assoc()) {
    // Send vaccination reminders (example: echo or email)
    echo "Reminder: " . $row['owner_name'] . ", your pet " . $row['pet_name'] . " needs a vaccination by " . $row['due_date'] . ".<br>";
}
?>

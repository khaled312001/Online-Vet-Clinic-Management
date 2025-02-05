<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message = strtolower(trim($_POST['message']));

    $responses = [
        'hello' => 'Hi there! How can I help you today?',
        'appointment' => 'You can book an appointment by going to the Appointments section.',
        'vaccine' => 'You can check vaccination reminders in the Vaccination section.',
        'contact' => 'You can contact us at support@VetDirect.com.'
    ];

    $response = isset($responses[$message]) ? $responses[$message] : 'Sorry, I didn\'t understand that. Please try again.';
    echo json_encode(['response' => $response]);
}
?>

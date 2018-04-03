<?php
header("Access-Control-Allow-Origin: http://10.0.0.49:3000");
header("Access-Control-Allow-Headers: Content-Type");
header("Vary: Origin");
header("Access-Control-Allow-Methods: POST,OPTIONS");
header('Content-Type: application/json');

$content = trim(file_get_contents("php://input"));
//Attempt to decode the incoming RAW post data from JSON.
$decoded = json_decode($content, true);

$email = $decoded['email']; // Email is required below
$phone = isset($decoded['phone']) ? $decoded['phone'] : '[not provided]';
$customerName = isset($decoded['customerName']) ? $decoded['customerName'] : '';
$message = isset($decoded['message']) ? $decoded['message'] : '';
if (isset($decoded['email'])) {
    $headers = 'From: UBS Customer Inquiry <no-reply@unabuenaspanish.com>';

    $sent = mail(
        'unabuenaspanish@gmail.com',
        "New message from customer: $customerName",
        "Customer name: $customerName\r\nCustomer email: $email\r\nCustomer phone: $phone\r\nMessage:\r\n\r\n$message",
        $headers
    );

    if ($sent) {
        echo "{\"message\": \"Message successfully sent.\"}";
        die();
    }
    echo "{\"message\": \"Message was unable to be sent -- there was an error!\"}";
    die();
}

echo "{\"message\": \"Couldn't send message - no return email was specified\"}";

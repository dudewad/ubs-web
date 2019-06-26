<?php
// Uncomment this to whitelist local domain access to the api
// header("Access-Control-Allow-Origin: http://10.0.0.49:3000");
header("Access-Control-Allow-Headers: Content-Type");
header("Vary: Origin");
header("Access-Control-Allow-Methods: POST,OPTIONS");
header('Content-Type: application/json');

$content = trim(file_get_contents("php://input"));
//Attempt to decode the incoming RAW post data from JSON.
$decoded = json_decode($content, true);

$appointmentType = $decoded['appointmentType'];
$email = $decoded['email']; // Email is required below
$phone = isset($decoded['phone']) ? $decoded['phone'] : '[not provided]';
$customerName = isset($decoded['customerName']) ? $decoded['customerName'] : '';
$time1 = isset($decoded['time1']) ? $decoded['time1'] : 'Not Specified';
$time2 = isset($decoded['time2']) ? $decoded['time2'] : 'Not Specified';
$time3 = isset($decoded['time3']) ? $decoded['time3'] : 'Not specified';
$firstName = preg_split("/[\s]+/", $customerName);
if (isset($decoded['email'])) {
    $headers = "From: $firstName[0] via ubs.com <no-reply@unabuenaspanish.com>";

    $sent = mail(
        // 'layton.r.miller@gmail.com',
        'unabuenaspanish@gmail.com',
        "Consultation",
        "¡Hola! $customerName te contacta desde ubs.com:\r\n\r\n$customerName\r\n$email\r\n$phone\r\n\r\nAppointment type: $appointmentType\r\n\r\nRequested times:\r\nTime 1: $time1\r\nTime 2: $time2\r\nTime 3: $time3\r\n\r\nMuchas gracias.",
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

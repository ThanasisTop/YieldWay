<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Input validation
    $name = htmlspecialchars($_POST['name']);
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $message = htmlspecialchars($_POST['message']);

    if (!$email || empty($name) || empty($message)) {
        http_response_code(400);
        echo json_encode(["error" => "Invalid input."]);
        exit;
    }

    // Elastic Email API URL
    $url = "https://api.elasticemail.com/v2/email/send";

    // API key (secure this in an environment variable)
    $apiKey = "0491B2014022EA1A4F9BB66BE7FDFDBECF6A";

    // Email data
    $postData = [
        'apikey' => $apiKey,
        'from' => "sakis530@hotmail.com",
        'fromName' => "Your Name",
        'to' => "sakis530@hotmail.com",
        'subject' => "Contact Form Message",
        'bodyText' => "Name: $name\nMessage: $message",
        'bodyHtml' => "<p><strong>Name:</strong> $name</p><p><strong>Message:</strong> $message</p>",
        'isTransactional' => true,
    ];

    // Send request to Elastic Email
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode == 200) {
        echo json_encode(["success" => "Email sent successfully."]);
    } else {
        echo json_encode(["error" => "Failed to send email.", "details" => $response]);
    }
} else {
    http_response_code(405); // Method not allowed
    echo json_encode(["error" => "Invalid request method."]);
}
?>

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
    $apiKey = "78546589768CE6389A9D861C5C9F09FD397C1DB96D4003AA5732523CA5C3E3C116D85AB2CCF43D7FE84185145930769F";

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

	$responseData = json_decode($response, true);

    if ($responseData['success'] === true) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "error" => $responseData['error']]);
    }

    // if ($response == 200) {
		// if ($responseData['success'] === true) {
			// echo "Success: Email sent successfully.";
		// } 
		// else
			// echo "Success request but response was: $response";
    // } else {
        // echo "Error: HTTP $httpCode. Response: $response";
    // }
} else {
    echo json_encode(["success" => false, "error" => "Invalid request method."]);
}
?>

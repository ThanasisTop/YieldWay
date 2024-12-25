<?php
header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
	// Input validation
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $message = trim($_POST['message']);
	$subject = trim($_POST['subject']);
	$isFromContact = isset($_POST['isFromContact']); // Default to 'false'

    // Sanitize and validate inputs
    $name = preg_replace("/[^\w\s\.-]/", "", $name); // Allow alphanumeric, spaces, dots, and hyphens
    $email = filter_var($email, FILTER_VALIDATE_EMAIL); // Validate email
    $message = htmlspecialchars($message); // Escape HTML entities
    $message = str_replace(["\r", "\n"], " ", $message); // Remove newlines to prevent injection
	//$subject = preg_replace("/[^\w\s\.-]/", "", $subject); // Sanitize subject

	// API key (secure this in an environment variable)
    $apiKey = "78546589768CE6389A9D861C5C9F09FD397C1DB96D4003AA5732523CA5C3E3C116D85AB2CCF43D7FE84185145930769F";	
		
	if($isFromContact){
		if (!$email || empty($name) || empty($message)|| empty($subject)) {
			http_response_code(400);
			echo json_encode(["error" => "Invalid input.Email: $email, Subject: $subject, Name: $name, Message: $message"]);
			exit;
		}
		
		// Email data for contact form
		$postData = [
			'apikey' => $apiKey,
			'from' => "sakis530@hotmail.com",
			'fromName' => $name,
			'to' => "sakis530@hotmail.com",
			'subject' => $subject,
			'bodyText' => "Name: $name\nMessage: $message",
			'bodyHtml' => "<p><strong>Email:</strong> $email</p><p><strong>Name:</strong> $name</p><p><strong>Message:</strong> $message</p>",
			'isTransactional' => true,
		];
	}
	else
	{
		if (!$email || empty($subject)) {
			http_response_code(400);
			echo json_encode(["error" => "Invalid input.Email: $email, Subject: $subject"]);
			exit;
		}
		
		// Email data for newsletter
		$postData = [
			'apikey' => $apiKey,
			'from' => "sakis530@hotmail.com",
			'to' => "sakis530@hotmail.com",
			'subject' => $subject,
			'bodyText' => "Email: $email",
			'bodyHtml' => "<p><strong>Email:</strong> $email</p>",
			'isTransactional' => true,
		];
	}
	
    // Elastic Email API URL
    $url = "https://api.elasticemail.com/v2/email/send";

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
} else {
    echo json_encode(["success" => false, "error" => "Invalid request method."]);
}
?>

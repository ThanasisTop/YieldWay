<?php
header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
	 // Input validation and sanitization
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $message = isset($_POST['message']) ? trim($_POST['message']) : '';
    $subject = isset($_POST['subject']) ? trim($_POST['subject']) : '';
	$isFromContact = filter_var($_POST['isFromContact'], FILTER_VALIDATE_BOOLEAN);

    // Sanitize and validate inputs
    $name = preg_replace("/[^\w\s\.-]/", "", $name); // Allow alphanumeric, spaces, dots, and hyphens
    $email = filter_var($email, FILTER_VALIDATE_EMAIL); // Validate email
    $message = htmlspecialchars(strip_tags($message), ENT_QUOTES, 'UTF-8');
    $subject = htmlspecialchars(strip_tags($subject), ENT_QUOTES, 'UTF-8');
	
	$apiKey = getenv('SMTP_API_KEY');
	
    if (!$apiKey) {
        http_response_code(500);
        echo json_encode(["error" => "Server configuration error. API key not set."]);
        exit;
    }	
		
	if(true){
		if (!$email || empty($name) || empty($message)|| empty($subject)) {
			http_response_code(400);
			echo json_encode(["error" => "Invalid input.Email: $email, Subject: $subject, Name: $name, Message: $message"]);
			exit;
		}
		
		// Email data for contact form
		$postData = [
			'apikey' => $apiKey,
			'from' => "info@yieldway.gr",
			'fromName' => $name,
			'to' => "info@yieldway.gr",
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
			'from' => "info@yieldway.gr",
			'to' => "info@yieldway.gr",
			'subject' => $subject,
			'bodyText' => "Email: $email",
			'bodyHtml' => "<p><strong>Email:</strong> $email</p>",
			'isTransactional' => true,
		];
	}
	
    // Elastic Email API URL
    $url = "https://api.elasticemail.com/v2/email/send";

    // Send request to Elastic Email
    /*$ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
	
	if (!$response || $httpCode >= 400) {
        http_response_code($httpCode ?: 500);
        echo json_encode(["success" => false, "error" => "Failed to send email. HTTP Code: $httpCode"]);
        exit;
    }
	
	$responseData = json_decode($response, true);

    if ($responseData['success'] === true) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "error" => $responseData['error']]);
    }*/
    http_response_code(200);
	echo json_encode(["success" => true, "postData" => $postData]);
	exit;
} else {
    echo json_encode(["success" => false, "error" => "Invalid request method."]);
}
?>

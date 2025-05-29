<?php
header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
	 // Input validation and sanitization
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $message = trim($_POST['message']);
	$subject = trim($_POST['subject']);
	$mobile = trim($_POST['mobile']);
	$isFromContact = filter_var($_POST['isFromContact'], FILTER_VALIDATE_BOOLEAN);

    // Sanitize and validate inputs
    //$name = preg_replace("/[^\w\s\.-]/", "", $name); // Allow alphanumeric, spaces, dots, and hyphens
    $email = filter_var($email, FILTER_VALIDATE_EMAIL); // Validate email
    $message = htmlspecialchars(strip_tags($message), ENT_QUOTES, 'UTF-8');
    $subject = htmlspecialchars(strip_tags($subject), ENT_QUOTES, 'UTF-8');
	
	$secret = getenv('CAPTCHA_SECRET_KEY'); // or use: $secret = 'YOUR_SECRET_KEY';
	$response = $_POST['g-recaptcha-response'];
	
	if (empty($response)) {
	    http_response_code(403);
	    echo json_encode(["error" => "reCAPTCHA was not completed."]);
	    exit;
	}
	
	// Verify with Google
	$verify = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$secret}&response={$response}");
	$captcha_success = json_decode($verify);
	
	// Validate response
	if (!$captcha_success->success) {
	    http_response_code(403);
	    echo json_encode(["error" => "reCAPTCHA validation failed."]);
	    exit;
	}
	
	$apiKey = getenv('SMTP_API_KEY');
	
    if (!$apiKey) {
        http_response_code(500);
        echo json_encode(["error" => "Server configuration error. API key not set."]);
        exit;
    }	
		
	if($isFromContact){
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
			'bodyText' => "Name: $name\nMobile: $mobile\nMessage: $message",
			'bodyHtml' => "<p><strong>Email:</strong> $email</p><p><strong>Name:</strong> $name</p><p><strong>Mobile:</strong> $mobile</p><p><strong>Message:</strong> $message</p>",
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
    //$ch = curl_init();
    //curl_setopt($ch, CURLOPT_URL, $url);
    //curl_setopt($ch, CURLOPT_POST, true);
    //curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
    //curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    //$response = curl_exec($ch);
    //$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    //curl_close($ch);

    if (mail($postData['to'], $postData['subject'], $postData['bodyHtml'], $headers)) {
        echo "Message sent successfully!";
    } else {
        echo "Failed to send message.";
    }
	
	//if (!$response || $httpCode >= 400) {
        //http_response_code($httpCode ?: 500);
        //echo json_encode(["success" => false, "error" => "Failed to send email. HTTP Code: $httpCode"]);
        //exit;
    //}
	
	//$responseData = json_decode($response, true);

    //if ($responseData['success'] === true) {
        //echo json_encode(["success" => true]);
    //} else {
        //echo json_encode(["success" => false, "error" => $responseData['error']]);
    //}

} else {
    echo json_encode(["success" => false, "error" => "Invalid request method."]);
}
?>

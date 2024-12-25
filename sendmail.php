<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Ensure PHPMailer is installed via Composer

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

    // Elastic Email SMTP settings
    $smtpHost = "smtp.elasticemail.com";
    $smtpUsername = "info@yieldway.gr"; // Replace with your Elastic Email username (usually your email)
    $smtpPassword = "0491B2014022EA1A4F9BB66BE7FDFDBECF6A";  // Replace with your Elastic Email API key
    $smtpPort = 587; // Default SMTP port (alternatives: 2525, 25)

    try {
        // Create a new PHPMailer instance
        $mail = new PHPMailer(true);

        // Server settings
        $mail->isSMTP();
        $mail->Host = $smtpHost;
        $mail->SMTPAuth = true;
        $mail->Username = $smtpUsername;
        $mail->Password = $smtpPassword;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Use TLS encryption
        $mail->Port = $smtpPort;

        // Email content
        $mail->setFrom('your_email@example.com', 'Your Name'); // Replace with your verified sender email and name
        $mail->addAddress($email, $name); // Add recipient
        $mail->isHTML(true); // Enable HTML format
        $mail->Subject = "Contact Form Message";
        $mail->Body = "<p><strong>Name:</strong> $name</p><p><strong>Message:</strong> $message</p>";
        $mail->AltBody = "Name: $name\nMessage: $message"; // Plain-text fallback

        // Send email
        $mail->send();
        echo json_encode(["success" => "Email sent successfully."]);
    } catch (Exception $e) {
        http_response_code(500); // Internal server error
        echo json_encode(["error" => "Failed to send email.", "details" => $mail->ErrorInfo]);
    }
} else {
    http_response_code(405); // Method not allowed
    echo json_encode(["error" => "Invalid request method."]);
}
?>

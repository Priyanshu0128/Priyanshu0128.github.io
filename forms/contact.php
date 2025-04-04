<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

header("Content-Type: application/json"); // Return JSON response

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST["name"]);
    $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
    $subject = htmlspecialchars($_POST["subject"]);
    $message = htmlspecialchars($_POST["message"]);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(["status" => "error", "message" => "Invalid email format"]);
        exit;
    }

    // Email Configuration
    $mail = new PHPMailer(true);

    try {
        // SMTP Settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';  // Change to your mail server
        $mail->SMTPAuth = true;
        $mail->Username = $email;
        $mail->Password = 'your-email-password'; // Use App Password if using Gmail
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Email Content
        $mail->setFrom($email, $name);
        $mail->addAddress("priyanshulaad28@gmail.com");
        $mail->Subject = $subject;
        $mail->Body = "You have received a new message from your portfolio contact form:\n\n"
                    . "Name: $name\n"
                    . "Email: $email\n"
                    . "Subject: $subject\n\n"
                    . "Message:\n$message";

        $mail->send();

        echo json_encode(["status" => "success", "message" => "Message sent successfully"]);
    } catch (Exception $e) {
        echo json_encode(["status" => "error", "message" => "Message could not be sent. Mailer Error: {$mail->ErrorInfo}"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request"]);
}
?>

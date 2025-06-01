<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Honeypot field check
    if (!empty($_POST['hp_field'])) {
        // This is likely a bot, redirect without processing
        header("Location: thank_you.html");
        exit;
    }

    // Sanitize and validate input data
    $fullName = filter_input(INPUT_POST, 'full_name', FILTER_SANITIZE_STRING);
    $emailAddress = filter_input(INPUT_POST, 'email_address', FILTER_SANITIZE_EMAIL);
    $phoneNumber = filter_input(INPUT_POST, 'phone_number', FILTER_SANITIZE_STRING);
    $subject = filter_input(INPUT_POST, 'subject', FILTER_SANITIZE_STRING);
    $message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING);

    // Check if required fields are empty
    if (empty($fullName) || empty($emailAddress) || empty($subject) || empty($message)) {
        // Redirect to error page if required fields are missing
        header("Location: error.html");
        exit;
    }

    // Validate email format
    if (!filter_var($emailAddress, FILTER_VALIDATE_EMAIL)) {
        // Redirect to error page for invalid email
        header("Location: error.html");
        exit;
    }

    // Your email details
    $to = "t.hubbell2015@gmail.com"; // <-- IMPORTANT: Change this to your actual email address!
    $emailSubject = "New Contact Form Submission: " . $subject;
    $headers = "From: " . $fullName . " <" . $emailAddress . ">\r\n";
    $headers .= "Reply-To: " . $emailAddress . "\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

    // Email body
    $emailBody = "You have received a new message from your website contact form.\n\n";
    $emailBody .= "Name: " . $fullName . "\n";
    $emailBody .= "Email: " . $emailAddress . "\n";
    $emailBody .= "Phone: " . (empty($phoneNumber) ? "N/A" : $phoneNumber) . "\n";
    $emailBody .= "Subject: " . $subject . "\n\n";
    $emailBody .= "Message:\n" . $message . "\n";

    // Attempt to send the email
    if (mail($to, $emailSubject, $emailBody, $headers)) {
        // Success: Redirect to thank you page
        header("Location: thank_you.html");
        exit;
    } else {
        // Failure: Redirect to error page
        header("Location: error.html");
        exit;
    }
} else {
    // If someone tries to access send_email.php directly, redirect them
    header("Location: index.html");
    exit;
}
?>
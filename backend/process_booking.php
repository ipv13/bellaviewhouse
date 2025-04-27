<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../vendor/autoload.php'; // Path to PHPMailer

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate form inputs
    $checkIn = filter_input(INPUT_POST, 'checkIn', FILTER_SANITIZE_STRING);
    $checkInTime = filter_input(INPUT_POST, 'checkInTime', FILTER_SANITIZE_STRING);
    $checkOut = filter_input(INPUT_POST, 'checkOut', FILTER_SANITIZE_STRING);
    $checkOutTime = filter_input(INPUT_POST, 'checkOutTime', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $specialRequests = filter_input(INPUT_POST, 'specialRequests', FILTER_SANITIZE_STRING);
    
    // New fields
    $fullName = filter_input(INPUT_POST, 'fullName', FILTER_SANITIZE_STRING);
    $telephone = filter_input(INPUT_POST, 'telephone', FILTER_SANITIZE_STRING);
    $adults = filter_input(INPUT_POST, 'adults', FILTER_VALIDATE_INT);
    $children = filter_input(INPUT_POST, 'children', FILTER_VALIDATE_INT);
    
    // Get the total price from the form submission
    $totalPrice = filter_input(INPUT_POST, 'totalPrice', FILTER_VALIDATE_FLOAT);

    // Validate required fields
    if (!$checkIn || !$checkOut || !$email || !$fullName || !$telephone || !$adults || !$totalPrice) {
        echo "<p style='color: red;'>Please fill in all required fields.</p>";
        exit;
    }

    // Validate check-in/check-out dates
    if (strtotime($checkIn) >= strtotime($checkOut)) {
        echo "<p style='color: red;'>Check-out date must be after check-in date.</p>";
        exit;
    }

    // If number of children is negative, default to 0
    if ($children === false) {
        $children = 0;
    }

    // Format the check-in and check-out times to AM/PM format
    $checkInTimeFormatted = date("g:i A", strtotime($checkInTime));
    $checkOutTimeFormatted = date("g:i A", strtotime($checkOutTime));

    // Prepare the email
    $mail = new PHPMailer(true);
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Gmail SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'volonakisg@gmail.com'; // Your Gmail address
        $mail->Password = 'dtoi xczv syzz lfbj'; // Use App Password (NOT your Gmail password)
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('your_email@gmail.com', 'Booking System');  // Sender's email (could be your email address)
        $mail->addAddress('volonakisg@gmail.com');  // Replace this with the actual recipient email address


        // Email content
        $mail->isHTML(true);
        $mail->Subject = 'New Booking Request';
        $mail->Body = "
            <h3>New Booking Request</h3>
            <p><strong>Full Name:</strong> $fullName</p>
            <p><strong>Telephone Number:</strong> $telephone</p>
            <p><strong>Check-in:</strong> $checkIn at $checkInTimeFormatted</p>
            <p><strong>Check-out:</strong> $checkOut at $checkOutTimeFormatted</p>
            <p><strong>Email:</strong> $email</p>
            <p><strong>Adults:</strong> $adults</p>
            <p><strong>Children:</strong> $children</p>
            <p><strong>Special Requests:</strong> $specialRequests</p>
            <p><strong>Total Price:</strong> $totalPrice €</p> <!-- Include total price in the email -->
        ";

        $mail->send();
        echo "<p style='color: green;'>Booking request sent successfully!</p>";
    } catch (Exception $e) {
        echo "<p style='color: red;'>Email could not be sent. Mailer Error: {$mail->ErrorInfo}</p>";
    }
} else {
    echo "<p style='color: red;'>Invalid request method.</p>";
}
?>

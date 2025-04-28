<?php
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];
    
    // Basic validation
    if (empty($name) || empty($email) || empty($message)) {
        die("Please fill in all fields");
    }
    
    // Sanitize inputs
    $name = mysqli_real_escape_string($conn, $name);
    $email = mysqli_real_escape_string($conn, $email);
    $message = mysqli_real_escape_string($conn, $message);
    
    // Insert into database
    $sql = "INSERT INTO messages (name, email, message, created_at) VALUES ('$name', '$email', '$message', NOW())";
    
    if ($conn->query($sql) === TRUE) {
        // Send email notification
        $to = "JRVirocel@gmail.com";
        $subject = "New Contact Form Submission - Jan Rowel A. Virocel Portfolio";
        $email_message = "Name: $name\nEmail: $email\nMessage: $message";
        $headers = "From: $email";
        
        mail($to, $subject, $email_message, $headers);
        
        // Redirect back to contact form with success message
        header("Location: index.php#contact?status=success");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
    
    $conn->close();
}
?> 
<?php
// Database connection
$servername = "localhost"; // Change this to your MySQL server hostname if it's different
$username = "your_username"; // Change this to your MySQL username
$password = "your_password"; // Change this to your MySQL password
$database = "njjmsl"; // Change this to your MySQL database name

$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['nwsltr-mail'];

    // Insert email into database
    $sql = "INSERT INTO newsletter_emails (email) VALUES ('$email')";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(array('success' => true));
    } else {
        echo json_encode(array('success' => false, 'error' => $conn->error));
    }
}

$conn->close();
?>
<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "njjmsl";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get data from POST request
$username = $_POST['username'];
$password = $_POST['password'];

// Prepare and execute SQL query
$sql = "SELECT * FROM login WHERE username = '$username' AND password = '$password'";
$result = $conn->query($sql);

// Check if the login is successful
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $pageName = $row['page_name'];

    // Store user information in the session
    $_SESSION['username'] = $username;
    $_SESSION['pageName'] = $pageName;

    echo json_encode(array('success' => true, 'pageName' => $pageName));
} else {
    echo json_encode(array('success' => false, 'message' => 'Invalid username or password'));
}

$conn->close();
?>

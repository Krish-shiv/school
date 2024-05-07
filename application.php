<?php
// Database connection parameters
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

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $name = $_POST["stud-name"];
    $fatherName = $_POST["stud-father-name"];
    $interestedClass = $_POST["stud-interested-class"];
    $address = $_POST["stud-address"];
    $contact = $_POST["stud-contact"];
    $gmail = $_POST["stud-gmail"];
    $comments = $_POST["stud-comments"];

    // Get the formatted date in 'Y-m-d' format
    $formattedDate = date('Y-m-d');

    // SQL query to insert data into the "application" table using prepared statement
    $sql = "INSERT INTO application (name, fathername, interestedclass, address, contact, gmail, comments, app_date)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);

    // Bind parameters
    $stmt->bind_param("ssssssss", $name, $fatherName, $interestedClass, $address, $contact, $gmail, $comments, $formattedDate);

    // Execute the statement
    if ($stmt->execute()) {
        $last_id = $conn->insert_id;

        // Display confirmation popup using JavaScript alert
        echo "<script>
            alert('Application submitted successfully! Registration Number: $last_id');
        </script>";
    } else {
        // Display error popup using JavaScript alert
        echo "<script>
            alert('Error! Failed to submit application. Please try again.');
        </script>";
    }

    // Close the statement
    $stmt->close();
}

// Close the database connection
$conn->close();
?>


<?php include('header.php') ?>
<!-- Application Form -->
<section class="tl-7-contact">
    <div class="container">
        <div class="row gy-4 gy-md-5 justify-content-between align-items-center">
            <div class="col-lg-6">
                <h2 class="tl-8-section-title">Application Form</h2>
                <form method="post" action="application.php" class="tl-7-contact-form">
                    <div class="row g-3 g-md-4">
                        <div class="col-6 col-xxs-12">
                            <input type="text" name="stud-name" placeholder="Your Name" required>
                        </div>

                        <div class="col-6 col-xxs-12">
                            <input type="text" name="stud-father-name" placeholder="Father's Name" required>
                        </div>

                        <div class="col-6 col-xxs-12">
                            <input type="text" name="stud-interested-class" placeholder="Interested Class" required>
                        </div>

                        <div class="col-6 col-xxs-12">
                            <input type="text" name="stud-address" placeholder="Address" required></input>
                        </div>

                        <div class="col-6 col-xxs-12">
                            <input type="number" name="stud-contact" placeholder="Contact" required>
                        </div>

                        <div class="col-6 col-xxs-12">
                            <input type="email" name="stud-gmail" placeholder="Gmail" required>
                        </div>

                        <div class="col-12">
                            <textarea name="stud-comments" placeholder="Comments"></textarea>
                        </div>

                        <div class="col">
                            <button type="submit" class="tl-7-def-btn">Submit Application</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-lg-6">
                     <img src="images/logo/logo_601_601.jpg" alt="LOGO" class="centered-image">
                </div>
        </div>
    </div>
</section>
 <!-- FOOTER SECTION STARTS HERE -->
 <?php include('footer.php') ?>
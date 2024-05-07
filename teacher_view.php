<?php
// Your database connection code here
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "njjmsl";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the search form is submitted
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["search-value"])) {
    $searchField = $_GET["search-field"];
    $searchValue = $_GET["search-value"];

    // Modify the SQL query based on the selected search criteria and value
    $sql = "SELECT * FROM teacher_details WHERE $searchField LIKE '%$searchValue%'";
} else {
    // Default query if the search form is not submitted
    $sql = "SELECT * FROM teacher_details";
    $sql .= " ORDER BY id LIMIT 1";
    
}

// Execute the query and fetch the result
$result = $conn->query($sql);
?>


<?php include('header_admin_dashboard.php') ?>


<style>
    body {
        background-color: #f8f9fa;
    }

    .container {
        max-width: 800px;
        margin: 30px auto;
    }

    .student-card {
        display: flex;
        background-color: #fff;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
        overflow: hidden;
        margin-bottom: 20px;
    }

    .student-details {
        flex: 1;
        padding: 20px;
    }

    .student-photo {
        width: 175px;
        height: 218.75px;
        object-fit: cover;
    }
</style>
<!-- Search form at the top of the page starts -->
<form class="search-form" method="get">
    <label for="search-field">Search by:</label>
    <select id="search-field" name="search-field">
        <option value="id">Registration Number</option>
        <option value="name">Name</option>
        <!-- Add other search criteria as needed -->
    </select>

    <input type="text" name="search-value" placeholder="Enter search value">
    <button type="submit">Search</button>
</form>
<!-- Search form at the top of the page ends -->

<center>
    <h1>Teacher's Data</h1>
</center>

<!-- Display results as a card-based layout -->
<section class="tl-7-contact">
    <div class="container">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
        ?>
                <div class="student-card">
                    <div class="student-details">
                        <p><strong>Teacher's ID:</strong> <?= $row["id"] ?></p>
                        <p><strong>Teacher's Name:</strong> <?= $row["teacher_name"] ?></p>
                        <p><strong>Father's Name:</strong> <?= $row["teachers_fathers_name"] ?></p>
                        <p><strong>Mother's Name:</strong> <?= $row["teachers_mothers_name"] ?></p>
                        <p><strong>DOB:</strong> <?= $row["dob"] ?> <strong>Gender:</strong> <?= $row["gender"] ?> <strong>Blood Group:</strong> <?= $row["blood_group"] ?> <strong>Category:</strong> <?= $row["category"] ?></p>
                        <hr>
                        <p><strong>Mailing Address</strong></p>
                        <p><strong>Address:</strong> <?= $row["mailing_address"] ?> <strong>Mobile Number:</strong> <?= $row["mailing_mobile"] ?></p>
                        <p><strong>Pin Code:</strong> <?= $row["mailing_pin_code"] ?></p>
                        <hr>
                        <p><strong>Permanent Address</strong></p>
                        <p><strong>Address:</strong> <?= $row["permanent_address"] ?> <strong>Email:</strong> <?= $row["email"] ?></p>
                        <p><strong>Pin code:</strong> <?= $row["permanent_pin_code"] ?></p>
                        <hr>
                        <p><strong>Last School Details</strong></p>
                        <p><strong>Qualification:</strong> <?= $row["qualification"] ?> <strong>Experience:</strong> <?= $row["experience"] ?> <strong>Salary:</strong> <?= $row["salary"] ?></p>
                        <p><strong>Subjects Taught:</strong> <?= $row["subject_taught"] ?> <strong>Aadhar Number:</strong> <?= $row["aadhar_number"] ?></p>
                        <p><strong> <strong>Certificates:</strong> ✔️ <strong>Resume:</strong> ✔️ <strong>Aadhar Card:</strong> ✔️ </p>
                        <strong>Document Links:</strong>
                        <a class='document-link' href='uploads/teachers/<?= $row["id"] ?>/<?= $row["id"] ?>_certificates.jpg' target='_blank'>|Certificates|</a>
                        <a class='document-link' href='uploads/teachers/<?= $row["id"] ?>/<?= $row["id"] ?>_resume.jpg' target='_blank'>|Resume|</a>
                        <a class='document-link' href='uploads/teachers/<?= $row["id"] ?>/<?= $row["id"] ?>_aadharcard.jpg' target='_blank'>|Aadhar Card|</a>
                    </div>
                    <img class="student-photo" src="uploads/teachers/<?= $row["id"] ?>/<?= $row["id"] ?>_passportsizephoto.jpg" alt="Student Photo">
                </div>
        <?php
            }
        } else {
            echo "<p>No student data found</p>";
        }
        ?>
  
    </div>
</section>

<?php include('footer_admin_dashboard.php') ?>

$conn->close();
?>

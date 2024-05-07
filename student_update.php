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

// Check if the form for updating details is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update-value"])) {
    $updateField = $_POST["update-field"];
    $updateValue = $_POST["update-value"];
    $studentId = $_POST["student-id"];

    // Update the selected field in the admission_details table
    $updateSql = 'UPDATE admission_details SET ' . $updateField . ' = ? WHERE id = ?';
    $updateStatement = mysqli_prepare($conn, $updateSql);

    mysqli_stmt_bind_param($updateStatement, 'si', $updateValue, $studentId);
    mysqli_stmt_execute($updateStatement);
    mysqli_stmt_close($updateStatement);

    // Log the changes in the changes_log table
    $logSql = 'INSERT INTO changes_log (admission_details_id, changed_field, new_value, change_date, changed_by) VALUES (?, ?, ?, NOW(), NULL)';
    $logStatement = mysqli_prepare($conn, $logSql);
    mysqli_stmt_bind_param($logStatement, 'iss', $studentId, $updateField, $updateValue);
    mysqli_stmt_execute($logStatement);
    mysqli_stmt_close($logStatement);

     // Update the uploaded files if they are included in the update
    //updateFiles($studentId);
    if (isset($_FILES["update-file"]) && $_FILES["update-file"]["error"] == UPLOAD_ERR_OK) {
        $tempName = $_FILES["update-file"]["tmp_name"];
        $fileExtension = pathinfo($_FILES["update-file"]["name"], PATHINFO_EXTENSION);
        $newFileName = $studentId . "_" . $updateField . "." . $fileExtension;  // Use $updateField instead of $dbColumnName
        $targetFolder = "uploads/" . $studentId;
        $filePath = $targetFolder . "/" . $newFileName;
    
        // Move the uploaded file to the target folder
        move_uploaded_file($tempName, $filePath);
    
        // Update the database with the correct file path
        $updateSql = "UPDATE admission_details SET $updateField = '$filePath' WHERE id = $studentId";
        $conn->query($updateSql);
    }

    echo 'Details updated successfully.';
    exit(); // Stop further execution after AJAX request
}
function updateFiles($studentId) {
    // Create a new folder for the student using the student_id as the folder name
    $targetFolder = "uploads/" . $studentId;

    // Ensure the folder exists (it should already exist during an update)
    if (!file_exists($targetFolder)) {
        mkdir($targetFolder);
    }

    // Update the uploaded files with the correct folder path
    $marksheet = updateFile("marksheet", $studentId, $targetFolder, "marksheet");
    $aadharCard = updateFile("aadharCard", $studentId, $targetFolder, "aadhar_card");
    $passportSizePhoto = updateFile("passportSizePhoto", $studentId, $targetFolder, "passport_size_photo");
    $transferCertificate = updateFile("transferCertificate", $studentId, $targetFolder, "transfer_certificate");

    // Update the database with the correct file paths
    $updateSql = "UPDATE admission_details SET 
        marksheet = '$marksheet',
        aadhar_card = '$aadharCard',
        passport_size_photo = '$passportSizePhoto',
        transfer_certificate = '$transferCertificate'
        WHERE id = $studentId";
    $conn->query($updateSql);
}

function updateFile($inputName, $studentId, $targetFolder, $dbColumnName) {
    if ($_FILES[$inputName]["error"] == UPLOAD_ERR_OK) {
        $tempName = $_FILES[$inputName]["tmp_name"];
        $fileExtension = pathinfo($_FILES[$inputName]["name"], PATHINFO_EXTENSION);
        $newFileName = $studentId . "_" . $dbColumnName . "." . $fileExtension;
        $filePath = $targetFolder . "/" . $newFileName;

        // Delete the old file if it exists
        $existingFilePath = getExistingFilePath($studentId, $dbColumnName);
        if (!empty($existingFilePath) && file_exists($existingFilePath)) {
            unlink($existingFilePath);
        }

        // Move the uploaded file to the target folder
        move_uploaded_file($newFileName, $filePath);

        return $filePath;
    } else {
        // If no new file is uploaded, return the existing file path from the database
        return getExistingFilePath($studentId, $dbColumnName);
    }
}

// Helper function to get the existing file path from the database
function getExistingFilePath($studentId, $dbColumnName) {
    global $conn;
    
    $sql = "SELECT $dbColumnName FROM admission_details WHERE id = $studentId";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row[$dbColumnName];
    } else {
        return '';
    }
}
?>

<!-- Your HTML and CSS code here -->

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

    .update-form {
        margin-top: 20px;
    }

    .update-form label {
        display: block;
        margin-bottom: 10px;
    }
   
</style>



<!-- Search form -->
<form class="search-form" method="post" style="text-align: center;">
    <label for="search-field">Search by:</label>
    <select id="search-field" name="search-field">
        <option value="id">Registration Number</option>
        <option value="student_name">Name</option>
        <!-- Add other search criteria as needed -->
    </select>

    <input type="text" name="search-value" placeholder="Enter search value">
    <button type="submit">Search</button>
</form>
<center>
    <h1>Update Student Data</h1>
</center>
<!-- Display results and update form -->
<section class="tl-7-contact">
    <div class="container">
        <?php
        // Check if the search form is submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["search-value"])) {
            $searchField = $_POST["search-field"];
            $searchValue = $_POST["search-value"];
            $sql = "SELECT * FROM admission_details WHERE $searchField LIKE '%$searchValue%'";
            $result = $conn->query($sql);

            // Fetch student details for display
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    // Display each student card
                    displayStudentCard($row);
                }
            } else {
                echo "<p>No student data found</p>";
            }
        } else {
            // Fetch all students for display
            $sql = "SELECT * FROM admission_details";
            $sql .= " ORDER BY id LIMIT 1";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    // Display each student card
                    displayStudentCard($row);
                }
            } else {
                echo "<p>No student data found</p>";
            }
        }

        function displayStudentCard($row)
        {
        ?>
            <div class="student-card">
                <div class="student-details">
                    <p><strong>Student ID:</strong> <?= $row["id"] ?></p>
                    <p><strong>Student's Name:</strong> <?= $row["student_name"] ?></p>
                    <p><strong>Father's Name :</strong> <?= $row["fathers_name"] ?></p>
                    <!-- Add other details as needed -->
                </div>
            

                <!-- Update form -->
                <form class="update-form" id="updateForm<?= $row["id"] ?>">
                    <input type="hidden" name="student-id" value="<?= $row["id"] ?>">
                    <label for="update-field">Select Field to Update:</label>
                    <select id="update-field" name="update-field">
                            <option value="student_name">Student's Name</option>
                            <option value="fathers_name">Father's Name</option>
                            <option value="mothers_name">Mother's Name</option>
                            <option value="dob">Date of Birth</option>
                            <option value="gender">Gender</option>
                            <option value="blood_group">Blood Group</option>
                            <option value="category">Category</option>
                            <option value="mailing_address">Mailing Address</option>
                            <option value="mailing_pin_code">Mailing Pin Code</option>
                            <option value="mailing_mobile">Mailing Mobile</option>
                            <option value="permanent_address">Permanent Address</option>
                            <option value="permanent_pin_code">Permanent Pin Code</option>
                            <option value="permanent_mobile">Permanent Mobile</option>
                            <option value="email">Email</option>
                            <option value="last_school_name">Last School Name</option>
                            <option value="last_school_year">Last School Year</option>
                            <option value="last_school_class_attended">Last School Class Attended</option>
                            <option value="last_school_board">Last School Board</option>
                            <option value="last_school_percentage">Last School Percentage</option>
                            <option value="admission_class">Admission Class</option>
                            <option value="admission_section">Admission Section</option>
                            <option value="aadhar_number">Aadhar Number</option>
                            <option value="marksheet">Marksheet</option>
                            <option value="aadhar_card">Aadhar Card</option>
                            <option value="passport_size_photo">Passport Size Photo</option>
                            <option value="transfer_certificate">Transfer Certificate</option>
                                <!-- Add other fields as needed -->
                    </select>
                    <!-- New file input fields -->
                    <label for="update-file">Upload File:</label>
                    <input type="file" id="update-file" name="update-file">

                    <label for="update-value">New Value:</label>
                    <input type="text" id="update-value" name="update-value"placeholder="Enter new value" required>
                    <button type="button" onclick="updateStudent(<?= $row["id"] ?>)">Update</button>
                </form>
                <!-- Passport size photo -->
            <div class="student-photo">
                <?php
            // Assuming 'photo_path' is the column name for the photo file path
                $photoPath = $row["passport_size_photo"];
                if (!empty($photoPath)) {
                    echo '<img src="' . $photoPath . '" alt="Passport Size Photo">';
                } else {
                    echo '<p>No photo available</p>';
                }
                ?>
            </div>

            </div>
        <?php
        }
        ?>
    </div>
</section>

<?php include('footer_admin_dashboard.php') ?>

<!-- JavaScript for AJAX -->
<script>
    function updateStudent(studentId) {
        var form = document.getElementById("updateForm" + studentId);
        var formData = new FormData(form);

        var xhr = new XMLHttpRequest();
        xhr.open("POST", "student_update.php", true);
        xhr.onload = function () {
            if (xhr.status === 200) {
                alert(xhr.responseText);
            }
        };
        xhr.send(formData);
    }
</script>

<?php
// Close the database connection
$conn->close();
?>

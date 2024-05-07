<?php
// Connect to the database (modify the connection details as needed)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "njjmsl";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process form data and insert into the database (modify as needed)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Personal Details
    $teachersName = $_POST["teachersName"];
    $teachersfathersName = $_POST["teachersfathersName"];
    $teachersmothersName = $_POST["teachersmothersName"];
    $dob = $_POST["dob"];
    $gender = $_POST["gender"];
    $bloodGroup = $_POST["bloodGroup"];
    $category = $_POST["category"];

    // Mailing Address
    $mailingAddress = $_POST["mailingAddress"];
    $pinCodeMailing = $_POST["mailingPinCode"];
    $mobileMailing = $_POST["mailingMobile"];

    // Permanent Address
    $permanentAddress = $_POST["permanentAddress"];
    $pinCodePermanent = $_POST["permanentPinCode"];
    $mobilePermanent = $_POST["permanentMobile"];
    $email = $_POST["email"];

    // Last School Details
    $qualification = $_POST["qualification"];
    $experience = $_POST["experience"];
    $subject_taught = $_POST["subject_taught"];
    $salary = $_POST["salary"];
    $aadharNumber = $_POST["aadharNumber"];
    $currentDate = date("Y-m-d");
    

   

    // Insert into the database (modify the SQL query as needed)
    $sql = "INSERT INTO teacher_details 
        (teacher_name, teachers_fathers_name, teachers_mothers_name, dob, gender, blood_group, category, mailing_address, mailing_pin_code, mailing_mobile, 
        permanent_address, permanent_pin_code, permanent_mobile, email, qualification, experience, subject_taught, salary,aadhar_number, admission_date) 
        VALUES 
        ('$teachersName', '$teachersfathersName', '$teachersmothersName', '$dob', '$gender', '$bloodGroup', '$category', '$mailingAddress', '$pinCodeMailing', 
        '$mobileMailing', '$permanentAddress', '$pinCodePermanent', '$mobilePermanent', '$email', '$qualification', '$experience', '$subject_taught', 
        '$salary','$aadharNumber', '$currentDate')";

    if ($conn->query($sql) === TRUE) {
        // Get the last inserted ID (student_id)
         // Create a new folder for the student using the student_id as the folder name
        $id = $conn->insert_id;
        $targetFolder = "uploads/teachers/" . $id;
        mkdir($targetFolder);

        // Update the uploaded files with the correct folder path
        $certificates = uploadFile("certificates", $id, $targetFolder, "certificates");
        $aadharCard = uploadFile("aadharCard", $id, $targetFolder, "aadharcard");
        $passportSizePhoto = uploadFile("passportSizePhoto", $id, $targetFolder, "passportsizephoto");
        $resume = uploadFile("resume",$id, $targetFolder, "resume");

        // Update the database with the correct file paths
        $updateSql = "UPDATE teacher_details SET 
            certificates = '$certificates',
            aadharcard = '$aadharCard',
            passportsizephoto = '$passportSizePhoto',
            resume = '$resume'
            WHERE id = $id";
        $conn->query($updateSql);

        echo "<script>
            alert('Form submitted successfully!');
            window.location.href = 'add_teachers.php';
        </script>";
    } else {
        echo "Error: " . $conn->error;
    }
}


// Close the database connection
$conn->close();

// Function to upload files and return the file path
function uploadFile($inputName, $id, $folder, $column) {
    global $conn;

    $targetDir = $folder . "/";
    $targetFile = $targetDir . $id . "_" . $column . "." . pathinfo($_FILES[$inputName]["name"], PATHINFO_EXTENSION);
    $uploadOk = 1;

    // Check if file already exists
    if (file_exists($targetFile)) {
        echo "Sorry, file already exists.";
        $uploadOk = 0;
    }

    // ... (existing code)
    // Check file size (limit to 1MB for images and PDFs)
    $fileSize = $_FILES[$inputName]["size"];
    if ($fileSize > 1000000) {
        echo "Sorry, your file is too large (maximum size: 1MB).";
        $uploadOk = 0;
    } elseif ($fileSize < 20000) { // Check if file size is less than 20KB
        echo "Sorry, your file is too small (minimum size: 20KB).";
        $uploadOk = 0;
    }

    // Allow certain file formats (JPG, JPEG, and PNG)
    $allowedFormats = array("jpg", "jpeg", "png");
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
    if (!in_array($imageFileType, $allowedFormats)) {
        echo "Sorry, only JPG, JPEG, and PNG files are allowed.";
        $uploadOk = 0;
}


    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    } else {
        // Move the uploaded file to the target folder with a new name
        $newFilePath = $folder . "/" . $id . "_" . $column . "." . pathinfo($_FILES[$inputName]["name"], PATHINFO_EXTENSION);
        if (move_uploaded_file($_FILES[$inputName]["tmp_name"], $newFilePath)) {
            return $newFilePath;
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}
?>


<?php include('header_admin_dashboard.php') ?>
<section class="tl-7-contact">
        <div class="container">
            <div class="row gy-4 gy-md-5 justify-content-between align-items-center">
                <div class="col-lg-6">
                    <h2 class="tl-8-section-title">Teachers Admission Form</h2>
                    <form id="teacheradmissionForm"  method="post" enctype="multipart/form-data" class="tl-7-contact-form">
                        <div class="row g-3 g-md-4">
                            <!-- Personal Details Section -->
                            <div class="col-6 col-xxs-12">
                                <label for="teachersName">Teacher's Name:</label>
                                <input type="text" name="teachersName" id="teachersName" placeholder="Enter Teacher's Name" required>
                            </div>

                            <div class="col-6 col-xxs-12">
                                <label for="teachersfathersName">Teacher's Father's Name:</label>
                                <input type="text" name="teachersfathersName" id="teachersfathersName" placeholder="Enter Teacher's Father's Name" required>
                            </div>

                            <div class="col-6 col-xxs-12">
                                <label for="teachersmothersName">Teacher's Mother's Name:</label>
                                <input type="text" name="teachersmothersName" id="teachersmothersName" placeholder="Enter Teacher's Mother's Name" required>
                            </div>

                            <div class="col-6 col-xxs-12">
                                <label for="dob">Date of Birth:</label>
                                <input type="date" name="dob" id="dob" required>
                            </div>

                            <div class="col-6 col-xxs-12">
                                <label for="gender">Gender:</label>
                                <select name="gender" id="gender" required>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                    <!-- Add more options as needed -->
                                </select>
                            </div>

                            <div class="col-6 col-xxs-12">
                                <label for="bloodGroup">Blood Group:</label>
                                <input type="text" name="bloodGroup" id="bloodGroup" placeholder="Enter Blood Group" required>
                            </div>

                            <div class="col-6 col-xxs-12">
                                <label for="category">Category:</label>
                                <select name="category" id="category" required>
                                    <option value="general">General</option>
                                    <option value="obc">OBC</option>
                                    <option value="sc">SC</option>
                                    <option value="st">ST</option>
                                    <option value="minor">MINOR</option>
                                    <!-- Add more options as needed -->
                                </select>
                            </div>
                            
                            <div class="col-6 col-xxs-12">
                                <label for="passportSizePhoto">Passport Size Photo (IMG):</label>
                                <input type="file" name="passportSizePhoto" id="passportSizePhoto" accept=".jpg, .jpeg, .png" onchange="previewImage(this, 'imagePreview')">

                             <!-- Image Preview Box 
                                <div id="imagePreviewBox" class="image-preview-box">
                                     <img id="imagePreview" src="#" alt="Image Preview">
                                 </div>-->
                            </div>  
                            <!-- End of Personal Details Section -->

                            <!-- Mailing Address Section -->
                            <div class="col-12">
                                <h3>Mailing Address</h3>
                            </div>

                            <div class="col-6 col-xxs-12">
                                <label for="mailingAddress">Mailing Address:</label>
                                <input type="text" name="mailingAddress" id="mailingAddress" placeholder="Enter Mailing Address" required>
                            </div>

                            <div class="col-6 col-xxs-12">
                                <label for="mailingPinCode">Pin Code:</label>
                                <input type="text" name="mailingPinCode" id="mailingPinCode" placeholder="Enter Pin Code" required>
                            </div>

                            <div class="col-6 col-xxs-12">
                                <label for="mailingMobile">Mobile Number:</label>
                                <input type="tel" name="mailingMobile" id="mailingMobile" placeholder="Enter Mobile Number" required>
                            </div>
                            <!-- End of Mailing Address Section -->
                            <div class="col-6 col-xxs-12">
                                <input type="checkbox" id="sameAsMailing" name="sameAsMailing"> Same as Mailing Address
                            </div>
                            <!-- Permanent Address Section -->
                            <div class="col-12">
                                <h3>Permanent Address</h3>
                            </div>

                            <div class="col-6 col-xxs-12">
                                <label for="permanentAddress">Permanent Address:</label>
                                <input type="text" name="permanentAddress" id="permanentAddress" placeholder="Enter Permanent Address" required>
                            </div>

                            <div class="col-6 col-xxs-12">
                                <label for="permanentPinCode">Pin Code:</label>
                                <input type="text" name="permanentPinCode" id="permanentPinCode" placeholder="Enter Pin Code" required>
                            </div>

                            <div class="col-6 col-xxs-12">
                                <label for="permanentMobile">Mobile Number:</label>
                                <input type="tel" name="permanentMobile" id="permanentMobile" placeholder="Enter Mobile Number" required>
                            </div>
                            <div class="col-6 col-xxs-12">
                                <label for="email">Email:</label>
                                <input type="text" name="email" id="email" placeholder="Enter Email">
                            </div>
                            <!-- End of Permanent Address Section -->

                            <!-- Last School Details Section -->
                            <div class="col-12">
                                <h3>Exprience &  Details</h3>
                            </div>

                            <div class="col-6 col-xxs-12">
                                <label for="qualification">Qualification:</label>
                                <input type="text" name="qualification" id="qualification" placeholder="Enter Qualification" >
                            </div>

                            <div class="col-6 col-xxs-12">
                                <label for="experience">Experience:</label>
                                <input type="text" name="experience" id="experience" placeholder="Enter Year" >
                            </div>

                            <div class="col-6 col-xxs-12">
                                <label for="subject_taught">Subjects taught:</label>
                                <input type="text" name="subject_taught" id="subject_taught" placeholder="Enter Subjects Taught" >
                            </div>

                            <div class="col-6 col-xxs-12">
                                <label for="salary">Salary</label>
                                <input type="text" name="salary" id="salary" placeholder="Enter Salary" >
                            </div>

                            <div class="col-6 col-xxs-12">
                                <label for="aadharNumber">Aadhar Number:</label>
                                <input type="text" name="aadharNumber" id="aadharNumber" placeholder="Enter Aadhar Number" required>
                            </div>


                            <div class="col-6 col-xxs-12">
                                <label for="certificates">Certificates (PDF, IMG):</label>
                                <input type="file" name="certificates" id="certificates" accept=".pdf, .jpg, .jpeg, .png" >
                            </div>

                            <div class="col-6 col-xxs-12">
                                <label for="aadharCard">Aadhar Card (PDF, IMG):</label>
                                <input type="file" name="aadharCard" id="aadharCard" accept=".pdf, .jpg, .jpeg, .png" required>
                            </div>
                            <div class="col-6 col-xxs-12">
                                <label for="resume">Resume (PDF, IMG):</label>
                                <input type="file" name="resume" id="resume" accept=".pdf, .jpg, .jpeg, .png" >
                            </div>
                            <!-- End of Last School Details Section -->

                            <div class="col-12">
                                <button type="button" class="tl-7-def-btn" onclick="confirmAndSubmit()">Submit</button>
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

    <!-- JavaScript for form submission confirmation -->
    <script>
        document.getElementById('sameAsMailing').addEventListener('change', function () {
            var permanentAddressFields = document.querySelectorAll('#teacheradmissionForm [name^="permanent"]');
            var mailingAddressFields = document.querySelectorAll('#teacheradmissionForm [name^="mailing"]');
        
            // Copy values for address, pin code, and mobile number
            permanentAddressFields.forEach(function (field, index) {
                if (index === 0) {
                 // Copy address
                    field.value = this.checked ? mailingAddressFields[index].value : '';
                } else if (index === 1 || index === 2) {
                // Copy pin code and mobile number
                    field.value = this.checked ? mailingAddressFields[index].value : '';
                }
            }, this);
        });

        function confirmAndSubmit() {
            if (confirm('Confirm to submit the form?')) {
                document.getElementById('teacheradmissionForm').submit();
            }
        }
    </script>
<?php include('footer_admin_dashboard.php') ?>
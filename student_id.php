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

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["generate-id-card"])) {
    // Get selected student IDs or range
    $startId = $_POST["start-id"];
    $endId = $_POST["end-id"];

    // Fetch student details for the selected range
    $sql = "SELECT * FROM admission_details WHERE id BETWEEN $startId AND $endId";
    $result = $conn->query($sql);

    // Generate PDF for ID cards
    require('fpdf/fpdf.php'); // Assuming you have FPDF library

    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 16);

    $x = 10; // X-coordinate of the top-left corner of the box
    $y = 10; // Y-coordinate of the top-left corner of the box
    $counter = 0; // Counter to keep track of ID cards generated

    while ($row = $result->fetch_assoc()) {

        // Increment counter
        $counter++;
        // Set font for the entire document
        $pdf->SetFont('Arial', '', 10);
    
        // Define coordinates and dimensions of the rectangle box

        $width = 55; // Width of the box
        $height = 85; // Height of the box
    
        // Draw the rectangle box
        $pdf->Rect($x, $y, $width, $height);
        $photoPath = 'images/logo/background2_id.jpg'; // Replace 'path_to_student_photo.jpg' with the actual path to the photo
        $pdf->Image($photoPath, $x + 1  , $y+0.5 ,53,84); 
    
        // Print the school details
        $photoPath = 'images/logo/logo2_id.jpg'; // Replace 'path_to_student_photo.jpg' with the actual path to the photo
        $pdf->Image($photoPath, $x + 1  , $y+0.5 ,53,20); 

        $photoPath = $row["passport_size_photo"]; // Path from the database
        if (!empty($photoPath) && file_exists($photoPath)) {
            $pdf->Image($photoPath, $x + 17.5, $y + 20.5, 20, 25); // Adjust coordinates and dimensions as needed
        } else {
            // If photo path is not available or photo file does not exist, display a placeholder or alternative image
            $pdf->Image('placeholder_image.jpg', $x + 17.5, $y + 20.5, 20, 25); // Adjust coordinates and dimensions as needed
        }
    


            
        // Set font for the text inside the box
        $pdf->SetFont('Arial', 'B', 9);
    
        // Set coordinates for text inside the box
        $textX = $x+1 ; // X-coordinate of the text
        $textY = $y+5; // Y-coordinate of the text
        // Print the student details
        $pdf->SetFont('Arial', '', 10);
        $pdf->Text($textX, $textY + 45, 'Student ID: ' . $row["id"]);
        $pdf->Text($textX, $textY + 50, 'Name: ' . $row["student_name"]);
        $pdf->Text($textX, $textY + 55, 'F. Name: ' . $row["fathers_name"]);
        $pdf->Text($textX, $textY + 60, 'Class: ' . $row["admission_class"] . '   Roll No: ' . $row["permanent_pin_code"]);
        $pdf->Text($textX, $textY + 65, 'Contact: ' . $row["permanent_mobile"]);
        
        $photoPath = 'images/logo/princ_sig.jpg'; // Replace 'path_to_student_photo.jpg' with the actual path to the photo
        $pdf->Image($photoPath, $x + 39  , $y+79 ,15,5); 
      
    
        // Move to the next ID card position
        $x += 60; // Adjust according to the width of the box and spacing between ID cards

        // Check if the next ID card will go beyond the page width
        if ($x + $width > $pdf->GetPageWidth() - 10) {
            // Move to the next row
            $x = 10; // Reset X-coordinate
            $y += 95; // Adjust according to the height of the box and spacing between ID cards

            // Check if the next ID card will go beyond the page height
            if ($y + $height > $pdf->GetPageHeight() - 10) {
                // Add a new page
                $pdf->AddPage();
                $x = 10; // Reset X-coordinate for the new page
                $y = 10; // Reset Y-coordinate for the new page
            }
        }
    }
    // Save or output the PDF file
    $pdfFileName = 'id_cards.pdf';
    $pdf->Output('D', $pdfFileName); // 'D' for force download

    echo "ID cards generated successfully. <a href='$pdfFileName' target='_blank'>Download PDF</a>";
}

// Close the database connection
$conn->close();
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

    .generate-form {
        margin-top: 20px;
    }

    .generate-form label {
        display: block;
        margin-bottom: 10px;
    }
</style>

<center>
    <h1>Generate ID Cards</h1>
</center>

<!-- ID Card Generation form -->
<form class="generate-form" method="post">
    <label for="start-id">Start Student ID:</label>
    <input type="text" id="start-id" name="start-id" required>

    <label for="end-id">End Student ID:</label>
    <input type="text" id="end-id" name="end-id" required>

    <button type="submit" name="generate-id-card">Generate ID Cards</button>
</form>

<?php include('footer_admin_dashboard.php') ?>

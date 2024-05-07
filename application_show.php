<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "njjmsl";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}



// Function to move an entry to application_accept table
function acceptApplication($conn, $regNo, $name, $fathername, $interestedClass, $address, $contact, $gmail, $comments, $appDate) {
    $acceptDate = date("Y-m-d"); // Current date as the accept date
    $sql = "INSERT INTO application_accept (reg_no, name, fathername, interestedclass, address, contact, gmail, comments, app_date, accept_date) 
            VALUES ('$regNo', '$name', '$fathername', '$interestedClass', '$address', '$contact', '$gmail', '$comments', '$appDate', '$acceptDate')";

    if ($conn->query($sql) === TRUE) {
         // Forward data to admission.php
         header("Location: admission.php?studentName=" . urlencode($name) . "&fatherName=" . urlencode($fathername) . "&mailingAddress=" . urlencode($address) . "&mailingContact=" . urlencode($contact) . "&email=" . urlencode($gmail));

         $deleteSql = "DELETE FROM application WHERE reg_no = $regNo";
         $conn->query($deleteSql);
         exit();
       
        //return true;
    } else {
        return false;
    }
     // Remove entry from application table

}
// Function to move an entry to application_reject table
function rejectApplication($conn, $regNo, $name, $fathername, $interestedClass, $address, $contact, $gmail, $comments, $appDate) {
    $rejectDate = date("Y-m-d"); // Current date as the reject date
    $sql = "INSERT INTO application_reject (reg_no, name, fathername, interestedclass, address, contact, gmail, comments, app_date, reject_date) 
            VALUES ('$regNo', '$name', '$fathername', '$interestedClass', '$address', '$contact', '$gmail', '$comments', '$appDate', '$rejectDate')";

    if ($conn->query($sql) === TRUE) {
        // Remove entry from application table
        $deleteSql = "DELETE FROM application WHERE reg_no = $regNo";
        $conn->query($deleteSql);
        return true;
    } else {
        return false;
    }
}

// Check if accept or reject button is clicked
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["accept"])) {
        $regNo = $_POST["reg_no"];
        $name = $_POST["name"];
        $fathername = $_POST["fathername"];
        $interestedClass = $_POST["interestedclass"];
        $address = $_POST["address"];
        $contact = $_POST["contact"];
        $gmail = $_POST["gmail"];
        $comments = $_POST["comments"];
        $appDate = $_POST["app_date"];

        acceptApplication($conn, $regNo, $name, $fathername, $interestedClass, $address, $contact, $gmail, $comments, $appDate);
    } elseif (isset($_POST["reject"])) {
        $regNo = $_POST["reg_no"];
        $name = $_POST["name"];
        $fathername = $_POST["fathername"];
        $interestedClass = $_POST["interestedclass"];
        $address = $_POST["address"];
        $contact = $_POST["contact"];
        $gmail = $_POST["gmail"];
        $comments = $_POST["comments"];
        $appDate = $_POST["app_date"];

        rejectApplication($conn, $regNo, $name, $fathername, $interestedClass, $address, $contact, $gmail, $comments, $appDate);
    }
}

// Fetch data from application table
$sql = "SELECT * FROM application";
$result = $conn->query($sql);
?>


<?php
// Your existing database connection code here

// Check if the search form is submitted
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["search-value"])) {
    $searchField = $_GET["search-field"];
    $searchValue = $_GET["search-value"];

    // Modify the SQL query based on the selected search criteria and value
    $sql = "SELECT * FROM application WHERE $searchField LIKE '%$searchValue%'";
} else {
    // Default query if the search form is not submitted
    $sql = "SELECT * FROM application";
}

// Execute the query for application table and fetch the result
$result_application = $conn->query($sql);

// Execute the query for application_accept table and fetch the result
$result_accept = $conn->query("SELECT * FROM application_accept");

// Execute the query for application_reject table and fetch the result
$result_reject = $conn->query("SELECT * FROM application_reject");
?>






<?php include('header_admin_dashboard.php') ?>
<center>
    <h1>Pending Applications-</h1>
</center>
<!--  search form at the top of the page stsrts -->
<form class="search-form" method="get">
    <label for="search-field">Search by:</label>
    <select id="search-field" name="search-field">
        <option value="reg_no">Registration Number</option>
        <option value="name">Name</option>
        <option value="fathername">Father's Name</option>
        <option value="interestedclass">Interested Class</option>
        <option value="address">Address</option>
        <option value="contact">Contact</option>
        <option value="gmail">Gmail</option>
        <option value="comments">Comments</option>
        <option value="app_date">Application Date</option>
    </select>

    <input type="text" name="search-value" placeholder="Enter search value">
    <button type="submit">Search</button>
</form>
<!--  table code starts -->
<table>
    <tr>
        <th>Reg No</th>
        <th>Name</th>
        <th>Father Name</th>
        <th>Interested Class</th>
        <th>Address</th>
        <th>Contact</th>
        <th>Gmail</th>
        <th>Comments</th>
        <th>Application Date</th>
        <th>Action</th>
    </tr>

    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["reg_no"] . "</td>";
            echo "<td>" . $row["name"] . "</td>";
            echo "<td>" . $row["fathername"] . "</td>";
            echo "<td>" . $row["interestedclass"] . "</td>";
            echo "<td>" . $row["address"] . "</td>";
            echo "<td>" . $row["contact"] . "</td>";
            echo "<td>" . $row["gmail"] . "</td>";
            echo "<td>" . $row["comments"] . "</td>";
            echo "<td>" . $row["app_date"] . "</td>";
            echo "<td class='action-buttons'>";
            echo "<form method='post'>";
            foreach ($row as $key => $value) {
                echo "<input type='hidden' name='$key' value='$value'>";
            }
            echo "<button class='accept-button' type='submit' name='accept'>Accept</button>";
            echo "<button class='reject-button' type='submit' name='reject'>Reject</button>";
            echo "</form>";
            echo "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='10'>No applications found</td></tr>";
    }
    ?>

</table>
<!-- Display results from application_accept table -->
<center>
    <h1>Accepected Applications-</h1>
</center>


<?php
// Execute the query for application_accept table and fetch the result
$result_accept = $conn->query("SELECT * FROM application_accept");

// Example code to loop through and display entries
if ($result_accept->num_rows > 0) {
    echo "<table border='1'>";
    echo "<tr><th>Registration Number</th><th>Name</th><th>Father's Name</th><th>Interested Class</th><th>Address</th><th>Contact</th><th>Gmail</th><th>Comments</th><th>Application Date</th><th>Accept Date</th></tr>";
    while ($row = $result_accept->fetch_assoc()) {
        echo "<tr>";
        echo "<td>{$row['reg_no']}</td>";
        echo "<td>{$row['name']}</td>";
        echo "<td>{$row['fathername']}</td>";
        echo "<td>{$row['interestedclass']}</td>";
        echo "<td>{$row['address']}</td>";
        echo "<td>{$row['contact']}</td>";
        echo "<td>{$row['gmail']}</td>";
        echo "<td>{$row['comments']}</td>";
        echo "<td>{$row['app_date']}</td>";
        echo "<td>{$row['accept_date']}</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>No accepted applications found.</p>";
}
?>

<!-- Display results from application_reject table -->
<center>
    <h1>Rejected Applications-</h1>
</center>
<?php
// Execute the query for application_reject table and fetch the result
$result_reject = $conn->query("SELECT * FROM application_reject");

// Example code to loop through and display entries
if ($result_reject->num_rows > 0) {
    echo "<table border='1'>";
    echo "<tr><th>Registration Number</th><th>Name</th><th>Father's Name</th><th>Interested Class</th><th>Address</th><th>Contact</th><th>Gmail</th><th>Comments</th><th>Application Date</th><th>Reject Date</th></tr>";
    while ($row = $result_reject->fetch_assoc()) {
        echo "<tr>";
        echo "<td>{$row['reg_no']}</td>";
        echo "<td>{$row['name']}</td>";
        echo "<td>{$row['fathername']}</td>";
        echo "<td>{$row['interestedclass']}</td>";
        echo "<td>{$row['address']}</td>";
        echo "<td>{$row['contact']}</td>";
        echo "<td>{$row['gmail']}</td>";
        echo "<td>{$row['comments']}</td>";
        echo "<td>{$row['app_date']}</td>";
        echo "<td>{$row['reject_date']}</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>No rejected applications found.</p>";
}
?>

<?php include('footer_admin_dashboard.php') ?>
<?php
$conn->close();
?>

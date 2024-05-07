<?php
// Your database connection code here
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "njjmsl";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Add a new class
if (isset($_POST['add_class'])) {
    $class_name = $_POST['class_name'];
    
    // Create a new table for the class
    $sql_create_table = "CREATE TABLE $class_name (
        subject_id INT AUTO_INCREMENT PRIMARY KEY,
        subject_name VARCHAR(50) NOT NULL,
        subject_teacher VARCHAR(50) NOT NULL
    )";
    
    if ($conn->query($sql_create_table) === TRUE) {
        // Insert the class name into the classes table
        $sql_insert_class = "INSERT INTO classes (class_name) VALUES ('$class_name')";
        if ($conn->query($sql_insert_class) === TRUE) {
            echo "New class added successfully";
        } else {
            echo "Error adding class: " . $conn->error;
        }
    } else {
        echo "Error creating table for class: " . $conn->error;
    }
}

// Add a new subject to a class
if (isset($_POST['add_subject'])) {
    $class_name = $_POST['class_name'];
    $subject_name = $_POST['subject_name'];
    $subject_teacher = $_POST['subject_teacher'];
    $sql = "INSERT INTO $class_name (subject_name, subject_teacher) VALUES ('$subject_name', '$subject_teacher')";
    if ($conn->query($sql) === TRUE) {
        echo "New subject added successfully";
    } else {
        echo "Error adding subject: " . $conn->error;
    }
}

// Edit the name or teacher for a subject
if (isset($_POST['edit_subject'])) {
    $class_name = $_POST['class_name'];
    $subject_id = $_POST['subject_id'];
    $new_name = $_POST['new_name'];
    $new_teacher = $_POST['new_teacher'];
    $sql = "UPDATE $class_name SET subject_name='$new_name', subject_teacher='$new_teacher' WHERE subject_id=$subject_id";
    if ($conn->query($sql) === TRUE) {
        echo "Subject updated successfully";
    } else {
        echo "Error updating subject: " . $conn->error;
    }
}

// Delete class and its associated table
if(isset($_POST['delete_class'])) {
    $class_name = $_POST['class_name'];
    // Delete class record from classes table
    $sql_delete_class = "DELETE FROM classes WHERE class_name='$class_name'";
    if($conn->query($sql_delete_class) === TRUE) {
        // Drop the table associated with this class
        $sql_drop_table = "DROP TABLE $class_name";
        if($conn->query($sql_drop_table) === TRUE) {
            echo "<script>alert('Class deleted successfully.');</script>";
            echo "<meta http-equiv='refresh' content='0'>";
        } else {
            echo "Error dropping table: " . $conn->error;
        }
    } else {
        echo "Error deleting class: " . $conn->error;
    }
}

?>
<?php include('header_admin_dashboard.php') ?>
    <style>
       form {
    margin-bottom: 20px;
}

form label {
    display: block;
    margin-bottom: 5px;
}

form input[type="text"],
form select {
    width: 10%; /* Adjusted width to fit the form */
    padding: 8px;
    margin-bottom: 10px;
    border: 1px solid #ccc;
    border-radius: 3px;
    box-sizing: border-box;
}

form button[type="submit"] {
    background-color: #4CAF50;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 3px;
    cursor: pointer;
}

form button[type="submit"]:hover {
    background-color: #45a049;
}

/* Additional styles for the Add Class form */
h2 {
    margin-top: 30px;
}

/* Additional styles for the Add Subject form */
h2.subject-heading {
    margin-top: 50px;
}

table {
    width: 100%;
    border-collapse: collapse;
}

th, td {
    padding: 10px; /* Adjusted padding for better spacing */
    border: 1px solid #ddd;
    text-align: left;
}

th {
    background-color: #f2f2f2;
}

/* Action buttons */
.action-buttons {
    display: flex;
    justify-content: space-between;
}

.action-buttons button {
    padding: 8px 16px;
    margin: 4px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    background-color: #4CAF50;
    color: white;
    transition: background-color 0.3s;
}

.action-buttons button:hover {
    background-color: #45a049;
}
    </style>


<h2>Add Class</h2>
<form method="post">
    <label for="class_name">Class Name:</label>
    <input type="text" name="class_name" required>
    <button type="submit" name="add_class">Add Class</button>
</form>

<h2>Add Subject</h2>
<form method="post">
    <label for="class_name">Class:</label>
    <select name="class_name" required>
        <?php
        // Fetch existing classes from the classes table
        $sql_classes = "SELECT class_name FROM classes";
        $result_classes = $conn->query($sql_classes);
        if ($result_classes->num_rows > 0) {
            while ($row = $result_classes->fetch_assoc()) {
                echo "<option value='" . $row['class_name'] . "'>" . $row['class_name'] . "</option>";
            }
        } else {
            echo "<option value='' disabled>No classes found</option>";
        }
        ?>
    </select>
    <label for="subject_name">Subject Name:</label>
    <input type="text" name="subject_name" required>
    <label for="subject_teacher">Subject Teacher:</label>
    <select name="subject_teacher" required>
        <?php
        // Fetch existing teachers from the teacher_details table
        $sql_teachers = "SELECT teacher_name FROM teacher_details";
        $result_teachers = $conn->query($sql_teachers);
        if ($result_teachers->num_rows > 0) {
            while ($row = $result_teachers->fetch_assoc()) {
                echo "<option value='" . $row['teacher_name'] . "'>" . $row['teacher_name'] . "</option>";
            }
        } else {
            echo "<option value='' disabled>No teachers found</option>";
        }
        ?>
    </select>
    <button type="submit" name="add_subject">Add Subject</button>
</form>
<h2>View Classes and Subjects</h2>
<table border="1">
    <thead>
        <tr>
            <th>Class Name</th>
            <th>Subject Name</th>
            <th>Subject Teacher</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // Fetch classes and their subjects
        $sql_classes = "SELECT class_name FROM classes";
        $result_classes = $conn->query($sql_classes);
        if ($result_classes->num_rows > 0) {
            while ($row = $result_classes->fetch_assoc()) {
                echo "<tr>";
                echo "<td rowspan='2'>" . $row['class_name'] . "</td>";
                // Fetch subjects and their teachers for this class
                $class_name = $row['class_name'];
                $sql_subjects = "SELECT subject_id, subject_name, subject_teacher FROM $class_name";
                $result_subjects = $conn->query($sql_subjects);
                if ($result_subjects->num_rows > 0) {
                    while ($subject_row = $result_subjects->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $subject_row['subject_name'] . "</td>";
                        echo "<td>" . $subject_row['subject_teacher'] . "</td>";
                        echo "<td>";
                        echo "<form method='post'>";
                        echo "<input type='hidden' name='class_name' value='" . $class_name . "'>";
                        echo "<input type='hidden' name='subject_id' value='" . $subject_row['subject_id'] . "'>";
                        echo "<input type='text' name='new_name' value='" . $subject_row['subject_name'] . "' required>";
                        echo "<select name='new_teacher' required>";
                        // Fetch existing teachers from the teacher_details table
                        $sql_teachers = "SELECT teacher_name FROM teacher_details";
                        $result_teachers = $conn->query($sql_teachers);
                        if ($result_teachers->num_rows > 0) {
                            while ($teacher_row = $result_teachers->fetch_assoc()) {
                                if ($subject_row['subject_teacher'] == $teacher_row['teacher_name']) {
                                    echo "<option value='" . $teacher_row['teacher_name'] . "' selected>" . $teacher_row['teacher_name'] . "</option>";
                                } else {
                                    echo "<option value='" . $teacher_row['teacher_name'] . "'>" . $teacher_row['teacher_name'] . "</option>";
                                }
                            }
                        } else {
                            echo "<option value='' disabled>No teachers found</option>";
                        }
                        echo "</select>";
                        echo "<button type='submit' name='edit_subject'>Save</button>";
                        echo "</form>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No subjects found</td></tr>";
                }
                echo "<td><form method='post'><input type='hidden' name='class_name' value='" . $class_name . "'><button type='submit' name='delete_class'>Delete</button></form></td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='4'>No classes found</td></tr>";
        }
        ?>
    </tbody>
</table>

<?php include('footer_admin_dashboard.php') ?>

<?php
$conn->close();
?>

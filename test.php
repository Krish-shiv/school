<?php
include('header_admin_dashboard.php');

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

// Fetching current 4 events details
$query_events = "SELECT * FROM upcoming_events LIMIT 4";
$result_events = mysqli_query($conn, $query_events);

// Fetching current 3 news details
$query_news = "SELECT * FROM latest_news LIMIT 3";
$result_news = mysqli_query($conn, $query_news);

// Check if event ID is provided
if (isset($_GET['edit_event'])) {
    $event_id = $_GET['edit_event'];

    // Fetch event details from the database
    $query_event = "SELECT * FROM upcoming_events WHERE id = $event_id";
    $result_event = mysqli_query($conn, $query_event);
    $event = mysqli_fetch_assoc($result_event);

    if ($event) {
        // Display a form to update event details
        ?>
        <h2>Edit Event</h2>
        <form action="event_news.php" method="post">
            <input type="hidden" name="event_id" value="<?php echo $event['id']; ?>">
            <label for="event_date">Date:</label>
            <input type="date" id="event_date" name="event_date" value="<?php echo $event['event_date']; ?>"><br><br>
            <label for="event_title">Title:</label>
            <input type="text" id="event_title" name="event_title" value="<?php echo $event['event_title']; ?>"><br><br>
            <input type="submit" name="update_event" value="Update Event">
        </form>
        <?php
    } else {
        echo "Event not found!";
    }
} elseif (isset($_GET['edit_news'])) {
    $news_id = $_GET['edit_news'];

    // Fetch news details from the database
    $query_news = "SELECT * FROM latest_news WHERE id = $news_id";
    $result_news = mysqli_query($conn, $query_news);
    $news = mysqli_fetch_assoc($result_news);

    if ($news) {
        // Display a form to update news details
        ?>
        <h2>Edit News</h2>
        <form action="event_news.php" method="post">
            <input type="hidden" name="news_id" value="<?php echo $news['id']; ?>">
            <label for="news_date">Date:</label>
            <input type="date" id="news_date" name="news_date" value="<?php echo $news['news_date']; ?>"><br><br>
            <label for="news_title">Title:</label>
            <input type="text" id="news_title" name="news_title" value="<?php echo $news['news_title']; ?>"><br><br>
            <input type="submit" name="update_news" value="Update News">
        </form>
        <?php
    } else {
        echo "News not found!";
    }
}

// Process event update
if (isset($_POST['update_event'])) {
    $event_id = $_POST['event_id'];
    $event_date = $_POST['event_date'];
    $event_title = $_POST['event_title'];

    // Update event details in the database
    $query_update_event = "UPDATE upcoming_events SET event_date='$event_date', event_title='$event_title' WHERE id=$event_id";

    if (mysqli_query($conn, $query_update_event)) {
        echo "Event updated successfully!";
    } else {
        echo "Error updating event: " . mysqli_error($conn);
    }
}

// Process news update
if (isset($_POST['update_news'])) {
    $news_id = $_POST['news_id'];
    $news_date = $_POST['news_date'];
    $news_title = $_POST['news_title'];

    // Update news details in the database
    $query_update_news = "UPDATE latest_news SET news_date='$news_date', news_title='$news_title' WHERE id=$news_id";

    if (mysqli_query($conn, $query_update_news)) {
        echo "News updated successfully!";
    } else {
        echo "Error updating news: " . mysqli_error($conn);
    }
}

// Close database connection
$conn->close();
?>

<!-- Display section for current events -->
<section class="current-events">
    <h2>Current Events</h2>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Title</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            while ($row = mysqli_fetch_assoc($result_events)) {
                echo "<tr>";
                echo "<td>{$row['event_date']}</td>";
                echo "<td>{$row['event_title']}</td>";
                echo "<td><a href='event_news.php?edit_event={$row['id']}'>Edit</a></td>"; // Link to edit event
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</section>

<!-- Display section for current news -->
<section class="current-news">
    <h2>Current News</h2>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Title</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            while ($row = mysqli_fetch_assoc($result_news)) {
                echo "<tr>";
                echo "<td>{$row['news_date']}</td>";
                echo "<td>{$row['news_title']}</td>";
                echo "<td><a href='event_news.php?edit_news={$row['id']}'>Edit</a></td>"; // Link to edit news
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</section>

<?php include('footer_admin_dashboard.php') ?>

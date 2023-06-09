<?php
session_start();

// Connect to the database (replace with your database credentials)
$servername = "localhost";
$username = "root";
$password = "";
$database = "timetest";
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the timetable ID is provided
if (isset($_POST['timetable_id'])) {
    $timetableID = $_POST['timetable_id'];
    
    // Construct the delete query
    $deleteSql = "DELETE FROM timetable WHERE id = $timetableID";
    
    // Execute the delete query
    if ($conn->query($deleteSql) === TRUE) {
        echo "Timetable entry deleted successfully.";
        header("Location: homepage.php");
    } else {
        echo "Error deleting timetable entry: " . $conn->error;
    }
} else {
    echo "Timetable ID not provided.";
}

// Close the database connection
$conn->close();
?>

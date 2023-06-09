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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the form is submitted and the selected values are present
    if (isset($_POST["classroom"]) && isset($_POST["teacher"]) && isset($_POST["subject"])) {
        $classroom = $_POST["classroom"];
        $teacher = $_POST["teacher"];
        $subject = $_POST["subject"];

        // Delete the timetable entry from the database
        $userID = $_SESSION['user_id'];
        $deleteSql = "DELETE FROM timetable WHERE user_id = $userID AND classroom_id = $classroom AND teacher_id = $teacher AND subject_id = $subject";
        if ($conn->query($deleteSql) === TRUE) {
            echo "Timetable entry deleted successfully.";
        } else {
            echo "Error deleting timetable entry: " . $conn->error;
        }
    }
}

// Close the database connection
$conn->close();
?>

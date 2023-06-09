<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "Selected Classroom ID: " . $_POST['classroom'];

// Retrieve form data
$classroomID = trim($_POST['classroom']);
$teacher = $_POST['teacher'];
$subject = $_POST['subject'];
$startTime = $_POST['startTime'];
$endTime = $_POST['endTime'];
$day = $_POST['day'];

// Connect to the database
$servername = "localhost";
$username = "root";
$password = "";
$database = "timetest";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the selected classroom exists in the classrooms table
$classroomSql = "SELECT * FROM classrooms WHERE id = '$classroomID'";
$classroomResult = $conn->query($classroomSql);

if ($classroomResult->num_rows > 0) {
    // Classroom exists, check if the teacher ID is not empty
    if (!empty($teacher)) {
        // Teacher ID is not empty, check if it exists in the teachers table
        $teacherSql = "SELECT * FROM teachers WHERE id = '$teacher'";
        $teacherResult = $conn->query($teacherSql);

        if ($teacherResult->num_rows > 0) {
            // Teacher ID exists, check if the subject ID is not empty
            if (!empty($subject)) {
                // Subject ID is not empty, check if it exists in the subjects table
                $subjectSql = "SELECT * FROM subjects WHERE id = '$subject'";
                $subjectResult = $conn->query($subjectSql);

                if ($subjectResult->num_rows > 0) {
                    // Subject ID exists

                    // Handle option removal
                    if (isset($_POST['removedOptions'])) {
                        $removedOptions = $_POST['removedOptions'];

                        // Loop through the removed options and delete the corresponding data
                        foreach ($removedOptions as $removedOption) {
                            $optionParts = explode('-', $removedOption);
                            $optionType = $optionParts[0]; // 'day', 'startTime', or 'endTime'
                            $optionValue = $optionParts[1]; // The value of the removed option

                            // Delete the corresponding data from the database based on the option type, value, and user
                            $userID = $_SESSION['user_id']; // Get the user ID from the session
                            switch ($optionType) {
                                case 'day':
                                    $deleteSql = "DELETE FROM timetable WHERE classroom_id = '$classroomID' AND day = '$optionValue' AND user_id = '$userID'";
                                    break;
                                case 'startTime':
                                    $deleteSql = "DELETE FROM timetable WHERE classroom_id = '$classroomID' AND start_time = '$optionValue' AND user_id = '$userID'";
                                    break;
                                case 'endTime':
                                    $deleteSql = "DELETE FROM timetable WHERE classroom_id = '$classroomID' AND end_time = '$optionValue' AND user_id = '$userID'";
                                    break;
                                default:
                                    continue; // Skip invalid option types
                            }

                            if ($conn->query($deleteSql) === TRUE) {
                                echo "Deleted data for removed option: $removedOption";
                            } else {
                                echo "Error deleting data for removed option: $removedOption";
                            }
                        }
                    }

                    // Insert the timetable entry if no options were removed
                    if (!isset($_POST['removedOptions']) || empty($_POST['removedOptions'])) {
                        $userID = $_SESSION['user_id'];
                        $insertSql = "INSERT INTO timetable (classroom_id, teacher_id, subject_id, start_time, end_time, day, user_id) 
                            VALUES ('$classroomID', '$teacher', '$subject', '$startTime', '$endTime', '$day', '$userID')
                            ON DUPLICATE KEY UPDATE classroom_id = VALUES(classroom_id), teacher_id = VALUES(teacher_id),
                            subject_id = VALUES(subject_id), start_time = VALUES(start_time), end_time = VALUES(end_time),
                            day = VALUES(day), user_id = VALUES(user_id)";

                        if ($conn->query($insertSql) === TRUE) {
                            echo "Timetable created successfully";

                            // Fetch the timetable entries for the logged-in user
                            $userID = $_SESSION['user_id']; // Get the user ID from the session
                            $timetableSql = "SELECT t.start_time, t.end_time, t.day, s.name AS subject, r.name AS room, c.name AS teacher
                                FROM timetable t
                                JOIN subjects s ON t.subject_id = s.id
                                JOIN classrooms r ON t.classroom_id = r.id
                                JOIN teachers c ON t.teacher_id = c.id
                                JOIN users u ON t.user_id = u.id
                                WHERE t.classroom_id = '$classroomID' AND u.id = '$userID'";

                            $timetableResult = $conn->query($timetableSql);

                            if ($timetableResult->num_rows > 0) {
                                echo "<br><br>Timetable for Classroom ID " . $classroomID . " and User ID " . $userID . ":<br>";

                                // Display the fetched timetable entries
                                while ($row = $timetableResult->fetch_assoc()) {
                                    echo "Start Time: " . $row['start_time'] . "<br>";
                                    echo "End Time: " . $row['end_time'] . "<br>";
                                    echo "Day: " . $row['day'] . "<br>";
                                    echo "Subject: " . $row['subject'] . "<br>";
                                    echo "Room: " . $row['room'] . "<br>";
                                    echo "Teacher: " . $row['teacher'] . "<br><br>";
                                }
                            } else {
                                echo "No timetable entries found for Classroom ID " . $classroomID . " and User ID " . $userID;
                            }

                            header("Location: homepage.php");
                            exit();
                        } else {
                            echo "Error creating timetable: " . $conn->error;
                        }
                    }
                } else {
                    echo "Selected subject does not exist";
                    echo "<br>Subject ID: " . $subject;
                    echo "<br>Subject SQL: " . $subjectSql;
                }
            } else {
                echo "Subject ID is empty";
                echo "<br>Subject: " . $subject;
            }
        } else {
            echo "Selected teacher does not exist";
            echo "<br>Teacher ID: " . $teacher;
            echo "<br>Teacher SQL: " . $teacherSql;
        }
    } else {
        echo "Teacher ID is empty";
        echo "<br>Teacher: " . $teacher;
    }
} else {
    echo "Selected classroom does not exist";
    echo "<br>Classroom ID: " . $classroomID;
    echo "<br>Classroom SQL: " . $classroomSql;
}

$conn->close();
?>
<?php
session_start();


$servername = "localhost";
$username = "root";
$password = "";
$database = "timetest";
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Delete timetable entry
if (isset($_POST['timetable_id'])) {
    $timetableID = $_POST['timetable_id'];

    // Delete the timetable entry
    $deleteTimetableSql = "DELETE FROM timetable WHERE id = $timetableID";
    if ($conn->query($deleteTimetableSql) === TRUE) {
        echo "Timetable entry deleted successfully.";
    } else {
        echo "Error deleting timetable entry: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>TimeTable Management System</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>TimeTable Management System</h1>
    <div class="logout-container">
        <div class="user-container">
            <p class="logged-in">Logged in as: <?php echo isset($_SESSION['username']) ? $_SESSION['username'] : ''; ?></p>
        </div>
        <div class="logout-button">
            <form method="post" action="logout.php" onsubmit="return confirmLogout()">
                <button type="submit">Logout</button>
            </form>
        </div>
    </div>
    <div class="container">
        <div class="option-card">
            <h2>TEACHERS</h2>
            <a href="add_teachers.php">Add Teachers</a>
        </div>

        <div class="option-card">
            <h2>SUBJECTS</h2>
            <a href="add_subjects.php">Add Subjects</a>
        </div>

        <div class="option-card">
            <h2>CLASSROOMS</h2>
            <a href="add_classrooms.php">Add Classrooms</a>
        </div>
    </div>

    <script src="script.js"></script>

    <h2>Create Timetable</h2>
    <form action="create_timetable.php" method="post">
        <!-- Select Classroom dropdown -->
        <label for="classroom">Select Classroom:</label>
        <select name="classroom" id="classroom" required>
            <?php
            // Retrieve existing classrooms from the database
            $classroomsSql = "SELECT * FROM classrooms";
            $classroomsResult = $conn->query($classroomsSql);

            if ($classroomsResult->num_rows > 0) {
                while ($row = $classroomsResult->fetch_assoc()) {
                    echo '<option value="' . $row["id"] . '">' . $row["name"] . '</option>';
                }
            }
            ?>
        </select>

        <!-- Select Teacher dropdown -->
        <label for="teacher">Select Teacher:</label>
        <select name="teacher" id="teacher" required>
            <?php
            // Retrieve existing teachers from the database
            $teachersSql = "SELECT * FROM teachers";
            $teachersResult = $conn->query($teachersSql);

            if ($teachersResult->num_rows > 0) {
                while ($row = $teachersResult->fetch_assoc()) {
                    echo '<option value="' . $row["id"] . '">' . $row["name"] . '</option>';
                }
            }
            ?>
        </select>

        <!-- Select Subject dropdown -->
        <label for="subject">Select Subject:</label>
        <select name="subject" id="subject" required>
            <?php
            // Retrieve existing subjects from the database
            $subjectsSql = "SELECT * FROM subjects";
            $subjectsResult = $conn->query($subjectsSql);

            if ($subjectsResult->num_rows > 0) {
                while ($row = $subjectsResult->fetch_assoc()) {
                    echo '<option value="' . $row["id"] . '">' . $row["name"] . '</option>';
                }
            }
            ?>
        </select>

        <!-- Rest of your form -->
        <label for="day">Select Day:</label>
        <select name="day" id="day">
            <option value="Monday">Monday</option>
            <option value="Tuesday">Tuesday</option>
            <option value="Wednesday">Wednesday</option>
            <option value="Thursday">Thursday</option>
            <option value="Friday">Friday</option>
            <option value="Saturday">Saturday</option>
            <option value="Sunday">Sunday</option>
        </select>

        <label for="startTime">Start Time:</label>
        <select name="startTime" id="startTime" required>
            <?php
            // Generate time options for every hour
            for ($hour = 0; $hour < 24; $hour++) {
                $time24HourFormat = sprintf("%02d", $hour) . ':00'; // Format as HH:00
                $time12HourFormat = date("g:00a", strtotime($time24HourFormat)); // Convert to 12-hour format
                echo '<option value="' . $time24HourFormat . '">' . $time12HourFormat . '</option>';
            }
            ?>
        </select>

        <label for="endTime">End Time:</label>
        <select name="endTime" id="endTime" required>
            <?php
            // Generate time options for every hour
            for ($hour = 0; $hour < 24; $hour++) {
                $time24HourFormat = sprintf("%02d", $hour) . ':00'; // Format as HH:00
                $time12HourFormat = date("g:00a", strtotime($time24HourFormat)); // Convert to 12-hour format
                echo '<option value="' . $time24HourFormat . '">' . $time12HourFormat . '</option>';
            }
            ?>
        </select>
        <style>
        .btn-create {
        display: inline-block;
        padding: 10px 20px;
        margin-left:20px;
        font-size: 16px;
        font-weight: bold;
        text-align: center;
        text-decoration: none;
        background-color: #333;
        color: #fff;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        }

        .btn-create:hover {
        background-color: #555;
        }

        .btn-create:focus {
        outline: none;
        }

        .btn-create:active {
        background-color: #3e8e41;
        }
        </style>
        
        <button type="submit" class="btn-create">Create Timetable</button>
    </form>

    <h2>Timetable</h2>

    <div class="timetable-container">
        <?php
        // Retrieve the timetable data from the database
        $userID = $_SESSION['user_id'];
        $selectSql = "SELECT t.id, c.name AS classroom_name, tr.name AS teacher_name, s.name AS subject_name, t.day, t.start_time, t.end_time
                      FROM timetable t
                      JOIN classrooms c ON t.classroom_id = c.id
                      JOIN teachers tr ON t.teacher_id = tr.id
                      JOIN subjects s ON t.subject_id = s.id
                      WHERE t.user_id = $userID";

        // Execute the query
        $result = $conn->query($selectSql);

        // Check if there are timetable entries
        if ($result->num_rows > 0) {
            echo "<table>";
            echo "<tr>";
            echo "<th>Classroom</th>";
            echo "<th>Teacher</th>";
            echo "<th>Subject</th>";
            echo "<th>Day</th>";
            echo "<th>Start Time</th>";
            echo "<th>End Time</th>";
            echo "<th>Action</th>";
            echo "</tr>";

            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["classroom_name"] . "</td>";
                echo "<td>" . $row["teacher_name"] . "</td>";
                echo "<td>" . $row["subject_name"] . "</td>";
                echo "<td>" . $row["day"] . "</td>";
                echo "<td>" . $row["start_time"] . "</td>";
                echo "<td>" . $row["end_time"] . "</td>";
                echo "<td>";
                echo "<div class='button-container'>";
                echo "<form method='GET' action='edit_timetable.php'>";
                echo "<input type='hidden' name='id' value='" . $row["id"] . "'>";
                echo "<button type='submit' class='btn-edit'>Edit</button>";
                echo "</form>";
                echo "<form method='POST' action='homepage.php' onsubmit='return confirmDelete()'>";
                echo "<input type='hidden' name='timetable_id' value='" . $row["id"] . "'>";
                echo "<button type='submit' class='btn-delete'>Delete</button>";
                echo "</form>";
                echo "</div>";
                echo "</td>";
                echo "</tr>";
            }

            echo "</table>";
        } else {
            echo "No timetable entries found.";
        }

        // Close the database connection
        $conn->close();
        ?>
    </div>
<script>
    // Confirm delete function
        function confirmDelete() {
            return confirm("Are you sure you want to delete this timetable entry?");
        }

        // Confirm logout function
        function confirmLogout() {
            return confirm("Are you sure you want to logout?");
        }
        
</script>
        
    
</body>
</html>

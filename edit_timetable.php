<?php
// Check if the form is submitted
if (isset($_POST['submit'])) {
    // Include the database connection
    include('config.php');

    // Get the selected ID and values from the form
    $id = $_POST['id'];
    $subject = $_POST['subject'];
    $teacher = $_POST['teacher'];
    $classroom = $_POST['classroom'];
    $day = $_POST['day'];
    $startTime = $_POST['startTime'];
    $endTime = $_POST['endTime'];

    // Update the timetable entry in the database
    $query = "UPDATE timetable SET classroom_id='$classroom', teacher_id='$teacher', subject_id='$subject', day='$day', start_time='$startTime', end_time='$endTime' WHERE id=$id";
    $result = mysqli_query($conn, $query);

    if ($result) {
        echo "<script>alert('Timetable entry updated successfully');</script>";
        // Redirect to homepage.php
        header("Location: homepage.php");
        exit();
    } else {
        echo "<script>alert('Failed to update timetable entry');</script>";
    }
}

// Get the selected ID from the URL parameter
$id = isset($_GET['id']) ? $_GET['id'] : '';

// Check if the ID is empty
if (empty($id)) {
    // Handle the case when the ID is not present
    echo "Invalid timetable entry.";
    exit;
}

// Include the database connection
include('config.php');

// Fetch the timetable entry from the database
$query = "SELECT t.*, c.name AS classroom_name, r.name AS teacher_name, s.name AS subject_name
          FROM timetable t
          INNER JOIN classrooms c ON t.classroom_id = c.id
          INNER JOIN teachers r ON t.teacher_id = r.id
          INNER JOIN subjects s ON t.subject_id = s.id
          WHERE t.id = $id";
$result = mysqli_query($conn, $query);

// Check if a row is returned
if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);

    // Rest of your code to display and update the timetable entry
    // ...
} else {
    echo "Timetable entry not found.";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Timetable Entry</title>
    <style>
        .container {
            max-width: 400px;
            margin: 0 auto;
        }

        h1 {
            text-align: center;
        }

        label {
            display: block;
            margin-top: 10px;
        }

        select, input[type="time"] {
            width: 100%;
            padding: 5px;
            margin-top: 5px;
        }

        input[type="submit"] {
            display: block;
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            background-color: #333;
            color: white;
            border: none;
            cursor: pointer;
        }

        .btn-edit {
            display: inline-block;
            padding: 5px 10px;
            margin-right: 5px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }

        
    </style>
</head>
<body>
    <div class="container">
        <h1>Edit Timetable Entry</h1>
        <form method="post" action="">
            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
            <label for="classroom">Classroom:</label>
            <select name="classroom" id="classroom">
                <?php echo getDropdownOptions($conn, 'classrooms', 'id', 'name', $row['classroom_id']); ?>
            </select>
            <label for="teacher">Teacher:</label>
            <select name="teacher" id="teacher">
                <?php echo getDropdownOptions($conn, 'teachers', 'id', 'name', $row['teacher_id']); ?>
            </select>
            <label for="subject">Subject:</label>
            <select name="subject" id="subject">
                <?php echo getDropdownOptions($conn, 'subjects', 'id', 'name', $row['subject_id']); ?>
            </select>
            <label for="day">Day:</label>
            <select name="day" id="day">
                <option value="Monday" <?php if ($row['day'] == 'Monday') echo 'selected'; ?>>Monday</option>
                <option value="Tuesday" <?php if ($row['day'] == 'Tuesday') echo 'selected'; ?>>Tuesday</option>
                <option value="Wednesday" <?php if ($row['day'] == 'Wednesday') echo 'selected'; ?>>Wednesday</option>
                <option value="Thursday" <?php if ($row['day'] == 'Thursday') echo 'selected'; ?>>Thursday</option>
                <option value="Friday" <?php if ($row['day'] == 'Friday') echo 'selected'; ?>>Friday</option>
                <option value="Saturday" <?php if ($row['day'] == 'Saturday') echo 'selected'; ?>>Saturday</option>
                <option value="Sunday" <?php if ($row['day'] == 'Sunday') echo 'selected'; ?>>Sunday</option>
            </select>
            <label for="startTime">Start Time:</label>
            <input type="time" name="startTime" id="startTime" value="<?php echo $row['start_time']; ?>">
            <label for="endTime">End Time:</label>
            <input type="time" name="endTime" id="endTime" value="<?php echo $row['end_time']; ?>">
            <input type="submit" name="submit" value="Update">
        </form>
    </div>

    <?php
    // Function to generate dropdown options
    function getDropdownOptions($conn, $table, $valueField, $textField, $selectedValue = '')
    {
        $options = '';
        $query = "SELECT * FROM $table";
        $result = mysqli_query($conn, $query);
        while ($row = mysqli_fetch_assoc($result)) {
            $selected = $row[$valueField] == $selectedValue ? 'selected' : '';
            $options .= "<option value='" . $row[$valueField] . "' $selected>" . $row[$textField] . "</option>";
        }
        return $options;
    }
    ?>
</body>
</html>

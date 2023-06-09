<?php
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the form data
    $subjectName = $_POST["subject_name"];
    $subjectID = $_POST["subject_id"];

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

    // Prepare and execute the SQL query to insert the data
    $sql = "INSERT INTO subjects (id, name) VALUES ('$subjectID', '$subjectName')";

    if ($conn->query($sql) === TRUE) {
        header("Location: homepage.php?success=1");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Close the database connection
    $conn->close();
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Add Subjects</title>
    <!-- CSS styles -->
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        
        h1 {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
        }
        
        .form-container {
            width: 400px;
            margin: 0 auto;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
        
        .form-container label {
            display: block;
            margin-bottom: 10px;
            color: #333;
        }
        
        .form-container input[type="text"] {
            width: 90%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        
        .form-container button[type="submit"] {
            background-color: #333;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        
        .form-container button[type="submit"]:hover {
            background-color: #555;
        }
    </style>
</head>
<body>
    <h1>Add Subjects</h1>
    
    <div class="form-container">
        <form method="POST" action="">
    <label for="subject_id">Subject ID:</label>
    <input type="text" id="subject_id" name="subject_id" placeholder="Enter the subject ID">

    <label for="subject_name">Subject Name:</label>
    <input type="text" id="subject_name" name="subject_name" placeholder="Enter the subject name">

    <button type="submit">Add Subject</button>
</form>
    </div>
</body>
</html>

<?php
// Start the session
session_start();

// Check if the user is already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: homepage.php");
    exit();
}

// Establish a connection to the database
$servername = "localhost";
$username = "root";
$password = "";
$database = "timetest";
$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Declare the $stmt variable and assign null
$stmt = null;

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the submitted login credentials
    $username = isset($_POST['username']) ? $_POST['username'] : "";
    $password = isset($_POST['password']) ? $_POST['password'] : "";

    // Prepare and execute a parameterized query to check if the user exists
    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $storedPassword = $row['password'];

        // Verify the entered password with the stored password hash
        if (password_verify($password, $storedPassword)) {
            // Login successful, set the session variables
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            
            // Redirect to the homepage
            header("Location: homepage.php");
            exit();
        } else {
            // Invalid password
            $error_message = "Invalid username or password!";
        }
    } else {
        // User does not exist
        $error_message = "User is not registered!";
    }
}

// Close the database connection and the prepared statement
if ($stmt !== null) {
    $stmt->close();
}
$conn->close();
?>

<?php
// Establish a connection to the database
$host = "localhost";
$username = "root";
$password = "";
$database = "timetest";
$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Retrieve the submitted registration data
$username = $_POST['username'];
$password = $_POST['password'];

// Check if the username is already taken
$stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    // Insert the new user into the database
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT); // Hash the password
    $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $hashedPassword);
    if ($stmt->execute()) {
        echo "Registration successful!";
        $_SESSION['username'] = $username;
        // You might want to redirect the user to the login page after successful registration
        header("Location: index.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
} else {
    echo "Username already exists!";
}

// Close the database connection
$stmt->close();
$conn->close();
?>

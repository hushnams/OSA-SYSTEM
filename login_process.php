<?php
session_start();

include('config/constants.php');

$host = "localhost";
$user = "root";
$password = "";
$database = "osa";

// Create a connection
$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $student_id = $_POST['student_id'] ?? '';
    $password = $_POST['password'] ?? '';

    // Sanitize inputs
    $student_id = $conn->real_escape_string(trim($student_id));
    $password = $conn->real_escape_string(trim($password));

    // Query to check user credentials
    $sql = "SELECT * FROM student_account WHERE student_id = '$student_id'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        // Fetch user data
        $row = $result->fetch_assoc();

        // Check the password (plain text)
        if (password_verify($password, $row['password'])) {
            // Set session variables
            $_SESSION['id'] = $row['id'];
            $_SESSION['student_id'] = $row['student_id'];
            $_SESSION['firstname'] = $row['firstname'];
            $_SESSION['middlename'] = $row['middlename'];
            $_SESSION['lastname'] = $row['lastname'];
            $_SESSION['course'] = $row['course'];
            $_SESSION['section'] = $row['section']; // Store last name in session
            $_SESSION['success_message'] = "Welcome, " . htmlspecialchars($row['lastname']) . "! You have successfully logged in.";

            // Redirect to the welcome page
            header("Location: user/welcome.php");
            exit();
        } else {
            // Set error message for incorrect password
            $_SESSION['error_message'] = "Incorrect password. Please try again.";
            header("Location: login.php");
            exit();
        }
    } else {
        // Set error message for invalid student ID
        $_SESSION['error_message'] = "Invalid student ID. Please try again.";
        header("Location: login.php");
        exit();
    }
}

// Close connection
$conn->close();
?>

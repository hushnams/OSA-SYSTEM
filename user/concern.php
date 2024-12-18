<?php
session_start();
include('../config/constants.php'); // Include your database connection

// Check if the user is logged in
if (!isset($_SESSION['student_id'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the submitted form data
    $student_id = $_SESSION['student_id'];  // Assume student_id is stored in session
    $name = $_POST['name']; // Optional
    $date = $_POST['date'];
    $office = $_POST['office'];
    $course = $_POST['course'];
    $message = $_POST['message'];

    // Prepare the SQL query
    $sql = "INSERT INTO concern (student_id, name, date, office, course, message) 
            VALUES (?, ?, ?, ?, ?, ?)";

    // Prepare and execute the query
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ssssss", $student_id, $name, $date, $office, $course, $message);
        if ($stmt->execute()) {
            echo "Concern submitted successfully.";
        } else {
            echo "Error submitting concern: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Concern</title>
    <link rel="stylesheet" href="/css/user.css"> <!-- Link to your CSS file -->
</head>
<body>

    <!-- Sidebar -->
    <?php include('../partials/sidenav.php');?>

    <!-- Main Content -->
    <div class="main">
        <form method="POST" action="concern.php" class="concern-form">
            <h1>Submit a COMPLAINT</h1>

            <!-- Name and Date Row -->
            <div class="row">
                <div class="col">
                    <label>Name</label>
                    <input type="text" name="name" placeholder="(Optional)">
                </div>
                <div class="col">
                    <label>Date *</label>
                    <input type="date" name="date" required>
                </div>
            </div>

            <!-- Office and Course Row -->
            <div class="row">
                <div class="col">
                    <label>Office *</label>
                    <input type="text" name="office" placeholder="CET/CED/CBA" required>
                </div>
                <div class="col">
                    <label>Course *</label>
                    <input type="text" name="course" placeholder="Your Course" required>
                </div>
            </div>

            <!-- Message Section -->
            <label>Summary of COMPLAINT/ISSUE *</label>
            <textarea name="message" rows="5" placeholder="Message..." required></textarea>

            <!-- Submit Button -->
            <button type="submit">SUBMIT</button>
        </form>
    </div>

</body>
</html>

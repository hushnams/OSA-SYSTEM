<?php
    include('../config/constants.php'); // Include the database connection file

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Handle form submission
        $student_id = $_POST['student_id'];
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $middlename = $_POST['middlename'];
        $course = $_POST['course'];
        $section = $_POST['section'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password for security

        // Insert student into the database
        $sql = "INSERT INTO student_account (student_id, firstname, lastname, middlename, course, section, password)
                VALUES ('$student_id', '$firstname', '$lastname', '$middlename', '$course', '$section', '$password')";

        if (mysqli_query($conn, $sql)) {
            echo "<script>alert('Student added successfully!');</script>";
        } else {
            echo "<script>alert('Error adding student: " . mysqli_error($conn) . "');</script>";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Student</title>
    <link rel="stylesheet" href="../css/admin.css"> <!-- Include your CSS -->
</head>
<body>

    <!-- Sidebar or Navbar -->
    <?php include('../partials/sidebar.php'); ?>

    <!-- Main Content -->
    <div class="dashboard-content">

            <!-- Add Student Button -->
        <div style="margin-bottom: 15px;">
            <a href="view-students.php" style="
                background-color: #4CAF50; 
                color: white; 
                padding: 10px 20px; 
                text-decoration: none; 
                border-radius: 5px;
                font-size: 16px;
            ">
                Back
            </a>
        </div>

        <h2>Add New Student</h2>

        <form action="add-student.php" method="POST">
            <label for="student_id">Student ID:</label>
            <input type="text" id="student_id" name="student_id" required>

            <label for="firstname">First Name:</label>
            <input type="text" id="firstname" name="firstname" required>

            <label for="lastname">Last Name:</label>
            <input type="text" id="lastname" name="lastname" required>

            <label for="middlename">Middle Name (Optional):</label>
            <input type="text" id="middlename" name="middlename">

            <label for="course">Course</label>
            <input type="text" id="course" name="course" required>

            <label for="section">Section:</label>
            <input type="text" id="section" name="section" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Add Student</button>
        </form>
    </div>

</body>
</html>

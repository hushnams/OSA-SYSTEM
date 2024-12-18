<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['student_id'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    
    <link rel="stylesheet" href="/css/user.css"> <!-- Link to your CSS file -->
    <style>

        .main {
            display: flex;
            align-items: center;
            width: 25%; /* Makes the container take up the full width */
            padding: 20px; /* Adds padding inside the container for extra space */
            border-radius: 10px;
        }

        .student-info {
            margin-right: 50px;
        }

        .student-info img {
            max-width: 150px;
            max-height: 150px;
        }

    </style>
</head>
<body>


    <!-- Sidebar -->
    <?php include('../partials/sidenav.php');?>

    <div class="main">
        <div class="student-info">
        <img src="/assets/img/images.jpg">
    </div>
    <div class="text">
        <p><b>Student Number: </b><?php echo htmlspecialchars($_SESSION['student_id']); ?> </p>
        <p><b>Student Name: </b><?php echo htmlspecialchars($_SESSION['firstname'] . ' ' . $_SESSION['middlename'] . ' ' . $_SESSION['lastname']); ?>  </p>
        <p><b>Course: </b><?php echo htmlspecialchars($_SESSION['course']); ?> </p>
        <p><b>Section: </b><?php echo htmlspecialchars($_SESSION['section']); ?> </p>
    </div>
</div>
<table>
    <thead>
        <tr>
                <th>START OF CLASSES</th>
                <th>AUGUST 19, 2024</th>
            </tr>
        </thead>
        <tbody>

            <tr>
                <td><b>PRELIM PERIOD</b></td>
                <td><b>August 19, 2024 - September 29, 2024</b></td>
            </tr>
            <tr>
                <td>Preliminary Examination Schedule</td>
                <td>September 23 - 29, 2024</td>
            </tr>
            <tr>
                <td>Encoding of Preliminary Grade</td>
                <td>October 7, 2024</td>
            </tr>
            <tr>
                <td><b>MIDTERM PERIOD</b></td>
                <td><b>September 30, 2024 - November 10, 2024</b></td>
            </tr>
            <tr>
                <td>Midterm Examination Schedule</td>
                <td>November 4 - 10, 2024</td>
            </tr>
            <tr>
                <td>Encoding of Midterm Grade</td>
                <td>November 18, 2024</td>
            </tr>
            <tr>
                <td><b>FINAL PERIOD</b></td>
                <td><b>November 11, 2024 - December 22, 2024</b></td>
            </tr>
            <tr>
                <td>Final Examination Schedule</td>
                <td>December 16 - 22, 2024</td>
            </tr>
            <tr>
                <td>Encoding of Final Grade</td>
                <td>January 6 - 7, 2024</td>
            </tr>
            <tr>
                <td><b>Last Day of Classes for First Semester S.Y. 2024 - 2025</b></td>
                <td><b>December 22, 2024</b></td>
            </tr>
            <tr>
                <td><b>CHRISTMAS BREAK</b></td>
                <td><b>December 22, 2024 - January 21 ,2024</b></td>
            </tr>
        </tbody>
    </table>

</body>
</html>
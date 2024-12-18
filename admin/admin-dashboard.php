<?php
// Include constants.php to access the database connection
include('../config/constants.php');

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    // If not logged in, redirect to login page
    header('Location: <?php echo SITEURL; ?>admin-login.php');
    exit();
}

// Get the admin's username from session
$username = $_SESSION['username'];

// Query to get the total number of students
$query_students = "SELECT COUNT(*) AS total_students FROM student_account";
$result_students = mysqli_query($conn, $query_students);
$data_students = mysqli_fetch_assoc($result_students);
$total_students = $data_students['total_students'];

// Query to get the total number of active violations
$query_active_violations = "SELECT COUNT(*) AS total_active_violations FROM violation WHERE violation_status = 2";  // Assuming 2 is the 'active' status
$result_active_violations = mysqli_query($conn, $query_active_violations);
$data_active_violations = mysqli_fetch_assoc($result_active_violations);
$total_active_violations = $data_active_violations['total_active_violations'];

// Query to get the total number of active (pending) concerns
$query_active_concerns = "SELECT COUNT(*) AS total_active_concerns FROM concern WHERE status = 'Pending'";  // Assuming 'Pending' is the status for active concerns
$result_active_concerns = mysqli_query($conn, $query_active_concerns);
$data_active_concerns = mysqli_fetch_assoc($result_active_concerns);
$total_active_concerns = $data_active_concerns['total_active_concerns'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>

    <!-- Include Sidebar -->
    <?php include('../partials/sidebar.php'); ?>

    <!-- Main content -->
    <div class="dashboard-content">
        <h2>Welcome, <?php echo $username; ?>!</h2><br><br><br><br><br><br><br>

        <div class="stats-boxes">
            <!-- Total Students -->
            <div class="stats-box">
                <h3>Total Students</h3>
                <p><?php echo $total_students; ?></p>
            </div>

            <!-- Total Active Violations -->
            <div class="stats-box">
                <h3>Total Active Violations</h3>
                <p><?php echo $total_active_violations; ?></p>
            </div>

            <!-- Total Active Concerns -->
            <div class="stats-box">
                <h3>Total Pending Concerns</h3>
                <p><?php echo $total_active_concerns; ?></p>
            </div>
        </div>
    </div>

</body>
</html>

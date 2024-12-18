<?php
include('../config/constants.php'); // Database connection

// Get the violation id from the query string
$violation_id = isset($_GET['id']) ? $_GET['id'] : '';

// Check if violation_id is provided
if ($violation_id == '') {
    echo "<script>alert('Invalid violation ID'); window.location.href = 'view-violations.php';</script>";
    exit();
}

// Check if form is submitted to clear the violation
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Set the status to 'Clear' (status_id = 1)
    $status = 1; // 'Clear' status
    $violation_type = 'N/A'; // Set the violation type to N/A
    $duration = 'N/A'; // Set the violation duration to N/A
    
    // Update the violation status to 'Clear' and set type and duration to NULL
    $sql = "UPDATE violation 
            SET violation_type = NULL, 
                violation_duration = 'N/A', 
                violation_status = '$status' 
            WHERE id = '$violation_id'";


    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Violation cleared successfully'); window.location.href = 'view-violations.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clear Violation</title>
    <link rel="stylesheet" href="../css/admin.css"> <!-- Link to your CSS -->
</head>
<body>

<?php include('../partials/sidebar.php'); ?>

<div class="dashboard-content">
    <h2>Clear Violation</h2>

    <!-- Clear Violation Confirmation Form -->
    <form action="clear-violation.php?id=<?php echo $violation_id; ?>" method="POST">
        <p>Are you sure you want to clear this violation?</p>
        <button type="submit" style="background-color: #4CAF50; color: white; padding: 10px 20px; border-radius: 5px;">Yes, Clear Violation</button>
        <a href="view-violations.php" style="background-color: #f44336; color: white; padding: 10px 20px; border-radius: 5px; text-decoration: none;">Cancel</a>
    </form>
</div>

</body>
</html>

<?php
// Close the database connection
mysqli_close($conn);
?>

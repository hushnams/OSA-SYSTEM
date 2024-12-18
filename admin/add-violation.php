<?php
include('../config/constants.php'); // Database connection

// Fetch violation types
$sql = "SELECT * FROM violation_type";
$violation_types = mysqli_query($conn, $sql);

// Get the student_id from the query string
$student_id = isset($_GET['student_id']) ? $_GET['student_id'] : '';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_id = mysqli_real_escape_string($conn, $_POST['student_id']);
    $violation_type = mysqli_real_escape_string($conn, $_POST['violation_type']);
    $duration = mysqli_real_escape_string($conn, $_POST['duration']);
    $status = 2; // Use the status ID for 'Active' (status_id = 2)

    // Insert violation into the database
    $sql_insert = "INSERT INTO violation (student_id, violation_type, violation_duration, violation_status)
                   VALUES ('$student_id', '$violation_type', '$duration', '$status')";

    if (mysqli_query($conn, $sql_insert)) {
        echo "<script>alert('Violation added successfully'); window.location.href = 'view-violations.php';</script>";
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
    <title>Add Violation</title>
    <link rel="stylesheet" href="../css/admin.css"> <!-- Link to your CSS -->
</head>
<body>

<?php include('../partials/sidebar.php'); ?>

<div class="dashboard-content">
    <h2>Add New Violation</h2>

    <!-- Add Violation Form -->
    <form action="add-violation.php" method="POST">
        <label for="student_id">Student ID:</label>
        <input type="text" id="student_id" name="student_id" value="<?php echo htmlspecialchars($student_id); ?>" readonly><br><br>

        <label for="violation_type">Violation Type:</label>
        <select name="violation_type" id="violation_type" required>
            <option value="">Select Violation Type</option>
            <?php
            if (mysqli_num_rows($violation_types) > 0) {
                while ($row = mysqli_fetch_assoc($violation_types)) {
                    echo "<option value='" . $row['type_id'] . "'>" . $row['type_name'] . "</option>";
                }
            }
            ?>
        </select><br><br>

        <label for="duration">Violation Duration:</label>
        <input type="text" id="duration" name="duration" required><br><br>

        <button type="submit" style="background-color: #4CAF50; color: white; padding: 10px 20px; border-radius: 5px;">Add Violation</button>
    </form>
</div>

</body>
</html>

<?php
// Close the database connection
mysqli_close($conn);
?>

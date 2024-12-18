<?php
session_start();

$host = "localhost";
$user = "root";
$password = "";
$database = "osa";

// Create a connection
$conn = new mysqli($host, $user, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if student ID is set in session
if (!isset($_SESSION['student_id'])) {
    die("No student ID found in session.");
}

$student_id = $_SESSION['student_id']; // Replace with the actual session variable for student ID

// Query to retrieve violations for the student
$sql = "SELECT 
            CONCAT(sa.firstname, ' ', sa.middlename, ' ', sa.lastname) AS fullname, 
            sa.course, 
            sa.section, 
            vt.type_name AS violation_type, 
            v.violation_duration,
            vs.status_name AS violation_status,
            v.created_at
        FROM violation AS v
        INNER JOIN student_account AS sa 
        ON v.student_id = sa.student_id
        LEFT JOIN violation_type AS vt
        ON v.violation_type = vt.type_id
        LEFT JOIN violation_status AS vs
        ON v.violation_status = vs.status_id
        WHERE v.student_id = ? AND v.violation_status != 1";  // Exclude 'cleared' violations


$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $student_id); // Changed "i" to "s" because student_id is a string (e.g., '21-16-012')
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Violations</title>
    <link rel="stylesheet" href="/css/user.css"> <!-- Link to your CSS file -->
</head>
<body>

    <!-- Sidebar -->
    <?php include('../partials/sidenav.php');?>

    <div class="content">
        <h1>Your Violations</h1>

        <?php
        // Check if there are any violations
        if ($result->num_rows > 0) {
            echo "<table border='1'>
                    <tr>
                        <th>Full Name</th>
                        <th>Course</th>
                        <th>Section</th>
                        <th>Violation Type</th>
                        <th>Violation Duration(hr)</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>";

            // Output data of each row
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>" . htmlspecialchars($row['fullname']) . "</td>
                        <td>" . htmlspecialchars($row['course']) . "</td>
                        <td>" . htmlspecialchars($row['section']) . "</td>
                        <td>" . htmlspecialchars($row['violation_type']) . "</td>
                        <td>" . htmlspecialchars($row['violation_duration']) . "</td>
                        <td>" . htmlspecialchars($row['violation_status']) . "</td>
                        <td>" . htmlspecialchars($row['created_at']) . "</td>
                    </tr>";
            }
            echo "</table>";
        } else {
            echo "<p>You are cleared, no violations found.</p>";
        }


        // Close the statement and connection
        $stmt->close();
        $conn->close();
        ?>
    </div>

</body>
</html>

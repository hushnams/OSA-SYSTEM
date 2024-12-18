<?php
session_start();
include('../config/constants.php'); // Include your database connection

// Check if the user is logged in
if (!isset($_SESSION['student_id'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

$student_id = $_SESSION['student_id'];

// Query to fetch the user's concerns and their statuses
$sql = "SELECT id, name, date, office, course, message, status FROM concern WHERE student_id = ? ORDER BY date DESC";
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("s", $student_id); // Bind the student_id parameter
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    echo "Error: " . $conn->error;
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Concerns</title>
    <link rel="stylesheet" href="/css/user.css"> <!-- Link to your CSS file -->
    <style>
        /* Additional CSS for concern list page */
        .main {
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .main h1 {
            font-size: 28px;
            color: #005c29;
            text-align: center;
            margin-bottom: 20px;
        }

        .add-button {
            display: block;
            width: 200px;
            margin: 20px auto;
            padding: 10px;
            background-color: #005c29;
            color: white;
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }

        .add-button:hover {
            background-color: #003d1c;
        }

        table {
            width: 100%;
            margin: 20px 0;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        th, td {
            border: 1px solid #ddd;
            padding: 18px;
            text-align: left;
            font-size: 16px;
        }

        th {
            background-color: #005c29;
            color: white;
            text-transform: uppercase;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .status-pending {
            color: orange;
            font-weight: bold;
        }

        .status-verified {
            color: green;
            font-weight: bold;
        }

        .status-rejected {
            color: red;
            font-weight: bold;
        }

        td {
            word-wrap: break-word;
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <?php include('../partials/sidenav.php');?>

    <!-- Main Content -->
    <div class="main">
        <h1>Your Submitted Concerns</h1>

        <!-- Add Concern Button -->
        <a href="concern.php"><button class="add-button">Add New Concern</button></a>

        <!-- Concerns Table -->
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Office</th>
                    <th>Course</th>
                    <th>Message</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Check if there are any concerns
                if ($result->num_rows > 0) {
                    // Loop through the concerns and display them
                    while ($row = $result->fetch_assoc()) {
                        $status_class = '';
                        switch ($row['status']) {
                            case 'Pending':
                                $status_class = 'status-pending';
                                break;
                            case 'Verified':
                                $status_class = 'status-verified';
                                break;
                            case 'Rejected':
                                $status_class = 'status-rejected';
                                break;
                        }
                        echo "<tr>";
                        echo "<td>" . $row['date'] . "</td>";
                        echo "<td>" . $row['office'] . "</td>";
                        echo "<td>" . $row['course'] . "</td>";
                        echo "<td>" . $row['message'] . "</td>";
                        echo "<td class='$status_class'>" . $row['status'] . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>No concerns found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

</body>
</html>

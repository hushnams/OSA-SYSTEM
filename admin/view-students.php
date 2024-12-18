<?php
    include('../config/constants.php'); // Include the database connection file

    // Check if the search form has been submitted and apply the search filter
    $search = "";
    if (isset($_POST['search'])) {
        $search = mysqli_real_escape_string($conn, $_POST['search']);
        $sql = "SELECT * FROM student_account WHERE student_id LIKE '%$search%' OR firstname LIKE '%$search%' OR lastname LIKE '%$search%' OR course LIKE '%$search%' OR section LIKE '%$search%'";
    } else {
        $sql = "SELECT * FROM student_account"; // Default query if no search is applied
    }

    $result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Students</title>
    <link rel="stylesheet" href="../css/admin.css"> <!-- Include your CSS -->
    <style>
        /* Add some simple styles to enhance the look */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7fa;
            margin: 0;
            padding: 0;
        }



        h2 {
            font-size: 24px;
            color: #333;
            margin-bottom: 20px;
        }

        .search-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .search-bar input {
            width: 50%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 16px;
        }

        .search-bar button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .search-bar button:hover {
            background-color: #45a049;
        }

        .table-container {
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th, table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        table th {
            background-color: #4CAF50;
            color: white;
        }

        table tr:hover {
            background-color: #f1f1f1;
        }

        .add-btn {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            margin-bottom: 20px;
        }

        .add-btn:hover {
            background-color: #45a049;
        }

        .actions a {
            color: #4CAF50;
            text-decoration: none;
            margin-right: 10px;
        }

        .actions a:hover {
            color: #45a049;
        }
    </style>
</head>
<body>

<?php include('../partials/sidebar.php'); ?>

    <!-- Main Content -->
    <div class="dashboard-content">
        <h2>Students List</h2>

        <!-- Search Bar -->
        <div class="search-bar">
            <form action="" method="post">
                <input type="text" name="search" placeholder="Search by Student ID, Name, Section, or Course" value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit">Search</button>
            </form>
        </div>

        <!-- Add Student Button -->
        <div style="margin-bottom: 15px;">
            <a href="add-student.php" class="add-btn">
                + Add Student
            </a>
        </div>

        <!-- Students Table -->
        <div class="table-container">
            <table>
                <tr>
                    <th>Student ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Middle Name</th>
                    <th>Course</th>
                    <th>Section</th>
                    <th>Actions</th>
                </tr>
                <?php
                    // Loop through the results and display each student's details
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['student_id']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['firstname']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['lastname']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['middlename']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['course']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['section']) . "</td>";
                            echo "<td class='actions'>";
                            echo "<a href='add-violation.php?student_id=" . $row['student_id'] . "'>Add Violation</a> | "; 
                            echo "<a href='edit-student.php?id=" . $row['id'] . "'>Edit Student</a>"; 
                            echo "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7'>No students found.</td></tr>";
                    }
                ?>
            </table>
        </div>
    </div>

</body>
</html>

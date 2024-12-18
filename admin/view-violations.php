<?php
include('../config/constants.php'); // Database connection

// Check for connection errors
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get the search query if present
$search_query = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

// SQL queries to fetch violations (active and clear)
$active_sql = "SELECT sa.student_id, 
                      CONCAT(sa.firstname, ' ', sa.lastname) AS student_name,
                      sa.course, sa.section, 
                      vt.type_name AS violation_type, 
                      v.violation_duration AS duration, 
                      'Active' AS status, 
                      v.id AS violation_id
               FROM student_account sa
               LEFT JOIN violation v ON sa.student_id = v.student_id
               LEFT JOIN violation_type vt ON v.violation_type = vt.type_id
               WHERE v.violation_status = 2"; // Active status

$clear_sql = "SELECT sa.student_id, 
                      CONCAT(sa.firstname, ' ', sa.lastname) AS student_name,
                      sa.course, sa.section, 
                      vt.type_name AS violation_type, 
                      v.violation_duration AS duration, 
                      'Clear' AS status, 
                      v.id AS violation_id
               FROM student_account sa
               LEFT JOIN violation v ON sa.student_id = v.student_id
               LEFT JOIN violation_type vt ON v.violation_type = vt.type_id
               WHERE v.violation_status = 1"; // Clear status

// Apply search filter to both queries if there's a search query
if (!empty($search_query)) {
    $active_sql .= " AND (sa.student_id LIKE '%$search_query%' OR
                          sa.firstname LIKE '%$search_query%' OR
                          sa.lastname LIKE '%$search_query%' OR
                          sa.course LIKE '%$search_query%' OR
                          sa.section LIKE '%$search_query%' OR
                          vt.type_name LIKE '%$search_query%' OR
                          v.violation_duration LIKE '%$search_query%')";
    
    $clear_sql .= " AND (sa.student_id LIKE '%$search_query%' OR
                         sa.firstname LIKE '%$search_query%' OR
                         sa.lastname LIKE '%$search_query%' OR
                         sa.course LIKE '%$search_query%' OR
                         sa.section LIKE '%$search_query%' OR
                         vt.type_name LIKE '%$search_query%' OR
                         v.violation_duration LIKE '%$search_query%')";
}

$active_result = mysqli_query($conn, $active_sql);
$clear_result = mysqli_query($conn, $clear_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Violations</title>
    <link rel="stylesheet" href="../css/admin.css"> <!-- Link to your CSS -->
    <style>
        /* Simple tab styling */
        .tab {
            display: inline-block;
            padding: 10px 20px;
            margin-right: 5px;
            cursor: pointer;
            background-color: #ddd;
            border-radius: 5px;
        }
        .tab.active {
            background-color: #4CAF50;
            color: white;
        }
        .tab-content {
            display: none;
        }
        .tab-content.active {
            display: block;
        }

        /* Search bar styling */
        .search-bar {
            margin-bottom: 20px;
            padding: 5px;
            width: 100%;
            max-width: 300px;
        }

        /* Status Color */
        .status-active {
            color: red;
        }
        .status-clear {
            color: green;
        }
    </style>
</head>
<body>

<?php include('../partials/sidebar.php'); ?>

<div class="dashboard-content">
    <h2>Student Violations</h2>

    <!-- Search Bar -->
    <form method="GET" action="">
        <input type="text" name="search" class="search-bar" placeholder="Search violations..." value="<?php echo htmlspecialchars($search_query); ?>">
        <button type="submit">Search</button>
    </form>

    <!-- Tab navigation -->
    <div>
        <span class="tab active" id="active-tab">Active Students</span>
        <span class="tab" id="clear-tab">Clear Students</span>
    </div>

    <!-- Active Students Tab Content -->
    <div class="tab-content active" id="active-content">
        <h3>Active Students</h3>
        <table border="1" cellpadding="8" cellspacing="0" width="100%">
            <tr>
                <th>Student ID</th>
                <th>Name</th>
                <th>Course</th>
                <th>Section</th>
                <th>Violation Type</th>
                <th>Duration</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            <?php
            // Check if there are active violations to display
            if (mysqli_num_rows($active_result) > 0) {
                while ($row = mysqli_fetch_assoc($active_result)) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['student_id']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['student_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['course']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['section']) . "</td>";
                    echo "<td>" . (!empty($row['violation_type']) ? htmlspecialchars($row['violation_type']) : 'N/A') . "</td>";
                    echo "<td>" . (!empty($row['duration']) ? htmlspecialchars($row['duration']) : 'N/A') . "</td>";
                    echo "<td class='status-active'>" . (!empty($row['status']) ? htmlspecialchars($row['status']) : 'N/A') . "</td>";
                    echo "<td>
                            <a href='clear-violation.php?id=" . urlencode($row['violation_id']) . "' style='color: green;'>Clear Violation</a> |
                            <a href='delete-violation.php?id=" . urlencode($row['violation_id']) . "' style='color: red;' onclick='return confirm(\"Are you sure you want to delete this violation?\")'>Delete</a>
                         </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='8'>No active violations found.</td></tr>";
            }
            ?>
        </table>
    </div>

    <!-- Clear Students Tab Content -->
    <div class="tab-content" id="clear-content">
        <h3>Clear Students</h3>
        <table border="1" cellpadding="8" cellspacing="0" width="100%">
            <tr>
                <th>Student ID</th>
                <th>Name</th>
                <th>Course</th>
                <th>Section</th>
                <th>Violation Type</th>
                <th>Duration</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            <?php
            // Check if there are clear violations to display
            if (mysqli_num_rows($clear_result) > 0) {
                while ($row = mysqli_fetch_assoc($clear_result)) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['student_id']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['student_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['course']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['section']) . "</td>";
                    echo "<td>" . (!empty($row['violation_type']) ? htmlspecialchars($row['violation_type']) : 'N/A') . "</td>";
                    echo "<td>" . (!empty($row['duration']) ? htmlspecialchars($row['duration']) : 'N/A') . "</td>";
                    echo "<td class='status-clear'>" . (!empty($row['status']) ? htmlspecialchars($row['status']) : 'N/A') . "</td>";
                    echo "<td>
                            <a href='delete-violation.php?id=" . urlencode($row['violation_id']) . "' style='color: red;' onclick='return confirm(\"Are you sure you want to delete this violation?\")'>Delete</a>
                         </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='8'>No clear violations found.</td></tr>";
            }
            ?>
        </table>
    </div>

</div>

<script>
    // Tab switching functionality
    document.getElementById('active-tab').onclick = function() {
        document.getElementById('active-content').classList.add('active');
        document.getElementById('clear-content').classList.remove('active');
        document.getElementById('active-tab').classList.add('active');
        document.getElementById('clear-tab').classList.remove('active');
    };

    document.getElementById('clear-tab').onclick = function() {
        document.getElementById('clear-content').classList.add('active');
        document.getElementById('active-content').classList.remove('active');
        document.getElementById('clear-tab').classList.add('active');
        document.getElementById('active-tab').classList.remove('active');
    };
</script>

</body>
</html>

<?php
// Close the database connection
mysqli_close($conn);
?>

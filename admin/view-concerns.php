<?php
session_start();
include('../config/constants.php'); // Include your database connection

// Check if the user is logged in as admin
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch all concerns, including the student ID
$sql = "SELECT c.id, c.name, c.date, c.office, c.course, c.message, c.status, c.created_at, s.student_id, s.firstname, s.lastname 
        FROM concern c
        JOIN student_account s ON c.student_id = s.student_id";
$result = $conn->query($sql);

// Fetch concerns based on their status
$pending_sql = "SELECT c.id, c.name, c.date, c.office, c.course, c.message, c.status, c.created_at, s.student_id, s.firstname, s.lastname 
                FROM concern c
                JOIN student_account s ON c.student_id = s.student_id
                WHERE c.status = 'Pending'";
$pending_result = $conn->query($pending_sql);

$resolved_sql = "SELECT c.id, c.name, c.date, c.office, c.course, c.message, c.status, c.created_at, s.student_id, s.firstname, s.lastname 
                 FROM concern c
                 JOIN student_account s ON c.student_id = s.student_id
                 WHERE c.status = 'Resolved'";
$resolved_result = $conn->query($resolved_sql);
?>

<?php include('../partials/sidebar.php'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Concerns</title>
    <link rel="stylesheet" href="../css/admin.css"> <!-- Link to your CSS -->
    <style>
        /* Tab navigation */
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

        /* Table styling */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }

        /* Status Color */
        .status-pending {
            color: red;
        }
        .status-resolved {
            color: green;
        }

        /* Search Bar */
        .search-bar {
            margin-bottom: 20px;
            padding: 5px;
            width: 100%;
            max-width: 300px;
        }
    </style>
</head>
<body>

<div class="dashboard-content">
    <h2>All Submitted Concerns</h2>

    <!-- Tab Navigation -->
    <div>
        <span class="tab active" id="pending-tab">Pending Concerns</span>
        <span class="tab" id="resolved-tab">Resolved Concerns</span>
    </div>

    <!-- Pending Concerns Tab Content -->
    <div class="tab-content active" id="pending-content">
        <h3>Pending Concerns</h3>
        <table>
            <thead>
                <tr>
                    <th>Student ID</th>
                    <th>Name</th>
                    <th>Date</th>
                    <th>Office</th>
                    <th>Course</th>
                    <th>Message</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $pending_result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['student_id']) ?></td>
                        <td><?= htmlspecialchars($row['firstname'] . ' ' . $row['lastname']) ?></td>
                        <td><?= htmlspecialchars($row['date']) ?></td>
                        <td><?= htmlspecialchars($row['office']) ?></td>
                        <td><?= htmlspecialchars($row['course']) ?></td>
                        <td><?= htmlspecialchars($row['message']) ?></td>
                        <td class="status-pending"><?= htmlspecialchars($row['status']) ?></td>
                        <td><?= htmlspecialchars($row['created_at']) ?></td>
                        <td>
                            <form method="POST" action="update-concern.php">
                                <input type="hidden" name="concern_id" value="<?= $row['id'] ?>">
                                <select name="status">
                                    <option value="Pending" <?= $row['status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
                                    <option value="Resolved" <?= $row['status'] == 'Resolved' ? 'selected' : '' ?>>Resolved</option>
                                </select>
                                <button type="submit">Update</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Resolved Concerns Tab Content -->
    <div class="tab-content" id="resolved-content">
        <h3>Resolved Concerns</h3>
        <table>
            <thead>
                <tr>
                    <th>Student ID</th>
                    <th>Name</th>
                    <th>Date</th>
                    <th>Office</th>
                    <th>Course</th>
                    <th>Message</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $resolved_result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['student_id']) ?></td>
                        <td><?= htmlspecialchars($row['firstname'] . ' ' . $row['lastname']) ?></td>
                        <td><?= htmlspecialchars($row['date']) ?></td>
                        <td><?= htmlspecialchars($row['office']) ?></td>
                        <td><?= htmlspecialchars($row['course']) ?></td>
                        <td><?= htmlspecialchars($row['message']) ?></td>
                        <td class="status-resolved"><?= htmlspecialchars($row['status']) ?></td>
                        <td><?= htmlspecialchars($row['created_at']) ?></td>
                        <td>
                            <form method="POST" action="update-concern.php">
                                <input type="hidden" name="concern_id" value="<?= $row['id'] ?>">
                                <select name="status">
                                    <option value="Pending" <?= $row['status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
                                    <option value="Resolved" <?= $row['status'] == 'Resolved' ? 'selected' : '' ?>>Resolved</option>
                                </select>
                                <button type="submit">Update</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

</div>

<script>
    // Tab switching functionality
    document.getElementById('pending-tab').onclick = function() {
        document.getElementById('pending-content').classList.add('active');
        document.getElementById('resolved-content').classList.remove('active');
        document.getElementById('pending-tab').classList.add('active');
        document.getElementById('resolved-tab').classList.remove('active');
    };

    document.getElementById('resolved-tab').onclick = function() {
        document.getElementById('resolved-content').classList.add('active');
        document.getElementById('pending-content').classList.remove('active');
        document.getElementById('resolved-tab').classList.add('active');
        document.getElementById('pending-tab').classList.remove('active');
    };
</script>

</body>
</html>

<?php
// Close the database connection
mysqli_close($conn);
?>

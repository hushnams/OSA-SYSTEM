<?php
// Include the constants.php file to access database and constants
include('../config/constants.php');

// Check if the form is submitted
if (isset($_POST['submit'])) {
    // Get form data
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Hash the password using password_hash (bcrypt by default)
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert data into the admin_account table
    $query = "INSERT INTO admin_account (username, password) VALUES ('$username', '$hashed_password')";
    $result = mysqli_query($conn, $query);

    // Check if the insertion was successful
    if ($result) {
        echo "<p style='color: green;'>Admin added successfully!</p>";
    } else {
        echo "<p style='color: red;'>Error: " . mysqli_error($conn) . "</p>";
    }
}

// Fetch the list of admins to display
$query = "SELECT * FROM admin_account";
$result = mysqli_query($conn, $query);

// Check if the query was successful
if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

$admins = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Admin</title>
    <link rel="stylesheet" href="../css/admin.css">
    <?php ini_set('display_errors', 1); error_reporting(E_ALL); ?> <!-- Enable error reporting -->
</head>
<body>

    <!-- Include Sidebar -->
    <?php include('../partials/sidebar.php'); ?>
    
    <div class="main-content">
        <div class="form-container">
            <h2>Add New Admin</h2>

            <form action="add-admin.php" method="POST">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required><br><br>

                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required><br><br>

                <button type="submit" name="submit">Add Admin</button>
            </form>
        </div>

        <div class="admins-list">
            <h2>Existing Admins</h2>

            <table>
                <thead>
                    <tr>
                        <th>Admin ID</th>
                        <th>Username</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($admins as $admin): ?>
                        <tr>
                            <td><?php echo $admin['admin_id']; ?></td>
                            <td><?php echo $admin['username']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>

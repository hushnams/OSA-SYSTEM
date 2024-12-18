<?php
include('../config/constants.php'); // Database connection

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type_name = mysqli_real_escape_string($conn, $_POST['type_name']);

    if (!empty($type_name)) {
        $sql = "INSERT INTO violation_type (type_name) VALUES ('$type_name')";
        if (mysqli_query($conn, $sql)) {
            $success_message = "Violation Type added successfully!";
        } else {
            $error_message = "Error: " . mysqli_error($conn);
        }
    } else {
        $error_message = "Type name cannot be empty!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Violation Type</title>
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>

<?php include('../partials/sidebar.php'); ?>

<div class="dashboard-content">
    <h2>Add Violation Type</h2>

    <?php if (isset($success_message)) echo "<p style='color:green;'>$success_message</p>"; ?>
    <?php if (isset($error_message)) echo "<p style='color:red;'>$error_message</p>"; ?>

    <form method="POST" action="">
        <label for="type_name">Violation Type Name:</label><br>
        <input type="text" name="type_name" id="type_name" required><br><br>
        <button type="submit" style="
            background-color: #4CAF50; 
            color: white; 
            padding: 10px 20px; 
            border: none; 
            cursor: pointer;
            border-radius: 5px;
        ">Add Violation Type</button>
    </form>
</div>

</body>
</html>

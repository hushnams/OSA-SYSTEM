<?php
include('../config/constants.php'); // Include your database connection

// Check if the user is logged in as admin
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Check if the form is submitted to update concern status
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $concern_id = $_POST['concern_id'];
    $status = $_POST['status'];

    // Update the concern status
    $sql = "UPDATE concern SET status = ? WHERE id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("si", $status, $concern_id);
        if ($stmt->execute()) {
            // Successful update, show message and redirect after 2 seconds
            echo "<script>
                    alert('Concern status updated successfully.');
                    window.location.href = 'view-concerns.php';
                  </script>";
        } else {
            echo "Error updating concern status: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

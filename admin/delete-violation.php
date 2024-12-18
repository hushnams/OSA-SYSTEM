<?php
include('../config/constants.php');

// Check for connection errors
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get the violation ID from the URL
if (isset($_GET['id'])) {
    $violation_id = $_GET['id'];

    // Prepare the DELETE query
    $delete_sql = "DELETE FROM violation WHERE id = ?";

    if ($stmt = $conn->prepare($delete_sql)) {
        // Bind the violation ID parameter
        $stmt->bind_param("i", $violation_id);

        // Execute the query
        if ($stmt->execute()) {
            // Redirect back to the violations page with a success message
            header("Location: view-violations.php?message=Violation deleted successfully");
        } else {
            // Error message
            echo "Error deleting violation: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    }
} else {
    echo "Violation ID not specified.";
}

// Close the database connection
mysqli_close($conn);
?>

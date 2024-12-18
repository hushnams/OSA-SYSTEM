<?php
session_start();
include('../config/constants.php');

// Ensure student_id is set in the session
if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_POST['op']) && isset($_POST['np']) && isset($_POST['c_np'])) {

    // Validate inputs
    function validate($data) {
        return htmlspecialchars(stripslashes(trim($data)));
    }

    $op = validate($_POST['op']);
    $np = validate($_POST['np']);
    $c_np = validate($_POST['c_np']);
    $id = $_SESSION['student_id'];

    // Password validation
    if (empty($op)) {
        header("Location: ch_password.php?error=Old Password is required");
        exit();
    } else if (empty($np)) {
        header("Location: ch_password.php?error=New Password is required");
        exit();
    } else if (strlen($np) < 8) {
        header("Location: ch_password.php?error=New password must be at least 8 characters long");
        exit();
    } else if (!preg_match('/[A-Z]/', $np) || !preg_match('/[a-z]/', $np) || !preg_match('/[0-9]/', $np)) {
        header("Location: ch_password.php?error=Password must include uppercase, lowercase, and a number");
        exit();
    } else if ($np !== $c_np) {
        header("Location: ch_password.php?error=The confirmation password does not match");
        exit();
    }

    // Check old password
    $stmt = $conn->prepare("SELECT password FROM student_account WHERE student_id = ?");
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        $hashed_password = $row['password'];

        // Verify the old password
        if (password_verify($op, $hashed_password)) {
            // Hash the new password
            $new_hashed_password = password_hash($np, PASSWORD_DEFAULT);

            // Update the password in the database
            $stmt = $conn->prepare("UPDATE student_account SET password = ? WHERE student_id = ?");
            $stmt->bind_param("ss", $new_hashed_password, $id);
            $stmt->execute();

            header("Location: ch_password.php?success=Your password has been changed successfully");
            exit();
        } else {
            header("Location: ch_password.php?error=Incorrect old password");
            exit();
        }
    } else {
        header("Location: ch_password.php?error=User not found");
        exit();
    }
}
?>

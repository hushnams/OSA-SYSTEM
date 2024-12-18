<?php
// Include constants.php to access SITEURL
include('../config/constants.php');

// Start the session
session_start();

// Destroy the session
session_destroy();

// Redirect to the login page
header('Location: ' . SITEURL . 'admin/admin-login.php');
exit();
?>

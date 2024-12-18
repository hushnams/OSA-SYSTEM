<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <link rel="stylesheet" href="/css/user.css"> <!-- Link to your CSS file -->
    <script>
        // Function to toggle password visibility
        function togglePassword(id) {
            var x = document.getElementById(id);
            if (x.type === "password") {
                x.type = "text";
            } else {
                x.type = "password";
            }
        }
    </script>
</head>
<body>

    <!-- Sidebar -->
    <?php include('../partials/sidenav.php');?>

    <!-- Main Content -->
    <form action="ch_pass.php" method="post">
        <h2>Change Password</h2>
        
        <?php if (isset($_GET['error'])) { ?>
            <p class="error"><?php echo htmlspecialchars($_GET['error']); ?></p>
        <?php } ?>

        <?php if (isset($_GET['success'])) { ?>
            <p class="success"><?php echo htmlspecialchars($_GET['success']); ?></p>
        <?php } ?>

        <label>Old Password</label>
        <input type="password" name="op" id="old_password" placeholder="Old Password" required>
        <button type="button" onclick="togglePassword('old_password')">Show</button>
        <br>

        <label>New Password</label>
        <input type="password" name="np" id="new_password" placeholder="New Password" required>
        <button type="button" onclick="togglePassword('new_password')">Show</button>
        <br>

        <label>Confirm New Password</label>
        <input type="password" name="c_np" id="confirm_new_password" placeholder="Confirm New Password" required>
        <button type="button" onclick="togglePassword('confirm_new_password')">Show</button>
        <br>

        <button type="submit">CHANGE</button>
        <a href="welcome.php" class="ca">HOME</a>
    </form>

</body>
</html>

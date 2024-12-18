<?php
include('../config/constants.php');

// Initialize $student as an empty array
$student = [
    'id' => '',
    'firstname' => '',
    'lastname' => '',
    'middlename' => '',
    'course' => '',
    'section' => '',
    'password' => ''
];

if (isset($_GET['id'])) {
    $id = $_GET['id']; // Use 'id' from the URL parameter

    // Fetch student data
    $stmt = $conn->prepare("SELECT * FROM student_account WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $student = $result->fetch_assoc();

    if (!$student) {
        echo "Student not found!";
        exit;
    }
} else {
    echo "No student ID provided!";
    exit;
}

// Update student data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $middlename = $_POST['middlename'];
    $course = $_POST['course'];
    $section = $_POST['section'];
    $password = $_POST['password']; // Plain text password from the form

    // Hash the password before storing it in the database
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Update query
    $stmt = $conn->prepare("UPDATE student_account SET firstname = ?, lastname = ?, middlename = ?, course = ?, section = ?, password = ? WHERE id = ?");
    $stmt->bind_param("ssssssi", $firstname, $lastname, $middlename, $course, $section, $hashedPassword, $id);

    if ($stmt->execute()) {
        echo "Student data updated successfully!";
        header("Location: view-students.php");
        exit;
    } else {
        echo "Error updating data: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student</title>
    <style>
        /* General form styling */
        form {
            width: 60%;
            margin: 50px auto;
            padding: 30px;
            background-color: #f9f9f9;
            border-radius: 10px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
            font-family: 'Arial', sans-serif;
            color: #333;
        }

        /* Heading */
        h2 {
            text-align: center;
            font-size: 2rem;
            color: #4CAF50;
            margin-bottom: 30px;
            font-family: 'Arial', sans-serif;
        }

        /* Label styling */
        label {
            display: block;
            margin-bottom: 10px;
            font-size: 1.1em;
            color: #555;
        }

        /* Input fields styling */
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 2px solid #ccc;
            border-radius: 8px;
            font-size: 1em;
            background-color: #f1f1f1;
            box-sizing: border-box;
            transition: border 0.3s ease-in-out;
        }

        input[type="text"]:focus, input[type="password"]:focus {
            border-color: #4CAF50;
            outline: none;
            background-color: #fff;
        }

        /* Submit Button Styling */
        button {
            padding: 12px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 1.2em;
            border-radius: 8px;
            width: 100%;
            transition: background-color 0.3s ease-in-out;
        }

        button:hover {
            background-color: #45a049;
        }

        /* Back Button Styling */
        .back-btn {
            display: inline-block;
            padding: 12px 20px;
            background-color: #f44336;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-size: 1.1em;
            margin-top: 20px;
            transition: background-color 0.3s ease;
            text-align: center;
            width: 100%;
        }

        .back-btn:hover {
            background-color: #e53935;
        }

        /* Button container */
        .button-container {
            display: flex;
            flex-direction: column;
            gap: 15px;
            margin-top: 20px;
        }

        /* Responsive Design */
        @media screen and (max-width: 768px) {
            form {
                width: 90%;
            }

            .button-container {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <h2>Edit Student</h2>
    <form method="POST" action="">
        <label for="firstname">First Name:</label>
        <input type="text" name="firstname" value="<?php echo htmlspecialchars($student['firstname']); ?>" required><br>

        <label for="lastname">Last Name:</label>
        <input type="text" name="lastname" value="<?php echo htmlspecialchars($student['lastname']); ?>" required><br>

        <label for="middlename">Middle Name:</label>
        <input type="text" name="middlename" value="<?php echo htmlspecialchars($student['middlename']); ?>"><br>

        <label for="course">Course:</label>
        <input type="text" name="course" value="<?php echo htmlspecialchars($student['course']); ?>"><br>

        <label for="section">Section:</label>
        <input type="text" name="section" value="<?php echo htmlspecialchars($student['section']); ?>"><br>

        <label for="password">Password:</label>
        <input type="password" name="password" placeholder="Enter new password" required><br>

        <div class="button-container">
            <button type="submit">Update</button>
            <a href="view-students.php" class="back-btn">Back</a>
        </div>
    </form>
</body>
</html>

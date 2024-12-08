<?php
include('config/constants.php');

// Check if the form is submitted
if (isset($_POST['submit'])) {
    // Get the form data
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // SQL query to insert the user into the database
    $sql = "INSERT INTO tbl_users (username, password, email) VALUES (?, ?, ?)";

    // Prepare the statement
    if ($stmt = $conn->prepare($sql)) {
        // Bind parameters
        $stmt->bind_param("sss", $username, $hashed_password, $email);

        // Execute the statement
        if ($stmt->execute()) {
            // Registration successful
            $_SESSION['register'] = "<div class='success'>Registration successful! You can now log in.</div>";
            header('location:'.SITEURL.'login.php');
            exit();
        } else {
            // Registration failed
            $_SESSION['register'] = "<div class='error'>Registration failed. Please try again.</div>";
            header('location:'.SITEURL.'register.php');
            exit();
        }

        // Close the statement
        $stmt->close();
    } else {
        // Handle SQL preparation error
        $_SESSION['register'] = "<div class='error'>Error preparing statement: " . $conn->error . "</div>";
        header('location:'.SITEURL.'register.php');
        exit();
    }
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h1>Register</h1>
        <form action="" method="POST">
            <label for="username">Username:</label>
            <input type="text" name="username" required>
            <label for="password">Password:</label>
            <input type="password" name="password" required>
            <label for="email">Email:</label>
            <input type="email" name="email" required>
            <input type="submit" name="submit" value="Register">
        </form>
        <p>Already have an account? <a href="login.php">Login here</a></p>
    </div>
</body>
</html>
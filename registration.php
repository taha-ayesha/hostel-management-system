<?php
session_start();

include('includes/db.php');

if(isset($_POST['register'])){
    // Sanitize and validate user input
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];  // Get password from form
    $role = $_POST['role'];
    
    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // Insert the user data into the database with the hashed password
    $sql = "INSERT INTO User (Name, Email, Password, Role) VALUES ('$name', '$email', '$hashed_password', '$role')";
    
    if ($conn->query($sql) === TRUE) {
        echo "Registration successful!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Registration</title>
</head>
<body>
    <h2>Registration</h2>
    <form method="post" action="">
        <input type="text" name="name" placeholder="Name" required><br>
        <input type="email" name="email" placeholder="Email" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <select name="role">
            <option value="Student">Student</option>
            <option value="Employee">Employee</option>
        </select><br>
        <button type="submit" name="register">Register</button>
    </form>
</body>
</html>

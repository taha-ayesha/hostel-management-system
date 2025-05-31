<?php
session_start();
include('includes/db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    // Insert into User table
    $sql = "INSERT INTO User (Name, Email, Password, Role) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $name, $email, $password, $role);

    if ($stmt->execute()) {
        $user_id = $stmt->insert_id;
        $_SESSION['user_id'] = $user_id;
        $_SESSION['role'] = $role;

        if ($role === 'Student') {
            $age = $_POST['age'];
            $gender = $_POST['gender'];
            $contact = $_POST['contact'];
            $address = $_POST['student_address'];

            $student_sql = "INSERT INTO Student (Name, Age, Gender, Contact, Address, User_ID) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt2 = $conn->prepare($student_sql);
            $stmt2->bind_param("sisssi", $name, $age, $gender, $contact, $address, $user_id);
            $stmt2->execute();
        } elseif ($role === 'Employee') {
            $salary = $_POST['salary'];
            $address = $_POST['employee_address'];

            $employee_sql = "INSERT INTO Employee (Name, Salary, Address, Role, User_ID) VALUES (?, ?, ?, ?, ?)";
            $stmt3 = $conn->prepare($employee_sql);
            $stmt3->bind_param("sdssi", $name, $salary, $address, $role, $user_id);
            $stmt3->execute();
        }

        header("Location: login.php");
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Signup - Hostel Management System</title>
    <link rel="stylesheet" href="css/style.css">
    <script>
        function toggleRoleFields() {
            var role = document.getElementById("role").value;
            document.getElementById("studentFields").style.display = (role === "Student") ? "block" : "none";
            document.getElementById("employeeFields").style.display = (role === "Employee") ? "block" : "none";
        }
    </script>
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="login.php">Login</a></li>
                <li><a href="signup.php">Sign Up</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <h1>Signup for Hostel Management System</h1>
        <form action="signup.php" method="POST">
            <label for="name">Name:</label>
            <input type="text" name="name" required>

            <label for="email">Email:</label>
            <input type="email" name="email" required>

            <label for="password">Password:</label>
            <input type="password" name="password" required>

            <label for="role">Select Role:</label>
            <select name="role" id="role" onchange="toggleRoleFields()" required>
                <option value="">--Select--</option>
                <option value="Student">Student</option>
                <option value="Employee">Employee</option>
            </select>

            <div id="studentFields" style="display: none;">
                <h3>Student Details</h3>
                <label for="age">Age:</label>
                <input type="number" name="age">

                <label for="gender">Gender:</label>
                <input type="text" name="gender">

                <label for="contact">Contact:</label>
                <input type="text" name="contact">

                <label for="student_address">Address:</label>
                <textarea name="student_address"></textarea>
            </div>

            <div id="employeeFields" style="display: none;">
                <h3>Employee Details</h3>
                <label for="salary">Salary:</label>
                <input type="number" step="0.01" name="salary">

                <label for="employee_address">Address:</label>
                <textarea name="employee_address"></textarea>
            </div>

            <button type="submit">Sign Up</button>
        </form>
    </main>
</body>
</html>

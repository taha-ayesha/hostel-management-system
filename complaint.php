<?php
include('includes/db.php');
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Handle complaint submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $description = $_POST['description'];
    $status = 'Pending';

    // First, get the Student_ID for the logged-in user
    $student_id = null;
    $stmt = $conn->prepare("SELECT Student_ID FROM student WHERE User_ID = ?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $stmt->bind_result($student_id);
    $stmt->fetch();
    $stmt->close();

    if ($student_id) {
        // Now insert the complaint
        $stmt = $conn->prepare("INSERT INTO Complaint (Student_ID, Description, Status) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $student_id, $description, $status);
        if ($stmt->execute()) {
            echo "Complaint submitted successfully.";
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error: Student not found for this user.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Submit Complaint</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include('header.php'); ?>
    <h2>Submit Complaint</h2>
    <form method="POST">
        <label for="description">Complaint Description:</label>
        <textarea id="description" name="description" required></textarea>
        <br>
        <button type="submit">Submit Complaint</button>
    </form>
    <?php include('footer.php'); ?>
</body>
</html>

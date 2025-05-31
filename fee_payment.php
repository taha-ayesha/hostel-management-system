<?php
session_start();
include('includes/db.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$role = $_SESSION['role'];
$user_id = $_SESSION['user_id'];

echo "<h1>Fee Payment Details</h1>";

if ($role == 'student') {
    $sql = "SELECT * FROM Fee WHERE Student_ID = (
                SELECT Student_ID FROM Student WHERE User_ID = ?
            )";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "Amount: ₹" . $row['Amount'] . " | Due Date: " . $row['Due_Date'] . " | Status: " . $row['Status'] . "<br>";
        }
    } else {
        echo "No fee details available.";
    }

} elseif ($role == 'employee') {
    // Form submission logic for employee to add new fee entry
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_fee'])) {
        $student_id = $_POST['student_id'];
        $amount = $_POST['amount'];
        $due_date = $_POST['due_date'];
        $status = $_POST['status'];

        $stmt = $conn->prepare("INSERT INTO Fee (Student_ID, Amount, Due_Date, Status) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("idss", $student_id, $amount, $due_date, $status);

        if ($stmt->execute()) {
            echo "<p style='color: green;'>Fee record added successfully.</p>";
        } else {
            echo "<p style='color: red;'>Error: " . $conn->error . "</p>";
        }
    }

    // Display fee records
    $sql = "SELECT s.Name AS StudentName, f.Amount, f.Due_Date, f.Status 
            FROM Fee f 
            JOIN Student s ON f.Student_ID = s.Student_ID";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<table border='1'>
                <tr>
                    <th>Student Name</th>
                    <th>Amount</th>
                    <th>Due Date</th>
                    <th>Status</th>
                </tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['StudentName']}</td>
                    <td>₹{$row['Amount']}</td>
                    <td>{$row['Due_Date']}</td>
                    <td>{$row['Status']}</td>
                  </tr>";
        }
        echo "</table><br>";
    } else {
        echo "No fee details available.";
    }

    // Display add form
    echo "<h3>Add New Fee Entry</h3>";
    echo "<form method='POST'>
            <label>Student:</label>
            <select name='student_id' required>";
    $students = $conn->query("SELECT Student_ID, Name FROM Student");
    while ($student = $students->fetch_assoc()) {
        echo "<option value='{$student['Student_ID']}'>{$student['Name']} (ID: {$student['Student_ID']})</option>";
    }
    echo "  </select><br><br>
            <label>Amount:</label>
            <input type='number' step='0.01' name='amount' required><br><br>
            <label>Due Date:</label>
            <input type='date' name='due_date' required><br><br>
            <label>Status:</label>
            <select name='status'>
                <option value='Paid'>Paid</option>
                <option value='Unpaid'>Unpaid</option>
            </select><br><br>
            <button type='submit' name='add_fee'>Add Fee</button>
          </form>";
}
?>

<?php
include('includes/db.php');
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'employee') {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_id = $_POST['student_id'];
    $month = $_POST['month'];
    $amount_paid = $_POST['amount_paid'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("INSERT INTO Food_Charge (Student_ID, Month, Amount_Paid, Status) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isds", $student_id, $month, $amount_paid, $status);
    $stmt->execute();
}
$result = $conn->query("SELECT * FROM Food_Charge");
?>

<h2>Food Charges</h2>
<form method="POST">
    Student ID: <input type="number" name="student_id" required>
    Month: <input type="text" name="month" required>
    Amount Paid: <input type="number" step="0.01" name="amount_paid" required>
    Status: <input type="text" name="status" required>
    <button type="submit">Add Food Charge</button>
</form>

<table border="1">
<tr><th>ID</th><th>Student ID</th><th>Month</th><th>Amount</th><th>Status</th></tr>
<?php while ($row = $result->fetch_assoc()): ?>
<tr>
    <td><?= $row['Food_ID'] ?></td>
    <td><?= $row['Student_ID'] ?></td>
    <td><?= $row['Month'] ?></td>
    <td><?= $row['Amount_Paid'] ?></td>
    <td><?= $row['Status'] ?></td>
</tr>
<?php endwhile; ?>
</table>

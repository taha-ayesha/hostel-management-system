<?php
session_start();
include('includes/db.php');

// Redirect to login if not logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    header('Location: login.php');
    exit;
}

$role = $_SESSION['role'];
$user_id = $_SESSION['user_id'];

// Handle status update (only for employees)
if ($role == 'employee' && isset($_POST['update_status'])) {
    $complaint_id = $_POST['complaint_id'];
    $new_status = $_POST['status'];

    $update_sql = "UPDATE Complaint SET Status = ? WHERE Complaint_ID = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("si", $new_status, $complaint_id);
    $stmt->execute();
    header("Location: view_complaints.php");
    exit;
}

// Fetch complaints based on role
if ($role == 'employee') {
    $sql = "SELECT c.Complaint_ID, s.Name AS Student_Name, c.Description, c.Status
            FROM Complaint c
            JOIN Student s ON c.Student_ID = s.Student_ID";
    $result = $conn->query($sql);
} elseif ($role == 'student') {
    $sql = "SELECT c.Complaint_ID, c.Description, c.Status
            FROM Complaint c
            JOIN Student s ON c.Student_ID = s.Student_ID
            WHERE s.User_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Complaint Status</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php include('header.php'); ?>
<h2><?php echo ($role == 'employee') ? 'All Complaints' : 'Your Complaints'; ?></h2>

<?php if ($result && $result->num_rows > 0): ?>
    <table border="1" cellpadding="10">
        <tr>
            <th>Complaint ID</th>
            <?php if ($role == 'employee'): ?>
                <th>Student Name</th>
            <?php endif; ?>
            <th>Description</th>
            <th>Status</th>
            <?php if ($role == 'employee'): ?>
                <th>Update Status</th>
            <?php endif; ?>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['Complaint_ID']); ?></td>
                <?php if ($role == 'employee'): ?>
                    <td><?php echo htmlspecialchars($row['Student_Name']); ?></td>
                <?php endif; ?>
                <td><?php echo htmlspecialchars($row['Description']); ?></td>
                <td><?php echo htmlspecialchars($row['Status']); ?></td>
                <?php if ($role == 'employee'): ?>
                    <td>
                        <form method="POST" action="view_complaints.php">
                            <input type="hidden" name="complaint_id" value="<?php echo $row['Complaint_ID']; ?>">
                            <select name="status">
                                <option value="Pending" <?php echo ($row['Status'] == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                                <option value="In Progress" <?php echo ($row['Status'] == 'In Progress') ? 'selected' : ''; ?>>In Progress</option>
                                <option value="Resolved" <?php echo ($row['Status'] == 'Resolved') ? 'selected' : ''; ?>>Resolved</option>
                            </select>
                            <input type="submit" name="update_status" value="Update">
                        </form>
                    </td>
                <?php endif; ?>
            </tr>
        <?php endwhile; ?>
    </table>
<?php else: ?>
    <p>No complaints found.</p>
<?php endif; ?>

<?php include('footer.php'); ?>
</body>
</html>

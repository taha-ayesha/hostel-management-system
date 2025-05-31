<?php
session_start();
include('includes/db.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$role = strtolower($_SESSION['role']); // Normalize for consistency

// Fetch user details
$sql = "SELECT * FROM User WHERE User_ID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Hostel Management System</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .button-group {
            margin-top: 20px;
        }
        .button-group a {
            display: inline-block;
            margin: 10px;
            padding: 10px 20px;
            background-color: #0077cc;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
        }
        .button-group a:hover {
            background-color: #005fa3;
        }
    </style>
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><strong>Logged in as: <?php echo htmlspecialchars($user['Name']) . " (" . ucfirst($role) . ")"; ?></strong></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <h1>Welcome to the Hostel Management Dashboard</h1>

        <div class="button-group">
            <?php if ($role == 'student'): ?>
                <a href="view_allocation.php">View Room Allocation</a>
                <a href="fee_payment.php">View Fee Payment</a>
                <a href="food_menu.php">View Food Menu</a>
                <a href="complaint.php">Submit Complaint</a>
                <a href="view_complaints.php">View Complaint Status</a>
            <?php elseif ($role == 'employee'): ?>
                <a href="allocate_room.php">Allocate Room</a>
                <a href="view_allocation.php">View Room Allocations</a>
                <a href="fee_payment.php">Manage Fee Payments</a>
                <a href="food_charge.php">Manage Food Charges</a>
                <a href="grocery_expense.php">Manage Grocery Expenses</a>
                <a href="food_menu.php">Update Food Menu</a>
                <a href="view_complaints.php">View Complaints</a>
                <a href="hostel_details.php">Hostel Details</a>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>

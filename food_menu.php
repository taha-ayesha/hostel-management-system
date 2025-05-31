<?php
session_start();
include('includes/db.php');

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$role = $_SESSION['role'];

// Handle form submission for adding new food menu item
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_menu_item']) && strtolower($role) == 'employee') {
    $day_of_week = trim($_POST['day_of_week']);
    $meal_type = trim($_POST['meal_type']);
    $item_name = trim($_POST['item_name']);
    $hostel_id = (int) $_POST['hostel_id'];

    // Check if the item already exists for the same day, meal, and hostel
    $checkSql = "SELECT * FROM Food_Menu WHERE Day_of_Week = ? AND Meal_Type = ? AND Hostel_ID = ?";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param("ssi", $day_of_week, $meal_type, $hostel_id);
    $checkStmt->execute();
    $resultCheck = $checkStmt->get_result();

    if ($resultCheck->num_rows > 0) {
        $message = "A menu item for this day, meal type, and hostel already exists.";
    } else {
        // Insert the new food menu item
        $insertSql = "INSERT INTO Food_Menu (Day_of_Week, Meal_Type, Item_Name, Hostel_ID) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($insertSql);
        $stmt->bind_param("sssi", $day_of_week, $meal_type, $item_name, $hostel_id);

        if ($stmt->execute()) {
            $message = "New food menu item added successfully.";
        } else {
            $message = "Error adding food menu item: " . $conn->error;
        }
    }
}

// Fetch all food menu items
$sql = "SELECT * FROM Food_Menu";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Food Menu</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <h1>Food Menu</h1>

        <?php if (isset($message)): ?>
            <p style="color: green;"><strong><?= htmlspecialchars($message) ?></strong></p>
        <?php endif; ?>

        <table border="1">
            <tr><th>Day of the Week</th><th>Meal Type</th><th>Item Name</th><th>Hostel ID</th></tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['Day_of_Week']) ?></td>
                    <td><?= htmlspecialchars($row['Meal_Type']) ?></td>
                    <td><?= htmlspecialchars($row['Item_Name']) ?></td>
                    <td><?= htmlspecialchars($row['Hostel_ID']) ?></td>
                </tr>
            <?php endwhile; ?>
        </table>

        <?php if (strtolower($role) == 'employee'): ?>
            <h2>Add New Menu Item</h2>
            <form method="POST" action="food_menu.php">
                <label>Day of Week:</label>
                <input type="text" name="day_of_week" required><br>
                <label>Meal Type:</label>
                <input type="text" name="meal_type" required><br>
                <label>Item Name:</label>
                <input type="text" name="item_name" required><br>
                <label>Hostel ID:</label>
                <input type="number" name="hostel_id" required><br>
                <button type="submit" name="add_menu_item">Add Menu Item</button>
            </form>
        <?php endif; ?>
    </main>
</body>
</html>

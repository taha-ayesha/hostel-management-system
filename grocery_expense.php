<?php
include('includes/db.php');
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'employee') {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $item = $_POST['item_name'];
    $qty = $_POST['quantity'];
    $cost = $_POST['cost'];
    $total = $qty * $cost;
    $date = $_POST['purchase_date'];
    $hostel_id = $_POST['hostel_id'];

    $stmt = $conn->prepare("INSERT INTO Grocery_Expense (Item_Name, Quantity, Cost, Total_Cost, Purchase_Date, Hostel_ID) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("siddsi", $item, $qty, $cost, $total, $date, $hostel_id);
    $stmt->execute();
}
$result = $conn->query("SELECT * FROM Grocery_Expense");
?>

<h2>Grocery Expenses</h2>
<form method="POST">
    Item: <input type="text" name="item_name" required>
    Quantity: <input type="number" name="quantity" required>
    Cost per unit: <input type="number" step="0.01" name="cost" required>
    Date: <input type="date" name="purchase_date" required>
    Hostel ID: <input type="number" name="hostel_id" required>
    <button type="submit">Add Expense</button>
</form>

<table border="1">
<tr><th>ID</th><th>Item</th><th>Qty</th><th>Cost</th><th>Total</th><th>Date</th><th>Hostel ID</th></tr>
<?php while ($row = $result->fetch_assoc()): ?>
<tr>
    <td><?= $row['Expense_ID'] ?></td>
    <td><?= $row['Item_Name'] ?></td>
    <td><?= $row['Quantity'] ?></td>
    <td><?= $row['Cost'] ?></td>
    <td><?= $row['Total_Cost'] ?></td>
    <td><?= $row['Purchase_Date'] ?></td>
    <td><?= $row['Hostel_ID'] ?></td>
</tr>
<?php endwhile; ?>
</table>

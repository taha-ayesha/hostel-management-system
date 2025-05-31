<?php
include('includes/db.php');
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'employee') {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $address = $_POST['address'];
    $rooms = $_POST['rooms'];
    $students = $_POST['students'];

    $stmt = $conn->prepare("INSERT INTO Hostel (Name, Address, No_of_Rooms, No_of_Students) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssii", $name, $address, $rooms, $students);
    $stmt->execute();
}
$result = $conn->query("SELECT * FROM Hostel");
?>

<h2>Hostel Details</h2>
<form method="POST">
    Name: <input type="text" name="name" required>
    Address: <input type="text" name="address" required>
    No. of Rooms: <input type="number" name="rooms" required>
    No. of Students: <input type="number" name="students" required>
    <button type="submit">Add Hostel</button>
</form>

<table border="1">
<tr><th>ID</th><th>Name</th><th>Address</th><th>Rooms</th><th>Students</th></tr>
<?php while ($row = $result->fetch_assoc()): ?>
<tr>
    <td><?= $row['Hostel_ID'] ?></td>
    <td><?= $row['Name'] ?></td>
    <td><?= $row['Address'] ?></td>
    <td><?= $row['No_of_Rooms'] ?></td>
    <td><?= $row['No_of_Students'] ?></td>
</tr>
<?php endwhile; ?>
</table>

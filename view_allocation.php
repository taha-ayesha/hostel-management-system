<?php
session_start();
include('includes/db.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$role = $_SESSION['role'];
$user_id = $_SESSION['user_id'];

echo "<h1>Room Allocation</h1>";

if ($role == 'student') {
    $sql = "SELECT a.*, r.Room_ID, r.Room_Type FROM Allocation a 
            JOIN Room r ON a.Room_ID = r.Room_ID 
            WHERE a.Student_ID = (
                SELECT Student_ID FROM Student WHERE User_ID = ?
            )";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "Allocated Room ID: " . $row['Room_ID'] . "<br>";
            echo "Room Type: " . $row['Room_Type'] . "<br>";
            echo "Allocation Date: " . $row['Allocation_Date'] . "<br>";
            echo "Status: " . $row['Status'] . "<br><hr>";
        }
    } else {
        echo "You have not been allocated a room.";
    }

} elseif ($role == 'employee') {
    $sql = "SELECT s.Name AS StudentName, r.Room_ID, r.Room_Type, a.Allocation_Date, a.Status 
            FROM Allocation a
            JOIN Student s ON a.Student_ID = s.Student_ID
            JOIN Room r ON a.Room_ID = r.Room_ID";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<table border='1'>
                <tr>
                    <th>Student Name</th>
                    <th>Room ID</th>
                    <th>Room Type</th>
                    <th>Allocation Date</th>
                    <th>Status</th>
                </tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['StudentName']}</td>
                    <td>{$row['Room_ID']}</td>
                    <td>{$row['Room_Type']}</td>
                    <td>{$row['Allocation_Date']}</td>
                    <td>{$row['Status']}</td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "No allocations found.";
    }
}
?>

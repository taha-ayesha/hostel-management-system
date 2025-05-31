<?php
// Start the session at the top of the file
session_start();

include('header.php');
include('includes/db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_id = $_POST['student_id'];
    $room_id = $_POST['room_id'];
    $allocation_date = date("Y-m-d");

    // Insert the allocation into the Allocation table
    $sql = "INSERT INTO Allocation (Student_ID, Room_ID, Allocation_Date, Status) VALUES ('$student_id', '$room_id', '$allocation_date', 'Allocated')";
    if ($conn->query($sql) === TRUE) {
        echo "Room allocated successfully!";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<div class="container">
    <h2>Allocate Room</h2>
    <form action="allocate_room.php" method="POST">
        <input type="text" name="student_id" placeholder="Student ID" required>
        <input type="text" name="room_id" placeholder="Room ID" required>
        <button type="submit">Allocate Room</button>
    </form>
</div>

<?php
include('footer.php');
?>

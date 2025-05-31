<header>
    <nav>
        <ul>
            <li><a href="dashboard.php">Dashboard</a></li>

            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'Student'): ?>
                <li><a href="view_allocation.php">My Room</a></li>
                <li><a href="fee_payment.php">Fees</a></li>
                <li><a href="food_charge.php">Food Charges</a></li>
                <li><a href="complaint.php">Submit Complaint</a></li>

            <?php elseif (isset($_SESSION['role']) && in_array($_SESSION['role'], ['Employee', 'Admin'])): ?>
                <li><a href="allocate_room.php">Room Allocation</a></li>
                <li><a href="view_complaints.php">View Complaints</a></li>
                <li><a href="grocery_expense.php">Grocery Expenses</a></li>
                <li><a href="hostel_details.php">Hostel Details</a></li>
                <li><a href="food_charge.php">Food Charges</a></li>
                <li><a href="food_menu.php">Food Menu</a></li>
            <?php endif; ?>

            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>
</header>

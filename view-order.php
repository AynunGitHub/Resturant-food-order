<?php include('partials-front/menu.php'); 

// Check if user is logged in
if(!isset($_SESSION['user'])) {
    $_SESSION['login'] = "<div class='error'>Please login to view your orders.</div>";
    header('location:'.SITEURL.'login.php');
    exit();
}

// Check if user_id is set
if (!isset($_SESSION['user_id'])) {
    // Handle the error, e.g., redirect to login page
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// SQL query to get orders for the logged-in user
$sql = "SELECT * FROM tbl_order WHERE customer_id = ? ORDER BY id DESC"; // Ensure 'tbl_order' is the correct table name

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id); // Assuming customer_id is an integer
$stmt->execute();
$res = $stmt->get_result();
$count = $res->num_rows;
$sn = 1;

// Debugging: Check if user_id is set correctly
if (!isset($_SESSION['user_id'])) {
    echo "<div class='error'>User ID is not set in the session.</div>";
    exit();
} else {
    echo "<div class='success'>User ID: " . $_SESSION['user_id'] . "</div>"; // Debugging output
}
?>

<div class="main-content">
    <div class="wrapper">
        <h1>My Orders</h1>

        <table class="tbl-full">
            <tr>
                <th>S.N.</th>
                <th>Food</th>
                <th>Price</th>
                <th>Qty.</th>
                <th>Total</th>
                <th>Order Date</th>
                <th>Status</th>
                <th>Name</th>
                <th>Contact</th>
                <th>Email</th>
                <th>Address</th>
            </tr>

            <?php 
            if($count > 0) {
                while($row = $res->fetch_assoc()) {
                    ?>
                    <tr>
                        <td><?php echo $sn++; ?>. </td>
                        <td><?php echo $row['food']; ?></td>
                        <td>$<?php echo $row['price']; ?></td>
                        <td><?php echo $row['qty']; ?></td>
                        <td>$<?php echo $row['total']; ?></td>
                        <td><?php echo $row['order_date']; ?></td>
                        <td>
                            <?php 
                            // Color coding for status
                            if($row['status'] == "Ordered") {
                                echo "<label style='color: blue;'>".$row['status']."</label>";
                            } elseif($row['status'] == "On Delivery") {
                                echo "<label style='color: orange;'>".$row['status']."</label>";
                            } elseif($row['status'] == "Delivered") {
                                echo "<label style='color: green;'>".$row['status']."</label>";
                            } elseif($row['status'] == "Cancelled") {
                                echo "<label style='color: red;'>".$row['status']."</label>";
                            }
                            ?>
                        </td>
                        <td><?php echo $row['customer_name']; ?></td>
                        <td><?php echo $row['customer_contact']; ?></td>
                        <td><?php echo $row['customer_email']; ?></td>
                        <td><?php echo $row['customer_address']; ?></td>
                    </tr>
                    <?php
                }
            } else {
                ?>
                <tr>
                    <td colspan="11" class="error">No Orders Found</td>
                </tr>
                <?php
            }
            ?>
        </table>
    </div>
</div>

<?php include('partials-front/footer.php'); ?>
</html>

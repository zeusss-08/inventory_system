<?php
session_start(); // start session

// Access control: only logged-in admins can view
if(!isset($_SESSION['admin_email'])){
    header("Location: admin_login.php"); // redirect if not logged in
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
</head>
<body>
<h1>Welcome, Admin!</h1>

<!-- Navigation links to other pages -->
<ul>
    <li><a href="suppliers.php">Suppliers</a></li>
    <li><a href="products.php">Products</a></li>
    <li><a href="warehouse.php">Warehouse</a></li>
    <li><a href="purchase_order.php">Purchase Orders</a></li>
    <li><a href="receiving.php">Receiving</a></li>
    <li><a href="inventory.php">Inventory</a></li>
    <li><a href="logout.php">Logout</a></li>
</ul>

</body>
</html>
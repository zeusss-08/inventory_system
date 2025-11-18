<?php
session_start();
include 'db_connection.php';

// Access control
if(!isset($_SESSION['admin_email'])){
    header("Location: admin_login.php");
    exit();
}

// Add Purchase Order
if(isset($_POST['add_order'])){
    $supplier_id = $_POST['supplier_id'];
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    $date = date('Y-m-d'); // current date

    $query = "INSERT INTO purchase_order (supplier_id, product_id, quantity, order_date) 
              VALUES ('$supplier_id', '$product_id', '$quantity', '$date')";
    mysqli_query($conn, $query);
    header("Location: purchase_order.php");
}

// Delete Purchase Order
if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    $query = "DELETE FROM purchase_order WHERE id='$id'";
    mysqli_query($conn, $query);
    header("Location: purchase_order.php");
}

// Fetch all purchase orders
$orders = mysqli_query($conn, "SELECT po.*, s.name AS supplier_name, p.name AS product_name 
                               FROM purchase_order po
                               JOIN suppliers s ON po.supplier_id = s.id
                               JOIN products p ON po.product_id = p.id");

// Fetch suppliers and products for dropdown
$suppliers = mysqli_query($conn, "SELECT * FROM suppliers");
$products = mysqli_query($conn, "SELECT * FROM products");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Purchase Orders</title>
</head>
<body>
<h1>Purchase Orders Management</h1>

<!-- Add Purchase Order Form -->
<form method="POST">
    <select name="supplier_id" required>
        <option value="">Select Supplier</option>
        <?php while($s = mysqli_fetch_assoc($suppliers)){ ?>
            <option value="<?= $s['id'] ?>"><?= $s['name'] ?></option>
        <?php } ?>
    </select>

    <select name="product_id" required>
        <option value="">Select Product</option>
        <?php while($p = mysqli_fetch_assoc($products)){ ?>
            <option value="<?= $p['id'] ?>"><?= $p['name'] ?></option>
        <?php } ?>
    </select>

    <input type="number" name="quantity" placeholder="Quantity" required>
    <button type="submit" name="add_order">Add Order</button>
</form>

<hr>

<!-- Purchase Orders Table -->
<table border="1">
    <tr>
        <th>ID</th>
        <th>Supplier</th>
        <th>Product</th>
        <th>Quantity</th>
        <th>Order Date</th>
        <th>Actions</th>
    </tr>
    <?php while($row = mysqli_fetch_assoc($orders)) { ?>
    <tr>
        <td><?= $row['id'] ?></td>
        <td><?= $row['supplier_name'] ?></td>
        <td><?= $row['product_name'] ?></td>
        <td><?= $row['quantity'] ?></td>
        <td><?= $row['order_date'] ?></td>
        <td>
            <a href="purchase_order.php?delete=<?= $row['id'] ?>" onclick="return confirm('Delete this order?')">Delete</a>
        </td>
    </tr>
    <?php } ?>
</table>

</body>
</html>
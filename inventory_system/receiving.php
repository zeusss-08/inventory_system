<?php
session_start();
include 'db_connection.php';

// Access control
if(!isset($_SESSION['admin_email'])){
    header("Location: admin_login.php");
    exit();
}

// Receive Product
if(isset($_POST['receive_product'])){
    $order_id = $_POST['order_id'];
    $quantity_received = $_POST['quantity_received'];
    $date_received = date('Y-m-d');

    // Get product_id from purchase order
    $order = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM purchase_order WHERE id='$order_id'"));
    $product_id = $order['product_id'];

    // Insert into receiving table
    $query = "INSERT INTO receiving (purchase_order_id, product_id, quantity_received, date_received) 
              VALUES ('$order_id', '$product_id', '$quantity_received', '$date_received')";
    mysqli_query($conn, $query);

    // Update product quantity in products table
    mysqli_query($conn, "UPDATE products SET quantity = quantity + $quantity_received WHERE id='$product_id'");

    header("Location: receiving.php");
}

// Fetch all purchase orders for receiving dropdown
$orders = mysqli_query($conn, "SELECT po.*, p.name AS product_name, s.name AS supplier_name 
                               FROM purchase_order po
                               JOIN products p ON po.product_id = p.id
                               JOIN suppliers s ON po.supplier_id = s.id");

// Fetch all received items
$receivings = mysqli_query($conn, "SELECT r.*, p.name AS product_name, s.name AS supplier_name 
                                   FROM receiving r
                                   JOIN products p ON r.product_id = p.id
                                   JOIN suppliers s ON p.supplier_id = s.id");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Receiving Products</title>
</head>
<body>
<h1>Receiving Products</h1>

<!-- Receive Product Form -->
<form method="POST">
    <select name="order_id" required>
        <option value="">Select Purchase Order</option>
        <?php while($o = mysqli_fetch_assoc($orders)){ ?>
            <option value="<?= $o['id'] ?>">
                <?= $o['supplier_name'] ?> - <?= $o['product_name'] ?> (Ordered: <?= $o['quantity'] ?>)
            </option>
        <?php } ?>
    </select>

    <input type="number" name="quantity_received" placeholder="Quantity Received" required>
    <button type="submit" name="receive_product">Receive Product</button>
</form>

<hr>

<!-- Received Products Table -->
<table border="1">
    <tr>
        <th>ID</th>
        <th>Supplier</th>
        <th>Product</th>
        <th>Quantity Received</th>
        <th>Date Received</th>
    </tr>
    <?php while($r = mysqli_fetch_assoc($receivings)) { ?>
    <tr>
        <td><?= $r['id'] ?></td>
        <td><?= $r['supplier_name'] ?></td>
        <td><?= $r['product_name'] ?></td>
        <td><?= $r['quantity_received'] ?></td>
        <td><?= $r['date_received'] ?></td>
    </tr>
    <?php } ?>
</table>

</body>
</html>
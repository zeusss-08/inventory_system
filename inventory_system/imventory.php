<?php
session_start();
include 'db_connection.php';

// Access control
if(!isset($_SESSION['admin_email'])){
    header("Location: admin_login.php");
    exit();
}

// Fetch all products with supplier info
$inventory = mysqli_query($conn, "SELECT p.*, s.name AS supplier_name 
                                  FROM products p 
                                  JOIN suppliers s ON p.supplier_id = s.id");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Inventory</title>
</head>
<body>
<h1>Inventory</h1>

<table border="1">
    <tr>
        <th>ID</th>
        <th>Product Name</th>
        <th>Supplier</th>
        <th>Price</th>
        <th>Quantity in Stock</th>
    </tr>
    <?php while($row = mysqli_fetch_assoc($inventory)) { ?>
    <tr>
        <td><?= $row['id'] ?></td>
        <td><?= $row['name'] ?></td>
        <td><?= $row['supplier_name'] ?></td>
        <td><?= $row['price'] ?></td>
        <td><?= $row['quantity'] ?></td>
    </tr>
    <?php } ?>
</table>

</body>
</html>
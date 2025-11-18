<?php
session_start();
include 'db_connection.php';

// Access control
if(!isset($_SESSION['admin_email'])){
    header("Location: admin_login.php");
    exit();
}

// Add Product
if(isset($_POST['add_product'])){
    $name = $_POST['name'];
    $supplier_id = $_POST['supplier_id'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];

    $query = "INSERT INTO products (name, supplier_id, price, quantity) 
              VALUES ('$name', '$supplier_id', '$price', '$quantity')";
    mysqli_query($conn, $query);
    header("Location: products.php");
}

// Update Product
if(isset($_POST['update_product'])){
    $id = $_POST['id'];
    $name = $_POST['name'];
    $supplier_id = $_POST['supplier_id'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];

    $query = "UPDATE products SET name='$name', supplier_id='$supplier_id', price='$price', quantity='$quantity' 
              WHERE id='$id'";
    mysqli_query($conn, $query);
    header("Location: products.php");
}

// Delete Product
if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    $query = "DELETE FROM products WHERE id='$id'";
    mysqli_query($conn, $query);
    header("Location: products.php");
}

// Fetch all products with supplier info
$products = mysqli_query($conn, "SELECT p.*, s.name AS supplier_name 
                                 FROM products p 
                                 JOIN suppliers s ON p.supplier_id = s.id");

// Fetch suppliers for dropdown
$suppliers = mysqli_query($conn, "SELECT * FROM suppliers");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Products</title>
</head>
<body>
<h1>Products Management</h1>

<!-- Add Product Form -->
<form method="POST">
    <input type="text" name="name" placeholder="Product Name" required>
    <select name="supplier_id" required>
        <option value="">Select Supplier</option>
        <?php while($s = mysqli_fetch_assoc($suppliers)){ ?>
            <option value="<?= $s['id'] ?>"><?= $s['name'] ?></option>
        <?php } ?>
    </select>
    <input type="number" step="0.01" name="price" placeholder="Price" required>
    <input type="number" name="quantity" placeholder="Quantity" required>
    <button type="submit" name="add_product">Add Product</button>
</form>

<hr>

<!-- Products Table -->
<table border="1">
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Supplier</th>
        <th>Price</th>
        <th>Quantity</th>
        <th>Actions</th>
    </tr>
    <?php while($row = mysqli_fetch_assoc($products)) { ?>
    <tr>
        <td><?= $row['id'] ?></td>
        <td><?= $row['name'] ?></td>
        <td><?= $row['supplier_name'] ?></td>
        <td><?= $row['price'] ?></td>
        <td><?= $row['quantity'] ?></td>
        <td>
            <a href="products.php?edit=<?= $row['id'] ?>">Edit</a> | 
            <a href="products.php?delete=<?= $row['id'] ?>" onclick="return confirm('Delete this product?')">Delete</a>
        </td>
    </tr>
    <?php } ?>
</table>

<?php
// Edit form
if(isset($_GET['edit'])){
    $id = $_GET['edit'];
    $product = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM products WHERE id='$id'"));
    // Reset suppliers pointer for dropdown
    $suppliers = mysqli_query($conn, "SELECT * FROM suppliers");
    ?>
    <hr>
    <h2>Edit Product</h2>
    <form method="POST">
        <input type="hidden" name="id" value="<?= $product['id'] ?>">
        <input type="text" name="name" value="<?= $product['name'] ?>" required>
        <select name="supplier_id" required>
            <?php while($s = mysqli_fetch_assoc($suppliers)){ ?>
                <option value="<?= $s['id'] ?>" <?= $s['id'] == $product['supplier_id'] ? 'selected' : '' ?>>
                    <?= $s['name'] ?>
                </option>
            <?php } ?>
        </select>
        <input type="number" step="0.01" name="price" value="<?= $product['price'] ?>" required>
        <input type="number" name="quantity" value="<?= $product['quantity'] ?>" required>
        <button type="submit" name="update_product">Update Product</button>
    </form>
<?php } ?>

</body>
</html>
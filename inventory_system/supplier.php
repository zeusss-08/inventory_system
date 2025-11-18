<?php
session_start();
include 'db_connection.php'; // connect to your database

// Access control
if(!isset($_SESSION['admin_email'])){
    header("Location: admin_login.php");
    exit();
}

// Add Supplier
if(isset($_POST['add_supplier'])){
    $name = $_POST['name'];
    $contact = $_POST['contact'];
    $email = $_POST['email'];

    $query = "INSERT INTO suppliers (name, contact, email) VALUES ('$name', '$contact', '$email')";
    mysqli_query($conn, $query);
    header("Location: suppliers.php");
}

// Update Supplier
if(isset($_POST['update_supplier'])){
    $id = $_POST['id'];
    $name = $_POST['name'];
    $contact = $_POST['contact'];
    $email = $_POST['email'];

    $query = "UPDATE suppliers SET name='$name', contact='$contact', email='$email' WHERE id='$id'";
    mysqli_query($conn, $query);
    header("Location: suppliers.php");
}

// Delete Supplier
if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    $query = "DELETE FROM suppliers WHERE id='$id'";
    mysqli_query($conn, $query);
    header("Location: suppliers.php");
}

// Fetch all suppliers
$suppliers = mysqli_query($conn, "SELECT * FROM suppliers");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Suppliers</title>
</head>
<body>
<h1>Suppliers Management</h1>

<!-- Add Supplier Form -->
<form method="POST">
    <input type="text" name="name" placeholder="Supplier Name" required>
    <input type="text" name="contact" placeholder="Contact Number" required>
    <input type="email" name="email" placeholder="Email" required>
    <button type="submit" name="add_supplier">Add Supplier</button>
</form>

<hr>

<!-- Suppliers Table -->
<table border="1">
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Contact</th>
        <th>Email</th>
        <th>Actions</th>
    </tr>
    <?php while($row = mysqli_fetch_assoc($suppliers)) { ?>
    <tr>
        <td><?= $row['id'] ?></td>
        <td><?= $row['name'] ?></td>
        <td><?= $row['contact'] ?></td>
        <td><?= $row['email'] ?></td>
        <td>
            <a href="suppliers.php?edit=<?= $row['id'] ?>">Edit</a> | 
            <a href="suppliers.php?delete=<?= $row['id'] ?>" onclick="return confirm('Delete this supplier?')">Delete</a>
        </td>
    </tr>
    <?php } ?>
</table>

<?php
// Edit form
if(isset($_GET['edit'])){
    $id = $_GET['edit'];
    $supplier = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM suppliers WHERE id='$id'"));
    ?>
    <hr>
    <h2>Edit Supplier</h2>
    <form method="POST">
        <input type="hidden" name="id" value="<?= $supplier['id'] ?>">
        <input type="text" name="name" value="<?= $supplier['name'] ?>" required>
        <input type="text" name="contact" value="<?= $supplier['contact'] ?>" required>
        <input type="email" name="email" value="<?= $supplier['email'] ?>" required>
        <button type="submit" name="update_supplier">Update Supplier</button>
    </form>
<?php } ?>

</body>
</html>
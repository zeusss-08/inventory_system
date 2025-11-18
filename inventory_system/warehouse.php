<?php
session_start();
include 'db_connection.php';

// Access control
if(!isset($_SESSION['admin_email'])){
    header("Location: admin_login.php");
    exit();
}

// Add Warehouse
if(isset($_POST['add_warehouse'])){
    $name = $_POST['name'];
    $location = $_POST['location'];

    $query = "INSERT INTO warehouse (name, location) VALUES ('$name', '$location')";
    mysqli_query($conn, $query);
    header("Location: warehouse.php");
}

// Update Warehouse
if(isset($_POST['update_warehouse'])){
    $id = $_POST['id'];
    $name = $_POST['name'];
    $location = $_POST['location'];

    $query = "UPDATE warehouse SET name='$name', location='$location' WHERE id='$id'";
    mysqli_query($conn, $query);
    header("Location: warehouse.php");
}

// Delete Warehouse
if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    $query = "DELETE FROM warehouse WHERE id='$id'";
    mysqli_query($conn, $query);
    header("Location: warehouse.php");
}

// Fetch all warehouses
$warehouses = mysqli_query($conn, "SELECT * FROM warehouse");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Warehouse Management</title>
</head>
<body>
<h1>Warehouse Management</h1>

<!-- Add Warehouse Form -->
<form method="POST">
    <input type="text" name="name" placeholder="Warehouse Name" required>
    <input type="text" name="location" placeholder="Location" required>
    <button type="submit" name="add_warehouse">Add Warehouse</button>
</form>

<hr>

<!-- Warehouse Table -->
<table border="1">
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Location</th>
        <th>Actions</th>
    </tr>
    <?php while($row = mysqli_fetch_assoc($warehouses)) { ?>
    <tr>
        <td><?= $row['id'] ?></td>
        <td><?= $row['name'] ?></td>
        <td><?= $row['location'] ?></td>
        <td>
            <a href="warehouse.php?edit=<?= $row['id'] ?>">Edit</a> | 
            <a href="warehouse.php?delete=<?= $row['id'] ?>" onclick="return confirm('Delete this warehouse?')">Delete</a>
        </td>
    </tr>
    <?php } ?>
</table>

<?php
// Edit form
if(isset($_GET['edit'])){
    $id = $_GET['edit'];
    $warehouse = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM warehouse WHERE id='$id'"));
    ?>
    <hr>
    <h2>Edit Warehouse</h2>
    <form method="POST">
        <input type="hidden" name="id" value="<?= $warehouse['id'] ?>">
        <input type="text" name="name" value="<?= $warehouse['name'] ?>" required>
        <input type="text" name="location" value="<?= $warehouse['location'] ?>" required>
        <button type="submit" name="update_warehouse">Update Warehouse</button>
    </form>
<?php } ?>

</body>
</html>
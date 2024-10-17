<?php
session_start();
include 'db.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: adminlogin.php');
    exit();
}

$id = $_GET['id'];
$sql = "SELECT * FROM inventory WHERE id='$id'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $item = $result->fetch_assoc();
} else {
    header('Location: admin_dashboard.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $model = $_POST['model'];
    $configuration = $_POST['configuration'];
    $serial_number = $_POST['serial_number'];
    $quantity = $_POST['quantity'];

    $sql = "UPDATE inventory SET model='$model', configuration='$configuration', serial_number='$serial_number', quantity='$quantity' WHERE id='$id'";

    if ($conn->query($sql) === TRUE) {
        header('Location: admin_dashboard.php');
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Inventory</title>
    <link rel="stylesheet" href="admindash.css">
    <link rel="icon" type="image/png" href="images/web.png">
</head>
<body>
<div class="container">
    <h1>Edit Inventory</h1>
    <form method="POST" action="">
        <div class="form-group">
            <label for="model">Model:</label>
            <input type="text" name="model" id="model" value="<?php echo $item['model']; ?>" required>
        </div>
        <div class="form-group">
            <label for="configuration">Configuration:</label>
            <input type="text" name="configuration" id="configuration" value="<?php echo $item['configuration']; ?>" required>
        </div>
        <div class="form-group">
            <label for="serial_number">Serial Number:</label>
            <input type="text" name="serial_number" id="serial_number" value="<?php echo $item['serial_number']; ?>" required>
        </div>
        <div class="form-group">
            <label for="quantity">Quantity:</label>
            <input type="number" name="quantity" id="quantity" value="<?php echo $item['quantity']; ?>" required>
        </div>
        <button type="submit" class="btn">Update Inventory</button>
    </form>
</div>
</body>
</html>

<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: register.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $laptop_model = $_POST['laptop_model'];
    $laptop_configuration = $_POST['laptop_configuration'];
    $serial_number = $_POST['serial_number'];
    $payment_type = $_POST['payment_type'];

    $sql = "INSERT INTO orders (user_id, laptop_model, laptop_configuration, serial_number, payment_type, order_status) 
            VALUES ('$user_id', '$laptop_model', '$laptop_configuration', '$serial_number', '$payment_type', 'pending')";

    if ($conn->query($sql) === TRUE) {
        header('Location: success.php');
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Form</title>
    <link rel="stylesheet" href="order_form.css">
    <link rel="icon" type="image/png" href="images/web.png">
</head>
<body>
<div class="form-container">
    <h1>Order Form</h1>
    <form method="POST" action="">
        <div class="form-group">
            <label for="laptop_model">Laptop Model:</label>
            <input type="text" name="laptop_model" id="laptop_model" required>
        </div>
        <div class="form-group">
            <label for="laptop_configuration">Laptop Configuration:</label>
            <input type="text" name="laptop_configuration" id="laptop_configuration" required>
        </div>
        <div class="form-group">
            <label for="serial_number">Serial Number:</label>
            <input type="text" name="serial_number" id="serial_number" required>
        </div>
        <div class="form-group">
            <label for="payment_type">Payment Type:</label>
            <select name="payment_type" id="payment_type" required>
                <option value="Cash">Cash</option>
                <option value="Installment">Installment</option>
            </select>
        </div>
        <button type="submit" class="btn">Place Order</button>
    </form>
</div>
</body>
</html>

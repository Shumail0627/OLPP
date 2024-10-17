<?php
include 'db.php';

if (!isset($_GET['order_id'])) {
    die("Order ID is not specified.");
}

$order_id = $_GET['order_id'];

// Fetch the order details
$sql = "SELECT * FROM orders WHERE id='$order_id'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $order = $result->fetch_assoc();

    // Insert the customer into the users table
    $name = $order['name'];
    $email = $order['email'];
    $phone = $order['phone'];
    $cnic = $order['cnic'];
    $laptop_model = $order['laptop_model'];
    $laptop_configuration = $order['laptop_configuration'];
    $serial_number = $order['serial_number'];
    $payment_type = $order['payment_type'];

    $sql_insert = "INSERT INTO users (name, email, phone, cnic, laptop_model, laptop_configuration, serial_number, payment_type)
                   VALUES ('$name', '$email', '$phone', '$cnic', '$laptop_model', '$laptop_configuration', '$serial_number', '$payment_type')";

    if ($conn->query($sql_insert) === TRUE) {
        // Update the order status to approved
        $sql_update = "UPDATE orders SET order_status='approved' WHERE id='$order_id'";

        if ($conn->query($sql_update) === TRUE) {
            header("Location: admin_dashboard.php");
        } else {
            echo "Error updating order status: " . $conn->error;
        }
    } else {
        echo "Error inserting customer: " . $conn->error;
    }
} else {
    echo "Order not found.";
}

$conn->close();
?>

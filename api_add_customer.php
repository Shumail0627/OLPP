<?php
header('Content-Type: application/json');
include 'db.php';

$name = $_POST['name'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$cnic = $_POST['cnic'];
$payment_type = $_POST['payment_type'];
$laptop_model = $_POST['laptop_model'];
$laptop_configuration = $_POST['laptop_configuration'];
$serial_number = $_POST['serial_number'];

$sql = "INSERT INTO users (name, email, phone, cnic, payment_type, laptop_model, laptop_configuration, serial_number) 
        VALUES ('$name', '$email', '$phone', '$cnic', '$payment_type', '$laptop_model', '$laptop_configuration', '$serial_number')";

if ($conn->query($sql) === TRUE) {
    echo json_encode(['status' => 'success', 'message' => 'Customer added successfully']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Error: ' . $conn->error]);
}

$conn->close();
?>
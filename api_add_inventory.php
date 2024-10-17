<?php
header('Content-Type: application/json');
include 'db.php';

$model = $_POST['model'];
$configuration = $_POST['configuration'];
$serial_number = $_POST['serial_number'];
$quantity = $_POST['quantity'];

$sql = "INSERT INTO inventory (model, configuration, serial_number, quantity) 
        VALUES ('$model', '$configuration', '$serial_number', '$quantity')";

if ($conn->query($sql) === TRUE) {
    echo json_encode(['status' => 'success', 'message' => 'Inventory item added successfully']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Error: ' . $conn->error]);
}

$conn->close();
?>
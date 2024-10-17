<?php
header('Content-Type: application/json');
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Fetch the input values
    $userId = isset($_POST['userId']) ? intval($_POST['userId']) : 0;
    $laptopModel = isset($_POST['laptop_model']) ? $_POST['laptop_model'] : '';
    $laptopConfiguration = isset($_POST['laptop_configuration']) ? $_POST['laptop_configuration'] : '';
    $serialNumber = isset($_POST['serial_number']) ? $_POST['serial_number'] : '';
    $paymentType = isset($_POST['payment_type']) ? $_POST['payment_type'] : '';

    // Check if userId is valid
    if ($userId <= 0) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid user ID']);
        exit;
    }

    // Insert order into the database
    $insertOrderQuery = $conn->prepare("INSERT INTO orders (user_id, laptop_model, laptop_configuration, serial_number, payment_type) VALUES (?, ?, ?, ?, ?)");
    $insertOrderQuery->bind_param("issss", $userId, $laptopModel, $laptopConfiguration, $serialNumber, $paymentType);
    
    if ($insertOrderQuery->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Order placed successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to place order: ' . $conn->error]);
    }
}
?>

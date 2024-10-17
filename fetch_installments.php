<?php
header('Content-Type: application/json');
include 'db.php'; // Make sure this file includes your database connection

// Check if it's a POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    exit;
}

// Check if customer_id is provided
if (!isset($_POST['customer_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Customer ID is required']);
    exit;
}

$customer_id = $_POST['customer_id'];

// Validate and sanitize the customer_id
if (!is_numeric($customer_id)) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid Customer ID']);
    exit;
}

// Fetch installments related to the customer
$stmt = $conn->prepare("SELECT * FROM installments WHERE user_id = ? ORDER BY due_date");
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$result = $stmt->get_result();

$installments = [];
while ($row = $result->fetch_assoc()) {
    $installments[] = $row;
}

if (count($installments) > 0) {
    echo json_encode(['status' => 'success', 'installments' => $installments]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'No installments found']);
}

$stmt->close();
$conn->close();
?>

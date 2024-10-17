<?php
header('Content-Type: application/json');
include 'db.php';

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

$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $customer = $result->fetch_assoc();
    
    // Escape output to prevent XSS attacks
    $customer = array_map('htmlspecialchars', $customer);

    if ($customer['payment_type'] == 'Installment') {
        $installment_stmt = $conn->prepare("SELECT * FROM installments WHERE user_id = ? ORDER BY due_date");
        $installment_stmt->bind_param("i", $customer_id);
        $installment_stmt->execute();
        $installment_result = $installment_stmt->get_result();
        
        $installments = [];
        while ($row = $installment_result->fetch_assoc()) {
            $installments[] = array_map('htmlspecialchars', $row);
        }
        
        $customer['installments'] = $installments;
    }
    
    echo json_encode([
        'status' => 'success', 
        'data' => $customer, 
        'timestamp' => date('Y-m-d H:i:s')
    ]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Customer not found']);
}

$stmt->close();
$conn->close();
?>
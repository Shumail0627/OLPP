<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

include 'db.php';

function sendResponse($status, $message, $data = null) {
    echo json_encode([
        'status' => $status,
        'message' => $message,
        'data' => $data
    ]);
    exit;
}

// Get the posted data
$contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';

if ($contentType === "application/json") {
    $content = trim(file_get_contents("php://input"));
    $decoded = json_decode($content, true);
    $username = isset($decoded['username']) ? $decoded['username'] : null;
    $password = isset($decoded['password']) ? $decoded['password'] : null;
} else {
    $username = isset($_POST['username']) ? $_POST['username'] : null;
    $password = isset($_POST['password']) ? $_POST['password'] : null;
}

if (!$username || !$password) {
    sendResponse('error', 'Username and password are required');
}

// Authenticate user by email and password
$stmt = $conn->prepare("SELECT id, password FROM users WHERE email = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$user_result = $stmt->get_result();

if ($user_result->num_rows == 0) {
    sendResponse('error', 'User not found');
}

$user_data = $user_result->fetch_assoc();

if (!password_verify($password, $user_data['password'])) {
    sendResponse('error', 'Invalid password');
}

$user_id = $user_data['id'];

// Fetch user details
$user_sql = "SELECT name, email, phone, cnic, laptop_model, laptop_configuration, serial_number, laptop_total_amount, received_amount, total_installments, paid_installments, installment_status, next_payment_date FROM users WHERE id = ?";
$stmt = $conn->prepare($user_sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user_result = $stmt->get_result();

if ($user_result->num_rows == 0) {
    sendResponse('error', 'User not found');
}

$user_details = $user_result->fetch_assoc();

// Fetch installments
$installment_sql = "SELECT due_date, amount, paid FROM installments WHERE user_id = ? ORDER BY due_date";
$stmt = $conn->prepare($installment_sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$installment_result = $stmt->get_result();

$installments = [];
while ($row = $installment_result->fetch_assoc()) {
    $installments[] = $row;
}

$response = [
    'userDetails' => $user_details,
    'installments' => $installments
];

sendResponse('success', 'Data fetched successfully', $response);

$conn->close();
?>
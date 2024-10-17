<?php
// Include your database connection
include 'db.php';

// Function to sanitize input data
function sanitizeInput($conn, $data) {
    return mysqli_real_escape_string($conn, trim($data));
}

// Function to authenticate user
function authenticateUser($conn, $username, $password) {
    $username = sanitizeInput($conn, $username);

    // Use prepared statements for security
    $stmt = $conn->prepare("SELECT password FROM users WHERE email = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($hashed_password);
    $stmt->fetch();

    if ($stmt->num_rows > 0) {
        if (password_verify($password, $hashed_password)) {
            return true;
        }
    }

    return false;
}

// Function to approve an order
function approveOrder($conn) {
    $username = sanitizeInput($conn, $_POST['username']);
    $password = sanitizeInput($conn, $_POST['password']);
    $orderId = sanitizeInput($conn, $_POST['order_id']);

    // Authenticate the user first
    if (authenticateUser($conn, $username, $password)) {
        // Update the order status to approved
        $stmt = $conn->prepare("UPDATE orders SET status = 'approved' WHERE id = ?");
        $stmt->bind_param("i", $orderId);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Order approved successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to approve order']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Authentication failed']);
    }
}

// Main logic to handle the POST request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    approveOrder($conn);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}

?>

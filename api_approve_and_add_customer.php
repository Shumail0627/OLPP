<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Include the database connection file
require_once 'db.php';

// Get the POST data
$username = $_POST['username'];
$password = $_POST['password'];
$orderId = isset($_POST['order_id']) ? $_POST['order_id'] : null;
$action = isset($_POST['action']) ? $_POST['action'] : null;

// Check if credentials, order ID, and action are provided
if (empty($username) || empty($password) || empty($orderId) || empty($action)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Username, password, order ID, and action are required.'
    ]);
    exit();
}

// Authenticate admin
$query = $conn->prepare("SELECT * FROM admins WHERE email = ? AND password = ?");
$query->bind_param("ss", $username, $password);

if ($query->execute()) {
    $result = $query->get_result();
    if ($result->num_rows > 0) {
        $adminData = $result->fetch_assoc();

        // Prepare the query to update order status
        if ($action === 'approve') {
            $updateQuery = $conn->prepare("UPDATE orders SET status = 'approved' WHERE id = ?");
        } elseif ($action === 'reject') {
            $updateQuery = $conn->prepare("UPDATE orders SET status = 'rejected' WHERE id = ?");
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Invalid action provided.'
            ]);
            exit();
        }

        $updateQuery->bind_param("i", $orderId);

        if ($updateQuery->execute()) {
            if ($updateQuery->affected_rows > 0) {
                // Fetch the updated customer list after the order has been approved/rejected
                $customersQuery = "SELECT u.id, u.name, u.email, u.phone, o.status 
                                   FROM users u 
                                   LEFT JOIN orders o ON u.id = o.user_id 
                                   WHERE o.status = 'approved' OR o.status = 'pending'";
                $customersResult = $conn->query($customersQuery);
                $customers = [];
                while ($row = $customersResult->fetch_assoc()) {
                    $customers[] = $row;
                }

                echo json_encode([
                    'status' => 'success',
                    'message' => "Order $action successfully.",
                    'customers' => $customers
                ]);
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Order not found or already processed.'
                ]);
            }
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Failed to update order status.'
            ]);
        }
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Invalid credentials.'
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Failed to execute query.'
    ]);
}

// Close the database connection
$conn->close();
?>

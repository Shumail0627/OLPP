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

// Function to get pending orders
function getPendingOrders($conn) {
    $username = sanitizeInput($conn, $_POST['username']);
    $password = sanitizeInput($conn, $_POST['password']);

    // Authenticate the user first
    if (authenticateUser($conn, $username, $password)) {
        // Fetch pending orders and include necessary user details
        $query = "SELECT orders.*, users.name, users.email FROM orders 
                  JOIN users ON orders.user_id = users.id 
                  WHERE orders.status = 'pending'";
        $result = mysqli_query($conn, $query);

        if ($result) {
            $orders = mysqli_fetch_all($result, MYSQLI_ASSOC);
            echo json_encode(['status' => 'success', 'orders' => $orders]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to retrieve orders']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Authentication failed']);
    }
}

// Main logic to handle the POST request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    getPendingOrders($conn);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}

?>

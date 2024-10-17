<?php
// api_search_customers.php

// Include database connection
include 'db.php';

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get username, password, and query from the request
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $query = $_POST['query'] ?? '';

    // Validate user (you should implement proper authentication)
    if ($username === 'admin' && $password === 'password') {
        // Search customers
        $search_query = "SELECT id, name, email FROM users 
                         WHERE name LIKE '%$query%' 
                         OR email LIKE '%$query%' 
                         OR phone LIKE '%$query%' 
                         OR cnic LIKE '%$query%' 
                         LIMIT 20";
        $search_result = mysqli_query($conn, $search_query);
        $customers = mysqli_fetch_all($search_result, MYSQLI_ASSOC);

        // Prepare response
        $response = [
            'status' => 'success',
            'customers' => $customers
        ];
    } else {
        $response = [
            'status' => 'error',
            'message' => 'Invalid credentials'
        ];
    }

    // Send JSON response
    header('Content-Type: application/json');
    echo json_encode($response);
} else {
    // If not a POST request, return an error
    header('HTTP/1.1 405 Method Not Allowed');
    echo 'Only POST requests are allowed';
}
?>
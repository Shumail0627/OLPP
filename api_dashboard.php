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

// Prepare and execute the query to authenticate the admin
$query = $conn->prepare("SELECT * FROM admins WHERE email = ? AND password = ?");
$query->bind_param("ss", $username, $password);

if ($query->execute()) {
    $result = $query->get_result();

    if ($result->num_rows > 0) {
        $adminData = $result->fetch_assoc();

        // Fetch customers
        $customersQuery = "SELECT * FROM users";
        $customersResult = $conn->query($customersQuery);
        $customers = [];
        while ($row = $customersResult->fetch_assoc()) {
            $customers[] = $row;
        }

        // Fetch pending orders with user names
        $ordersQuery = "
            SELECT o.*, u.name 
            FROM orders o 
            JOIN users u ON o.user_id = u.id 
            WHERE o.status = 'pending'
        ";
        $ordersResult = $conn->query($ordersQuery);
        $pendingOrders = [];
        while ($row = $ordersResult->fetch_assoc()) {
            $pendingOrders[] = $row;
        }

        // Fetch inventory
        $inventoryQuery = "SELECT * FROM inventory";
        $inventoryResult = $conn->query($inventoryQuery);
        $inventory = [];
        while ($row = $inventoryResult->fetch_assoc()) {
            $inventory[] = $row;
        }

        // Fetch laptop distribution
        $distributionQuery = "
            SELECT location, COUNT(*) as count 
            FROM users 
            WHERE laptop_model != '' 
            GROUP BY location
        ";
        $distributionResult = $conn->query($distributionQuery);
        $laptopDistribution = [];
        while ($row = $distributionResult->fetch_assoc()) {
            $laptopDistribution[$row['location']] = (int)$row['count'];
        }

        // Fetch all laptops
        $allLaptopsQuery = "
            SELECT name, laptop_model, serial_number, location, laptop_status 
            FROM users 
            WHERE laptop_model != '' 
            ORDER BY location, laptop_status
        ";
        $allLaptopsResult = $conn->query($allLaptopsQuery);
        $allLaptops = [];
        while ($row = $allLaptopsResult->fetch_assoc()) {
            $allLaptops[] = $row;
        }

        // Calculate total laptops
        $totalLaptops = array_sum($laptopDistribution);

        // Response array
        $response = [
            'status' => 'success',
            'customers' => $customers,
            'pendingOrders' => $pendingOrders,
            'inventory' => $inventory,
            'laptopDistribution' => $laptopDistribution,
            'allLaptops' => $allLaptops,
            'totalLaptops' => $totalLaptops,
        ];
    } else {
        // Invalid admin credentials
        $response = [
            'status' => 'error',
            'message' => 'Invalid credentials',
        ];
    }
} else {
    // Query execution failed
    $response = [
        'status' => 'error',
        'message' => 'Database query failed',
    ];
}

echo json_encode($response);
?>
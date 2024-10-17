<?php
session_start();
include 'db.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: adminlogin.php');
    exit();
}

// Function to execute SQL query and fetch result
function executeQuery($conn, $sql, $errorMessage) {
    $result = $conn->query($sql);
    if (!$result) {
        die($errorMessage . $conn->error);
    }
    return $result->fetch_assoc();
}

// Fetch total number of laptops purchased
$sql_total_laptops = "SELECT SUM(quantity) AS total_laptops FROM purchases";
$total_laptops = executeQuery($conn, $sql_total_laptops, "Error fetching total laptops: ")['total_laptops'] ?? 0;

// Fetch total cost of purchased laptops
$sql_total_cost = "SELECT SUM(total_amount) AS total_purchase_cost FROM purchases";
$total_purchase_cost = executeQuery($conn, $sql_total_cost, "Error fetching total cost: ")['total_purchase_cost'] ?? 0;

// Fetch total sales
$sql_sales = "SELECT SUM(total_amount) AS total_sales FROM sales";
$total_sales = executeQuery($conn, $sql_sales, "Error fetching total sales: ")['total_sales'] ?? 0;

// Calculate total profit
$sql_profit = "SELECT 
    (SELECT SUM(total_amount) FROM sales) - 
    (SELECT SUM(total_amount) FROM purchases) AS total_profit";
$total_profit = executeQuery($conn, $sql_profit, "Error calculating total profit: ")['total_profit'] ?? 0;

// HTML part starts here
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laptop Summary</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            width: 80%;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #4CAF50;
        }

        .summary {
            display: flex;
            justify-content: space-around;
            margin-top: 30px;
        }

        .summary-card {
            background: #4CAF50;
            color: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            width: 200px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .summary-card:hover {
            transform: translateY(-10px);
        }

        .summary-card h3 {
            margin-top: 0;
            font-size: 24px;
        }

        .summary-card p {
            font-size: 18px;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Laptop Purchase Summary</h1>

    <div class="summary">
        <div class="summary-card">
            <h3>Total Laptops</h3>
            <p><?php echo $total_laptops; ?></p>
        </div>

        <div class="summary-card">
            <h3>Total Purchase Cost</h3>
            <p>PKR <?php echo number_format($total_purchase_cost, 2); ?></p>
        </div>

        <div class="summary-card">
            <h3>Total Sales</h3>
            <p>PKR <?php echo number_format($total_sales, 2); ?></p>
        </div>

        <div class="summary-card">
            <h3>Total Profit</h3>
            <p>PKR <?php echo number_format($total_profit, 2); ?></p>
        </div>
    </div>

    <div style="text-align: center;">
        <a href="admin_dashboard.php" class="btn">Back to Dashboard</a>
    </div>
</div>

</body>
</html>

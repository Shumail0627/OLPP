<?php
session_start();
include 'db.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: adminlogin.php');
    exit();
}

$user_id = $_GET['user_id'];

$sql = "SELECT * FROM users WHERE id='$user_id' AND payment_type='Cash'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "<p class='error'>No cash payment details found for this user.</p>";
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cash Payment Details</title>
    <link rel="icon" type="image/png" href="images/web.png">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            width: 100%;
            text-align: left;
        }

        h1 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #333;
            text-align: center;
        }

        p {
            font-size: 16px;
            margin: 10px 0;
            color: #555;
        }

        .error {
            color: red;
        }

        .container p span {
            font-weight: bold;
            color: #000;
            display: inline-block;
            width: 200px;
        }

        .back-button {
            display: block;
            width: 100px;
            margin: 20px auto 0;
            padding: 10px;
            background-color: #4caf50;
            color: white;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
        }

        .back-button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Cash Payment Details</h1>
    <p><span>Name:</span> <?php echo $user['name']; ?></p>
    <p><span>Email:</span> <?php echo $user['email']; ?></p>
    <p><span>Phone:</span> <?php echo $user['phone']; ?></p>
    <p><span>CNIC:</span> <?php echo $user['cnic']; ?></p>
    <p><span>Payment Type:</span> <?php echo $user['payment_type']; ?></p>
    <p><span>Laptop Model:</span> <?php echo $user['laptop_model']; ?></p>
    <p><span>Laptop Configuration:</span> <?php echo $user['laptop_configuration']; ?></p>
    <p><span>Serial Number:</span> <?php echo $user['serial_number']; ?></p>
    <p><span>Purchase Date:</span> <?php echo $user['purchase_date']; ?></p>
    <p><span>Total Laptop Amount:</span> Rs. <?php echo number_format($user['laptop_total_amount'], 2); ?></p>
    <p><span>Received Amount:</span> Rs. <?php echo number_format($user['received_amount'], 2); ?></p>
    <p><span>Payment Status:</span> <?php echo ($user['laptop_total_amount'] == $user['received_amount']) ? 'Fully Paid' : 'Partially Paid'; ?></p>
    <p><span>Location:</span> <?php echo $user['location']; ?></p>
    <p><span>Laptop Status:</span> <?php echo $user['laptop_status']; ?></p>
    
    <a href="all_customers.php" class="back-button">Back</a>
</div>
</body>
</html>
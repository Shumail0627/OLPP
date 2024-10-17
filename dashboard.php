<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

include 'db.php';

$user_id = $_SESSION['user_id'];

// Fetch user information including the laptop_total_amount
$sql = "SELECT * FROM users WHERE id='$user_id'";
$result = $conn->query($sql);
$user = $result->fetch_assoc();

// Extracting the laptop_total_amount
$totalAmount = isset($user['laptop_total_amount']) ? $user['laptop_total_amount'] : 0;

// Fetch installment information
$sql = "SELECT * FROM installments WHERE user_id='$user_id' ORDER BY due_date ASC";
$installments = $conn->query($sql);

// Calculate paid amount
$paidAmount = 0;
$installmentDetails = [];
$firstInstallmentDate = null;

while ($installment = $installments->fetch_assoc()) {
    if ($installment['paid']) {
        $paidAmount += $installment['amount'];
        $installmentDetails[] = [
            'amount' => $installment['amount'],
            'due_date' => $installment['due_date'],
            'payment_date' => $installment['payment_date'],
            'payment_method' => $installment['payment_method'] ?? 'Unknown',
        ];

        // Capture the first installment date
        if ($firstInstallmentDate === null) {
            $firstInstallmentDate = $installment['payment_date'];
        }
    }
}

// Calculate remaining amount
$remainingAmount = $totalAmount - $paidAmount;

// Calculate warranty status
$warrantyStatus = 'Expired';
if ($firstInstallmentDate !== null) {
    $warrantyEndDate = date('Y-m-d', strtotime($firstInstallmentDate . ' +1 year'));
    $currentDate = date('Y-m-d');

    if ($currentDate <= $warrantyEndDate) {
        $warrantyStatus = 'Valid until ' . date('d M Y', strtotime($warrantyEndDate));
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="icon" type="image/png" href="images/web.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.24/dist/sweetalert2.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333;
        }

        .navbar {
            background-color: #4caf50;
            padding: 10px 0;
            text-align: center;
        }

        .navbar a {
            color: white;
            padding: 14px 20px;
            text-decoration: none;
            display: inline-block;
            font-size: 18px;
        }

        .navbar a:hover {
            background-color: #45a049;
        }

        .container {
            width: 80%;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        h1, h2 {
            color: #333;
        }

        h1 {
            border-bottom: 2px solid #4caf50;
            padding-bottom: 10px;
        }

        p {
            margin: 10px 0;
        }

        .info p {
            margin: 5px 0;
        }

        table.user-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table.user-table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #4caf50;
            color: #fff;
        }

        td {
            background-color: #fff;
        }

        tr:nth-child(even) td {
            background-color: #f9f9f9;
        }

        .installment-status {
            text-align: center;
            font-size: 1.5em;
            margin-top: 20px;
            color: #4caf50;
        }

        .installment-status-box {
            text-align: center;
            font-size: 1.2em;
            color: green;
            margin-bottom: 20px;
        }

        .completed-icon {
            width: 50px;
            height: 50px;
            margin-top: 10px;
            fill: green;
        }

        .remaining-amount {
            color: <?php echo $remainingAmount == 0 ? 'green' : 'red'; ?>;
            font-weight: bold;
        }

        .warranty-status {
            color: <?php echo $warrantyStatus === 'Expired' ? 'red' : 'green'; ?>;
            font-weight: bold;
        }

    </style>
</head>
<body>
    <div class="navbar">
        <a href="dashboard.php">Dashboard</a>
        <a href="profile.php">Profile</a>
        <a href="support.php">Support</a>
        <a href="logout.php">Logout</a>
    </div>

    <div class="container">
        <h1>Welcome, <?php echo htmlspecialchars($user['name']); ?></h1>
        <p class="user-details">Email: <?php echo htmlspecialchars($user['email']); ?></p>
        <p class="user-details">Phone: <?php echo htmlspecialchars($user['phone']); ?></p>

        <h2>Laptop Information</h2>
        <div class="info">
            <p><strong>Model:</strong> <?php echo htmlspecialchars($user['laptop_model']); ?></p>
            <p><strong>Configuration:</strong> <?php echo htmlspecialchars($user['laptop_configuration']); ?></p>
            <p><strong>Serial Number:</strong> <?php echo htmlspecialchars($user['serial_number']); ?></p>
            <p><strong>Total Amount:</strong> Rs. <?php echo number_format($user['laptop_total_amount'], 2); ?></p>
            <p><strong>Warranty Status:</strong> <span class="warranty-status"><?php echo $warrantyStatus; ?></span></p>
        </div>

        <h2 class="installment-status">Installment Status</h2>
        <div class="installment-status-box" style="color: <?php echo $remainingAmount == 0 ? 'green' : 'red'; ?>;">
    <p><?php echo $remainingAmount == 0 ? "Installment Completed" : "Installment Pending"; ?></p>
    <?php if ($remainingAmount == 0) { ?>
        <svg class="completed-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
            <path d="M12 0C5.373 0 0 5.373 0 12c0 6.627 5.373 12 12 12s12-5.373 12-12C24 5.373 18.627 0 12 0zm-1.5 17.25l-5-5L7.43 10.82l3.07 3.07 6.57-6.57L18 8.5l-7.5 8.75z"/>
        </svg>
    <?php } ?>
</div>

        <h2>Payment Tracking</h2>
        <table class="user-table">
            <tr>
                <th>Total Amount</th>
                <th>Amount Received</th>
                <th>Amount Remaining</th>
                <th>Payment Dates</th>
            </tr>
            <tr>
                <td><?php echo number_format($user['laptop_total_amount'], 2); ?></td>
                <td><?php echo number_format($paidAmount, 2); ?></td>
                <td class="remaining-amount"><?php echo number_format($remainingAmount, 2); ?></td>
                <td>
                    <?php foreach ($installmentDetails as $detail) { ?>
                        <p><?php echo htmlspecialchars($detail['due_date']) . ": Received Rs. " . number_format($detail['amount'], 2) . " via " . htmlspecialchars($detail['payment_method']); ?></p>
                    <?php } ?>
                </td>
            </tr>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.24/dist/sweetalert2.all.min.js"></script>
    <script>
        window.onload = function() {
            var remainingAmount = <?php echo $remainingAmount; ?>;
            if (remainingAmount > 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Pending Installments',
                    text: 'You have pending installments! Your remaining amount is Rs. ' + remainingAmount.toFixed(2),
                    timer: 5000,
                    showConfirmButton: false
                });
            }
        }
    </script>
</body>
</html>

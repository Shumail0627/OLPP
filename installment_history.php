<?php
session_start();
include 'db.php';

if (!isset($_GET['user_id'])) {
    die("User ID is not specified.");
}

$user_id = $_GET['user_id'];

// Check if a new installment has been added
$new_installment_id = isset($_GET['new_installment']) ? $_GET['new_installment'] : null;

// Fetch user details
$sql = "SELECT * FROM users WHERE id='$user_id'";
$result = $conn->query($sql);
$user = $result->fetch_assoc();

// Fetch the details of the new installment if it exists
$new_installment = null;
if ($new_installment_id) {
    $sql = "SELECT * FROM installments WHERE id='$new_installment_id'";
    $new_installment_result = $conn->query($sql);
    $new_installment = $new_installment_result->fetch_assoc();
}

// Fetch installment details
$sql = "SELECT * FROM installments WHERE user_id='$user_id'";
$installments = $conn->query($sql);

// Calculate total amount received
$sql = "SELECT SUM(amount) as total_received, COUNT(id) as total_installments FROM installments WHERE user_id='$user_id' AND paid=1";
$total_received_result = $conn->query($sql);
$total_received_data = $total_received_result->fetch_assoc();
$total_received = $total_received_data['total_received'];
$total_installments_paid = $total_received_data['total_installments'];

// Fetch total amount from the user's details
$laptop_total_amount = $user['laptop_total_amount'] ?? 0; // Assuming column 'laptop_total_amount' in 'users' table

// Calculate remaining installments
$total_installments = 14; // Total number of installments
$remaining_installments = $total_installments - $total_installments_paid;

// Check if installments are completed
$installment_status = ($total_received >= $laptop_total_amount) ? 'Installment Completed' : 'Installments Pending';

// Fetch warranty information
$first_installment_date = $conn->query("SELECT MIN(payment_date) as first_date FROM installments WHERE user_id='$user_id' AND paid=1")->fetch_assoc()['first_date'];
$warranty_end_date = date('Y-m-d', strtotime('+1 year', strtotime($first_installment_date)));
$warranty_status = (strtotime($warranty_end_date) > time()) ? "Valid until $warranty_end_date" : "Expired";

$conn->close();

// Get current date
$current_date = date('Y-m-d');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Installment Payment Details</title>
    <link rel="icon" type="image/png" href="images/web.png">
    <style>
    /* Navbar Styling */
        .navbar {
            background-color: #4caf50;
            overflow: hidden;
            padding: 10px;
            color: white;
        }

        .navbar .container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar .navbar-brand {
            display: flex;
            align-items: center;
        }

        .navbar .navbar-brand .logo-bold {
            font-weight: bold;
            color: #000;
            font-size: 24px;
            margin-right: 5px;
        }

        .navbar .navbar-brand .logo-thin {
            font-weight: 300;
            color: #000;
            font-size: 16px;
        }

        .navbar .navbar-menu {
            list-style-type: none;
            margin: 0;
            padding: 0;
            display: flex;
        }

        .navbar .navbar-menu li {
            margin-left: 20px;
        }

        .navbar .navbar-menu li a {
            color: #000;
            text-decoration: none;
            padding: 10px;
        }

        .navbar .navbar-menu li a:hover {
            background-color: #575757;
            border-radius: 5px;
        }

        .navbar .navbar-search {
            display: flex;
            align-items: center;
        }

        .navbar .navbar-search input[type="text"] {
            padding: 5px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
        }

        .navbar .navbar-search button {
            padding: 6px 10px;
            font-size: 16px;
            background-color: #4CAF50;
            color: #fff;
            border: none;
            border-radius: 5px;
            margin-left: 5px;
            cursor: pointer;
        }

        .navbar .navbar-search button:hover {
            background-color: #45a049;
        }
        /* General Styles */
        .container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        h1 {
            text-align: center;
            color: #333;
            border-bottom: 2px solid #4caf50;
            padding-bottom: 10px;
        }

        p {
            margin: 10px 0;
        }

        .info p {
            margin: 5px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: #4caf50;
            color: #fff;
        }

        .edit-icon {
            cursor: pointer;
            margin-left: 5px;
        }

        .edit-form {
            display: none;
        }

        .btn {
            padding: 8px 16px;
            text-align: center;
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
            text-decoration: none;
        }

        .btn-green {
            background-color: #4caf50;
        }

        .btn-blue {
            background-color: #007bff;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        .status-completed {
            text-align: center;
            color: green;
            font-size: 1.5em;
            margin: 20px 0;
        }

        .status-pending {
            text-align: center;
            color: red;
            font-size: 1.5em;
            margin: 20px 0;
        }

        .completed-icon {
            display: block;
            margin: 10px auto;
            fill: green;
            width: 50px;
            height: 50px;
        }

        .table-container {
            text-align: center;
        }

        .btn-container {
            margin-top: 20px;
            text-align: center;
        }

        .edit-svg {
            width: 20px;
            height: 20px;
            fill: #007bff;
            cursor: pointer;
        }

        .receipt {
            margin-top: 20px;
            padding: 20px;
            background-color: #f9f9f9;
            page-break-inside: avoid;
            text-align: center;
            border-top: 2px solid #000;
            border-bottom: 2px solid #000;
        }

        .receipt h2 {
            text-align: center;
            font-size: 24px;
            margin-bottom: 20px;
            color: #333;
        }

        .receipt p {
            margin: 5px 0;
            font-size: 16px;
        }

        .signature-section {
            margin-top: 30px;
            text-align: left;
            padding-top: 50px;
        }

        /* Print Styles */
        @media print {
            body * {
                visibility: hidden;
            }

            .receipt, .receipt * {
                visibility: visible;
            }

            .receipt {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                margin: 0;
                padding-top: 30px;
                border: none;
            }

            .btn-print {
                display: none;
            }
        }
        .action-buttons {
            display: flex;
            justify-content: space-around;
        }
        .btn-delete {
            background-color: #dc3545;
        }
        .btn-delete:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
<!-- Navigation Bar -->
    <nav class="navbar">
        <div class="container">
            <div class="navbar-brand">
                <span class="logo-bold">OLPP</span>
                <span class="logo-thin">One Laptop Per Pakistani</span>
            </div>
            <ul class="navbar-menu">
                <li><a href="admin_dashboard.php">Dashboard</a></li>
                <li><a href="all_customers.php">Customers</a></li>
                <li><a href="get_replacement_details.php">Replacement</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
            <form class="navbar-search" method="GET" action="search_results.php">
                <input type="text" name="query" placeholder="Search..." id="search-box" required>
                <button type="submit">Search</button>
            </form>
        </div>
    </nav>
<div class="container">
    <h1>Installment Payment Details</h1>

    <div class="info">
        <p><strong>Name:</strong> <?php echo htmlspecialchars($user['name']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
        <p><strong>Phone:</strong> <?php echo htmlspecialchars($user['phone']); ?></p>
        <p><strong>CNIC:</strong> <?php echo htmlspecialchars($user['cnic']); ?></p>
        <p><strong>Payment Type:</strong> <?php echo htmlspecialchars($user['payment_type']); ?></p>
        <p><strong>Serial Number:</strong> <?php echo htmlspecialchars($user['serial_number']); ?></p>
        <p><strong>Laptop Model:</strong> <?php echo htmlspecialchars($user['laptop_model']); ?></p>
        <p><strong>Laptop Configuration:</strong> <?php echo htmlspecialchars($user['laptop_configuration']); ?></p>
        <p><strong>Laptop Total Amount:</strong> 
            <span id="laptop_total_amount_display"><?php echo number_format($laptop_total_amount, 2); ?></span>
            <svg class="edit-icon" onclick="showEditForm()" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16">
                <path d="M0 0h24v24H0z" fill="none"/>
                <path d="M14.06 9.94l-4.12 4.12 2.83 2.83 4.12-4.12-2.83-2.83zM18.37 3.58l1.65 1.65-2.83 2.83-1.65-1.65 2.83-2.83zM3 17.25v3h3l9.91-9.91-2.83-2.83L3 17.25z"/>
            </svg>
            <form id="edit_form" class="edit-form" method="POST" action="update_total_amount.php">
                <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                <input type="text" name="laptop_total_amount" value="<?php echo number_format($laptop_total_amount, 2); ?>">
                <button type="submit" class="btn btn-blue">Save</button>
                <button type="button" class="btn" onclick="hideEditForm()">Cancel</button>
            </form>
        </p>
        <p><strong>Total Amount Received:</strong> <?php echo number_format($total_received, 2); ?></p>
       <p><strong>Warranty Status:</strong>
    <span style="color: <?php echo (strtotime($warranty_end_date) > time()) ? 'green' : 'red'; ?>; font-weight: bold;">
        <?php echo $warranty_status; ?>
    </span>
    <?php if (strtotime($warranty_end_date) > time()) { ?>
        <svg xmlns="http://www.w3.org/2000/svg" fill="#4CAF50" viewBox="0 0 24 24" width="16px" height="16px">
            <path d="M0 0h24v24H0z" fill="none"/>
            <path d="M12 0C5.373 0 0 5.373 0 12c0 6.627 5.373 12 12 12s12-5.373 12-12C24 5.373 18.627 0 12 0zm-2 17.25l-5-5L7.43 10.82l3.07 3.07 6.57-6.57L18 8.5l-7.5 8.75z"/>
        </svg>
    <?php } ?>
</p>
        <div class="<?php echo ($installment_status === 'Installment Completed') ? 'status-completed' : 'status-pending'; ?>">
            <?php echo $installment_status; ?>
            <?php if ($installment_status === 'Installment Completed') { ?>
                <svg class="completed-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="green" width="24px" height="24px">
                    <path d="M0 0h24v24H0z" fill="none"/>
                    <path d="M12 0C5.373 0 0 5.373 0 12c0 6.627 5.373 12 12 12s12-5.373 12-12C24 5.373 18.627 0 12 0zm-2 16.9l-5-5 1.41-1.41L10 13.17l7.59-7.59L19 7l-7.5 8.75z"/>
                </svg>
            <?php } ?>
        </div>
    </div>

    <!-- Display the receipt if a new installment has been added -->
    <?php if ($new_installment): ?>
        <div class="receipt">
            <h2>OLPP - One Laptop Per Pakistani</h2>
            <p><strong>Name:</strong> <?php echo htmlspecialchars($user['name']); ?></p>
            <p><strong>Phone:</strong> <?php echo htmlspecialchars($user['phone']); ?></p>
            <p><strong>Date:</strong> <?php echo $current_date; ?></p>
            <p><strong>Amount Paid:</strong> Rs. <?php echo number_format($new_installment['amount'], 2); ?></p>
            <p><strong>Payment Method:</strong> <?php echo htmlspecialchars($new_installment['payment_method'] ?? 'Unknown'); ?></p>
            <p><strong>Transaction ID:</strong> <?php echo htmlspecialchars($new_installment['transaction_id'] ?? 'N/A'); ?></p>
            <p><strong>Remaining Balance:</strong> Rs. <?php echo number_format($laptop_total_amount - $total_received, 2); ?></p>
            <div class="signature-section">
                <p style="margin-top: 50px;"><strong>Signature:</strong> _____________________</p>
            </div>
            <div class="btn-container">
                <a href="#" class="btn btn-print" onclick="window.print()">Print Receipt</a>
            </div>
        </div>
    <?php endif; ?>

    <h2>Payment History</h2>
    <div class="table-container">
        <table>
            <tr>
                <th>Amount</th>
                <th>Due Date</th>
                <th>Payment Date</th>
                <th>Payment Method</th>
                <th>Transaction ID</th>
                <th>Remaining Amount</th>
                <th>Action</th>
            </tr>
            <?php 
            $remaining_balance = $laptop_total_amount;
            while ($installment = $installments->fetch_assoc()): 
                $remaining_balance -= $installment['amount'];
            ?>
            <tr>
                <td><?php echo number_format($installment['amount'], 2); ?></td>
                <td><?php echo htmlspecialchars($installment['due_date']); ?></td>
                <td><?php echo htmlspecialchars($installment['payment_date']); ?></td>
                <td><?php echo htmlspecialchars($installment['payment_method'] ?? 'Unknown'); ?></td>
                <td><?php echo $installment['payment_method'] === 'Bank Transfer' ? htmlspecialchars($installment['transaction_id']) : 'N/A'; ?></td>
                <td><?php echo number_format($remaining_balance, 2); ?></td>
                <td class="action-buttons">
                    <a href="edit_installment.php?id=<?php echo $installment['id']; ?>" class="btn btn-blue">
                        Edit
                    </a>
                    <a href="delete_installment.php?id=<?php echo $installment['id']; ?>&user_id=<?php echo $user_id; ?>" class="btn btn-delete" onclick="return confirm('Are you sure you want to delete this installment?');">
                        Delete
                    </a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>

    <div class="btn-container">
        <a href="add_installment.php?user_id=<?php echo $user_id; ?>" class="btn btn-blue">Add Installment</a>
        <a href="admin_dashboard.php" class="btn btn-green">Dashboard</a>
    </div>
</div>

<script>
function showEditForm() {
    document.getElementById('laptop_total_amount_display').style.display = 'none';
    document.getElementById('edit_form').style.display = 'inline-block';
}

function hideEditForm() {
    document.getElementById('edit_form').style.display = 'none';
    document.getElementById('laptop_total_amount_display').style.display = 'inline-block';
}
</script>
</body>
</html>
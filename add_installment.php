<?php
session_start();
include 'db.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: adminlogin.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_POST['user_id'];
    $amount = $_POST['amount'];
    $due_date = $_POST['due_date'];
    $payment_date = $_POST['payment_date'];
    $paid = $_POST['paid'];
    $payment_method = $_POST['payment_method'];
    $transaction_id = $payment_method === 'Bank Transfer' ? $_POST['transaction_id'] : '';

    $sql = "INSERT INTO installments (user_id, amount, due_date, payment_date, paid, payment_method, transaction_id) 
            VALUES ('$user_id', '$amount', '$due_date', '$payment_date', '$paid', '$payment_method', '$transaction_id')";

    if ($conn->query($sql) === TRUE) {
        header('Location: installment_history.php?user_id=' . $user_id . '&new_installment=' . $conn->insert_id);
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$user_id = $_GET['user_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Installment</title>
    <link rel="icon" type="image/png" href="images/web.png">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333;
        }
        .container {
            width: 60%;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        h1 {
            color: #4caf50;
            text-align: center;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="number"], input[type="date"], input[type="text"], select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            color: #fff;
            background-color: #4caf50;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            margin-top: 10px;
            width: 100%;
        }
        .btn:hover {
            background-color: #45a049;
        }
        #transaction-id-group {
            display: none;
        }
    </style>
    <script>
        function toggleTransactionIdField() {
            var paymentMethod = document.getElementById('payment_method').value;
            var transactionIdGroup = document.getElementById('transaction-id-group');
            if (paymentMethod === 'Bank Transfer') {
                transactionIdGroup.style.display = 'block';
            } else {
                transactionIdGroup.style.display = 'none';
            }
        }
    </script>
</head>
<body>
<div class="container">
    <h1>Add Installment</h1>
    <form method="POST" action="">
        <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
        <div class="form-group">
            <label for="amount">Amount:</label>
            <input type="number" name="amount" id="amount" required>
        </div>
        <div class="form-group">
            <label for="due_date">Due Date:</label>
            <input type="date" name="due_date" id="due_date" required>
        </div>
        <div class="form-group">
            <label for="payment_date">Payment Date:</label>
            <input type="date" name="payment_date" id="payment_date" required>
        </div>
        <div class="form-group">
            <label for="paid">Paid:</label>
            <select name="paid" id="paid" required>
                <option value="1">Yes</option>
                <option value="0">No</option>
            </select>
        </div>
        <div class="form-group">
            <label for="payment_method">Payment Method:</label>
            <select name="payment_method" id="payment_method" onchange="toggleTransactionIdField()" required>
                <option value="Cash">Cash</option>
                <option value="Bank Transfer">Bank Transfer</option>
            </select>
        </div>
        <div class="form-group" id="transaction-id-group">
            <label for="transaction_id">Transaction ID:</label>
            <input type="text" name="transaction_id" id="transaction_id" placeholder="Enter Transaction ID">
        </div>
        <button type="submit" class="btn">Add Installment</button>
    </form>
</div>
</body>
</html>

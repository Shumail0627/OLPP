<?php
session_start();
include 'db.php';

// Check if user_id is set
if (!isset($_GET['user_id'])) {
    die("User ID is not specified.");
}

$user_id = $_GET['user_id'];

// Fetch user details
$sql = "SELECT payment_type FROM users WHERE id='$user_id'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $payment_type = strtolower($user['payment_type']);

    if ($payment_type == 'cash') {
        header("Location: cash_payment.php?user_id=$user_id");
    } elseif ($payment_type == 'installment') {
        header("Location: installment_history.php?user_id=$user_id");
    } else {
        echo "Invalid payment type.";
    }
} else {
    echo "User not found.";
}

$conn->close();
?>

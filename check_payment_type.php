<?php
session_start();
include 'db.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: adminlogin.php');
    exit();
}

if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];
    
    // Fetch the payment type for the user
    $sql = "SELECT payment_type FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        $payment_type = $row['payment_type'];
        
        // Redirect based on payment type
        if (strtolower($payment_type) == 'installment') {
            header("Location: installment_history.php?user_id=$user_id");
        } else {
            header("Location: cash_payment.php?user_id=$user_id");
        }
    } else {
        // User not found
        echo "User not found.";
    }
} else {
    // No user ID provided
    echo "No user ID provided.";
}

$conn->close();
?>
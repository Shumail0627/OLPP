<?php
include 'db.php'; // Include your database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $customer_id = $_POST['customer_id'];

    // Update the table name to 'users'
    $query = "DELETE FROM users WHERE id='$customer_id'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to delete user']);
    }
}
?>

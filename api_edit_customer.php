<?php
include 'db.php'; // Include your database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $customer_id = $_POST['customer_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $cnic = $_POST['cnic'];

    // Update the table name to 'users'
    $query = "UPDATE users SET name='$name', email='$email', phone='$phone', cnic='$cnic' WHERE id='$customer_id'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to update user']);
    }
}
?>

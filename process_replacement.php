<?php
include 'db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_POST['user_id'];
    $replacement_date = $_POST['replacement_date'];
    $date_of_issue = $_POST['date_of_issue'];
    $laptop_model = $_POST['laptop_model'];
    $serial_number = $_POST['serial_number'];
    $original_cost = $_POST['original_cost'];
    $amount_received = $_POST['amount_received'];
    $issue = $_POST['issue'];
    $status = $_POST['status'];

    $sql = "INSERT INTO laptop_replacements (user_id, replacement_date, date_of_issue, laptop_model, serial_number, original_cost, amount_received, issue, status) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssssiss", $user_id, $replacement_date, $date_of_issue, $laptop_model, $serial_number, $original_cost, $amount_received, $issue, $status);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => $conn->error]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
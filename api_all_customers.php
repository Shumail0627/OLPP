<?php
header('Content-Type: application/json');
include 'db.php';

$sql = "SELECT * FROM users";
$result = $conn->query($sql);

$customers = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $customers[] = $row;
    }
}

echo json_encode($customers);
$conn->close();
?>
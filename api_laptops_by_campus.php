// api_laptops_by_campus.php

<?php
$campus = $_POST['campus'];

// Sample query, adapt this to your actual database schema
$query = "SELECT * FROM laptops WHERE campus = '$campus'";
$result = mysqli_query($conn, $query);

$laptops = array();
while ($row = mysqli_fetch_assoc($result)) {
    $laptops[] = $row;
}

echo json_encode(array('laptops' => $laptops));
?>

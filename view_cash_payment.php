<?php
session_start();
include 'db.php';

if (!isset($_GET['user_id'])) {
    die("User ID is not specified.");
}

$user_id = $_GET['user_id'];

// Fetch user details
$sql = "SELECT * FROM users WHERE id='$user_id'";
$result = $conn->query($sql);
$user = $result->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cash Payment Details</title>
    <link rel="icon" type="image/png" href="images/web.png">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Cash Payment Details</h1>
    <p>Name: <?php echo $user['name']; ?></p>
    <p>Email: <?php echo $user['email']; ?></p>
    <p>Phone: <?php echo $user['phone']; ?></p>
    <p>CNIC: <?php echo $user['cnic']; ?></p>
    <p>Payment Type: Full payment paid</p>
    <p>Laptop Model: <?php echo $user['laptop_model']; ?></p>
    <p>Laptop Configuration: <?php echo $user['laptop_configuration']; ?></p>
    <p>Serial Number: <?php echo $user['serial_number']; ?></p>
    <p>Payment Date: <?php echo isset($user['created_at']) ? $user['created_at'] : 'N/A'; ?></p>
</body>
</html>

<?php
$conn->close();
?>

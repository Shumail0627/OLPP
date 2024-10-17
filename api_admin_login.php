<?php
include 'db.php';

$username = $_POST['username'];
$password = $_POST['password'];

// Fetch admin data from the database
$sql = "SELECT * FROM admins WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$admin = $result->fetch_assoc();

// Debugging: Show fetched admin data
echo "Fetched Admin Data: <br>";
print_r($admin);

if ($admin) {
    // Direct password comparison (assuming passwords are not hashed)
    if ($password === $admin['password']) {
        // Password is correct
        $response = array('status' => 'success', 'message' => 'Login successful', 'role' => 'admin', 'userId' => $admin['id']);
    } else {
        // Password is incorrect
        $response = array('status' => 'error', 'message' => 'Invalid username or password');
    }
} else {
    // No admin found with that email
    $response = array('status' => 'error', 'message' => 'Invalid username or password');
}

// Debugging: Show the response
echo "Response: ";
print_r($response);

echo json_encode($response);
?>

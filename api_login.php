<?php
header('Content-Type: application/json');
include 'db.php';

if (!isset($_POST['username']) || !isset($_POST['password'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Username and password are required'
    ]);
    exit;
}

$username = $_POST['username'];
$password = $_POST['password'];

// Check in admin table first
$adminQuery = $conn->prepare("SELECT id, name, email, password FROM admins WHERE email = ?");
$adminQuery->bind_param("s", $username);
$adminQuery->execute();
$adminResult = $adminQuery->get_result();

if ($adminResult->num_rows > 0) {
    $admin = $adminResult->fetch_assoc();
    
    // Assuming passwords are stored hashed in the database
    if ($password === $admin['password']) {

        echo json_encode([
            'status' => 'success',
            'message' => 'Login successful',
            'role' => 'admin',
            'userId' => $admin['id'],
            'isAdmin' => true
        ]);
        exit();
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Invalid username or password'
        ]);
        exit();
    }
}

// Check in user table if not an admin
$userQuery = $conn->prepare("SELECT id, name, email, password FROM users WHERE email = ?");
$userQuery->bind_param("s", $username);
$userQuery->execute();
$userResult = $userQuery->get_result();

if ($userResult->num_rows > 0) {
    $user = $userResult->fetch_assoc();
    
    if (password_verify($password, $user['password'])) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Login successful',
            'role' => 'user',
            'userId' => $user['id'],
            'isAdmin' => false
        ]);
        exit();
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Invalid username or password'
        ]);
        exit();
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid username or password'
    ]);
}

$conn->close();
?>

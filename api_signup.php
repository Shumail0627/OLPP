<?php
ob_start();
include 'db.php';

// Function to sanitize input data
function sanitizeInput($conn, $data) {
    return mysqli_real_escape_string($conn, trim($data));
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = sanitizeInput($conn, $_POST['name']);
    $email = sanitizeInput($conn, $_POST['email']);
    $password = sanitizeInput($conn, $_POST['password']);
    $phone = sanitizeInput($conn, $_POST['phone']);
    $cnic = sanitizeInput($conn, $_POST['cnic']);

    // Hash the password before storing
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert user into the database
    $stmt = $conn->prepare("INSERT INTO users (name, email, password, phone, cnic) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $name, $email, $hashedPassword, $phone, $cnic);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Signup successful']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Signup failed. Please try again.']);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
ob_flush();
?>

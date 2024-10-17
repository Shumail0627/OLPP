<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM admins WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $admin = $result->fetch_assoc();
        if (md5($password) == $admin['password']) {
            $_SESSION['admin_id'] = $admin['id'];
            header('Location: admin_dashboard.php');
        } else {
            echo "<p class='error'>Invalid password</p>";
        }
    } else {
        echo "<p class='error'>No admin found with this email</p>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="Login.css">
    <link rel="icon" type="image/png" href="images/web.png">
</head>
<body>
<div class="form-container">
    <h1>Admin Login</h1>
    <form method="POST" action="">
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required>
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>
        </div>
        <button type="submit" class="btn">Login</button>
    </form>
</div>
</body>
</html>

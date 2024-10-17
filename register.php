<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $phone = $_POST['phone'];
    $cnic = $_POST['cnic'];

    $sql = "INSERT INTO users (name, email, password, phone, cnic) 
            VALUES (?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $name, $email, $password, $phone, $cnic);

    if ($stmt->execute()) {
        $_SESSION['user_id'] = $conn->insert_id;
        header('Location: order_form.php');
        exit();
    } else {
        $error_message = "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OLPP Signup</title>
    <link rel="icon" type="image/png" href="images/web.png">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #c8e6c9, #4caf50);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .signup-container {
            background-color: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            padding: 2rem;
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            width: 300px;
        }
        .logo {
            width: 80px;
            height: 80px;
            background-color: rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            margin: 0 auto 20px;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .logo img {
            max-width: 60px;
            max-height: 60px;
        }
        h1 {
            color: #4caf50;
            text-align: center;
            margin-bottom: 20px;
        }
        .input-group {
            position: relative;
            margin-bottom: 1.5rem;
        }
        input {
            width: 100%;
            padding: 12px 40px 12px 16px;
            border: none;
            border-radius: 25px;
            font-size: 16px;
            background-color: rgba(255, 255, 255, 0.3);
            color: #333;
            box-sizing: border-box;
        }
        input::placeholder {
            color: rgba(0, 0, 0, 0.5);
        }
        .input-icon {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(0, 0, 0, 0.5);
        }
        .signup-btn {
            width: 100%;
            padding: 12px;
            background-color: #4caf50;
            color: white;
            border: none;
            border-radius: 25px;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .signup-btn:hover {
            background-color: #45a049;
        }
        .login-link {
            text-align: center;
            margin-top: 1rem;
            color: #ffffff;
        }
        a {
            color: #ffffff;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="signup-container">
        <div class="logo">
            <img src="./images/web.png" alt="OLPP Logo">
        </div>
        <h1>Welcome to OLPP</h1>
        <form method="POST" action="">
            <div class="input-group">
                <input type="text" name="name" id="name" required placeholder="Name">
                <i class="input-icon fas fa-user"></i>
            </div>
            <div class="input-group">
                <input type="email" name="email" id="email" required placeholder="Email">
                <i class="input-icon fas fa-envelope"></i>
            </div>
            <div class="input-group">
                <input type="tel" name="phone" id="phone" required placeholder="Phone">
                <i class="input-icon fas fa-phone"></i>
            </div>
            <div class="input-group">
                <input type="text" name="cnic" id="cnic" required placeholder="CNIC">
                <i class="input-icon fas fa-id-card"></i>
            </div>
            <div class="input-group">
                <input type="password" name="password" id="password" required placeholder="Password">
                <i class="input-icon fas fa-lock"></i>
            </div>
            <button type="submit" class="signup-btn">Sign Up</button>
        </form>
        <p class="login-link">Already have an account? <a href="login.php">Login</a></p>
    </div>
    <script src="https://kit.fontawesome.com/your-fontawesome-kit.js" crossorigin="anonymous"></script>
</body>
</html>
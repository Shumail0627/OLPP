<?php
session_start();
include 'db.php';

$error_message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // First, check in the admins table
    $admin_sql = "SELECT * FROM admins WHERE email = ?";
    $admin_stmt = $conn->prepare($admin_sql);
    $admin_stmt->bind_param("s", $email);
    $admin_stmt->execute();
    $admin_result = $admin_stmt->get_result();

    if ($admin_result->num_rows > 0) {
        $admin = $admin_result->fetch_assoc();
        if (md5($password) === $admin['password']) {
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_name'] = $admin['name'];
            header('Location: admin_dashboard.php');
            exit();
        } else {
            $error_message = "Invalid email or password";
        }
    } else {
        // If not an admin, check the users table
        $user_sql = "SELECT * FROM users WHERE email = ?";
        $user_stmt = $conn->prepare($user_sql);
        $user_stmt->bind_param("s", $email);
        $user_stmt->execute();
        $user_result = $user_stmt->get_result();

        if ($user_result->num_rows > 0) {
            $user = $user_result->fetch_assoc();
            if (password_verify($password, $user['password']) || $password === $user['password']) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                header('Location: dashboard.php');
                exit();
            } else {
                $error_message = "Invalid email or password";
            }
        } else {
            $error_message = "Invalid email or password";
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="images/web.png">
    <title>OLPP Login</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
        .login-container {
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
        .forgot-password {
            text-align: right;
            margin-bottom: 1rem;
        }
        .login-btn {
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
        .login-btn:hover {
            background-color: #45a049;
        }
        .signup-link {
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
    <div class="login-container">
        <div class="logo">
            <img src="./images/web.png" alt="OLPP Logo">
        </div>
        <h1>Welcome to OLPP</h1>
        <form method="POST" action="">
            <div class="input-group">
                <input type="email" name="email" id="email" required placeholder="Email">
            </div>
            <div class="input-group">
                <input type="password" name="password" id="password" required placeholder="Password">
            </div>
            <div class="forgot-password">
                <a href="forgot-password.php">Forgot Password?</a>
            </div>
            <button type="submit" class="login-btn">LOGIN</button>
        </form>
        <p class="signup-link">Don't have an account? <a href="register.php">Sign up</a></p>
    </div>

    <script>
    <?php if ($error_message): ?>
    Swal.fire({
        title: 'Error!',
        text: '<?php echo $error_message; ?>',
        icon: 'error',
        confirmButtonText: 'OK',
        confirmButtonColor: '#4caf50'
    });
    <?php endif; ?>
    </script>
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to OLPP</title>
    <link rel="icon" type="image/png" href="images/web.png">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #c8e6c9, #4caf50);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            padding: 40px;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #4caf50;
            margin-bottom: 20px;
        }
        p {
            color: #333;
            margin-bottom: 30px;
        }
        .btn {
            padding: 12px 24px;
            margin: 10px;
            color: #fff;
            background-color: #4caf50;
            border: none;
            border-radius: 25px;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .btn:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Welcome to OLPP</h1>
    <p>Empower your learning journey with One Laptop Per Pakistani. Choose an option to get started:</p>
    <a href="login.php" class="btn">Login</a>
    <a href="signup.php" class="btn">Sign Up</a>
</div>
</body>
</html>
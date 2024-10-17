<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

include 'db.php';

$user_id = $_SESSION['user_id'];

// Fetch user information
$sql = "SELECT * FROM users WHERE id='$user_id'";
$result = $conn->query($sql);
$user = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_BCRYPT) : $user['password'];

    $update_sql = "UPDATE users SET name=?, email=?, phone=?, password=? WHERE id=?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("ssssi", $name, $email, $phone, $password, $user_id);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Profile updated successfully!";
        header('Location: profile.php');
        exit;
    } else {
        $error = "Error updating profile: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333;
        }

        .container {
            width: 80%;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        h1, h2 {
            color: #333;
        }

        h1 {
            border-bottom: 2px solid #4caf50;
            padding-bottom: 10px;
        }

        .navbar {
            background-color: #4caf50;
            padding: 10px;
            text-align: center;
            margin-bottom: 20px;
        }

        .navbar a {
            color: #fff;
            text-decoration: none;
            padding: 10px 20px;
            display: inline-block;
            margin: 0 10px;
        }

        .navbar a:hover {
            background-color: #45a049;
            border-radius: 4px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"], input[type="email"], input[type="password"] {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .btn {
            padding: 10px 15px;
            color: #fff;
            background-color: #4caf50;
            border: none;
            border-radius: 4px;
            text-decoration: none;
            display: inline-block;
            text-align: center;
            cursor: pointer;
        }

        .btn:hover {
            background-color: #45a049;
        }

    </style>
</head>
<body>
    <div class="navbar">
        <a href="dashboard.php">Dashboard</a>
        <a href="profile.php">Profile</a>
        <a href="support.php">Support</a>
        <a href="logout.php">Logout</a>
    </div>

    <div class="container">
        <h1>Profile Management</h1>

        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-success">
                <?php echo $_SESSION['message']; unset($_SESSION['message']); ?>
            </div>
        <?php endif; ?>

        <form method="post" action="profile.php">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>
            <div class="form-group">
                <label for="phone">Phone</label>
                <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" required>
            </div>
            <div class="form-group">
                <label for="password">New Password (leave blank if you don't want to change)</label>
                <input type="password" id="password" name="password">
            </div>
            <button type="submit" class="btn">Update Profile</button>
        </form>
    </div>
</body>
</html>

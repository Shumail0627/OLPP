<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Support</title>
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

        input[type="text"], input[type="email"], textarea {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        textarea {
            resize: vertical;
            height: 150px;
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
        <h1>Support</h1>
        <form method="post" action="support.php">
            <div class="form-group">
                <label for="subject">Subject</label>
                <input type="text" id="subject" name="subject" required>
            </div>
            <div class="form-group">
                <label for="message">Message</label>
                <textarea id="message" name="message" required></textarea>
            </div>
            <button type="submit" class="btn">Submit</button>
        </form>
    </div>
</body>
</html>
<?php
session_start();
include 'db.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: adminlogin.php');
    exit();
}

// Fetch all laptops data
$sql = "SELECT name, laptop_model, serial_number, location, laptop_status FROM users WHERE laptop_model != '' ORDER BY location, laptop_status";
$all_laptops = $conn->query($sql);

// Include the location names array
$location_names = [
    'SMI' => 'Munawwar Campus',
    'RSK' => 'Korangi Campus',
    'Online' => 'Online Academy',
    'Other' => 'Other'
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Laptops - OLPP Admin</title>
    <link rel="icon" type="image/png" href="images/web.png">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        /* Navbar Styling */
        .navbar {
            background-color: #4caf50;
            overflow: hidden;
            padding: 10px;
        }

        .navbar .container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }

        .navbar .navbar-brand {
            display: flex;
            align-items: center;
        }

        .navbar .navbar-brand .logo-bold {
            font-weight: bold;
            color: #000;
            font-size: 24px;
            margin-right: 5px;
        }

        .navbar .navbar-brand .logo-thin {
            font-weight: 300;
            color: #000;
            font-size: 16px;
        }

        .navbar .navbar-menu {
            list-style-type: none;
            margin: 0;
            padding: 0;
            display: flex;
        }

        .navbar .navbar-menu li {
            margin-left: 20px;
        }

        .navbar .navbar-menu li a {
            color: #000;
            text-decoration: none;
            padding: 10px;
        }

        .navbar .navbar-menu li a:hover {
            background-color: #575757;
            border-radius: 5px;
        }

        .navbar .navbar-search {
            display: flex;
            align-items: center;
            margin-top: 10px;
        }

        .navbar .navbar-search input[type="text"] {
            padding: 5px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
        }

        .navbar .navbar-search button {
            padding: 6px 10px;
            font-size: 16px;
            background-color: #4CAF50;
            color: #fff;
            border: none;
            border-radius: 5px;
            margin-left: 5px;
            cursor: pointer;
        }

        .navbar .navbar-search button:hover {
            background-color: #45a049;
        }

        
        .container {
            width: 90%;
            max-width: 1200px;
            margin: 2rem auto;
            background-color: #fff;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #4caf50;
            text-align: center;
            margin-bottom: 2rem;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 2rem;
            background-color: #fff;
        }

        .table th,
        .table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .table th {
            background-color: #4caf50;
            color: #fff;
            font-weight: bold;
            text-transform: uppercase;
        }

        .table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .table tr:hover {
            background-color: #e0e0e0;
            transition: background-color 0.3s ease;
        }

        .btn {
            display: inline-block;
            background-color: #4caf50;
            color: #fff;
            padding: 0.75rem 1.5rem;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #45a049;
        }

        /* Responsive styles */
        @media screen and (max-width: 768px) {
            .table {
                font-size: 0.9rem;
            }

            .table th,
            .table td {
                padding: 0.75rem;
            }

            .container {
                padding: 1rem;
            }
        }

        @media screen and (max-width: 480px) {
            .table {
                font-size: 0.8rem;
            }

            .table th,
            .table td {
                padding: 0.5rem;
            }

            .btn {
                display: block;
                text-align: center;
                margin-top: 1rem;
            }
        }
    </style>
</head>
<body>

    <!-- Navigation Bar -->
    <nav class="navbar">
        <div class="container">
            <div class="navbar-brand">
                <span class="logo-bold">OLPP</span>
                <span class="logo-thin">One Laptop Per Pakistani</span>
            </div>
            <ul class="navbar-menu">
                <li><a href="admin_dashboard.php">Dashboard</a></li>
                <li><a href="all_customers.php">Customers</a></li>
                <li><a href="orders.php">Orders</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
            <form class="navbar-search" method="GET" action="search_results.php">
                <input type="text" name="query" placeholder="Search..." id="search-box" required>
                <button type="submit">Search</button>
            </form>
        </div>
    </nav>

    <div class="container">
        <h1>All Laptops</h1>
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Laptop Model</th>
                    <th>Serial Number</th>
                    <th>Location</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($laptop = $all_laptops->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($laptop['name']); ?></td>
                    <td><?php echo htmlspecialchars($laptop['laptop_model']); ?></td>
                    <td><?php echo htmlspecialchars($laptop['serial_number']); ?></td>
                    <td><?php echo htmlspecialchars($location_names[$laptop['location']]); ?></td>
                    <td><?php echo htmlspecialchars($laptop['laptop_status']); ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <a href="admin_dashboard.php" class="btn">Back to Dashboard</a>
    </div>
</body>
</html>
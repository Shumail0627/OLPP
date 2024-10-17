<?php
session_start();
include 'db.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: adminlogin.php');
    exit();
}

$notification = "";

// Handle form submission to add a new customer
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_customer'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $cnic = $_POST['cnic'];
    $payment_type = $_POST['payment_type'];
    $laptop_model = $_POST['laptop_model'];
    $laptop_configuration = $_POST['laptop_configuration'];
    $serial_number = $_POST['serial_number'];
    $laptop_total_amount = $_POST['laptop_total_amount'];
    $address = $_POST['address'];
    $location = $_POST['location'];
    $laptop_status = $_POST['laptop_status'];

    $sql = "INSERT INTO users (name, email, phone, cnic, address, payment_type, laptop_model, laptop_configuration, serial_number, laptop_total_amount, location, laptop_status) 
            VALUES ('$name', '$email', '$phone', '$cnic', '$address', '$payment_type', '$laptop_model', '$laptop_configuration', '$serial_number', '$laptop_total_amount', '$location', '$laptop_status')";

    if ($conn->query($sql) === TRUE) {
        $notification = "New customer added successfully!";
    } else {
        $notification = "Error: " . $sql . "<br>" . $conn->error;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_replacement'])) {
    $user_id = $_POST['user_id'];
    $replacement_date = $_POST['replacement_date'];
    $date_of_issue = $_POST['date_of_issue'];  // New line
    $laptop_model = $_POST['laptop_model'];
    $serial_number = $_POST['serial_number'];
    $original_cost = $_POST['original_cost'];
    $amount_received = $_POST['amount_received'];
    $issue = $_POST['issue'];
    $status = $_POST['status'];

    $sql = "INSERT INTO laptop_replacements (user_id, replacement_date, date_of_issue, laptop_model, serial_number, original_cost, amount_received, issue, status) 
            VALUES ('$user_id', '$replacement_date', '$date_of_issue', '$laptop_model', '$serial_number', '$original_cost', '$amount_received', '$issue', '$status')";

     if ($conn->query($sql) === TRUE) {
        echo "Replacement request added successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Fetch all laptops data (limited to 10)
$sql = "SELECT name, laptop_model, serial_number, location, laptop_status FROM users WHERE laptop_model != '' ORDER BY id DESC LIMIT 10";
$all_laptops = $conn->query($sql);

// Fetch total count of laptops
$sql_count = "SELECT COUNT(*) as total FROM users WHERE laptop_model != ''";
$result_count = $conn->query($sql_count);
$total_laptops_count = $result_count->fetch_assoc()['total'];

// Fetch limited users
$limit = 10;
$sql = "SELECT * FROM users LIMIT $limit";
$users = $conn->query($sql);

// Fetch inventory
$sql = "SELECT * FROM inventory";
$inventory = $conn->query($sql);

// Fetch pending orders
$sql = "SELECT orders.*, users.name, users.email, users.phone, users.cnic 
        FROM orders 
        JOIN users ON orders.user_id = users.id 
        WHERE orders.status = 'Pending'";
$orders = $conn->query($sql);

// Fetch laptop distribution data
$sql = "SELECT location, COUNT(*) as count FROM users WHERE laptop_model != '' GROUP BY location";
$laptop_distribution = $conn->query($sql);

$location_names = [
    'SMI' => 'Munawwar Campus',
    'RSK' => 'Korangi Campus',
    'Online' => 'Online Academy',
    'Other' => 'On Installment'  // Change 'Other' to 'On Installment'
];
// Fetch all laptops data
$sql = "SELECT name, laptop_model, serial_number, location, laptop_status FROM users WHERE laptop_model != '' ORDER BY location, laptop_status";
$all_laptops = $conn->query($sql);

// Initialize the total laptops count
$total_laptops = 0;

// Calculate the total number of laptops
$locations = ['SMI', 'RSK', 'Online', 'Other'];
$laptop_counts = []; // Array to store the laptop counts for each location

foreach ($locations as $location) {
    $count = 0;
    foreach ($laptop_distribution as $row) {
        if ($row['location'] == $location) {
            $count = $row['count'];
            break;
        }
    }
    $laptop_counts[$location] = $count;
    $total_laptops += $count; // Add to the total laptops count
}

$sql_analytics = "SELECT MONTH(replacement_date) as month, COUNT(*) as count 
                  FROM laptop_replacements 
                  WHERE YEAR(replacement_date) = YEAR(CURDATE()) 
                  GROUP BY MONTH(replacement_date)";
$analytics_result = $conn->query($sql_analytics);

$chart_data = array_fill(0, 12, 0); // Initialize with 0 for all 12 months
while ($row = $analytics_result->fetch_assoc()) {
    $chart_data[$row['month'] - 1] = $row['count'];
}
$chart_data_json = json_encode($chart_data);

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" type="image/png" href="images/web.png">
    <title>Admin Dashboard</title>
    <style>
        /* Global Styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
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

        /* Main Content Styling */
        .main-content {
            padding: 20px;
        }

        .main-content h1 {
            margin-top: 0;
        }

        /* Date Picker Styling */
input[type="date"] {
    width: 100%;
    padding: 12px;
    font-size: 16px;
    border-radius: 4px;
    border: 1px solid #ccc;
    background-color: #fff;
    color: #555;
    box-sizing: border-box;
    transition: border-color 0.3s ease;
    appearance: none; /* Removes the default browser styling */
    -webkit-appearance: none; /* For Safari */
    position: relative;
}

/* Adding a calendar icon */
input[type="date"]::-webkit-calendar-picker-indicator {
    color: #4CAF50;
    opacity: 1;
    cursor: pointer;
}

/* Date Picker Focus */
input[type="date"]:focus {
    border-color: #4caf50;
    outline: none;
}

/* Date Picker Container Styling */
.date-picker-container {
    position: relative;
}

.date-picker-container input[type="date"] {
    padding-right: 40px; /* Adding space for the calendar icon */
}

.date-picker-container::before {
    content: "\f073"; /* Unicode for a calendar icon (FontAwesome) */
    font-family: FontAwesome;
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    color: #4caf50;
    font-size: 18px;
    pointer-events: none;
}

/* Date picker for smaller devices */
@media (max-width: 768px) {
    input[type="date"] {
        padding: 10px;
        font-size: 14px;
    }
}

        /* Table Styling */
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .table th, .table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .table tr:hover {
            background-color: #ddd;
        }

        /* Responsive Table Styling */
        @media (max-width: 768px) {
            .table, .table-inventory {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
            }

            .table th, .table td, .table-inventory th, .table-inventory td {
                display: block;
                text-align: right;
                padding-left: 50%;
                position: relative;
            }

            .table th::before, .table td::before, .table-inventory th::before, .table-inventory td::before {
                content: attr(data-label);
                position: absolute;
                left: 10px;
                width: 45%;
                padding-right: 10px;
                white-space: nowrap;
                text-align: left;
                font-weight: bold;
            }
        }

        /* Mobile-specific adjustments */
        @media (max-width: 480px) {
            .navbar .navbar-brand .logo-bold, .navbar .navbar-brand .logo-thin {
                font-size: 18px;
            }

            .table th, .table td, .table-inventory th, .table-inventory td {
                padding-left: 45%;
            }

            .navbar .navbar-menu {
                display: block;
                text-align: center;
            }

            .navbar .navbar-menu li {
                margin: 5px 0;
            }

            .navbar .navbar-search {
                display: block;
                margin-top: 10px;
            }
        }

        /* Button Styling */
        .btn {
            background-color: #4caf50;
            color: white;
            padding: 10px;
            text-decoration: none;
            border-radius: 4px;
            display: inline-block;
            text-align: center;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        /* Form Styling */
        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 16px;
        }

        /* Notification Styling */
        .notification {
            position: fixed;
            top: 10px;
            right: 10px;
            background-color: #4CAF50;
            color: white;
            padding: 15px;
            border-radius: 4px;
            z-index: 1000;
            display: none;
            transition: opacity 1s ease;
        }

        /* Container Styling */
        .container {
            width: 90%;
            max-width: 1200px;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        /* Responsive Styling */
        @media (max-width: 768px) {
            .navbar .container {
                flex-direction: column;
            }

            .navbar .navbar-menu {
                margin-top: 10px;
                flex-direction: column;
            }

            .navbar .navbar-menu li {
                margin: 5px 0;
            }

            .navbar .navbar-search {
                margin-top: 10px;
                width: 100%;
            }

            .navbar .navbar-search input[type="text"] {
                width: 70%;
            }

            .table {
                border: 0;
            }

            .table thead {
                display: none;
            }

            .table tr {
                margin-bottom: 10px;
                display: block;
                border-bottom: 2px solid #ddd;
            }

            .table td {
                display: block;
                text-align: right;
                font-size: 13px;
                border-bottom: 1px dotted #ccc;
            }

            .table td:last-child {
                border-bottom: 0;
            }

            .table td:before {
                content: attr(data-label);
                float: left;
                text-transform: uppercase;
                font-weight: bold;
            }

            .btn {
                display: block;
                width: 100%;
                margin-bottom: 10px;
            }

            .form-group input[type="text"],
            .form-group input[type="email"],
            .form-group input[type="password"],
            .form-group select {
                font-size: 14px;
            }
        }

        /* Chart Container */
        .chart-container {
            width: 80%;
            margin: 50px auto;
        }

 .card-container {
    display: flex;
    justify-content: space-around;
    flex-wrap: wrap;
    margin-bottom: 20px;
}

.card {
    background: linear-gradient(135deg, #4caf50 30%, #a8e063 100%);
    border-radius: 15px;
    padding: 20px;
    text-align: center;
    width: 230px;
    margin: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    color: white;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.card:hover {
    transform: translateY(-10px);
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
}

.card h3 {
    margin-top: 10px;
    font-size: 28px;
    font-weight: bold;
    color: white;
}

.card p {
    margin-top: 5px;
    font-size: 16px;
    font-weight: 400;
    color: white;
}

.card-icon {
    font-size: 40px;
    color: white;
    margin-bottom: 20px;
}

/* Specific colors for each card based on the type */
.card-tariff {
    background: linear-gradient(135deg, #17a2b8 30%, #2ac8e3 100%);
}

.card-required-price {
    background: linear-gradient(135deg, #dc3545 30%, #f26363 100%);
}

.card-detailed-payments {
    background: linear-gradient(135deg, #6f42c1 30%, #9f5eed 100%);
}

.card-force-tariff {
    background: linear-gradient(135deg, #e83e8c 30%, #f061ab 100%);
}

.site-footer {
    background-color: #4caf50;
    color: #fff;
    padding: 40px 0 20px;
    font-family: Arial, sans-serif;
}

.footer-content {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-around;
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

.footer-section {
    flex: 1;
    margin-bottom: 20px;
    min-width: 200px;
    padding: 0 15px;
}

.footer-section h3 {
    font-size: 18px;
    margin-bottom: 15px;
    color: #fff;
}

.footer-section p, .footer-section ul {
    font-size: 14px;
    line-height: 1.6;
}

.footer-section ul {
    list-style-type: none;
    padding: 0;
}

.footer-section ul li {
    margin-bottom: 8px;
}

.footer-section ul li a {
    color: #fff;
    text-decoration: none;
    transition: color 0.3s ease;
}

.footer-section ul li a:hover {
    color: #e0e0e0;
}

.social-icons {
    display: flex;
    gap: 10px;
}

.social-icon {
    color: #fff;
    font-size: 20px;
    transition: color 0.3s ease;
}

.social-icon:hover {
    color: #e0e0e0;
}

.footer-bottom {
    text-align: center;
    padding-top: 20px;
    margin-top: 20px;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
}

.footer-bottom p {
    font-size: 14px;
}

@media (max-width: 768px) {
    .footer-content {
        flex-direction: column;
    }

    .footer-section {
        margin-bottom: 30px;
    }
}

    </style>
    <!-- Include Chart.js library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
                <li><a href="get_replacement_details.php">Replacement</a></li>
                <li><a href="laptop_summary.php">Laptop Summary</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
            <form class="navbar-search" method="GET" action="search_results.php">
                <input type="text" name="query" placeholder="Search..." id="search-box" required>
                <button type="submit">Search</button>
            </form>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container">
        <div class="notification" id="notification"><?php echo $notification; ?></div>
        <h1>OLPP Admin Dashboard</h1>

        <!-- Total Laptops -->
        <section id="total-laptops">
            <h2>Total Laptops Distributed: <?php echo $total_laptops; ?></h2>
        </section>

       <!-- Laptop Distribution Section -->
    <h<section id="laptop-distribution">
    <h2>Laptop Distribution</h2>
    <div class="card-container">
        <?php
        foreach ($locations as $location) {
            $count = $laptop_counts[$location];
            $display_name = $location_names[$location] ?? $location;
            echo "<div class='card'>";
            echo "<a href='laptop_by_campus.php?location=$location' style='text-decoration: none; color: inherit;'>";
            echo "<h3>$display_name</h3>";  // This will now display 'On Installment' instead of 'Other'
            echo "<p>$count laptops</p>";
            echo "</a>";
            echo "</div>";
        }
        ?>
    </div>
</section>

        <!-- All Laptops Section -->
<section id="all-laptops">
    <h2>All Laptops</h2>
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
            <?php
            $limit = 5; // Set the number of rows to display initially
            $count = 0;
            $all_laptops->data_seek(0); // Reset the result set pointer
            while ($laptop = $all_laptops->fetch_assoc()) {
                if ($count >= $limit) break;
                echo "<tr>";
                echo "<td>" . $laptop['name'] . "</td>";
                echo "<td>" . $laptop['laptop_model'] . "</td>";
                echo "<td>" . $laptop['serial_number'] . "</td>";
                echo "<td>" . $location_names[$laptop['location']] . "</td>";
                echo "<td>" . $laptop['laptop_status'] . "</td>";
                echo "</tr>";
                $count++;
            }
            ?>
        </tbody>
    </table>
    <a href="all_laptops.php" class="btn">View All Laptops (<?php echo $total_laptops_count; ?>)</a>
</section>

        <!-- Customer Management Section -->
        <section id="customer-management">
            <h2>Customer Management</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>CNIC</th>
                        <th>Payment Type</th>
                        <th>Laptop Model</th>
                        <th>Serial Number</th>
                        <th>Location</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($user = $users->fetch_assoc()): ?>
                    <tr>
                        <td data-label="Name"><?php echo $user['name']; ?></td>
                        <td data-label="Email"><?php echo $user['email']; ?></td>
                        <td data-label="Phone"><?php echo $user['phone']; ?></td>
                        <td data-label="CNIC"><?php echo $user['cnic']; ?></td>
                        <td data-label="Payment Type"><?php echo $user['payment_type']; ?></td>
                        <td data-label="Laptop Model"><?php echo $user['laptop_model']; ?></td>
                        <td data-label="Serial Number"><?php echo $user['serial_number']; ?></td>
                        <td data-label="Location"><?php echo $user['location']; ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <a href="all_customers.php" class="btn">View All Customers</a>
        </section>

        <!-- Add New Customer Section -->
        <section id="add-customer">
            <h2>Add New Customer</h2>
            <form method="POST" action="">
                <input type="hidden" name="add_customer" value="1">
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" name="name" id="name" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" name="email" id="email" required>
                </div>
                <div class="form-group">
                    <label for="phone">Phone:</label>
                    <input type="text" name="phone" id="phone" required>
                </div>
                <div class="form-group">
                    <label for="cnic">CNIC:</label>
                    <input type="text" name="cnic" id="cnic" required>
                </div>
                <div class="form-group">
                    <label for="address">Address:</label>
                    <input type="text" name="address" id="address" required>
                </div>
                <div class="form-group">
                    <label for="payment_type">Payment Type:</label>
                    <select name="payment_type" id="payment_type" required>
                        <option value="cash">Cash</option>
                        <option value="installment">Installment</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="laptop_model">Laptop Model:</label>
                    <input type="text" name="laptop_model" id="laptop_model" required>
                </div>
                <div class="form-group">
                    <label for="laptop_configuration">Laptop Configuration:</label>
                    <input type="text" name="laptop_configuration" id="laptop_configuration" required>
                </div>
                <div class="form-group">
                    <label for="serial_number">Serial Number:</label>
                    <input type="text" name="serial_number" id="serial_number" required>
                </div>
                <div class="form-group">
                    <label for="laptop_total_amount">Laptop Selling Rate:</label>
                    <input type="number" name="laptop_total_amount" id="laptop_total_amount" required>
                </div>
                <div class="form-group">
                    <label for="location">Location:</label>
                    <select name="location" id="location" required>
                        <option value="SMI">Munawwar Campus</option>
                        <option value="RSK">Korangi Campus</option>
                        <option value="Online">Online Academy</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="laptop_status">Laptop Status:</label>
                    <select name="laptop_status" id="laptop_status" required>
                        <option value="Issued">Issued</option>
                        <option value="In Stock">In Stock</option>
                        <option value="Under Repair">Under Repair</option>
                    </select>
                </div>
                <button type="submit" class="btn">Add Customer</button>
            </form>
        </section>

        <!-- Pending Orders Section -->
        <section id="pending-orders">
            <h2>Pending Orders</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>CNIC</th>
                        <th>Laptop Model</th>
                        <th>Serial Number</th>
                        <th>Payment Type</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($order = $orders->fetch_assoc()): ?>
                    <tr>
                        <td data-label="Name"><?php echo $order['name']; ?></td>
                        <td data-label="Email"><?php echo $order['email']; ?></td>
                        <td data-label="Phone"><?php echo $order['phone']; ?></td>
                        <td data-label="CNIC"><?php echo $order['cnic']; ?></td>
                        <td data-label="Laptop Model"><?php echo $order['laptop_model']; ?></td>
                        <td data-label="Serial Number"><?php echo $order['serial_number']; ?></td>
                        <td data-label="Payment Type"><?php echo $order['payment_type']; ?></td>
                        <td data-label="Action"><a href="approve_order.php?order_id=<?php echo $order['id']; ?>" class="btn">Approve</a></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </section>

        <div class="container">
        <h2>Laptop Replacement Analytics</h2>
        <canvas id="replacementChart"></canvas>
    </div>

    <!-- New section for View All Replacements button -->
<div style="text-align: center; margin-top: 20px; margin-bottom: 20px;">
    <a href="get_replacement_details.php" class="btn btn-primary" style="display: inline-block; padding: 10px 20px; font-size: 16px; text-decoration: none; background-color: #4caf50; color: white; border-radius: 5px; transition: background-color 0.3s;">View All Replacements</a>
</div>

    <div id="replacementDetails" style="display: none;">
        <!-- This div will be populated with replacement details and form -->
    </div>


        <!-- Inventory Management Section -->
        <section id="inventory-management">
            <h2>Inventory Management</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>Model</th>
                        <th>Configuration</th>
                        <th>Serial Number</th>
                        <th>Quantity</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($item = $inventory->fetch_assoc()): ?>
                    <tr>
                        <td data-label="Model"><?php echo $item['model']; ?></td>
                        <td data-label="Configuration"><?php echo $item['configuration']; ?></td>
                        <td data-label="Serial Number"><?php echo $item['serial_number']; ?></td>
                        <td data-label="Quantity"><?php echo $item['quantity']; ?></td>
                        <td data-label="Action"><a href="edit_inventory.php?id=<?php echo $item['id']; ?>" class="btn">Edit</a></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </section>

        

    <footer class="site-footer">
    <div class="footer-content">
        <div class="footer-section">
            <h3>About OLPP</h3>
            <p>One Laptop Per Pakistani (OLPP) is an initiative to provide affordable laptops to every Pakistani student and professional.</p>
        </div>
        <div class="footer-section">
            <h3>Quick Links</h3>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="about.php">About Us</a></li>
                <li><a href="laptops.php">Our Laptops</a></li>
                <li><a href="contact.php">Contact Us</a></li>
            </ul>
        </div>
        <div class="footer-section">
            <h3>Contact Info</h3>
            <p>Email: info@olpp.com</p>
            <p>Phone: +92 300 1234567</p>
            <p>Address: 123 Main St, Karachi, Pakistan</p>
        </div>
        <div class="footer-section">
            <h3>Follow Us</h3>
            <div class="social-icons">
                <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
                <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
                <a href="#" class="social-icon"><i class="fab fa-linkedin-in"></i></a>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <p>&copy; 2024 One Laptop Per Pakistani. All rights reserved.</p>
    </div>
</footer>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const notification = document.getElementById('notification');
        if (notification.innerHTML.trim() !== "") {
            notification.style.display = 'block';
            setTimeout(() => {
                notification.style.opacity = '0';
                setTimeout(() => {
                    notification.style.display = 'none';
                }, 1000);
            }, 3000);
        }

        // Sales Chart
        const ctx = document.getElementById('salesChart').getContext('2d');
        const salesChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Total Cost', 'Total Selling Amount', 'Total Profit'],
                datasets: [{
                    label: 'Amount (PKR)',
                    data: [<?php echo $total_cost; ?>, <?php echo $total_selling_amount; ?>, <?php echo $total_profit; ?>],
                    backgroundColor: ['#ff9800', '#007bff', '#4caf50'],
                    borderColor: ['#ff5722', '#0056b3', '#388e3c'],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                    }
                }
            }
        });
    });

    // Chart initialization
    var ctx = document.getElementById('replacementChart').getContext('2d');
    var chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [{
                label: 'Laptop Replacements',
                data: <?php echo $chart_data_json; ?>,
                backgroundColor: 'rgba(75, 192, 192, 0.6)'
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    precision: 0
                }
            },
            onClick: (event, elements) => {
                if (elements.length > 0) {
                    var monthIndex = elements[0].index;
                    loadReplacementDetails(monthIndex + 1);
                }
            }
        }
    });

    function loadReplacementDetails(month) {
        // AJAX call to fetch replacement details and form
        fetch('get_replacement_details.php?month=' + month)
            .then(response => response.text())
            .then(data => {
                document.getElementById('replacementDetails').innerHTML = data;
                document.getElementById('replacementDetails').style.display = 'block';
            });
    }
    </script>
</body>
</html>
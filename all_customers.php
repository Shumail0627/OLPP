<?php
session_start();
include 'db.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: adminlogin.php');
    exit();
}

// Handle deletion
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $sql = "DELETE FROM users WHERE id='$delete_id'";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('User deleted successfully');</script>";
    } else {
        echo "<script>alert('Error deleting user: " . $conn->error . "');</script>";
    }
}

// Fetch the total number of customers
$sql_total_customers = "SELECT COUNT(*) AS total FROM users";
$result_total_customers = $conn->query($sql_total_customers);
$total_customers = $result_total_customers->fetch_assoc()['total'];

// Fetch all users
$sql = "SELECT * FROM users";
$users = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="images/web.png">
    <title>All Customers</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }

        /* Navbar Styling */
        .navbar {
            background-color: #4caf50;
            overflow: hidden;
            padding: 10px;
            color: white;
        }

        .navbar .container {
            display: flex;
            justify-content: space-between;
            align-items: center;
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


            /* Total Customers Styling */
        .total-customers {
            text-align: center;
            font-size: 18px;
            margin-bottom: 30px;
            color: #333;
        }


        /* Main Content Styling */
        .container {
            width: 100%;
            max-width: 1200px; /* Ensures it doesn't stretch too much */
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        /* Table Styling */
table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
    table-layout: fixed; /* Ensure equal distribution of width */
}

table, th, td {
    border: 1px solid #ddd;
}

th, td {
    padding: 12px;
    text-align: left;
    word-wrap: break-word; /* Prevent overflow by wrapping text */
}

th {
    background-color: #f2f2f2;
}

/* Actions column width adjustment */
td.actions {
    width: 120px; /* Make sure there's enough width for the buttons */
    text-align: center;
}

/* Buttons Styling */
.btn {
    background-color: #4caf50;
    color: white;
    padding: 8px 12px;
    text-decoration: none;
    border-radius: 4px;
    display: inline-block;
    text-align: center;
    margin: 2px 0; /* Space between buttons */
    white-space: nowrap; /* Prevent text from wrapping */
}

.btn:hover {
    background-color: #0056b3;
}

.btn-delete {
    background-color: #f44336;
}

.btn-delete:hover {
    background-color: #d32f2f;
}

/* Responsive Design */
@media (max-width: 768px) {
    .container {
        padding: 10px;
    }

    table, thead, tbody, th, td, tr {
        display: block;
    }

    th {
        display: none;
    }

    td {
        display: block;
        position: relative;
        padding-left: 50%;
        text-align: right;
        border: none;
        border-bottom: 1px solid #ddd;
    }

    td:before {
        content: attr(data-label);
        position: absolute;
        left: 0;
        width: 50%;
        padding-left: 10px;
        font-weight: bold;
        text-align: left;
    }

    td.actions {
        text-align: center;
    }
}

@media (max-width: 480px) {
    .container {
        width: 100%;
        margin: 0;
    }

    td {
        padding-left: 45%;
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
                <li><a href="get_replacement_details.php">Replacement</a></li>
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
        <h1>All Customers</h1>
        <!-- Display total number of customers -->
        <div class="total-customers">
            Total Customers: <?php echo $total_customers; ?>
        </div>
        <table>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>CNIC</th>
                <th>Payment Type</th>
                <th>Laptop Model</th>
                <th>Laptop Configuration</th>
                <th>Serial Number</th>
                <th>Actions</th>
            </tr>
            <?php while ($user = $users->fetch_assoc()): ?>
            <tr>
                <td data-label="Name"><?php echo $user['name']; ?></td>
                <td data-label="Email"><?php echo $user['email']; ?></td>
                <td data-label="Phone"><?php echo $user['phone']; ?></td>
                <td data-label="CNIC"><?php echo $user['cnic']; ?></td>
                <td data-label="Payment Type"><?php echo $user['payment_type']; ?></td>
                <td data-label="Laptop Model"><?php echo $user['laptop_model']; ?></td>
                <td data-label="Laptop Configuration"><?php echo $user['laptop_configuration']; ?></td>
                <td data-label="Serial Number"><?php echo $user['serial_number']; ?></td>
                <td data-label="Actions" class="actions">
                    <a href="redirect.php?user_id=<?php echo $user['id']; ?>" class="btn">Details</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>

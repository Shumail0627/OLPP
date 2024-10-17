<?php
session_start();
require_once 'db.php';
// Enable error reporting for debugging (remove in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Get current month and year if not provided
$month = isset($_GET['month']) ? intval($_GET['month']) : date('n');
$year = isset($_GET['year']) ? intval($_GET['year']) : date('Y');

// Validate month and year
if ($month < 1 || $month > 12) $month = date('n');
if ($year < 2000 || $year > date('Y') + 1) $year = date('Y');

// Fetch replacement details for the selected month
$sql = "SELECT lr.*, u.name, (lr.original_cost - lr.amount_received) AS remaining_balance
        FROM laptop_replacements lr 
        LEFT JOIN users u ON lr.user_id = u.id 
        WHERE MONTH(lr.replacement_date) = ? AND YEAR(lr.replacement_date) = ?
        ORDER BY lr.replacement_date DESC";

try {
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("ii", $month, $year);
    if (!$stmt->execute()) {
        throw new Exception("Execute failed: " . $stmt->error);
    }

    $result = $stmt->get_result();
} catch (Exception $e) {
    error_log("Error in get_replacement_details.php: " . $e->getMessage());
    $error_message = "An error occurred while fetching data. Please try again later.";
}

// Function to calculate remaining balance
function calculateRemainingBalance($original_cost, $amount_received) {
    return max(0, $original_cost - $amount_received);
}

// Start HTML output
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laptop Replacement Requests - <?php echo date('F Y', mktime(0, 0, 0, $month, 1, $year)); ?></title>
    <link rel="icon" type="image/png" href="images/web.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        :root {
            --primary-color: #4caf50;
            --secondary-color: #45a049;
            --text-color: #333;
            --bg-color: #f4f4f4;
            --table-border-color: #ddd;
        }

        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: var(--text-color);
            background-color: var(--bg-color);
            margin: 0;
            padding: 0;
        }

        .container {
            width: 90%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        /* Navbar Styling */
        .navbar {
            background-color: var(--primary-color);
            padding: 10px 0;
        }

        .navbar .container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            color: white;
            text-decoration: none;
        }

        .logo-bold {
            font-weight: bold;
            font-size: 24px;
            margin-right: 5px;
        }

        .logo-thin {
            font-weight: 300;
            font-size: 16px;
        }

        .navbar-menu {
            list-style-type: none;
            padding: 0;
            display: flex;
        }

        .navbar-menu li {
            margin-left: 20px;
        }

        .navbar-menu a {
            color: white;
            text-decoration: none;
            padding: 5px 10px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .navbar-menu a:hover {
            background-color: var(--secondary-color);
        }

        .navbar-search {
            display: flex;
            align-items: center;
        }

        .navbar-search input[type="text"] {
            padding: 5px;
            border: none;
            border-radius: 3px 0 0 3px;
        }

        .navbar-search button {
            padding: 5px 10px;
            background-color: var(--secondary-color);
            color: white;
            border: none;
            border-radius: 0 3px 3px 0;
            cursor: pointer;
        }

        /* Main Content Styling */
        h1, h2 {
            color: var(--primary-color);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            background-color: white;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid var(--table-border-color);
        }

        th {
            background-color: var(--primary-color);
            color: white;
        }

        tr:hover {
            background-color: #f5f5f5;
        }

        .no-data {
            text-align: center;
            padding: 20px;
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            border-radius: 4px;
        }

        /* Responsive Design */
        @media screen and (max-width: 768px) {
            .navbar .container {
                flex-direction: column;
            }

            .navbar-menu {
                margin-top: 10px;
            }

            .navbar-search {
                margin-top: 10px;
                width: 100%;
            }

            table {
                font-size: 14px;
            }

            th, td {
                padding: 8px;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <a href="admin_dashboard.php" class="navbar-brand">
                <span class="logo-bold">OLPP</span>
                <span class="logo-thin">One Laptop Per Pakistani</span>
            </a>
            <ul class="navbar-menu">
                <li><a href="admin_dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li><a href="all_customers.php"><i class="fas fa-users"></i> Customers</a></li>
                <li><a href="get_replacement_details.php">Replacement</a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
            <form class="navbar-search" method="GET" action="search_results.php">
                <input type="text" name="query" placeholder="Search..." aria-label="Search" required>
                <button type="submit"><i class="fas fa-search"></i></button>
            </form>
        </div>
    </nav>

    <div class="container">
        <h1>Laptop Replacement Requests</h1>
        <h2><?php echo date('F Y', mktime(0, 0, 0, $month, 1, $year)); ?></h2>

        <?php if (isset($error_message)): ?>
            <p class="error"><?php echo $error_message; ?></p>
        <?php elseif ($result && $result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>User Name</th>
                        <th>Date of Issue</th>
                        <th>Laptop Model</th>
                        <th>Serial Number</th>
                        <th>Original Cost</th>
                        <th>Amount Received</th>
                        <th>Remaining Balance</th>
                        <th>Issue</th>
                        <th>Status</th>
                        <th>Replacement Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($replacement = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($replacement['name'] ?? 'N/A'); ?></td>
                            <td><?php echo $replacement['date_of_issue'] ? date('d M Y', strtotime($replacement['date_of_issue'])) : 'N/A'; ?></td>
                            <td><?php echo htmlspecialchars($replacement['laptop_model'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($replacement['serial_number'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($replacement['original_cost'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($replacement['amount_received'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($replacement['remaining_balance'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($replacement['issue'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($replacement['status'] ?? 'N/A'); ?></td>
                            <td><?php echo date('d M Y', strtotime($replacement['replacement_date'])); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="no-data">No replacement requests found for this month.</p>
        <?php endif; ?>

        <?php include 'replacement_form.php'; ?>
    </div>

    <script>
    function calculateRemaining() {
        var originalCost = parseFloat(document.getElementById('original_cost').value) || 0;
        var amountReceived = parseFloat(document.getElementById('amount_received').value) || 0;
        var remainingBalance = Math.max(0, originalCost - amountReceived);
        document.getElementById('remaining_balance').value = remainingBalance.toFixed(2);
    }
    </script>
</body>
</html>
<?php
if (isset($stmt)) $stmt->close();
$conn->close();
?>
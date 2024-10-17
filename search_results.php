<?php
session_start();
include 'db.php';

if (isset($_GET['query'])) {
    $search_term = $conn->real_escape_string($_GET['query']);

    // Extended SQL query to search in multiple columns including serial number and location
    $sql = "SELECT * FROM users WHERE 
            name LIKE '%$search_term%' OR 
            email LIKE '%$search_term%' OR 
            phone LIKE '%$search_term%' OR 
            cnic LIKE '%$search_term%' OR
            serial_number LIKE '%$search_term%' OR
            location LIKE '%$search_term%' OR
            laptop_model LIKE '%$search_term%' OR
            laptop_configuration LIKE '%$search_term%' OR
            payment_type LIKE '%$search_term%' OR
            laptop_status LIKE '%$search_term%'";

    $result = $conn->query($sql);
    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Search Results</title>
        <link rel="stylesheet" href="search_results.css">
        <link rel="icon" type="image/png" href="images/web.png">
    </head>
    <body>
        <div class="container">
            <?php if ($result->num_rows > 0): ?>
                <h2>Search Results for '<?php echo htmlspecialchars($search_term); ?>':</h2>
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
                        <th>Location</th>
                        <th>Status</th>
                        <th>Details</th>
                    </tr>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo htmlspecialchars($row['phone']); ?></td>
                            <td><?php echo htmlspecialchars($row['cnic']); ?></td>
                            <td><?php echo htmlspecialchars($row['payment_type']); ?></td>
                            <td><?php echo htmlspecialchars($row['laptop_model']); ?></td>
                            <td><?php echo htmlspecialchars($row['laptop_configuration']); ?></td>
                            <td><?php echo htmlspecialchars($row['serial_number']); ?></td>
                            <td><?php echo htmlspecialchars($row['location']); ?></td>
                            <td><?php echo htmlspecialchars($row['laptop_status']); ?></td>
                            <td><a href="redirect.php?user_id=<?php echo $row['id']; ?>" class="btn">Details</a></td>
                        </tr>
                    <?php endwhile; ?>
                </table>
            <?php else: ?>
                <div class="no-results">
                    <h2>No results found for '<?php echo htmlspecialchars($search_term); ?>'</h2>
                </div>
            <?php endif; ?>
        </div>
    </body>
    </html>

    <?php
} else {
    echo "No search term provided.";
}

$conn->close();
?>
<?php
session_start();
include 'db.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: adminlogin.php');
    exit();
}

// Fetch the installment details
$id = $_GET['id'];
$sql = "SELECT * FROM installments WHERE id='$id'";
$result = $conn->query($sql);
$installment = $result->fetch_assoc();

// Handle form submission to update installment data
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $amount = $_POST['amount'];
    $due_date = $_POST['due_date'];
    $paid = isset($_POST['paid']) ? 1 : 0;
    $payment_date = $paid ? $_POST['payment_date'] : NULL;

    $sql = "UPDATE installments SET amount='$amount', due_date='$due_date', paid='$paid', payment_date='$payment_date' WHERE id='$id'";

    if ($conn->query($sql) === TRUE) {
        header('Location: installment_history.php?user_id=' . $installment['user_id']);
    } else {
        echo "<p class='error'>Error: " . $sql . "<br>" . $conn->error . "</p>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Installment</title>
    <link rel="stylesheet" href="Edit.css">
    <link rel="icon" type="image/png" href="images/web.png">
</head>
<body>
<div class="form-container">
    <h1>Edit Installment</h1>
    <form method="POST" action="">
        <div class="form-group">
            <label for="amount">Amount:</label>
            <input type="number" step="0.01" name="amount" id="amount" value="<?php echo $installment['amount']; ?>" required>
        </div>
        <div class="form-group">
            <label for="due_date">Due Date:</label>
            <input type="date" name="due_date" id="due_date" value="<?php echo $installment['due_date']; ?>" required>
        </div>
        <div class="form-group">
            <label for="paid">Paid:</label>
            <input type="checkbox" name="paid" id="paid" <?php echo $installment['paid'] ? 'checked' : ''; ?>>
        </div>
        <div class="form-group">
            <label for="payment_date">Payment Date:</label>
            <input type="date" name="payment_date" id="payment_date" value="<?php echo $installment['payment_date']; ?>">
        </div>
        <button type="submit" class="btn">Update Installment</button>
    </form>
</div>
</body>
</html>

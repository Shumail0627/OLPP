<?php
session_start();
include 'db.php';

if (!isset($_GET['id']) || !isset($_GET['user_id'])) {
    die("Invalid request.");
}

$installment_id = $_GET['id'];
$user_id = $_GET['user_id'];

// Delete the installment
$delete_sql = "DELETE FROM installments WHERE id = ? AND user_id = ?";
$delete_stmt = $conn->prepare($delete_sql);
$delete_stmt->bind_param("ii", $installment_id, $user_id);

if ($delete_stmt->execute()) {
    // Recalculate total received amount
    $recalculate_sql = "UPDATE users SET received_amount = (SELECT COALESCE(SUM(amount), 0) FROM installments WHERE user_id = ?) WHERE id = ?";
    $recalculate_stmt = $conn->prepare($recalculate_sql);
    $recalculate_stmt->bind_param("ii", $user_id, $user_id);
    $recalculate_stmt->execute();

    $_SESSION['success_message'] = "Installment deleted successfully.";
} else {
    $_SESSION['error_message'] = "Error deleting installment.";
}

$conn->close();

// Redirect back to the installment details page
header("Location: installment_history.php?user_id=" . $user_id);
exit();
?>
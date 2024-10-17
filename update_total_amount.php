<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_POST['user_id'];
    $laptop_total_amount = $_POST['laptop_total_amount'];

    $sql = "UPDATE users SET laptop_total_amount='$laptop_total_amount' WHERE id='$user_id'";

    if ($conn->query($sql) === TRUE) {
        echo "<script>
            window.onload = function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'Laptop total amount updated successfully',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'installment_history.php?user_id=$user_id';
                    }
                });
            };
            </script>";
    } else {
        echo "Error updating record: " . $conn->error;
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Total Amount</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
</head>
<body>

</body>
</html>
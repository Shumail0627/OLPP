<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

include 'db.php';

$inputFileName = '"E:\xampp\htdocs\olpp\Book1.xml"'; // Path to your Excel file

$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileName);
$sheet = $spreadsheet->getActiveSheet();
$rows = $sheet->toArray();

foreach ($rows as $row) {
    // Assuming the columns are: Name, Email, Phone, CNIC, Payment Type, Laptop Model, Laptop Configuration, Serial Number
    $name = $row[0];
    $email = $row[1];
    $phone = $row[2];
    $cnic = $row[3];
    $payment_type = $row[4];
    $laptop_model = $row[5];
    $laptop_configuration = $row[6];
    $serial_number = $row[7];

    $sql = "INSERT INTO users (name, email, phone, cnic, payment_type, laptop_model, laptop_configuration, serial_number) 
            VALUES ('$name', '$email', '$phone', '$cnic', '$payment_type', '$laptop_model', '$laptop_configuration', '$serial_number')";

    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully for $name<br>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>

<?php
include 'db.php';

// Function to import customers from the PDF
function importCustomersFromPDF($pdfFilePath) {
    // Load and parse the PDF
    // Note: Use a library like TCPDF or FPDF for this
    $pdfContent = file_get_contents($pdfFilePath);

    // Parse the content and extract customer data
    // Example: Regular expressions to find patterns (adjust as needed)
    preg_match_all('/(\d+)\s+([a-zA-Z\s]+)\s+([\d\-]+)\s+(\d+)\s+([a-zA-Z\s]+)\s+([\d\s-]+)/', $pdfContent, $matches, PREG_SET_ORDER);

    // Insert each customer into the database
    foreach ($matches as $match) {
        $name = $match[2];
        $cnic = $match[3];
        $phone = $match[4];
        $amount = $match[5];
        $email = strtolower(str_replace(' ', '', $name)) . '@example.com';

        $sql = "INSERT INTO users (name, email, phone, cnic, payment_type) VALUES ('$name', '$email', '$phone', '$cnic', 'cash')";
        if ($GLOBALS['conn']->query($sql) === TRUE) {
            echo "New record created successfully for $name\n";
        } else {
            echo "Error: " . $sql . "<br>" . $GLOBALS['conn']->error;
        }
    }
}

// Path to the PDF file
$pdfFilePath = '/mnt/data/OLPP - Project Sheet followup record 2024.pdf';
importCustomersFromPDF($pdfFilePath);

$conn->close();
?>

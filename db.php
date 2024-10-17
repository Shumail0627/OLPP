<?php
$servername = "localhost";
$username = "ukhz76eirbicc";
$password = "@13py7hk164x";
$dbname = "dbcxw5qyg5togs";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

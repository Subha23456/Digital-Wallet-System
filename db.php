<?php
$host = 'localhost';
$user = 'root';
$pass = ''; // leave blank if you haven't set a MySQL password
$dbname = 'digital_wallet'; // <-- REPLACE with your actual database name

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

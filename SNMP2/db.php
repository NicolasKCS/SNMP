<?php
$servername = "localhost";
$username = "your_mysql_username";
$password = "your_mysql_password";
$dbname = "switch_management";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

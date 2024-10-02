<?php
$servername = "localhost";
$user = "root";
$password = "";
$dbname = "shipping";

// Create connection
$conn = new mysqli($servername, $user, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

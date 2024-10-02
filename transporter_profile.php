<?php
session_start();
require_once 'db_connection.php';

// Check if transporter is logged in
if (!isset($_SESSION['transporter_id'])) {
    // Transporter is not logged in, redirect to login page
    header("Location: login.php");
    exit; // Stop further execution
}

// Retrieve transporter ID from session
$transporter_id = $_SESSION['transporter_id'];

// Prepare and bind the SQL statement to fetch transporter's information
$stmt = $conn->prepare("SELECT full_name, email, mobile_number, DLNumber, vehicle_number, vehicle_model FROM transporters WHERE id = ?");
$stmt->bind_param("i", $transporter_id);
$stmt->execute();
$result = $stmt->get_result();

// Check if transporter exists
if ($result->num_rows == 1) {
    // Fetch transporter's information
    $transporter_info = $result->fetch_assoc();
    // Print transporter's information in HTML format
    echo "<h1>Transporter Profile</h1>";
    echo "<p>Name: " . $transporter_info['full_name'] . "</p>";
    echo "<p>Email: " . $transporter_info['email'] . "</p>";
    echo "<p>Mobile Number: " . $transporter_info['mobile_number'] . "</p>";
    echo "<p>Driver's License Number: " . $transporter_info['DLNumber'] . "</p>";
    echo "<p>Vehicle Number: " . $transporter_info['vehicle_number'] . "</p>";
    echo "<p>Vehicle Model: " . $transporter_info['vehicle_model'] . "</p>";
    // Add more fields as needed
} else {
    // Transporter not found
    echo "Transporter not found.";
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>

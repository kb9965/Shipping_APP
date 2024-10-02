<?php
// Start the session
session_start();
require_once 'db_connection.php';

// Check if user is logged in
if(isset($_SESSION['user_id']) && isset($_SESSION['user_type'])) {
    // If user is logged in as a user
    if($_SESSION['user_type'] === 'user') {
        // Retrieve form data
        $orderType = $_POST['orderType'];
        $totalWeight = $_POST['totalweight'];
        $infoToTransporter = $_POST['infotoTransporter'];
        $pickupAddress = $_POST['pickupAddress'];
        $dropAddress = $_POST['dropAddress'];
        $distance = $_POST['distance'];
        $receiverMobileNumber = $_POST['ReceivermobileNumber']; // Note the correct variable name here
        $userId = $_SESSION['user_id'];

        // Prepare and bind the SQL statement to insert data into shipments table
        $stmt = $conn->prepare("INSERT INTO shipments (user_id, order_type, total_weight, info_to_transporter, pickup_address, drop_address, distance, receiver_mobile_number) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isdssssd", $userId, $orderType, $totalWeight, $infoToTransporter, $pickupAddress, $dropAddress, $distance, $receiverMobileNumber);
        
        // Execute the statement
        if($stmt->execute()) {
            echo "Shipment order placed successfully.";
            header('location: shipment.html');
        } else {
            echo "Error: Unable to place the shipment order.";
        }

        // Close the statement and connection
        $stmt->close();
        $conn->close();
    } 
    // If user is logged in as a transporter
    elseif($_SESSION['user_type'] === 'transporter') {
        // Redirect to carriers page
        header("Location: carriers.html");
        exit();
    }
} else {
    // Redirect to login page if user is not logged in
    header("Location: login.html");
    exit();
}
?>

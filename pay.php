<?php
// Start the session
session_start();
require_once 'db_connection.php';

// Check if user is logged in as a user
if(isset($_SESSION['user_id']) && isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'user') {
    if(isset($_POST['pay'])) {
        // Retrieve shipment ID from form data
        $shipmentId = $_POST['shipment_id'];

        // Fetch transporter's details for the current bid
        $sql = "SELECT t.id AS transporter_id, t.full_name AS transporter_name, t.mobile_number AS transporter_mobile
                FROM bids b
                INNER JOIN transporters t ON b.transporter_id = t.id
                WHERE b.shipment_id = $shipmentId
                ORDER BY b.bid_amount ASC
                LIMIT 1";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $transporterId = $row['transporter_id'];
            $transporterName = $row['transporter_name'];
            $transporterMobileNumber = $row['transporter_mobile'];

            // Update bid status to indicate that it has been paid
            $updateBidSql = "UPDATE bids SET bid_status = 'Paid' WHERE shipment_id = $shipmentId";
            if ($conn->query($updateBidSql) === TRUE) {
                // Redirect to myorders.php with transporter's details
                header("Location: myorders.php?transporter_id=$transporterId&transporter_name=$transporterName&transporter_mobile=$transporterMobileNumber");
                exit();
            } else {
                echo "Error updating bid status: " . $conn->error;
            }
        } else {
            echo "No transporter found for the selected shipment.";
        }
    } else {
        echo "Invalid request.";
    }
} else {
    // Redirect to login page if user is not logged in as a user
    header("Location: login.html");
    exit();
}
?>

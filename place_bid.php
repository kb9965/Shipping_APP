<?php
// Start the session
session_start();
require_once 'db_connection.php';

// Check if user is logged in and is a transporter
if(isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'transporter') {
    // Check if form data is received
    if(isset($_POST['shipment_id']) && isset($_POST['bid_amount'])) {
        // Retrieve form data
        $shipmentId = $_POST['shipment_id'];
        $bidAmount = $_POST['bid_amount'];
        $transporterId = $_SESSION['transporter_id']; // Assuming transporter_id is stored in user_id session variable

        // Check if bid amount is valid
        if(is_numeric($bidAmount) && $bidAmount > 0) {
            // Check if there is an existing bid for the shipment
            $existingBidSql = "SELECT * FROM bids WHERE shipment_id = $shipmentId";
            $existingBidResult = $conn->query($existingBidSql);

            if ($existingBidResult->num_rows > 0) {
                // Fetch the existing bid details
                $existingBidRow = $existingBidResult->fetch_assoc();
                $existingTransporterId = $existingBidRow['transporter_id'];
                $existingBidAmount = $existingBidRow['bid_amount'];

                if($existingTransporterId != $transporterId) {
                    // If the transporter ID is different, update the bid
                    $updateBidSql = "UPDATE bids SET transporter_id = $transporterId, bid_amount = $bidAmount WHERE shipment_id = $shipmentId";
                    if ($conn->query($updateBidSql) === TRUE) {
                        // Redirect back to carriers.php with the updated bid amount
                        header("Location: carriers.php?shipment_id=$shipmentId&bid_amount=$bidAmount");
                        exit();
                    } else {
                        echo "Error updating bid: " . $conn->error;
                    }
                } else {
                    // If the transporter ID is the same, check if the new bid amount is less than the existing bid amount
                    if($bidAmount < $existingBidAmount) {
                        // Update the bid amount
                        $updateBidSql = "UPDATE bids SET bid_amount = $bidAmount WHERE shipment_id = $shipmentId";
                        if ($conn->query($updateBidSql) === TRUE) {
                            // Redirect back to carriers.php with the updated bid amount
                            header("Location: carriers.php?shipment_id=$shipmentId&bid_amount=$bidAmount");
                            exit();
                        } else {
                            echo "Error updating bid: " . $conn->error;
                        }
                    } else {
                        echo "Bid amount must be less than the current bid amount.";
                    }
                }
            } else {
                // If no previous bid exists, place bid
                $placeBidSql = "INSERT INTO bids (shipment_id, transporter_id, bid_amount) VALUES ($shipmentId, $transporterId, $bidAmount)";
                if ($conn->query($placeBidSql) === TRUE) {
                    // Redirect back to carriers.php with the updated bid amount
                    header("Location: carriers.php?shipment_id=$shipmentId&bid_amount=$bidAmount");
                    exit();
                } else {
                    echo "Error placing bid: " . $conn->error;
                }
            }
        } else {
            echo "Invalid bid amount.";
        }
    } else {
        echo "Form data not received.";
    }
    // Close the database connection
    $conn->close();
} else {
    // Redirect to login page if user is not logged in as a transporter
    header("Location: login.html");
    exit();
}
?>

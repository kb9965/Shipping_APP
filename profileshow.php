<?php
// Start the session
session_start();

require_once 'db_connection.php';

// Check if user is logged in
if(isset($_SESSION['user_type'])) {
    // If user type is 'user'
    if($_SESSION['user_type'] === 'user') {
        // Retrieve user details from the database
        $userId = $_SESSION['user_id'];
        $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result->num_rows > 0) {
            $userDetails = $result->fetch_assoc();
            // Display user details
            echo "User Details:<br>";
            echo "Name: " . $userDetails['full_name'] . "<br>";
            echo "Email: " . $userDetails['email'] . "<br>";
            echo "Mobile Number: " . $userDetails['mobile_number'] . "<br>";
            // Add more fields as needed
        } else {
            echo "User details not found.";
        }
    }
    // If user type is 'transporter'
    elseif($_SESSION['user_type'] === 'transporter') {
        // Retrieve transporter details from the database
        $transporterId = $_SESSION['transporter_id'];
        $stmt = $conn->prepare("SELECT * FROM transporters WHERE id = ?");
        $stmt->bind_param("i", $transporterId);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result->num_rows > 0) {
            $transporterDetails = $result->fetch_assoc();
            // Display transporter details
            echo "Transporter Details:<br>";
            echo "Name: " . $transporterDetails['full_name'] . "<br>";
            echo "Email: " . $transporterDetails['email'] . "<br>";
            echo "Mobile Number: " . $transporterDetails['mobile_number'] . "<br>";
            echo "DL Number: " . $transporterDetails['DLNumber'] . "<br>";
            echo "Vehicle Number: " . $transporterDetails['vehicle_number'] . "<br>";
            echo "Vehicle Model: " . $transporterDetails['vehicle_model'] . "<br>";
            // Add more fields as needed
        } else {
            echo "Transporter details not found.";
        }
    } else {
        echo "Invalid user type.";
    }
} else {
    // Redirect to login page if user is not logged in
    header("Location: login.html");
    exit();
}
?>

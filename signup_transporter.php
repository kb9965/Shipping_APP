<?php
require_once 'db_connection.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $fullName = $_POST["fullName"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $DLNumber = $_POST["DLNumber"];
    $mobileNumber = $_POST["mobileNumber"];
    $vehicleNumber = $_POST["vehicleNumber"];
    $vehicleModel = $_POST["vehicleModel"];

    // Sanitize form data to prevent SQL injection
    $fullName = htmlspecialchars($fullName);
    $email = htmlspecialchars($email);
    $DLNumber = htmlspecialchars($DLNumber);
    $mobileNumber = htmlspecialchars($mobileNumber);
    $vehicleNumber = htmlspecialchars($vehicleNumber);
    $vehicleModel = htmlspecialchars($vehicleModel);
    // You should also hash the password for security
    

    // Include the database connection file
    require_once 'db_connection.php';

    // Prepare and bind the SQL statement
    $stmt = $conn->prepare("INSERT INTO transporters (full_name, email, password, DLNumber, mobile_number, vehicle_number, vehicle_model) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $fullName, $email, $password, $DLNumber, $mobileNumber, $vehicleNumber, $vehicleModel);

    // Execute the statement
    if ($stmt->execute()) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>

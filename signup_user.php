<?php
require_once 'db_connection.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $fullName = $_POST["fullName"];
    $mobileNumber = $_POST["mobileNumber"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Sanitize form data to prevent SQL injection
    $fullName = htmlspecialchars($fullName);
    $mobileNumber = htmlspecialchars($mobileNumber);
    $email = htmlspecialchars($email);

    // Include the database connection file
    require_once 'db_connection.php';

    // Prepare and bind the SQL statement
    $stmt = $conn->prepare("INSERT INTO users (full_name, mobile_number, email, password) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $fullName, $mobileNumber, $email, $password);

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

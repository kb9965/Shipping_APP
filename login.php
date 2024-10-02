<?php
// Start the session
session_start();

require_once 'db_connection.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Sanitize form data to prevent SQL injection
    $email = htmlspecialchars($email);

    // Prepare and bind the SQL statement for users table
    $stmt = $conn->prepare("SELECT id, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if a user with the provided email exists in users table
    if ($result->num_rows == 1) {
        // Fetch the password from the database
        $row = $result->fetch_assoc();
        $storedPassword = $row['password'];

        // Verify the provided password against the stored password
        if ($password === $storedPassword) {
            // Passwords match, login successful
            // Store user ID in session for later use
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user_type'] = 'user'; // Indicate the user type
            // Don't perform any redirect here, just continue with your script
            echo "sucessfully logged in";
        } else {
            // Passwords do not match, login failed
            echo "Invalid email or password";
        }
    } else {
        // User with the provided email does not exist in users table
        // Check the transporters table for the email
        $stmt = $conn->prepare("SELECT id, password FROM transporters WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if a user with the provided email exists in transporters table
        if ($result->num_rows == 1) {
            // Fetch the password from the database
            $row = $result->fetch_assoc();
            $storedPassword = $row['password'];

            // Verify the provided password against the stored password
            if ($password === $storedPassword) {
                // Passwords match, login successful
                $_SESSION['transporter_id'] = $row['id'];
                $_SESSION['user_type'] = 'transporter'; // Indicate the user type
                // Don't perform any redirect here, just continue with your script
                echo "sucessfully logged in";
            } else {
                // Passwords do not match, login failed
                echo "Invalid email or password";
            }
        } else {
            // User with the provided email does not exist in transporters table
            echo "Invalid email or password";
        }
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>

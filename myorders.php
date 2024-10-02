<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
    
        <h1>My Orders</h1>
    </header>
    <nav>
        <a href="index.html">Home</a>
        <a href="about.html">About</a>
        <a href="Shipment.html">Shipment</a>
        <a href="myshipment.php">MyShipment</a>
        <a href="carriers.php">Available shipments</a>
        <a href="profile.php">Profile</a>
    </nav>

    <div class="container">
        <h2>Order Details</h2>
        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Order Type</th>
                    <th>Total Weight</th>
                    <th>Pickup Address</th>
                    <th>Drop Address</th>
                    <th>Distance</th>
                    <th>Bid Amount</th>
                    <th>Transporter Name</th>
                    <th>Transporter Mobile</th>
                </tr>
            </thead>
            <tbody>
            <?php
    // Start the session
    session_start();
    require_once 'db_connection.php';

    // Check if user is logged in as a user
    if(isset($_SESSION['user_id']) && isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'user') {
        // Fetch paid shipments for the current user
        $userId = $_SESSION['user_id'];
        $sql = "SELECT s.*, b.bid_amount, t.full_name AS transporter_name, t.mobile_number AS transporter_mobile
                FROM shipments s
                INNER JOIN bids b ON s.id = b.shipment_id
                INNER JOIN transporters t ON b.transporter_id = t.id
                WHERE s.user_id = $userId AND b.bid_status = 'Paid'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Output data of each row
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['id'] . "</td>"; // Shipment ID column
                echo "<td>" . $row['order_type'] . "</td>";
                echo "<td>" . $row['total_weight'] . "</td>";
                echo "<td>" . $row['pickup_address'] . "</td>";
                echo "<td>" . $row['drop_address'] . "</td>";
                echo "<td>" . $row['distance'] . "</td>";
                echo "<td>$" . $row['bid_amount'] . "</td>"; // Paid bid amount column
                echo "<td>" . $row['transporter_name'] . "</td>"; // Transporter name column
                echo "<td>" . $row['transporter_mobile'] . "</td>"; // Transporter mobile number column
                echo "</tr>";
            }
        } else {
            echo "No paid shipments found.";
        }
        // Close the database connection
        $conn->close();
    } else {
        // Redirect to login page if user is not logged in as a user
        header("Location: login.html");
        exit();
    }
?>

            </tbody>
        </table>
    </div>

    <footer>
        <p>&copy; 2024 ZORO LOGISTICS</p>
    </footer>
</body>
</html>

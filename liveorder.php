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
    <img src="img/logo.png.jpg" alt="ZORO LOGISTICS Logo" class="logo">
        <h1>My Orders</h1>
    </header>
    <nav>
        <a href="index.html">Home</a>
        <a href="about.html">About</a>
        <a href="liveorder.php">Live Orders</a>
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
                    <th>User Name</th>
                    <th>User Email</th>
                    <th>Order type</th>
                    <th>Total Weight</th>
                    <th>Pick Up Address</th>
                    <th>Drop Address</th>
                    <th>Distance</th>
                    <th>Receiver Mobile Number</th>
                    <th>Bid Amount</th>
                
                </tr>
            </thead>
            <tbody>
            <?php
    // Start the session
    session_start();
    require_once 'db_connection.php';

    // Check if user is logged in as a transporter
    if(isset($_SESSION['transporter_id']) && isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'transporter') {
        // Fetch paid shipments for the current transporter
        $transporterId = $_SESSION['transporter_id'];
        $sql = "SELECT s.*, b.bid_amount, u.full_name AS user_name, u.email AS user_email
                FROM bids b
                INNER JOIN shipments s ON b.shipment_id = s.id
                INNER JOIN users u ON s.user_id = u.id
                WHERE b.transporter_id = $transporterId AND b.bid_status = 'Paid'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Output data of each row
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['id'] . "</td>"; // Shipment ID column
                echo "<td>" . $row['user_name'] . "</td>"; // User name column
                echo "<td>" . $row['user_email'] . "</td>"; // User email column
                echo "<td>" . $row['order_type'] . "</td>";
                echo "<td>" . $row['total_weight'] . "</td>";
                echo "<td>" . $row['pickup_address'] . "</td>";
                echo "<td>" . $row['drop_address'] . "</td>";
                echo "<td>" . $row['distance'] . "</td>";
                echo "<td>" . $row['receiver_mobile_number'] . "</td>";
                echo "<td>$" . $row['bid_amount'] . "</td>"; // Paid bid amount column
                echo "</tr>";
            }
        } else {
            echo "No live orders found.";
        }
        // Close the database connection
        $conn->close();
    } else {
        // Redirect to login page if user is not logged in as a transporter
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
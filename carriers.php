<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrier Bidding Page</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Carrier Bidding Page</h1>
    </header>
    <nav>
        <a href="index.html">Home</a>
        <a href="about.html">About</a>
        <a href="myshipment.php">MyShipment</a>
        <a href="myorders.php">MyOrders</a>
        <a href="liveorder.php">Live Orders</a>
        <a href="carriers.php">Available shipments</a>
        <a href="profile.php">Profile</a>
    </nav>
    <div class="container">
        <h2>Shipment Orders</h2>
        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Order Type</th>
                    <th>Total Weight</th>
                    <th>Pickup Address</th>
                    <th>Drop Address</th>
                    <th>Distance</th>
                    <th>Maximum Bid Price</th>
                    <th>Current Bid</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php
            // Start the session
            session_start();
            require_once 'db_connection.php';

            // Check if user is logged in and is a transporter
            if(isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'transporter') {
                // Fetch shipment orders from the database
                $sql = "SELECT * FROM shipments WHERE id NOT IN (SELECT shipment_id FROM bids WHERE bid_status != 'pending')";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    // Output data of each row
                    while($row = $result->fetch_assoc()) {
                        $maxBidPrice = $row['distance'] * 70; // Calculate maximum bid price
                        echo "<tr>";
                        echo "<td>" . $row['id'] . "</td>"; // Order ID column
                        echo "<td>" . $row['order_type'] . "</td>";
                        echo "<td>" . $row['total_weight'] . "</td>";
                        echo "<td>" . $row['pickup_address'] . "</td>";
                        echo "<td>" . $row['drop_address'] . "</td>";
                        echo "<td>" . $row['distance'] . "</td>";
                        echo "<td>$" . $maxBidPrice . "</td>";
                        echo "<td>"; // Current bid column
                        // Fetch and display current bid amount from the database
                        $shipmentId = $row['id'];
                        $bidSql = "SELECT MAX(bid_amount) AS current_bid FROM bids WHERE shipment_id = $shipmentId";
                        $bidResult = $conn->query($bidSql);
                        if ($bidResult->num_rows > 0) {
                            $bidRow = $bidResult->fetch_assoc();
                            $currentBid = $bidRow['current_bid'];
                            if ($currentBid) {
                                echo "$" . $currentBid;
                            } else {
                                echo "No bids yet";
                            }
                        } else {
                            echo "No bids yet";
                        }
                        echo "</td>";
                        echo "<td>"; // Action column (place bid button)
                        echo "<form action='place_bid.php' method='post'>";
                        echo "<input type='hidden' name='shipment_id' value='" . $row['id'] . "'>"; // Hidden input to store shipment ID
                        echo "<input type='number' name='bid_amount' placeholder='Enter bid amount' required>";
                        echo "<button type='submit'>Place Bid</button>";
                        echo "</form>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "No shipment orders available.";
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

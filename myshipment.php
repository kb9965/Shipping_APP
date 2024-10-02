<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Shipments</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
    
        <h1>My Shipments</h1>
    </header>
    <nav>
        <a href="index.html">Home</a>
        <a href="about.html">About</a>
        <a href="shipment.html">Shipment</a>
        <a href="myorders.php">MyOrders</a>
        <a href="liveorder.php">Live Orders</a>
        <a href="carriers.php">Available shipments</a>
        <a href="profile.php">Profile</a>
    </nav>

    <div class="container">
        <h2>Shipment Details</h2>
        <table>
            <thead>
                <tr>
                    <th>Order Type</th>
                    <th>Total Weight</th>
                    <th>Pickup Address</th>
                    <th>Drop Address</th>
                    <th>Distance</th>
                    <th>Current Bid</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Start the session
                session_start();
                require_once 'db_connection.php';

                // Check if user is logged in as a user
                if(isset($_SESSION['user_id']) && isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'user') {
                    $userId = $_SESSION['user_id'];

                    // Fetch user's shipment details along with the current bid
                    $sql = "SELECT s.*, b.bid_amount, t.mobile_number AS transporter_mobile 
                            FROM shipments s
                            LEFT JOIN bids b ON s.id = b.shipment_id
                            LEFT JOIN transporters t ON b.transporter_id = t.id
                            WHERE s.user_id = $userId";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        // Output data of each row
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row['order_type'] . "</td>";
                            echo "<td>" . $row['total_weight'] . "</td>";
                            echo "<td>" . $row['pickup_address'] . "</td>";
                            echo "<td>" . $row['drop_address'] . "</td>";
                            echo "<td>" . $row['distance'] . "</td>";
                            echo "<td>" . ($row['bid_amount'] ? "$" . $row['bid_amount'] : "No bid yet") . "</td>";
                            echo "<td>";
                            echo "<form action='pay.php' method='post'>";
                            echo "<input type='hidden' name='shipment_id' value='" . $row['id'] . "'>";
                            echo "<button type='submit' name='pay'>Pay</button>";
                            echo "</form>";
                            echo "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7'>No shipment orders available.</td></tr>";
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

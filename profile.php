<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>User Profile Page</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <header>
    <img src="img\logo.jpg" alt="ZORO LOGISTICS Logo" class="logo" />
    <h1>ZORO LOGISTICS</h1>
  </header>
  <nav>
    <a href="about.html">About</a>
    <a href="shipment.html">Shipment</a>
    <a href="myshipment.php">MyShipment</a>
    <a href="myorders.php">MyOrders</a>
    <a href="carriers.php">Available shipments</a>
    <a href="profile.php">Profile</a>
  </nav>
  <div id="profileData">
    <?php include_once 'profileshow.php'; ?>
  </div>
  <form action="logout.php" method="post">
    <button type="submit" style="margin-top: 350px;">Logout</button>
  </form>
</body>
</html>

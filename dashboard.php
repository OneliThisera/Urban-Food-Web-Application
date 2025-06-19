<?php
session_start();
if (!isset($_SESSION['user_name'])) {
    header("Location: login.html");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<header class="navbar">
  <div class="container">
    <div class="logo">
      <a href="index.html"><img src="img/logo.png" alt="Logo"></a>
    </div>
    <div class="menu text-right">
      <ul>
        <li><a href="order.html">Order</a></li>
        <li><a href="Supplier.html">Supplier</a></li>
        <li><a href="Customer.html">Customer</a></li>
        <li><a href="payment.html">Payment</a></li>
        <li><a href="Feedback.html">Feedback</a></li>
        <li><a href="logout.php">Logout</a></li>
        <li><span style="color: white;">Welcome, <?php echo $_SESSION['user_name']; ?>!</span></li>
      </ul>
    </div>
  </div>
</header>
<div class="container">
    <h2>Welcome to Your Dashboard, <?php echo $_SESSION['user_name']; ?>!</h2>
    <p>You can manage your orders and profile here.</p>
</div>
</body>
</html>

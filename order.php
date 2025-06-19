<?php
session_start(); // Start the session

// Include the DB connection file
require_once 'db_oracle.php';

// Get the connection using the method from db_oracle.php
$conn = getConnection();

$success = "";
$error = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get user details from the form
    $sn = $_POST["sn"];
    $food = $_POST["food"];
    $name = $_POST["name"];
    $price = $_POST["price"];
    $qty = $_POST["qty"];

    // Initialize cart and calculate total
    $cart = $_SESSION["cart"] ?? [];
    $total = 0;
    $items = [];

    foreach ($cart as $item) {
        $subtotal = $item["price"] * $item["qty"];
        $total += $subtotal;
        $items[] = $item["name"] . " x" . $item["qty"];
    }

    // Serialize the action if needed or process as a string
    $action = implode(", ", $items); 

    // Prepare the SQL statement to insert order into the database
    $stmt = $conn->prepare("INSERT INTO urban_orders (sn, food, name, price, qty, total, action) VALUES (?, ?, ?, ?, ?, ?, ?)");
    // Updated bind_param with correct types (sssdids)
    $stmt->bind_param("sssdids", $sn, $food, $name, $price, $qty, $total, $action);

    // Execute the query and check for success
    if ($stmt->execute()) {
        $success = "Order placed successfully!";
        unset($_SESSION["cart"]); // Clear the cart session
    } else {
        $error = "Error placing order: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Confirm Order - UrbanFood</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php if ($success): ?>
        <div class="alert success"><?php echo $success; ?></div>
    <?php elseif ($error): ?>
        <div class="alert error"><?php echo $error; ?></div>
    <?php endif; ?>

    <div class="container">
        <h2>Confirm Your Order</h2>
        <table border="1">
            <tr>
                <th>S.N</th>
                <th>Food</th>
                <th>Name</th>
                <th>Price</th>
                <th>Qty</th>
                <th>Total</th>
                <th>Action</th>
            </tr>
            <?php
            $total = 0;
            if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
                foreach ($_SESSION['cart'] as $index => $item) {
                    $subtotal = $item['price'] * $item['qty'];
                    $total += $subtotal;
                    echo "<tr>
                        <td>" . ($index + 1) . "</td>
                        <td><img src='" . htmlspecialchars($item['img'], ENT_QUOTES, 'UTF-8') . "' width='50'></td>
                        <td>" . htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8') . "</td>
                        <td>" . htmlspecialchars($item['price'], ENT_QUOTES, 'UTF-8') . "</td>
                        <td>" . htmlspecialchars($item['qty'], ENT_QUOTES, 'UTF-8') . "</td>
                        <td>" . number_format($subtotal, 2) . "</td>
                    </tr>";
                }
                echo "<tr>
                    <th colspan='5'>Total</th>
                    <th>Rs. " . number_format($total, 2) . "</th>
                </tr>";
            } else {
                echo "<tr><td colspan='6'>Cart is empty.</td></tr>";
            }
            ?>
        </table>
    </div>
</body>
</html>


        <form method="POST" class="form">
            <fieldset>
                <legend>Delivery Details</legend>
                <label>Full Name</label>
                <input type="text" name="fullname" required>
                <label>Phone Number</label>
                <input type="text" name="phone" required>
                <label>Email</label>
                <input type="email" name="email" required>
                <label>Address</label>
                <input type="text" name="address" required>
                <input type="submit" value="Confirm Order" class="btn-primary">
            </fieldset>
        </form>
    </div>
</body>
</html>

<?php
require_once 'db_oracle.php';

// Check if supplier_id is passed in the URL
if (isset($_GET['supplier_id'])) {
    $supplier_id = $_GET['supplier_id'];

    // Fetch supplier data from the database
    $conn = getConnection();
    $sql_select = "SELECT * FROM URBAN_SUPPLIERS WHERE SUPPLIER_ID = :supplier_id";
    $stmt = $conn->prepare($sql_select);
    $stmt->execute([':supplier_id' => $supplier_id]);
    $supplier = $stmt->fetch();

    // If the supplier doesn't exist
    if (!$supplier) {
        echo "Supplier not found!";
        exit;
    }

    // Handle form submission to update the supplier
    if (isset($_POST['update'])) {
        $supplier_name = $_POST['supplier_name'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $address = $_POST['address'];
        $phone = $_POST['phone'];

        try {
            $conn->beginTransaction();
            $sql_update = "UPDATE URBAN_SUPPLIERS 
                           SET SUPPLIER_NAME = :supplier_name, EMAIL = :email, PASSWORD = :password, 
                               ADDRESS = :address, PHONE = :phone 
                           WHERE SUPPLIER_ID = :supplier_id";
            $stmt = $conn->prepare($sql_update);
            $stmt->execute([
                ':supplier_name' => $supplier_name,
                ':email' => $email,
                ':password' => $password,
                ':address' => $address,
                ':phone' => $phone,
                ':supplier_id' => $supplier_id
            ]);
            $conn->commit();
            echo "<script>alert('Supplier updated successfully!'); window.location.href='Supplier.php';</script>";
        } catch (Exception $e) {
            $conn->rollBack();
            echo "Failed to update supplier: " . $e->getMessage();
        }
    }
} else {
    echo "Supplier ID not specified!";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Supplier</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center">Update Supplier</h2>

    <form method="POST" action="update_supplier.php?supplier_id=<?php echo $supplier_id; ?>">
        <div class="mb-3">
            <label for="supplier_name" class="form-label">Supplier Name</label>
            <input type="text" class="form-control" id="supplier_name" name="supplier_name" value="<?php echo $supplier['SUPPLIER_NAME']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="<?php echo $supplier['EMAIL']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" value="<?php echo $supplier['PASSWORD']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="address" class="form-label">Address</label>
            <input type="text" class="form-control" id="address" name="address" value="<?php echo $supplier['ADDRESS']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="phone" class="form-label">Phone</label>
            <input type="text" class="form-control" id="phone" name="phone" value="<?php echo $supplier['PHONE']; ?>" required>
        </div>
        <button type="submit" name="update" class="btn btn-primary">Update Supplier</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

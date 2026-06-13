<?php
include 'conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $mines_name = $_POST['mines_name'];
    $do_no = $_POST['do_no'];
    $quantity = (int) $_POST['quantity'];
    $lapsing_date = $_POST['lapsing_date'];
    $balance_quantity = (int) $_POST['balance_quantity'];
    $asking_rate = (float) $_POST['asking_rate'];

    $stmt = $mysqli->prepare("INSERT INTO mines_do (mines_name, do_no, quantity, lapsing_date, balance_quantity, asking_rate) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssisis", $mines_name, $do_no, $quantity, $lapsing_date, $balance_quantity, $asking_rate);
    $stmt->execute();
    $stmt->close();

    header("Location: admin.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Insert Mines DO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
<div class="container mt-5">
    <h2>Insert Mines DO</h2>
    <form method="post">
        <div class="mb-3">
            <label for="mines_name" class="form-label">Mines Name</label>
            <input type="text" class="form-control" id="mines_name" name="mines_name" required>
        </div>
        <div class="mb-3">
            <label for="do_no" class="form-label">DO Number</label>
            <input type="text" class="form-control" id="do_no" name="do_no" required>
        </div>
        <div class="mb-3">
            <label for="quantity" class="form-label">Quantity</label>
            <input type="number" class="form-control" id="quantity" name="quantity" required>
        </div>
        <div class="mb-3">
            <label for="lapsing_date" class="form-label">Lapsing Date</label>
            <input type="date" class="form-control" id="lapsing_date" name="lapsing_date" required>
        </div>
        <div class="mb-3">
            <label for="balance_quantity" class="form-label">Balance Quantity</label>
            <input type="number" class="form-control" id="balance_quantity" name="balance_quantity" required>
        </div>
        <div class="mb-3">
            <label for="asking_rate" class="form-label">Asking Rate</label>
            <input type="number" step="0.01" class="form-control" id="asking_rate" name="asking_rate" required>
        </div>
        <button type="submit" class="btn btn-success">Insert</button>
        <a href="index.php" class="btn btn-secondary">Back</a>
    </form>
</div>
</body>
</html>

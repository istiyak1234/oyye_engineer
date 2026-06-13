<?php
include 'conn.php';

$row = null; // Initialize $row

if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Prevent SQL injection
    $result = $mysqli->query("SELECT * FROM mines_do WHERE id = $id");
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo "Record not found.";
        exit();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = intval($_POST['id']);
    $mines_name = $_POST['mines_name'];
    $do_no = $_POST['do_no'];
    $quantity = floatval($_POST['quantity']);
    $lapsing_date = $_POST['lapsing_date'];
    $balance_quantity = floatval($_POST['balance_quantity']);
    $asking_rate = floatval($_POST['asking_rate']);

    $stmt = $mysqli->prepare("UPDATE mines_do SET mines_name = ?, do_no = ?, quantity = ?, lapsing_date = ?, balance_quantity = ?, asking_rate = ? WHERE id = ?");
    $stmt->bind_param("ssdsddi", $mines_name, $do_no, $quantity, $lapsing_date, $balance_quantity, $asking_rate, $id);
    $stmt->execute();
    $stmt->close();

    header("Location: admin.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Mines DO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
<div class="container mt-5">
    <h2>Update Mines DO</h2>

    <?php if ($row): ?>
    <form method="post">
        <input type="hidden" name="id" value="<?= htmlspecialchars($row['id']) ?>">
        <div class="mb-3">
            <label for="mines_name" class="form-label">Mines Name</label>
            <input type="text" class="form-control" id="mines_name" name="mines_name" value="<?= htmlspecialchars($row['mines_name']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="do_no" class="form-label">DO Number</label>
            <input type="text" class="form-control" id="do_no" name="do_no" value="<?= htmlspecialchars($row['do_no']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="quantity" class="form-label">Quantity</label>
            <input type="number" step="0.01" class="form-control" id="quantity" name="quantity" value="<?= htmlspecialchars($row['quantity']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="lapsing_date" class="form-label">Lapsing Date</label>
            <input type="date" class="form-control" id="lapsing_date" name="lapsing_date" value="<?= htmlspecialchars($row['lapsing_date']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="balance_quantity" class="form-label">Balance Quantity</label>
            <input type="number" step="0.01" class="form-control" id="balance_quantity" name="balance_quantity" value="<?= htmlspecialchars($row['balance_quantity']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="asking_rate" class="form-label">Asking Rate</label>
            <input type="number" step="0.01" class="form-control" id="asking_rate" name="asking_rate" value="<?= htmlspecialchars($row['asking_rate']) ?>" required>
        </div>
        <button type="submit" class="btn btn-warning">Update</button>
        <a href="index.php" class="btn btn-secondary">Back</a>
    </form>
    <?php else: ?>
        <div class="alert alert-danger">No record found to edit.</div>
    <?php endif; ?>
</div>
</body>
</html>

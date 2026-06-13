<?php
include 'conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $value = $_POST['value'];

    $stmt = $mysqli->prepare("INSERT INTO coal_summary (name, value) VALUES (?, ?)");
    $stmt->bind_param("si", $name, $value);
    $stmt->execute();
    $stmt->close();

    header("Location: admin.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Insert Coal Summary</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
<div class="container mt-5">
    <h2>Insert Coal Summary</h2>
    <form method="post">
        <div class="mb-3">
            <label for="name" class="form-label">Summary Name</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="mb-3">
            <label for="value" class="form-label">Value</label>
            <input type="number" class="form-control" id="value" name="value" required>
        </div>
        <button type="submit" class="btn btn-success">Insert</button>
        <a href="admin.php" class="btn btn-secondary">Back</a>
    </form>
</div>
</body>
</html>

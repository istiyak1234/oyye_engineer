<?php
include 'conn.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $result = $mysqli->query("SELECT * FROM coal_summary WHERE id = $id");
    $row = $result->fetch_assoc();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $value = $_POST['value'];

    $stmt = $mysqli->prepare("UPDATE coal_summary SET name = ?, value = ? WHERE id = ?");
    $stmt->bind_param("sii", $name, $value, $id);
    $stmt->execute();
    $stmt->close();

    header("Location: admin.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Coal Summary</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
<div class="container mt-5">
    <h2>Update Coal Summary</h2>
    <form method="post">
        <input type="hidden" name="id" value="<?= $row['id'] ?>">
        <div class="mb-3">
            <label for="name" class="form-label">Summary Name</label>
            <input type="text" class="form-control" id="name" name="name" value="<?= $row['name'] ?>" required>
        </div>
        <div class="mb-3">
            <label for="value" class="form-label">Value</label>
            <input type="number" class="form-control" id="value" name="value" value="<?= $row['value'] ?>" required>
        </div>
        <button type="submit" class="btn btn-warning">Update</button>
        <a href="index.php" class="btn btn-secondary">Back</a>
    </form>
</div>
</body>
</html>

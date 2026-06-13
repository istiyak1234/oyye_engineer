<?php

session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}


$mysqli = new mysqli("sql303.infinityfree.com", "if0_38823587", "Istiyak0209", "if0_38823587_istiyak_web");
$summary = $mysqli->query("SELECT * FROM coal_summary");
$mines = $mysqli->query("SELECT * FROM mines_do");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Coal Stock Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
  <style>
    body, html { height: 100%; margin: 0; font-family: 'Segoe UI', sans-serif; background: #f0f2f5; }
    .split-screen { display: flex; height: 100vh; }
    .left-pane, .right-pane { flex: 1; padding: 30px; overflow-y: auto; }
    .left-pane { background: #f8f9fa; border-right: 2px solid #dee2e6; }
    .right-pane { background: #ffffff; }
    .card { border: none; border-radius: 15px; color: white; box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1); }
    .bg-opening { background: #17a2b8; }
    .bg-receipt { background: #28a745; }
    .bg-consumption { background: #ffc107; color: #212529; }
    .bg-stock { background: #007bff; }
    .bg-do { background: #6f42c1; }
    .bg-balance { background: #dc3545; }
    .section-title { font-size: 22px; font-weight: 600; margin-bottom: 20px; text-align: center; color: #343a40; }
  </style>
</head>
<body>
<div class="container-fluid mt-3">
  
  <div class="d-flex justify-content-end mb-3">
  <a href="logout.php" class="btn btn-danger btn-sm">Logout</a>
</div>

</div>

<div class="split-screen">
  <!-- Left Panel: Summary Cards -->
  <div class="left-pane">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h4 class="section-title m-0">
    Coal Stock Summary 
    <small class="text-muted">(as of <?= date('d/m/Y', strtotime('-1 day')) ?>)</small>
</h4>
      <a href="summary_insert.php" class="btn btn-success btn-sm">Add New Stock</a>
    </div>
    <div class="row g-4">
      <?php
      $bg_classes = ['bg-opening', 'bg-receipt', 'bg-consumption', 'bg-stock', 'bg-do', 'bg-balance'];
      $i = 0;
      while($row = $summary->fetch_assoc()):
      ?>
        <div class="col-md-6">
          <div class="card <?= $bg_classes[$i++ % count($bg_classes)] ?> p-3">
            <h5 class="text-center"><?= $row['name'] ?>: <?= number_format($row['value']) ?></h5>
            <div class="text-center mt-3">
              <a href="summary_update.php?id=<?= $row['id'] ?>" class="btn btn-light btn-sm me-2">Edit</a>
              <a href="summary_delete.php?id=<?= $row['id'] ?>" class="btn btn-dark btn-sm" onclick="return confirm('Are you sure to delete this summary?')">Delete</a>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    </div>
  </div>

  <!-- Right Panel: Mines DO Table -->
  <div class="right-pane">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h4 class="section-title m-0">Mines DO Records</h4>
      <a href="mines_insert.php" class="btn btn-success btn-sm">Add New DO</a>
    </div>
    <table class="table table-bordered table-striped text-center">
      <thead class="table-dark">
        <tr>
          <th>S.No</th>
          <th>Mines Name</th>
          <th>DO No.</th>
          <th>Quantity</th>
          <th>Lapsing_date</th>
          <th>balance_quantity</th>
          <th>asking_rate</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php $sno = 1; while($row = $mines->fetch_assoc()): ?>
          <tr>
            <td><?= $sno++ ?></td>
            <td><?= htmlspecialchars($row['mines_name']) ?></td>
            <td><?= htmlspecialchars($row['do_no']) ?></td>
            <td><?= number_format($row['quantity']) ?></td>
            <td>
    <?php 
    if (!empty($row['lapsing_date']) && strtotime($row['lapsing_date'])) {
        echo date('d/m/Y', strtotime($row['lapsing_date']));
    } else {
        echo '-'; // or leave empty, or show 'N/A'
    }
    ?>
</td>

            <td><?= htmlspecialchars($row['balance_quantity']) ?></td>
            <td><?= htmlspecialchars($row['asking_rate']) ?></td>
            <td>
              <a href="mines_update.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm me-1">Edit</a>
              <a href="mines_delete.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure to delete this DO record?')">Delete</a>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>

</body>
</html>

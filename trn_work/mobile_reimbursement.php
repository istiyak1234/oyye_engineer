<?php
// Database connection
include "conn.php";

// Handle Create/Update
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = $_POST['id'] ?? '';
    $stmt = $id ?
        $conn->prepare("UPDATE mobile_reimbursements SET name=?, employee_id=?, designation=?, department=?, amount=?, date_of_reimbursement=?, advised_date=?, payment_date=?, comments=? WHERE id=?") :
        $conn->prepare("INSERT INTO mobile_reimbursements (name, employee_id, designation, department, amount, date_of_reimbursement, advised_date, payment_date, comments) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

    if ($id) {
        $stmt->bind_param("sssssssssi", $_POST['name'], $_POST['employee_id'], $_POST['designation'], $_POST['department'], $_POST['amount'], $_POST['date_of_reimbursement'], $_POST['advised_date'], $_POST['payment_date'], $_POST['comments'], $id);
    } else {
        $stmt->bind_param("sssssssss", $_POST['name'], $_POST['employee_id'], $_POST['designation'], $_POST['department'], $_POST['amount'], $_POST['date_of_reimbursement'], $_POST['advised_date'], $_POST['payment_date'], $_POST['comments']);
    }
    $stmt->execute();
    header("Location: ".$_SERVER['PHP_SELF']);
    exit;
}

// Handle Delete
if (isset($_GET['delete'])) {
    $conn->query("DELETE FROM mobile_reimbursements WHERE id = " . intval($_GET['delete']));
    header("Location: ".$_SERVER['PHP_SELF']);
    exit;
}

// Handle Edit
$editData = null;
if (isset($_GET['edit'])) {
    $res = $conn->query("SELECT * FROM mobile_reimbursements WHERE id = " . intval($_GET['edit']));
    $editData = $res->fetch_assoc();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Reimbursement Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
<div class="container">
    <h3 class="mb-4">Mobile Reimbursement Form</h3>

    <!-- Form -->
    <form method="POST" class="border p-3 mb-4">
        <input type="hidden" name="id" value="<?= $editData['id'] ?? '' ?>">
        <div class="row g-2">
            <div class="col-md-4"><input name="name" class="form-control" placeholder="Name" value="<?= $editData['name'] ?? '' ?>" required></div>
            <div class="col-md-4"><input name="employee_id" class="form-control" placeholder="Employee ID" value="<?= $editData['employee_id'] ?? '' ?>" required></div>
            <div class="col-md-4"><input name="designation" class="form-control" placeholder="Designation" value="<?= $editData['designation'] ?? '' ?>" required></div>
            <div class="col-md-4"><input name="department" class="form-control" placeholder="Department" value="<?= $editData['department'] ?? '' ?>" required></div>
            <div class="col-md-4"><input type="number" step="0.01" name="amount" class="form-control" placeholder="Amount" value="<?= $editData['amount'] ?? '' ?>" required></div>
            <div class="col-md-4"><input type="date" name="date_of_reimbursement" class="form-control" value="<?= $editData['date_of_reimbursement'] ?? '' ?>" required></div>
            <div class="col-md-4"><input type="date" name="advised_date" class="form-control" value="<?= $editData['advised_date'] ?? '' ?>"></div>
            <div class="col-md-4"><input type="date" name="payment_date" class="form-control" value="<?= $editData['payment_date'] ?? '' ?>"></div>
            <div class="col-md-12"><textarea name="comments" class="form-control" placeholder="Comments"><?= $editData['comments'] ?? '' ?></textarea></div>
        </div>
        <div class="mt-3">
            <button class="btn btn-success"><?= $editData ? 'Update' : 'Submit' ?></button>
            <?php if ($editData): ?>
                <a href="<?= $_SERVER['PHP_SELF'] ?>" class="btn btn-secondary">Cancel</a>
            <?php endif; ?>
        </div>
    </form>

    <!-- Table -->
    <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
    <table class="table table-bordered table-striped">
        <thead>
            <tr style="white-space:nowrap; position: sticky; top: 0; background-color: #fff; z-index: 100;">
                <th>S No.</th><th>Name</th><th>Emp ID</th><th>Designation</th><th>Dept</th><th>Amount</th>
                <th>Date</th><th>Comments</th><th>Advised</th><th>Paid</th><th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php
       $result = $conn->query("SELECT * FROM mobile_reimbursements");
       $serial = 1;

        while ($row = $result->fetch_assoc()):
        ?>
            <tr style="white-space:nowrap;">
                 <td><?= $serial++ ?></td>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['employee_id']) ?></td>
                <td><?= htmlspecialchars($row['designation']) ?></td>
                <td><?= htmlspecialchars($row['department']) ?></td>
                <td><?= number_format($row['amount'], 2) ?></td>
                <td><?= $row['date_of_reimbursement'] ?></td>
                <td><?= htmlspecialchars($row['comments']) ?></td>
                <td><?= $row['advised_date'] ?></td>
                <td><?= $row['payment_date'] ?></td>
                
                <td>
                    <a href="?edit=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                    <a href="?delete=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this record?');">Delete</a>
                </td>
            </tr>
        <?php endwhile ?>
        </tbody>
    </table>
    </div>
</div>
</body>
</html>

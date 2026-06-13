<?php
include 'conn.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Prepare the DELETE statement
    $stmt = $mysqli->prepare("DELETE FROM mines_do WHERE id = ?");
    $stmt->bind_param("i", $id);

    // Execute and close
    if ($stmt->execute()) {
        echo "<script>alert('Record deleted successfully.'); window.location.href='admin.php';</script>";
    } else {
        echo "<script>alert('Failed to delete record.'); window.location.href='admin.php';</script>";
    }

    $stmt->close();
} else {
    echo "<script>alert('Invalid ID.'); window.location.href='admin.php';</script>";
}
?>

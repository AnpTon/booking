<?php
require '../config.php';
require '../functions.php';
requireLogin();

if (!isLibrarian()) {
    header("Location: ../index.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: admin.php?tab=borrow");
    exit();
}

$record_id = $_GET['id'];

$conn->query("
    UPDATE borrowrecords
    SET status = 'rejected'
    WHERE record_id = '$record_id'
");

header("Location: admin.php?tab=borrow");
exit();
?>

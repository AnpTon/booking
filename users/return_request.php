<?php
require '../config.php';
require '../functions.php';
requireLogin();


if (!isset($_GET['id'])) {
    header("Location: user.php?tab=mybooks");
    exit();
}


$record_id = $_GET['id'];
$user_id = $_SESSION['user_id'];


$check = $conn->query("
    SELECT * FROM borrowrecords
    WHERE record_id = '$record_id' AND user_id = '$user_id'
");


if ($check->num_rows === 0) {
    header("Location: user.php?tab=mybooks");
    exit();
}


$conn->query("
    UPDATE borrowrecords
    SET status = 'return_pending', return_date = NOW()
    WHERE record_id = '$record_id'
");


header("Location: user.php?tab=pending");
exit();
?>

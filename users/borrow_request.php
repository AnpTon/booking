<?php
require '../config.php';
require '../functions.php';
requireLogin();


if (!isset($_GET['id'])) {
    header("Location: user.php?tab=books");
    exit();
}


$book_id = $_GET['id'];
$user_id = $_SESSION['user_id'];


$check = $conn->query("SELECT status FROM books WHERE book_id = '$book_id'");
$book = $check->fetch_assoc();


if (!$book || $book['status'] !== 'available') {
    header("Location: user.php?tab=books");
    exit();
}


$conn->query("
    INSERT INTO borrowrecords (user_id, book_id, borrow_date, status)
    VALUES ('$user_id', '$book_id', NOW(), 'pending')
");


header("Location: user.php?tab=pending");
exit();
?>

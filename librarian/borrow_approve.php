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

    $record = $conn->query("
        SELECT * FROM borrowrecords WHERE record_id = '$record_id'
    ")->fetch_assoc();

    $book_id = $record['book_id'];

    $conn->query("
        UPDATE borrowrecords
        SET status = 'approved'
        WHERE record_id = '$record_id'
    ");

    $conn->query("
        UPDATE books
        SET status = 'approved'
        WHERE book_id = '$book_id'
    ");

    header("Location: admin.php?tab=borrow");
    exit();
?>

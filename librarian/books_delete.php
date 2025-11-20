<?php
require '../config.php';

$id = $_GET['id'];

$conn->query("DELETE FROM books WHERE book_id = $id");

header("Location: admin.php?tab=books");
exit();
?>

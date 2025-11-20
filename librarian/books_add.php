<?php
require '../config.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $description = $_POST['description'];

    $stmt = $conn->prepare("
        INSERT INTO books (title, author, description)
        VALUES (?, ?, ?)
    ");
    $stmt->bind_param("sss", $title, $author, $description);

    if ($stmt->execute()) {
        header("Location: admin.php?tab=books");
        exit();
    } else {
        $message = "Error adding book!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Book</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light p-4">
<div class="container col-md-6">
    <h3>Add Book</h3>
    <form method="POST">
        <label>Title</label>
        <input type="text" name="title" class="form-control" required>

        <label class="mt-3">Author</label>
        <input type="text" name="author" class="form-control" required>

        <label class="mt-3">Description</label>
        <textarea name="description" class="form-control"></textarea>

        <button class="btn btn-primary mt-3">Save</button>
        <a href="admin.php?tab=books" class="btn btn-secondary mt-3">Cancel</a>
    </form>
    <p class="text-danger mt-2"><?= $message ?></p>
</div>

</body>
</html>

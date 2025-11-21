<?php
require '../config.php';

$id = $_GET['id'];
$message = "";

$book = $conn->query("SELECT * FROM books WHERE book_id = $id")->fetch_assoc();

if (!$book) {
    die("Book not found!");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $description = $_POST['description'];

    $stmt = $conn->prepare("
        UPDATE books 
        SET title=?, author=?, description=?
        WHERE book_id=?
    ");
    $stmt->bind_param("sssi", $title, $author, $description, $id);

    if ($stmt->execute()) {
        header("Location: admin.php?tab=books");
        exit();
    } else {
        $message = "Failed to update book!";
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Book</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light p-4">
<div class="container col-md-6">
    <h3>Edit Book</h3>
    <form method="POST">
        <label>Title</label>
        <input type="text" name="title" class="form-control" value="<?= $book['title'] ?>" required>

        <label class="mt-3">Author</label>
        <input type="text" name="author" class="form-control" value="<?= $book['author'] ?>" required>

        <label class="mt-3">Description</label>
        <textarea name="description" class="form-control"><?= $book['description'] ?></textarea>

        <button class="btn btn-primary mt-3">Save Changes</button>
        <a href="admin.php?tab=books" class="btn btn-secondary mt-3">Cancel</a>
    </form>
    <p class="text-danger mt-2"><?= $message ?></p>
</div>
</body>
</html>

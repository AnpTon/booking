<?php
require '../config.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password_hashed = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST["role"];
    $stmt = $conn->prepare("
        INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)
    ");
    $stmt->bind_param("ssss", $name, $email, $password_hashed, $role);

    if ($stmt->execute()) {
        header("Location: admin.php?tab=users");
        exit();
    } else {
        $message = "Error adding user!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light p-4">
<div class="container col-md-6">
    <h3>Add User</h3>
    <form method="POST">
        <label>Name</label>
        <input type="text" name="name" class="form-control" required>

        <label>Email</label>
        <input type="email" name="email" class="form-control" required>

        <label>Password</label>
        <input type="password" name="password" class="form-control" required>

        <label>Role</label>
        <select name="role" class="form-control">
            <option value="user">User</option>
            <option value="librarian">Librarian</option>
        </select>

        <button class="btn btn-primary mt-3">Save</button>
        <a href="admin.php?tab=users" class="btn btn-secondary mt-3">Cancel</a>
    </form>
    <p class="text-danger mt-2"><?= $message ?></p>
</div>

</body>
</html>
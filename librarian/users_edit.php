<?php
require '../config.php';

$id = $_GET['id'];
$message = "";

$user = $conn->query("SELECT * FROM users WHERE user_id = $id")->fetch_assoc();

if (!$user) {
    die("User not found!");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        $stmt = $conn->prepare("
            UPDATE users SET username=?, password=?, role=? WHERE id=?
        ");
        $stmt->bind_param("sssi", $username, $password, $role, $id);

    } else {
        $stmt = $conn->prepare("
            UPDATE users SET username=?, role=? WHERE id=?
        ");
        $stmt->bind_param("ssi", $username, $role, $id);
    }

    if ($stmt->execute()) {
        header("Location: admin.php?tab=users");
        exit();
    } else {
        $message = "Failed to update user!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light p-4">
<div class="container col-md-6">
    <h3>Edit User</h3>
    <form method="POST">
        <label>Name</label>
        <input type="text" name="name" class="form-control"
               value="<?= $user['name'] ?>" required>
        
        <label>Email</label>
        <input type="email" name="email" class="form-control"
               value="<?= $user['email'] ?>" required>

        <label class="mt-3">New Password (leave blank to keep old)</label>
        <input type="password" name="password" class="form-control">

        <label class="mt-3">Role</label>
        <select name="role" class="form-control">
            <option value="librarian" <?= $user['role']=="librarian"?"selected":"" ?>>Librarian</option>
            <option value="user" <?= $user['role']=="user"?"selected":"" ?>>User</option>
        </select>

        <button class="btn btn-primary mt-3">Save Changes</button>
        <a href="admin.php?tab=users" class="btn btn-secondary mt-3">Cancel</a>
    </form>
    <p class="text-danger mt-2"><?= $message ?></p>
</div>
</body>
</html>

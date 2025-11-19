<?php
session_start();
require 'config.php';

// If user is already logged in → redirect to admin dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: admin.php");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT user_id, email, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    // Validate user
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id, $db_email, $db_password);
        $stmt->fetch();

        if (password_verify($password, $db_password)) {
            // Login OK → Set session
            $_SESSION['user_id'] = $user_id;
            $_SESSION['email'] = $db_email;

            header("Location: admin.php");
            exit;
        } else {
            $error = "Invalid email or password.";
        }
    } else {
        $error = "User not found.";
    }
}
?>

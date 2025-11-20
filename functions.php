<?php
session_start();
include 'config.php';

function checkLogin($email, $password) {
    global $conn;

    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['role']    = $row['role'];
            $_SESSION['name']    = $row['name'];

            return true;
        }
    }

    return false;
}

function requireLogin() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: auth/login.php");
        exit();
    }
}

function isLibrarian() {
    return isset($_SESSION['role']) && $_SESSION['role'] === "librarian";
}

function isUser() {
    return isset($_SESSION['role']) && $_SESSION['role'] === "user";
}

function redirectDashboard() {
    if (isLibrarian()) {
        header("Location: ../librarian/admin.php");
        exit();
    }

    if (isUser()) {
        header("Location: ../users/user.php");
        exit();
    }
}

?>

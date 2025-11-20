<?php
include '../functions.php';

if (isset($_SESSION['user_id'])) {
    redirectDashboard();
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $_POST['email'];
    $password = $_POST['password'];

    if (checkLogin($email, $password)) {
        redirectDashboard();
    } else {
        $message = "Invalid email or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Booking System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-4">

                <div class="card shadow">
                    <div class="card-header text-center bg-primary text-white">
                        <h4>Admin Login</h4>
                    </div>

                    <div class="card-body">

                        <?php if (!empty($message)): ?>
                        <div class="alert alert-danger text-center">
                            <?= $message ?>
                        </div>
                        <?php endif; ?>

                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>

                            <button class="btn btn-primary w-100">Login</button>
                        </form>

                    </div>

                </div>

            </div>
        </div>
    </div>
</body>
</html>

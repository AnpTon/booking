<?php
include 'functions.php';

if (isset($_SESSION['user_id'])) {
    redirectDashboard();
}

header("Location: auth/login.php");
exit();
?>
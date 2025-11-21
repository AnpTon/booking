<?php
require '../config.php';
require '../functions.php';
requireLogin();


$activeTab = isset($_GET['tab']) ? $_GET['tab'] : 'books';


$user_id = $_SESSION['user_id'];


$books = $conn->query("
    SELECT * FROM books ORDER BY title ASC
");


$myBorrows = $conn->query("
    SELECT br.record_id, br.borrow_date, br.return_date, b.title
    FROM borrowrecords br
    JOIN books b ON br.book_id = b.book_id
    WHERE br.user_id = '$user_id'
      AND br.status = 'approved'
");


$pending = $conn->query("
    SELECT br.record_id, br.status, br.borrow_date, br.return_date, b.title
    FROM borrowrecords br
    JOIN books b ON br.book_id = b.book_id
    WHERE br.user_id = '$user_id'
      AND (br.status = 'pending' OR br.status = 'return_pending')
    ORDER BY br.record_id DESC
");
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>


<body class="bg-light">
<nav class="navbar navbar-dark bg-primary">
    <div class="container">
        <span class="navbar-brand">User Dashboard</span>
        <span class="text-white">
            Logged in as <strong><?= $_SESSION['name'] ?></strong> |
            <a href="../auth/logout.php" class="text-white text-decoration-underline">Logout</a>
        </span>
    </div>
</nav>
<div class="container mt-4">
    <ul class="nav nav-tabs" id="adminTabs" role="tablist">
        <li class="nav-item">
            <button class="nav-link <?= $activeTab === 'books' ? 'active' : '' ?>"
                    data-bs-toggle="tab" data-bs-target="#books" type="button">
                Books
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link <?= $activeTab === 'mybooks' ? 'active' : '' ?>"
                    data-bs-toggle="tab" data-bs-target="#mybooks" type="button">
                My Borrowed Books
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link <?= $activeTab === 'pending' ? 'active' : '' ?>"
                    data-bs-toggle="tab" data-bs-target="#pending" type="button">
                Pending Requests
            </button>
        </li>
    </ul>


    <div class="tab-content mt-3">
        <!-- Did you Book? -->
        <div class="tab-pane fade <?= $activeTab === 'books' ? 'show active' : '' ?>" id="books">
            <h4 class="mb-3">Available Books</h4>
            <div class="card">
                <div class="card-body table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Author</th>
                                <th>Description</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>


                        <tbody>
                            <?php while ($row = $books->fetch_assoc()): ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['title']) ?></td>
                                    <td><?= htmlspecialchars($row['author']) ?></td>
                                    <td><?= strlen($row['description']) > 40
                                            ? substr($row['description'], 0, 40) . "..."
                                            : $row['description'] ?></td>
                                    <td>
                                        <?php if ($row['status'] === 'available'): ?>
                                            <span class="badge bg-success">Available</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Borrowed</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($row['status'] === 'available'): ?>
                                            <a href="borrow_request.php?id=<?= $row['book_id'] ?>"
                                               class="btn btn-primary btn-sm">
                                                Borrow
                                            </a>
                                        <?php else: ?>
                                            <button class="btn btn-secondary btn-sm" disabled>Unavailable</button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>


        <!-- TAB 2: Borrow? -->
        <div class="tab-pane fade <?= $activeTab === 'mybooks' ? 'show active' : '' ?>" id="mybooks">
            <h4 class="mb-3">My Borrowed Books</h4>
            <div class="card">
                <div class="card-body table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Book</th>
                                <th>Borrow Date</th>
                                <th>Return Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>


                        <tbody>
                            <?php while ($row = $myBorrows->fetch_assoc()): ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['title']) ?></td>
                                    <td><?= $row['borrow_date'] ?></td>
                                    <td><?= $row['return_date'] ?></td>
                                    <td>
                                        <a href="return_request.php?id=<?= $row['record_id'] ?>"
                                           class="btn btn-warning btn-sm">
                                            Request Return
                                        </a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>


        <!-- Pend your hear -->
        <div class="tab-pane fade <?= $activeTab === 'pending' ? 'show active' : '' ?>" id="pending">
            <h4 class="mb-3">Pending Requests</h4>


            <div class="card">
                <div class="card-body table-responsive">
               
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Book</th>
                                <th>Request Type</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $pending->fetch_assoc()): ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['title']) ?></td>


                                    <td>
                                        <?php if ($row['status'] === 'pending'): ?>
                                            Borrow Request
                                        <?php elseif ($row['status'] === 'return_pending'): ?>
                                            Return Request
                                        <?php endif; ?>
                                    </td>


                                    <td>
                                        <span class="badge bg-warning text-dark">
                                            <?= ucfirst($row['status']) ?>
                                        </span>
                                    </td>


                                    <td>
                                        <?= $row['status'] === 'pending'
                                                ? $row['borrow_date']
                                                : $row['return_date'] ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
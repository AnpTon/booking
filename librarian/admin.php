<?php
require '../config.php';
require '../functions.php';
$activeTab = isset($_GET['tab']) ? $_GET['tab'] : 'books';
requireLogin();
if (!isLibrarian()) {
    header("Location: ../index.php");
    exit();
}

$books = $conn->query("SELECT * FROM books ORDER BY title ASC");

$users = $conn->query("SELECT user_id, name, email, role FROM users ORDER BY name ASC");

$borrow = $conn->query("
    SELECT br.record_id, br.status, br.borrow_date, br.return_date,
           u.name AS user_name,
           b.title AS book_title
    FROM borrowrecords br
    JOIN users u ON br.user_id = u.user_id
    JOIN books b ON br.book_id = b.book_id
    ORDER BY br.record_id DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Librarian Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<nav class="navbar navbar-dark bg-primary">
    <div class="container">
        <span class="navbar-brand">Librarian Dashboard</span>
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
            <button class="nav-link <?= $activeTab === 'users' ? 'active' : '' ?>" 
                    data-bs-toggle="tab" data-bs-target="#users" type="button">
                Users
            </button>
        </li>

        <li class="nav-item">
            <button class="nav-link <?= $activeTab === 'borrow' ? 'active' : '' ?>" 
                    data-bs-toggle="tab" data-bs-target="#borrow" type="button">
                Borrow Records
            </button>
        </li>
    </ul>

    <div class="tab-content mt-3">

        <!-- Booker -->
        <div class="tab-pane fade <?= $activeTab === 'books' ? 'show active' : '' ?>" id="books">
            <div class="d-flex justify-content-between mb-2">
                <h4>Books</h4>
                <a href="books_add.php" class="btn btn-primary btn-sm">Add Book</a>
            </div>
            <div class="card">
                <div class="card-body table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Title</th>
                                <th>Author</th>
                                <th>Description</th>
                                <th>Status</th>
                                <th>Date Added</th>
                                <th>Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php while ($row = $books->fetch_assoc()): ?>
                                <tr>
                                    <td><?= $row['book_id'] ?></td>
                                    <td><?= htmlspecialchars($row['title']) ?></td>
                                    <td><?= htmlspecialchars($row['author']) ?></td>
                                    <td><?= strlen($row['description']) > 40 
                                            ? substr($row['description'], 0, 40) . '...' 
                                            : $row['description'] ?></td>
                                    <td>
                                        <?php if ($row['status'] === 'available'): ?>
                                            <span class="badge bg-success">Available</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Borrowed</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= $row['date_added'] ?></td>
                                    <td>
                                        <a href="books_edit.php?id=<?= $row['book_id'] ?>" 
                                        class="btn btn-warning btn-sm">Edit</a>

                                        <a href="books_delete.php?id=<?= $row['book_id'] ?>" 
                                        class="btn btn-danger btn-sm"
                                        onclick="return confirm('Delete this book?');">
                                        Delete
                                        </a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Use This -->
        <div class="tab-pane fade <?= $activeTab === 'users' ? 'show active' : '' ?>" id="users">
            <div class="d-flex justify-content-between mb-3">
                <h4>Users</h4>
                <a href="users_add.php" class="btn btn-primary btn-sm">Add User</a>
            </div>
            <div class="card">
                <div class="card-body table-responsive">

                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $users->fetch_assoc()): ?>
                                <tr>
                                    <td><?= $row['user_id'] ?></td>
                                    <td><?= htmlspecialchars($row['name']) ?></td>
                                    <td><?= htmlspecialchars($row['email']) ?></td>
                                    <td><?= ucfirst($row['role']) ?></td>
                                    <td>
                                        <a href="users_edit.php?id=<?= $row['user_id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                                        <a href="users_delete.php?id=<?= $row['user_id'] ?>" class="btn btn-danger btn-sm"
                                           onclick="return confirm('Delete this user?')">Delete</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Are you recording? -->
        <div class="tab-pane fade <?= $activeTab === 'borrow' ? 'show active' : '' ?>" id="borrow">
            <h4 class="mb-3">Borrow & Return Requests</h4>
            <div class="card">
                <div class="card-body table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>User</th>
                                <th>Book</th>
                                <th>Status</th>
                                <th>Borrow Date</th>
                                <th>Return Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $borrow->fetch_assoc()): ?>
                                <tr>
                                    <td><?= $row['record_id'] ?></td>
                                    <td><?= htmlspecialchars($row['user_name']) ?></td>
                                    <td><?= htmlspecialchars($row['book_title']) ?></td>
                                    <td><?= ucfirst($row['status']) ?></td>
                                    <td><?= $row['borrow_date'] ?></td>
                                    <td><?= $row['return_date'] ?></td>
                                    <td>
                                        <?php if ($row['status'] === 'pending'): ?>
                                            <a href="borrow_approve.php?id=<?= $row['record_id'] ?>" class="btn btn-success btn-sm">
                                                Approve Borrow
                                            </a>
                                        <?php endif; ?>

                                        <?php if ($row['status'] === 'return_pending'): ?>
                                            <a href="return_approve.php?id=<?= $row['record_id'] ?>" class="btn btn-primary btn-sm">
                                                Approve Return
                                            </a>
                                        <?php endif; ?>
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

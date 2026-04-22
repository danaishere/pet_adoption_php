<!DOCTYPE html>
<html>
<head>
    <title>Pet Adoption System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f4f6f8; }
        .navbar { margin-bottom: 30px; }
        .badge-pending   { background: #ffc107; color:#000; }
        .badge-approved  { background: #198754; color:#fff; }
        .badge-completed { background: #0d6efd; color:#fff; }
        .badge-rejected  { background: #dc3545; color:#fff; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark px-4">
    <a class="navbar-brand fw-bold" href="#">Pet Adoption</a>
    <div class="ms-auto d-flex align-items-center gap-2">

        <?php if (isset($_SESSION['user_id'])): ?>
            <span class="text-white">Hello, <?= htmlspecialchars($_SESSION['user_name']) ?></span>

            <?php if ($_SESSION['role'] === 'admin'): ?>
                <a href="../admin/dashboard.php"          class="btn btn-sm btn-warning">Dashboard</a>
                <a href="../applications/list.php"         class="btn btn-sm btn-info">All Applications</a>
            <?php else: ?>
                <a href="../applications/apply.php"        class="btn btn-sm btn-success">Apply</a>
                <a href="../applications/my_applications.php" class="btn btn-sm btn-info">My Applications</a>
            <?php endif; ?>

            <a href="../logout.php" class="btn btn-sm btn-danger">Logout</a>
        <?php else: ?>
            <a href="../login.php"  class="btn btn-sm btn-primary">Login</a>
        <?php endif; ?>

    </div>
</nav>

<div class="container">

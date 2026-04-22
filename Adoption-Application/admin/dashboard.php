<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

include "../includes/header.php";

$pending   = $conn->query("SELECT COUNT(*) AS c FROM applications WHERE status='pending'"  )->fetch_assoc();
$approved  = $conn->query("SELECT COUNT(*) AS c FROM applications WHERE status='approved'" )->fetch_assoc();
$completed = $conn->query("SELECT COUNT(*) AS c FROM applications WHERE status='completed'")->fetch_assoc();
$rejected  = $conn->query("SELECT COUNT(*) AS c FROM applications WHERE status='rejected'" )->fetch_assoc();

// total applications
$total = $pending['c'] + $approved['c'] + $completed['c'] + $rejected['c'];
?>

<h2 class="mb-4">Admin Dashboard</h2>


<div class="row mb-4 g-3">

    <div class="col-md-3">
        <div class="card text-white bg-secondary text-center p-3">
            <h5>Total Applications</h5>
            <h2><?= $total ?></h2>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card text-dark bg-warning text-center p-3">
            <h5>Pending</h5>
            <h2><?= $pending['c'] ?></h2>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card text-white bg-success text-center p-3">
            <h5>Approved</h5>
            <h2><?= $approved['c'] ?></h2>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card text-white bg-primary text-center p-3">
            <h5>Completed</h5>
            <h2><?= $completed['c'] ?></h2>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card text-white bg-danger text-center p-3">
            <h5>Rejected</h5>
            <h2><?= $rejected['c'] ?></h2>
        </div>
    </div>

</div>

<h4 class="mb-3">Recent Applications</h4>

<?php
$recent = $conn->query("
    SELECT applications.id, adopters.full_name, pet_profiles.name AS pet_name,
           applications.status, applications.created_at
    FROM applications
    JOIN adopters    ON applications.adopter_id = adopters.id
    JOIN pet_profiles ON applications.pet_id    = pet_profiles.pet_id
    ORDER BY applications.created_at DESC
    LIMIT 5
");
?>

<table class="table table-bordered table-hover">
    <thead class="table-dark">
        <tr>
            <th>#</th>
            <th>Adopter</th>
            <th>Pet</th>
            <th>Status</th>
            <th>Date Applied</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $recent->fetch_assoc()): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['full_name']) ?></td>
            <td><?= htmlspecialchars($row['pet_name']) ?></td>
            <td>
                <?php
                    $badge = ['pending'=>'warning','approved'=>'success','completed'=>'primary','rejected'=>'danger'];
                    $b = $badge[$row['status']] ?? 'secondary';
                ?>
                <span class="badge bg-<?= $b ?>"><?= $row['status'] ?></span>
            </td>
            <td><?= $row['created_at'] ?></td>
            <td><a href="../applications/view.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-info">View</a></td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<a href="../applications/list.php" class="btn btn-dark"> View All Applications</a>

<?php include "../includes/footer.php"; ?>

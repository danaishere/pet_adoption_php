<?php
session_start();
require_once '../shared/db.php';
require_once '../shared/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: /Adoption-Application/login.php");
    exit();
}

$role = $_SESSION['role'];

// Build query based on role
if ($role === 'admin') {
    $sql = "
        SELECT applications.id, adopters.full_name, pet_profiles.name AS pet_name,
               applications.status, applications.interview_date, applications.created_at
        FROM applications
        JOIN adopters     ON applications.adopter_id = adopters.id
        JOIN pet_profiles ON applications.pet_id     = pet_profiles.pet_id
        ORDER BY applications.created_at DESC
    ";
    $result = $conn->query($sql);
} else {
    $user_id = $_SESSION['user_id'];
    $sql = "
        SELECT applications.id, pet_profiles.name AS pet_name,
               applications.status, applications.interview_date, applications.created_at
        FROM applications
        JOIN pet_profiles ON applications.pet_id = pet_profiles.pet_id
        WHERE applications.adopter_id = $user_id
        ORDER BY applications.created_at DESC
    ";
    $result = $conn->query($sql);
}

function statusBadge($status) {
    $map = ['pending'=>'warning','approved'=>'success','completed'=>'primary','rejected'=>'danger'];
    $b = $map[$status] ?? 'secondary';
    return "<span class='badge bg-$b'>$status</span>";
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Adoption Applications</h2>
    <?php if ($role === 'adopter'): ?>
        <a href="apply.php" class="btn btn-success">Apply for a Pet</a>
    <?php endif; ?>
</div>

<?php if (isset($_GET['deleted'])): ?>
    <div class="alert alert-success">Application removed successfully.</div>
<?php endif; ?>

<?php if ($result->num_rows === 0): ?>
    <div class="alert alert-info">No applications found.</div>
<?php else: ?>
<table class="table table-bordered table-hover align-middle">
    <thead class="table-dark">
        <tr>
            <th>#</th>
            <?php if ($role === 'admin'): ?><th>Adopter</th><?php endif; ?>
            <th>Pet</th>
            <th>Status</th>
            <th>Interview Date</th>
            <th>Applied On</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <?php if ($role === 'admin'): ?>
                <td><?= htmlspecialchars($row['full_name']) ?></td>
            <?php endif; ?>
            <td><?= htmlspecialchars($row['pet_name']) ?></td>
            <td><?= statusBadge($row['status']) ?></td>
            <td><?= $row['interview_date'] ?? '<span class="text-muted">Not Scheduled</span>' ?></td>
            <td><?= $row['created_at'] ?></td>
            <td>
               <a href="applications/view.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-info">View</a>
                <?php if ($role === 'admin'): ?>
                    <a href="applications/edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
<a href="applications/delete.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger"
   onclick="return confirm('Delete this application?')">Delete</a>
                <?php endif; ?>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>
<?php endif; ?>

<?php require_once '../shared/footer.php'; ?>
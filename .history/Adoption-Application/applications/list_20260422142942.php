<?php
session_start();
include "../shared/db.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

include "../shared/header.php";


$status = $_GET['status'] ?? 'all';

$sql = "
    SELECT applications.id,
           applications.status,
           applications.interview_date,
           applications.created_at,
           adopters.full_name,
           adopters.email,
           pet_profiles.name    AS pet_name,
           pet_profiles.species AS pet_species
    FROM applications
    JOIN adopters     ON applications.adopter_id = adopters.id
    JOIN pet_profiles ON applications.pet_id     = pet_profiles.pet_id
";

if ($status !== 'all') {
    $sql .= " WHERE applications.status = '$status'";
}

$sql .= " ORDER BY applications.created_at DESC";

$result = $conn->query($sql);

function statusBadge($status) {
    $map = ['pending'=>'warning','approved'=>'success','completed'=>'primary','rejected'=>'danger'];
    $b   = $map[$status] ?? 'secondary';
    return "<span class='badge bg-$b'>$status</span>";
}
?>

<h2 class="mb-3"> All Applications</h2>

<?php if (isset($_GET['updated'])): ?>
    <div class="alert alert-success">Application updated successfully.</div>
<?php endif; ?>
<?php if (isset($_GET['deleted'])): ?>
    <div class="alert alert-warning">Application deleted.</div>
<?php endif; ?>

<!-- Filter by status -->
<form method="GET" class="d-flex align-items-center gap-2 mb-4">
    <label class="fw-bold mb-0">Filter by Status:</label>
    <select name="status" class="form-select w-auto">
        <option value="all"       <?= $status==='all'       ? 'selected':'' ?>>All</option>
        <option value="pending"   <?= $status==='pending'   ? 'selected':'' ?>>Pending</option>
        <option value="approved"  <?= $status==='approved'  ? 'selected':'' ?>>Approved</option>
        <option value="completed" <?= $status==='completed' ? 'selected':'' ?>>Completed</option>
        <option value="rejected"  <?= $status==='rejected'  ? 'selected':'' ?>>Rejected</option>
    </select>
    <button class="btn btn-dark">Filter</button>
    <?php if ($status !== 'all'): ?>
        <a href="list.php" class="btn btn-outline-secondary">Clear</a>
    <?php endif; ?>
</form>

<?php if ($result->num_rows === 0): ?>
    <div class="alert alert-info">No applications found for this filter.</div>
<?php else: ?>

<table class="table table-bordered table-hover align-middle">
    <thead class="table-dark">
        <tr>
            <th>#</th>
            <th>Adopter</th>
            <th>Email</th>
            <th>Pet</th>
            <th>Species</th>
            <th>Status</th>
            <th>Interview Date</th>
            <th>Applied On</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['full_name']) ?></td>
            <td><?= htmlspecialchars($row['email']) ?></td>
            <td><?= htmlspecialchars($row['pet_name']) ?></td>
            <td><?= htmlspecialchars($row['pet_species']) ?></td>
            <td><?= statusBadge($row['status']) ?></td>
            <td><?= $row['interview_date'] ? $row['interview_date'] : '<span class="text-muted">—</span>' ?></td>
            <td><?= $row['created_at'] ?></td>
            <td>
                <a href="view.php?id=<?= $row['id'] ?>"   class="btn btn-sm btn-info">View</a>
                <a href="edit.php?id=<?= $row['id'] ?>"   class="btn btn-sm btn-warning">Edit</a>
                <a href="delete.php?id=<?= $row['id'] ?>"
                   class="btn btn-sm btn-danger"
                   onclick="return confirm('Delete this application?')">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<?php endif; ?>

<?php include "../shared/footer.php"; ?>

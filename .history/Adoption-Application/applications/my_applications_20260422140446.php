<?php
session_start();
include "../shared/db.php";


if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'adopter') {
    header("Location: ../login.php");
    exit();
}

include "../includes/header.php";

$user_id = $_SESSION['user_id'];

$sql = "
    SELECT applications.id,
           applications.status,
           applications.interview_date,
           applications.created_at,
           pet_profiles.name        AS pet_name,
           pet_profiles.species     AS pet_species,
           pet_profiles.breed       AS pet_breed,
           pet_profiles.photo       AS pet_photo
    FROM applications
    JOIN pet_profiles ON applications.pet_id = pet_profiles.pet_id
    WHERE applications.adopter_id = $user_id
    ORDER BY applications.created_at DESC
";

$result = $conn->query($sql);


function statusBadge($status) {
    $map = ['pending'=>'warning','approved'=>'success','completed'=>'primary','rejected'=>'danger'];
    $b   = $map[$status] ?? 'secondary';
    return "<span class='badge bg-$b'>$status</span>";
}
?>

<h2 class="mb-4"> My Applications</h2>

<?php if (isset($_GET['deleted'])): ?>
    <div class="alert alert-success">Application withdrawn successfully.</div>
<?php endif; ?>

<?php if ($result->num_rows === 0): ?>
    <div class="alert alert-info">
        You have not applied for any pets yet.
        <a href="apply.php" class="alert-link">Apply now!</a>
    </div>
<?php else: ?>

<table class="table table-bordered table-hover align-middle">
    <thead class="table-dark">
        <tr>
            <th>#</th>
            <th>Pet</th>
            <th>Species / Breed</th>
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

            <!-- pet photo + name -->
            <td class="d-flex align-items-center gap-2">
                <?php if (!empty($row['pet_photo'])): ?>
                    <img src="/pet-adoption/<?= htmlspecialchars($row['pet_photo']) ?>"
                         width="50" height="50"
                         style="object-fit:cover; border-radius:6px;">
                <?php else: ?>
                    <span style="font-size:30px;">🐾</span>
                <?php endif; ?>
                <?= htmlspecialchars($row['pet_name']) ?>
            </td>

            <td><?= htmlspecialchars($row['pet_species']) ?> / <?= htmlspecialchars($row['pet_breed'] ?? '—') ?></td>

            <td><?= statusBadge($row['status']) ?></td>

            <td><?= $row['interview_date'] ? $row['interview_date'] : '<span class="text-muted">Not Scheduled</span>' ?></td>

            <td><?= $row['created_at'] ?></td>

            <td>
               
                <?php if (in_array($row['status'], ['pending', 'rejected'])): ?>
                    <a href="delete.php?id=<?= $row['id'] ?>"
                       class="btn btn-sm btn-danger"
                       onclick="return confirm('Withdraw this application?')">
                       Withdraw
                    </a>
                <?php else: ?>
                    <span class="text-muted small">Cannot withdraw</span>
                <?php endif; ?>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<?php endif; ?>

<a href="apply.php" class="btn btn-success mt-2">Apply for Another Pet</a>

<?php include "../includes/footer.php"; ?>

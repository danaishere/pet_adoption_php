<?php
session_start();
require_once "../../shared/db.php";
require_once "../../shared/header.php";

$id  = intval($_GET['id']);
$app = $conn->query("
    SELECT applications.*,
           adopters.full_name, adopters.email,
           pet_profiles.name AS pet_name, pet_profiles.species AS pet_species,
           pet_profiles.pet_id AS pet_profile_id
    FROM applications
    JOIN adopters     ON applications.adopter_id = adopters.id
    JOIN pet_profiles ON applications.pet_id     = pet_profiles.pet_id
    WHERE applications.id = $id
")->fetch_assoc();

if (!$app) {
    echo "<div class='alert alert-danger'>Application not found.</div>";
    require_once "../../shared/footer.php";
    exit();
}

if (isset($_POST['update'])) {
    $new_status     = $_POST['status'];
    $interview_date = $_POST['interview_date'];
    $pet_id         = $app['pet_profile_id'];

    if (empty($interview_date)) {
        $conn->query("UPDATE applications SET status='$new_status', interview_date=NULL WHERE id=$id");
    } else {
        $conn->query("UPDATE applications SET status='$new_status', interview_date='$interview_date' WHERE id=$id");
    }

    if ($new_status === 'rejected') {
        $conn->query("UPDATE pet_profiles SET adoption_status='Available' WHERE pet_id=$pet_id");
    } elseif ($new_status === 'completed') {
        $conn->query("UPDATE pet_profiles SET adoption_status='Adopted' WHERE pet_id=$pet_id");
    } elseif ($new_status === 'approved') {
        $conn->query("UPDATE pet_profiles SET adoption_status='Pending' WHERE pet_id=$pet_id");
    }

    header("Location: /Adoption-Application/index.php?updated=1");
    exit();
}

$interview_val = '';
if (!empty($app['interview_date'])) {
    $interview_val = date('Y-m-d\TH:i', strtotime($app['interview_date']));
}
?>

<h2 class="mb-4">Edit Application #<?= $id ?></h2>

<div class="card mb-4 shadow-sm" style="max-width:600px;">
    <div class="card-body">
        <p><strong>Adopter:</strong> <?= htmlspecialchars($app['full_name']) ?> (<?= htmlspecialchars($app['email']) ?>)</p>
        <p><strong>Pet:</strong> <?= htmlspecialchars($app['pet_name']) ?> — <?= htmlspecialchars($app['pet_species']) ?></p>
        <p><strong>Current Status:</strong> <span class="badge bg-warning"><?= $app['status'] ?></span></p>
        <p><strong>Current Interview Date:</strong>
            <?= $app['interview_date'] ?? '<span class="text-muted">Not Scheduled</span>' ?>
        </p>
    </div>
</div>

<form method="POST" class="card p-4 shadow-sm" style="max-width:500px;">
    <div class="mb-3">
        <label class="form-label fw-bold">Update Status</label>
        <select name="status" class="form-select">
            <option value="pending"   <?= $app['status'] === 'pending'   ? 'selected' : '' ?>>Pending</option>
            <option value="approved"  <?= $app['status'] === 'approved'  ? 'selected' : '' ?>>Approved</option>
            <option value="completed" <?= $app['status'] === 'completed' ? 'selected' : '' ?>>Completed</option>
            <option value="rejected"  <?= $app['status'] === 'rejected'  ? 'selected' : '' ?>>Rejected</option>
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label fw-bold">Interview Date & Time</label>
        <input type="datetime-local" name="interview_date" class="form-control"
               value="<?= $interview_val ?>">
        <small class="text-muted">Leave blank to clear the interview date.</small>
    </div>

    <div class="d-flex gap-2">
        <button type="submit" name="update" class="btn btn-success">Save Changes</button>
        <a href="/Adoption-Application/index.php" class="btn btn-outline-secondary">← Back to List</a>
    </div>
</form>

<?php require_once "../../shared/footer.php"; ?>
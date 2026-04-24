<?php
session_start();
// In applications/apply.php, list.php, edit.php, view.php, delete.php, my_applications.php, success.php
include "../../shared/db.php";      // ✅
include "../../shared/header.php";  // ✅
include "../../shared/footer.php";  // ✅

$id  = intval($_GET['id']);
$sql = "
    SELECT applications.*,
           adopters.full_name, adopters.email, adopters.phone,
           adopters.housing_type, adopters.has_yard, adopters.experience, adopters.pet_preference,
           pet_profiles.name AS pet_name, pet_profiles.species AS pet_species,
           pet_profiles.breed AS pet_breed, pet_profiles.gender AS pet_gender,
           pet_profiles.age_years, pet_profiles.age_months,
           pet_profiles.color AS pet_color, pet_profiles.weight_kg,
           pet_profiles.photo AS pet_photo, pet_profiles.description AS pet_description
    FROM applications
    JOIN adopters     ON applications.adopter_id = adopters.id
    JOIN pet_profiles ON applications.pet_id     = pet_profiles.pet_id
    WHERE applications.id = $id
";
$row = $conn->query($sql)->fetch_assoc();

if (!$row) {
    echo "<div class='alert alert-danger'>Application not found.</div>";
    include "../shared/footer.php";
    exit();
}

$badge_map = ['pending'=>'warning','approved'=>'success','completed'=>'primary','rejected'=>'danger'];
$badge = $badge_map[$row['status']] ?? 'secondary';
?>

<h2 class="mb-4">🔍 Application Details #<?= $row['id'] ?></h2>

<div class="row g-4">
    <div class="col-md-6">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-dark text-white">Adopter Information</div>
            <div class="card-body">
                <table class="table table-borderless mb-0">
                    <tr><th>Name</th>          <td><?= htmlspecialchars($row['full_name']) ?></td></tr>
                    <tr><th>Email</th>          <td><?= htmlspecialchars($row['email']) ?></td></tr>
                    <tr><th>Phone</th>          <td><?= htmlspecialchars($row['phone'] ?? '—') ?></td></tr>
                    <tr><th>Housing Type</th>   <td><?= htmlspecialchars($row['housing_type'] ?? '—') ?></td></tr>
                    <tr><th>Has Yard</th>       <td><?= $row['has_yard'] ? 'Yes' : 'No' ?></td></tr>
                    <tr><th>Experience</th>     <td><?= htmlspecialchars($row['experience'] ?? '—') ?></td></tr>
                    <tr><th>Pet Preference</th> <td><?= htmlspecialchars($row['pet_preference'] ?? '—') ?></td></tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-dark text-white">🐾 Pet Information</div>
            <div class="card-body">
                <?php if (!empty($row['pet_photo'])): ?>
                    <img src="/Adoption-Application/<?= htmlspecialchars($row['pet_photo']) ?>"
                         class="img-fluid rounded mb-3" style="max-height:160px; object-fit:cover;">
                <?php endif; ?>
                <table class="table table-borderless mb-0">
                    <tr><th>Name</th>    <td><?= htmlspecialchars($row['pet_name']) ?></td></tr>
                    <tr><th>Species</th> <td><?= htmlspecialchars($row['pet_species']) ?></td></tr>
                    <tr><th>Breed</th>   <td><?= htmlspecialchars($row['pet_breed'] ?? '—') ?></td></tr>
                    <tr><th>Gender</th>  <td><?= $row['pet_gender'] ?></td></tr>
                    <tr><th>Age</th>     <td><?= $row['age_years'] ?>y <?= $row['age_months'] ?>m</td></tr>
                    <tr><th>Color</th>   <td><?= htmlspecialchars($row['pet_color'] ?? '—') ?></td></tr>
                    <tr><th>Weight</th>  <td><?= $row['weight_kg'] ?> kg</td></tr>
                </table>
                <p class="text-muted small mt-2"><?= htmlspecialchars($row['pet_description'] ?? '') ?></p>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white">Application Status</div>
            <div class="card-body">
                <table class="table table-borderless mb-0">
                    <tr><th>Status</th>         <td><span class="badge bg-<?= $badge ?> fs-6"><?= $row['status'] ?></span></td></tr>
                    <tr><th>Interview Date</th>  <td><?= $row['interview_date'] ?? '<span class="text-muted">Not Scheduled</span>' ?></td></tr>
                    <tr><th>Applied On</th>      <td><?= $row['created_at'] ?></td></tr>
                    <tr><th>Last Updated</th>    <td><?= $row['updated_at'] ?></td></tr>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="mt-4 d-flex gap-2">
    <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-warning">Edit Application</a>
    <a href="list.php"                       class="btn btn-outline-secondary">← Back to List</a>
</div>

<?php include "../shared/footer.php"; ?>
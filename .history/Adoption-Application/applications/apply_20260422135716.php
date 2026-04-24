<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'adopter') {
    header("Location: ../login.php");
    exit();
}

include "../includes/header.php";

$user_id = $_SESSION['user_id'];


$applied_pet_ids = [];
$res = $conn->query("SELECT pet_id FROM applications WHERE adopter_id=$user_id AND status != 'rejected'");
while ($r = $res->fetch_assoc()) {
    $applied_pet_ids[] = $r['pet_id'];
}


if (isset($_POST['apply'])) {

    $pet_id = intval($_POST['pet_id']);


    $check = $conn->query("SELECT id FROM applications
                           WHERE adopter_id=$user_id AND pet_id=$pet_id
                           AND status != 'rejected'");

    if ($check->num_rows > 0) {
        header("Location: success.php?msg=already");
        exit();
    }

   
    $sql = "INSERT INTO applications (adopter_id, pet_id, status, interview_date, notes)
            VALUES ($user_id, $pet_id, 'pending', NULL, NULL)";

    if ($conn->query($sql)) {
       
        $conn->query("UPDATE pet_profiles SET adoption_status='Pending' WHERE pet_id=$pet_id");
        header("Location: success.php");
        exit();
    }
}


$pets = $conn->query("SELECT * FROM pet_profiles WHERE adoption_status='Available' ORDER BY name ASC");
?>

<h2 class="mb-4"> Available Pets for Adoption</h2>

<?php if ($pets->num_rows === 0): ?>
    <div class="alert alert-info">No pets are available for adoption right now. Please check back later.</div>
<?php else: ?>

<div class="row g-4">
    <?php while ($pet = $pets->fetch_assoc()): ?>
    <div class="col-md-4">
        <div class="card shadow-sm h-100">

            <!-- pet photo -->
            <?php if (!empty($pet['photo'])): ?>
                <img src="/pet-adoption/<?= htmlspecialchars($pet['photo']) ?>"
                     class="card-img-top"
                     style="height:200px; object-fit:cover;"
                     alt="<?= htmlspecialchars($pet['name']) ?>">
            <?php else: ?>
                <div class="bg-secondary text-white d-flex align-items-center justify-content-center"
                     style="height:200px;">
                    <span style="font-size:60px;"></span>
                </div>
            <?php endif; ?>

            <div class="card-body d-flex flex-column">
                <h5 class="card-title"><?= htmlspecialchars($pet['name']) ?></h5>

                <p class="text-muted mb-1">
                    <strong>Species:</strong> <?= htmlspecialchars($pet['species']) ?><br>
                    <strong>Breed:</strong>   <?= htmlspecialchars($pet['breed'] ?? 'Unknown') ?><br>
                    <strong>Gender:</strong>  <?= $pet['gender'] ?><br>
                    <strong>Age:</strong>     <?= $pet['age_years'] ?>y <?= $pet['age_months'] ?>m<br>
                    <strong>Color:</strong>   <?= htmlspecialchars($pet['color'] ?? 'N/A') ?><br>
                    <strong>Weight:</strong>  <?= $pet['weight_kg'] ?> kg
                </p>

                <p class="card-text text-muted small mt-1">
                    <?= htmlspecialchars($pet['description'] ?? '') ?>
                </p>

                <div class="mt-auto">
                    <?php if (in_array($pet['pet_id'], $applied_pet_ids)): ?>
                        <button class="btn btn-secondary w-100" disabled>Already Applied</button>
                    <?php else: ?>
                        <form method="POST">
                            <input type="hidden" name="pet_id" value="<?= $pet['pet_id'] ?>">
                            <button type="submit" name="apply" class="btn btn-success w-100">
                                Apply Now
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>
    <?php endwhile; ?>
</div>

<?php endif; ?>

<div class="mt-4">
    <a href="my_applications.php" class="btn btn-outline-dark"> My Applications</a>
</div>

<?php include "../includes/footer.php"; ?>

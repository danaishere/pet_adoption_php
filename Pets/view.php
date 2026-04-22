<?php
$pageStyles = 'style.css';
require_once '../shared/db.php';

// Get pet by ID
if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit();
}

$id = $_GET['id'];
$stmt = $conn->prepare('SELECT * FROM pet_profiles WHERE pet_id = ?');
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
$pet = $result->fetch_assoc();

if (!$pet) {
    header('Location: index.php');
    exit();
}

$pageTitle = $pet['name'];
require_once '../shared/header.php';
?>

<a href="index.php" class="btn btn-secondary-custom mb-3">Back to All Pets</a>

<div class="petDetailContainer">
    <div class="row">
        <div class="col-md-5">
            <?php if ($pet['photo'] && file_exists('../shared/' . $pet['photo'])): ?>
                <img src="../shared/<?php echo $pet['photo']; ?>" alt="<?php echo $pet['name']; ?>" class="petDetailImage">
            <?php else: ?>
                <div class="noPhoto" style="height: 350px;">No Photo Available</div>
            <?php endif; ?>
        </div>

        <div class="col-md-7">
            <div class="petDetailInfo">
                <?php
                    $statusClass = 'statusAvailable';
                    if ($pet['adoption_status'] == 'Pending') $statusClass = 'statusPending';
                    if ($pet['adoption_status'] == 'Adopted') $statusClass = 'statusAdopted';
                ?>
                <span class="statusBadge <?php echo $statusClass; ?>"><?php echo $pet['adoption_status']; ?></span>

                <h1><?php echo $pet['name']; ?></h1>

                <p><strong>Species:</strong> <?php echo $pet['species']; ?></p>
                <p><strong>Breed:</strong> <?php echo $pet['breed']; ?></p>
                <p><strong>Age:</strong> <?php echo $pet['age_years']; ?> year(s) and <?php echo $pet['age_months']; ?> month(s)</p>
                <p><strong>Gender:</strong> <?php echo $pet['gender']; ?></p>
                <p><strong>Color:</strong> <?php echo $pet['color']; ?></p>
                <p><strong>Weight:</strong> <?php echo $pet['weight_kg']; ?> kg</p>
                <p><strong>Date Added:</strong> <?php echo date('F j, Y', strtotime($pet['date_added'])); ?></p>
                <p><strong>Last Updated:</strong> <?php echo date('F j, Y', strtotime($pet['date_updated'])); ?></p>

                <div class="petDetailDescription">
                    <strong>Description:</strong><br>
                    <?php echo $pet['description']; ?>
                </div>

                <div class="mt-3">
                    <a href="edit.php?id=<?php echo $pet['pet_id']; ?>" class="btn btn-primary-custom">Edit Pet</a>
                    <a href="delete.php?id=<?php echo $pet['pet_id']; ?>" class="btn btn-danger-custom" onclick="return confirm('Are you sure you want to remove this pet?')">Delete Pet</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../shared/footer.php'; ?>

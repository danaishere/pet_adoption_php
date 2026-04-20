<?php
require_once "../shared/db.php";
include("../includes/header.php");

$sql = "SELECT * FROM shelters ORDER BY created_at DESC";
$result = $conn->query($sql);
$shelters = $result->fetch_all(MYSQLI_ASSOC);
?>

<div class="container-custom">

<!-- HERO SECTION -->
<div class="hero-wrapper">
    <img src="../assets/images/cat_shelter.jpg" alt="Shelter Banner">

    <div class="hero-overlay">
        <div>
            <h1>Find Your Furever Friend 🐾</h1>
            <p>Discover trusted animal shelters and give pets a second chance at life</p>
            <a href="create.php" class="btn btn-light btn-sm mt-2">
                + Add Shelter
            </a>
        </div>
    </div>
</div>

<!-- TITLE -->
<h2 class="mb-4 fw-bold">
    <i class="fa-solid fa-building"></i> Shelter Directory
</h2>

<!-- SUCCESS -->
<?php if(isset($_GET['success'])): ?>
    <div class="alert alert-success">
        Action completed successfully!
    </div>
<?php endif; ?>

<!-- EMPTY STATE -->
<?php if(empty($shelters)): ?>
    <div class="empty-state">
        <h4>No Shelters Found 🏠</h4>
        <p>Be the first to add a shelter and help animals find homes.</p>
        <a href="create.php" class="btn btn-primary">Add Shelter</a>
    </div>
<?php endif; ?>

<!-- SHELTER LIST -->
<div class="row">
<?php foreach($shelters as $shelter): ?>

    <?php
        $capacity = $shelter['capacity'];
        $current = $shelter['current_occupancy'];
        $percent = ($capacity > 0) ? ($current / $capacity) * 100 : 0;
    ?>

    <div class="col-md-4 mb-4">
        <div class="card shadow-sm">

            <img src="../assets/images/shelter.jpg"
                 class="card-img-top"
                 style="height:200px; object-fit:cover;"
                 alt="Shelter Image">

            <div class="card-body">

                <h5 class="card-title">
                    <?= htmlspecialchars($shelter['name']) ?>
                </h5>

                <p><strong>Type:</strong> <?= htmlspecialchars($shelter['type']) ?></p>
                <p><strong>City:</strong> <?= htmlspecialchars($shelter['city']) ?></p>

                <p><strong>Capacity:</strong> <?= $current ?> / <?= $capacity ?></p>

                <div class="progress mb-3">
                    <div class="progress-bar bg-info"
                         style="width: <?= $percent ?>%">
                        <?= round($percent) ?>%
                    </div>
                </div>

                <?php if($shelter['verification_status'] == "Verified"): ?>
                    <span class="badge bg-success">Verified</span>
                <?php else: ?>
                    <span class="badge bg-warning text-dark">Pending</span>
                <?php endif; ?>

                <div class="mt-3 d-flex justify-content-between">

                    <a href="details.php?id=<?= $shelter['shelter_id'] ?>" class="btn btn-primary btn-sm">
                        View
                    </a>

                    <a href="edit.php?id=<?= $shelter['shelter_id'] ?>" class="btn btn-warning btn-sm">
                        Edit
                    </a>

                    <a href="delete.php?id=<?= $shelter['shelter_id'] ?>"
                       class="btn btn-danger btn-sm"
                       onclick="return confirm('Are you sure?')">
                        Delete
                    </a>

                </div>

            </div>
        </div>
    </div>

<?php endforeach; ?>
</div>

</div>

<?php include("../includes/footer.php"); ?>
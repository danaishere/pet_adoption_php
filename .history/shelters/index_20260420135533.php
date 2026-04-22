<?php
require_once "../shared/db.php";
include("../includes/header.php");

$sql = "SELECT * FROM shelters ORDER BY created_at DESC";
$result = $conn->query($sql);
$shelters = $result->fetch_all(MYSQLI_ASSOC);
?>
<!-- HERO SECTION -->
<div class="hero-wrapper">
    <img src="../assets/images/cat_shelter.jpg" alt="Shelter Banner">

    <div class="hero-overlay">
        <div>
            <h1>Find Your Furever Friend 🐾</h1>
            <p>Discover trusted animal shelters and give pets a second chance at life</p>
         
        </div>
    </div>
</div>

<div class="container-custom">





<!-- SUCCESS -->
<?php if(isset($_GET['success'])): ?>
    <div class="alert alert-success">
        Action completed successfully!
    </div>
<?php endif; ?>

<!-- EMPTY STATE -->
<?php if(empty($shelters)): ?>
    <div class="empty-state">
        <h4>No Shelters Found</h4>
        <p>Be the first to add a shelter and help animals find homes.</p>
        <a href="add_shelter.php" class="btn btn-add  ">Add Shelter</a>
    </div>

<?php else: ?>
      <!-- TITLE -->
<h2 class="mb-4 fw-bold">
    <i class="fa-solid fa-building"></i> Shelter Directory
</h2>
    <div class="d-flex justify-content-end mb-3">
        <a href="add_shelter.php" class="btn btn-primary">
            <i class="fa-solid fa-plus"></i> Add Shelter
        </a>
    </div>

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

                    <a href="details_shelter.php?id=<?= $shelter['shelter_id'] ?>" class="btn  btn-sm">
                        View
                    </a>

                    <a href="edit_shelter.php?id=<?= $shelter['shelter_id'] ?>" class="btn btn-warning btn-sm">
                        Edit
                    </a>

                    <a href="delete_shelter.php?id=<?= $shelter['shelter_id'] ?>"
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

<?php endif; ?>

</div>

<?php include("../includes/footer.php"); ?>
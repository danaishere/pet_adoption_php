<?php
require_once "../shared/db.php";
include("../includes/header.php");
include("../")

$sql = "SELECT * FROM shelters ORDER BY created_at DESC";
$result = $conn->query($sql);
$shelters = $result->fetch_all(MYSQLI_ASSOC);
?>

<!-- HERO SECTION (ONLY ONCE) -->
<div class="position-relative mb-5">
    <img src="../assets/images/puppy_shelter.jpg" 
         class="img-fluid rounded shadow w-100" 
         style="max-height: 300px; object-fit: cover;" 
         alt="Shelter Banner">

    <div class="position-absolute top-50 start-50 translate-middle text-white text-center">
        <h1 class="fw-bold">Find Your Furever Friend 🐾</h1>
        <p class="lead">Browse trusted animal shelters near you</p>
        <a href="create.php" class="btn btn-light btn-sm mt-2">+ Add Shelter</a>
    </div>
</div>

<h2 class="mb-4">
    <i class="fa-solid fa-building"></i> Shelter Directory
</h2>

<!-- SUCCESS MESSAGE -->
<?php if(isset($_GET['success'])): ?>
    <div class="alert alert-success">
        Operation completed successfully!
    </div>
<?php endif; ?>

<!-- EMPTY STATE -->
<?php if(empty($shelters)): ?>
    <div class="alert alert-info">
        No shelters found. Please add a new shelter.
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

    <div class="col-md-4">
        <div class="card mb-4 shadow-sm border-0">

            <img src="../assets/images/shelter.jpg" 
                 class="card-img-top"
                 style="height:200px; object-fit:cover;"
                 alt="Shelter Image">

            <div class="card-body">

                <h5 class="card-title">
                    <?= htmlspecialchars($shelter['name']) ?>
                </h5>

                <p class="mb-1">
                    <strong>Type:</strong> <?= htmlspecialchars($shelter['type']) ?>
                </p>

                <p class="mb-1">
                    <strong>City:</strong> <?= htmlspecialchars($shelter['city']) ?>
                </p>

                <p class="mb-2">
                    <strong>Capacity:</strong> <?= $current ?> / <?= $capacity ?>
                </p>

                <!-- Progress Bar -->
                <div class="progress mb-3">
                    <div class="progress-bar bg-info"
                         role="progressbar"
                         style="width: <?= $percent ?>%">
                        <?= round($percent) ?>%
                    </div>
                </div>

                <!-- STATUS -->
                <?php if($shelter['verification_status'] == "Verified"): ?>
                    <span class="badge bg-success">Verified</span>
                <?php else: ?>
                    <span class="badge bg-warning text-dark">Pending</span>
                <?php endif; ?>

                <!-- ACTION BUTTONS -->
                <div class="mt-3 d-flex justify-content-between">

                    <a href="details.php?id=<?= $shelter['shelter_id'] ?>" 
                       class="btn btn-primary btn-sm">
                        View
                    </a>

                    <a href="edit.php?id=<?= $shelter['shelter_id'] ?>" 
                       class="btn btn-warning btn-sm">
                        Edit
                    </a>

                    <a href="delete.php?id=<?= $shelter['shelter_id'] ?>" 
                       class="btn btn-danger btn-sm"
                       onclick="return confirm('Are you sure you want to delete this shelter?')">
                        Delete
                    </a>

                </div>

            </div>
        </div>
    </div>

<?php endforeach; ?>
</div>

<?php include("../includes/footer.php"); ?>
<?php
require_once "../config/db.php";
include("../includes/header.php");

$sql = "SELECT * FROM shelters ORDER BY created_at DESC";
$stmt = $conn->query($sql);
$shelters = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2 class="mb-4">
    <i class="fa-solid fa-building"></i> Shelter Directory
</h2>

<?php if(isset($_GET['success'])): ?>
<div class="alert alert-success">
    Operation completed successfully!
</div>
<?php endif; ?>

<div class="row">
<?php foreach($shelters as $shelter): ?>
    <div class="col-md-4">
        <div class="card mb-4">
            <img src="../assets/images/cat.jpg" 
                 class="card-img-top" 
                 style="height:200px; object-fit:cover; border-radius:16px 16px 0 0;">

            <div class="card-body">
                <h5 class="card-title">
                    <?= htmlspecialchars($shelter['name']) ?>
                </h5>

                <p>
                    <strong>Type:</strong> <?= $shelter['type'] ?><br>
                    <strong>City:</strong> <?= $shelter['city'] ?><br>
                    <strong>Capacity:</strong>
                    <?= $shelter['current_occupancy'] ?> / <?= $shelter['capacity'] ?>
                </p>

                <!-- Status Badge -->
                <?php if($shelter['verification_status'] == "Verified"): ?>
                    <span class="badge bg-success">Verified</span>
                <?php else: ?>
                    <span class="badge bg-warning text-dark">Pending</span>
                <?php endif; ?>

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
                       onclick="return confirm('Are you sure?')">
                       Delete
                    </a>
                </div>

            </div>
        </div>
    </div>
<?php endforeach; ?>
</div>

<?php include("../includes/footer.php"); ?>
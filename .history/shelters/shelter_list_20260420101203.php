<?php
require_once "../config/db.php";
 include("../includes/header.php"); 

$sql = "SELECT * FROM shelters ORDER BY created_at DESC";
$stmt = $conn->query($sql);
$shelters = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<h2>All Shelters</h2>

<?php if(isset($_GET['success'])): ?>
    <p style="color:green;">Operation successful!</p>
<?php endif; ?>

<table border="1">
<tr>
    <th>Name</th>
    <th>Type</th>
    <th>City</th>
    <th>Capacity</th>
    <th>Actions</th>
</tr>

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
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title">
                    <?= htmlspecialchars($shelter['name']) ?>
                </h5>

                <p class="card-text">
                    <strong>Type:</strong> <?= $shelter['type'] ?><br>
                    <strong>City:</strong> <?= $shelter['city'] ?><br>
                    <strong>Capacity:</strong> 
                    <?= $shelter['current_occupancy'] ?> / <?= $shelter['capacity'] ?>
                </p>

                <div class="d-flex justify-content-between">
                    <a href="shelter_details.php?id=<?= $shelter['shelter_id'] ?>" 
                       class="btn btn-primary btn-sm">
                        <i class="fa-solid fa-eye"></i>
                    </a>

                    <a href="edit_shelter.php?id=<?= $shelter['shelter_id'] ?>" 
                       class="btn btn-warning btn-sm">
                        <i class="fa-solid fa-pen"></i>
                    </a>

                    <a href="delete_shelter.php?id=<?= $shelter['shelter_id'] ?>" 
                       class="btn btn-danger btn-sm"
                       onclick="return confirm('Are you sure?')">
                        <i class="fa-solid fa-trash"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>
</div>

<?php include("../includes/footer.php"); ?>
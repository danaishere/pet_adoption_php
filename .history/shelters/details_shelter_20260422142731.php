<?php
require_once "../shared/db.php";
include("../shared/header.php");

// Get ID safely
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<div class='alert alert-danger m-4'>Invalid Shelter ID</div>";
    include("../includes/footer.php");
    exit();
}

$id = $_GET['id'];

// Fetch shelter
$sql = "SELECT * FROM shelters WHERE shelter_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();
$shelter = $result->fetch_assoc();

if (!$shelter) {
    echo "<div class='alert alert-warning m-4'>Shelter not found</div>";
    include("../includes/footer.php");
    exit();
}

// Progress calculation
$capacity = $shelter['capacity'];
$current = $shelter['current_occupancy'];
$percent = ($capacity > 0) ? ($current / $capacity) * 100 : 0;
?>

<div class="container mt-4">

    <!-- HERO CARD -->
    <div class="card shadow-lg border-0 mb-4">
        <div class="card-body">

            <h2 class="mb-2 fw-bold">
                <?= htmlspecialchars($shelter['name']) ?>
            </h2>

            <span class="badge <?= $shelter['verification_status'] == 'Verified' ? 'bg-success' : 'bg-warning text-dark' ?>">
                <?= $shelter['verification_status'] ?>
            </span>

            <hr>

            <div class="row">

                <div class="col-md-6">
                    <p><strong>Type:</strong> <?= htmlspecialchars($shelter['type']) ?></p>
                    <p><strong>Email:</strong> <?= htmlspecialchars($shelter['email']) ?></p>
                    <p><strong>Phone:</strong> <?= htmlspecialchars($shelter['phone']) ?></p>
                    <p><strong>City:</strong> <?= htmlspecialchars($shelter['city']) ?></p>
                    <p><strong>Province:</strong> <?= htmlspecialchars($shelter['province']) ?></p>
                </div>

                <div class="col-md-6">
                    <p><strong>Address:</strong><br>
                        <?= htmlspecialchars($shelter['address']) ?>
                    </p>

                    <p><strong>Capacity:</strong> <?= $current ?> / <?= $capacity ?></p>

                    <div class="progress mb-3">
                        <div class="progress-bar bg-info"
                             style="width: <?= $percent ?>%">
                            <?= round($percent) ?>%
                        </div>
                    </div>
                </div>

            </div>

            <hr>

            <h5>Description</h5>
            <p class="text-muted">
                <?= nl2br(htmlspecialchars($shelter['description'])) ?>
            </p>

            <!-- ACTION BUTTONS -->
            <div class="mt-4 d-flex gap-2">

                <a href="edit_shelter.php?id=<?= $shelter['shelter_id'] ?>" class="btn btn-warning">
                     Edit
                </a>

                <a href="delete.php?id=<?= $shelter['shelter_id'] ?>" 
                   class="btn btn-danger"
                   onclick="return confirm('Are you sure?')">
                    Delete
                </a>

                <a href="index.php" class="btn btn-secondary">
                 Back
                </a>

            </div>

        </div>
    </div>

</div>

<?php include("../shared/footer.php"); ?>
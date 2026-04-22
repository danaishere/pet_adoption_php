<?php
$pageTitle = 'Furever Home';
require_once 'shared/header.php';


require_once 'shared/db.php';
// Get counts for the dashboard
$petResult = $conn->query("SELECT COUNT(*) as total FROM pet_profiles");
$totalPets = $petResult->fetch_assoc()['total'];

$availableResult = $conn->query("SELECT COUNT(*) as total FROM pet_profiles WHERE adoption_status = 'Available'");
$availablePets = $availableResult->fetch_assoc()['total'];

$pendingResult = $conn->query("SELECT COUNT(*) as total FROM pet_profiles WHERE adoption_status = 'Pending'");
$pendingPets = $pendingResult->fetch_assoc()['total'];

$adoptedResult = $conn->query("SELECT COUNT(*) as total FROM pet_profiles WHERE adoption_status = 'Adopted'");
$adoptedPets = $adoptedResult->fetch_assoc()['total'];
?>

<div class="hero-section text-center">
    <h1>Welcome to Pet Adoption</h1>
    <p class="lead">Find your perfect companion today</p>
    <a href="Pets/index.php" class="btn btn-primary-custom btn-lg mt-3">Browse Pets</a>
</div>

<div class="row mt-5">
    <div class="col-md-3">
        <div class="stat-card text-center">
            <h2><?php echo $totalPets; ?></h2>
            <p>Total Pets</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card text-center">
            <h2><?php echo $availablePets; ?></h2>
            <p>Available</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card text-center">
            <h2><?php echo $pendingPets; ?></h2>
            <p>Pending</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card text-center">
            <h2><?php echo $adoptedPets; ?></h2>
            <p>Adopted</p>
        </div>
    </div>
</div>

<?php require_once 'shared/footer.php'; ?>

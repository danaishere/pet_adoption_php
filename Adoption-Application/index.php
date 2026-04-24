<?php
$pageTitle = 'Applications';
require_once '../shared/header.php';   // ✅ one level up
require_once '../shared/db.php';       // ✅ one level up
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Adoption Applications</h2>
    <a href="applications/list.php" class="btn btn-dark">View All Applications</a>
</div>

<?php require_once '../shared/footer.php'; ?>  // ✅ one level up
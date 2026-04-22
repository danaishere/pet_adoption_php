<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

include "../includes/header.php";
?>

<div class="text-center mt-5">

    <?php if (isset($_GET['msg']) && $_GET['msg'] === 'already'): ?>

        <div class="display-1"></div>
        <h2 class="text-warning mt-3">Already Applied</h2>
        <p class="text-muted">You have already submitted an active application for this pet.</p>

    <?php else: ?>

        <div class="display-1"></div>
        <h2 class="text-success mt-3">Application Submitted!</h2>
        <p class="text-muted">
            Your adoption application has been submitted successfully.<br>
            We will review it and get back to you soon.
        </p>

    <?php endif; ?>

    <div class="mt-4 d-flex justify-content-center gap-3">
        <a href="apply.php"           class="btn btn-success">Apply for Another Pet</a>
        <a href="my_applications.php" class="btn btn-outline-dark">View My Applications</a>
    </div>

</div>

<?php include "../includes/footer.php"; ?>

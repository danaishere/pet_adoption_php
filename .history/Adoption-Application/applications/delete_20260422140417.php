<?php
session_start();
include "../sha/db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$id      = intval($_GET['id']);
$role    = $_SESSION['role'];
$user_id = $_SESSION['user_id'];


$app = $conn->query("SELECT * FROM applications WHERE id=$id")->fetch_assoc();

if (!$app) {
    header("Location: list.php");
    exit();
}

if ($role === 'adopter' && $app['adopter_id'] != $user_id) {
    die("Access denied. This is not your application.");
}


if ($role === 'adopter' && !in_array($app['status'], ['pending', 'rejected'])) {
    die("You cannot withdraw an approved or completed application. Please contact us.");
}

$pet_id = $app['pet_id'];


$conn->query("DELETE FROM applications WHERE id=$id");


$other = $conn->query("SELECT id FROM applications WHERE pet_id=$pet_id AND status NOT IN ('rejected')");
if ($other->num_rows === 0) {
    $conn->query("UPDATE pet_profiles SET adoption_status='Available' WHERE pet_id=$pet_id");
}


if ($role === 'admin') {
    header("Location: list.php?deleted=1");
} else {
    header("Location: my_applications.php?deleted=1");
}
exit();

<?php
require_once "../config/db.php";

$id = $_GET['id'];
$check = $conn->prepare("SELECT COUNT(*) FROM pets WHERE shelter_id = :id");
$check->execute([':id' => $id]);
$count = $check->fetchColumn();

if ($count > 0) {
    die("Cannot delete shelter with existing pets.");
}
$stmt = $conn->prepare("DELETE FROM shelters WHERE shelter_id = :id");
$stmt->execute([':id' => $id]);

header("Location: index.php?success=deleted");
exit();
?>
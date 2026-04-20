<?php
require_once "../config/db.php";

$id = $_GET['id'];

$sql = "SELECT * FROM shelters WHERE shelter_id = :id";
$stmt = $conn->prepare($sql);
$stmt->execute([':id' => $id]);

$shelter = $stmt->fetch(PDO::FETCH_ASSOC);
?>


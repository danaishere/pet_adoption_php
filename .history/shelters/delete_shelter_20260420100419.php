<?php
require_once "../config/db.php";

$id = $_GET['id'];

$stmt = $conn->prepare("DELETE FROM shelters WHERE shelter_id = :id");
$stmt->execute([':id' => $id]);

header("Location: index.php?success=deleted");
exit();
?>
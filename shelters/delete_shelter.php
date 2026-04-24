<?php
require_once "../shared/db.php";


$id = $_GET['id'];



$stmt = $conn->prepare("DELETE FROM shelters WHERE shelter_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();


header("Location: index.php?success=deleted");
exit();
?>
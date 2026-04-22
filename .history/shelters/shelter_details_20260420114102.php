<?php
require_once "../shared/db.php";

$id = $_GET['id'];

$sql = "SELECT * FROM shelters WHERE shelter_id = :id";
$stmt = $conn->prepare($sql);
$stmt->execute([':id' => $id]);

$shelter = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!-- 
SELECT s.*, COUNT(p.pet_id) AS total_pets
FROM shelters s
LEFT JOIN pets p ON s.shelter_id = p.shelter_id
WHERE s.shelter_id = :id -->
<!-- GROUP BY s.shelter_id; -->
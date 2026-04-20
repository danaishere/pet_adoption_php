<?php
require_once "../config/db.php";

$sql = "SELECT * FROM shelters ORDER BY created_at DESC";
$stmt = $conn->query($sql);
$shelters = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
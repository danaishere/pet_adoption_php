<?php
require_once "../shared/db.php";


$id = $_GET['id'];

// /* ---------------- CHECK IF PETS EXIST ---------------- */
// $check = $conn->prepare("SELECT COUNT(*) AS total FROM pets WHERE shelter_id = ?");
// $check->bind_param("i", $id);
// $check->execute();

// $result = $check->get_result();
// $row = $result->fetch_assoc();

// if ($row['total'] > 0) {
//     die("Cannot delete shelter because it has existing pets.");
// }

/* ---------------- DELETE SHELTER ---------------- */
$stmt = $conn->prepare("DELETE FROM shelters WHERE shelter_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

/* ---------------- REDIRECT ---------------- */
header("Location: index.php?success=deleted");
exit();
?>
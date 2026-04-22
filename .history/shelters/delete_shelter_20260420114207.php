<?php
require_once "../shared/db.php";

$id = $_GET['id'];
$check = $conn->prepare("SELECT COUNT(*) FROM pets WHERE shelter_id = :id");
$check->execute([':id' => $id]);
$count = $check->fetchColumn();

if ($count > 0) {
    die("Cannot delete shelter with existing pets.");
}
else {
$stmt = $conn->prepare("DELETE FROM shelters WHERE shelter_id = :id");
$stmt->execute([':id' => $id]);

header("Location: index.php?success=deleted");
exit();
}
?>
<script>
function validateForm() {
    let capacity = document.forms["shelterForm"]["capacity"].value;
    if (capacity < 0) {
        alert("Capacity cannot be negative.");
        return false;
    }
}
</script>
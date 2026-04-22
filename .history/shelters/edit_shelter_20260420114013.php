<?php
require_once "../shared/db.php";
$id = $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM shelters WHERE shelter_id = :id");
$stmt->execute([':id' => $id]);
$shelter = $stmt->fetch(PDO::FETCH_ASSOC);
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $sql = "UPDATE shelters SET
            name = :name,
            email = :email,
            phone = :phone,
            capacity = :capacity,
            verification_status = :verification_status
            WHERE shelter_id = :id";

    $stmt = $conn->prepare($sql);

    $stmt->execute([
        ':name' => $_POST['name'],
        ':email' => $_POST['email'],
        ':phone' => $_POST['phone'],
        ':capacity' => $_POST['capacity'],
        ':verification_status' => $_POST['verification_status'],
        ':id' => $id
    ]);

    header("Location: index.php?success=updated");
    exit();
}
<input type="text" name="name" value="<?= $shelter['name'] ?>">

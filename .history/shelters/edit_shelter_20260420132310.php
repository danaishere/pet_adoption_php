<?php
require_once "../shared/db.php";

$id = $_GET['id'];

/* ------------------ GET SHELTER ------------------ */
$stmt = $conn->prepare("SELECT * FROM shelters WHERE shelter_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();
$shelter = $result->fetch_assoc();

if (!$shelter) {
    die("Shelter not found");
}

/* ------------------ UPDATE ------------------ */
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $sql = "UPDATE shelters SET
            name = ?,
            email = ?,
            phone = ?,
            capacity = ?,
            verification_status = ?
            WHERE shelter_id = ?";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "sssisi",
        $_POST['name'],
        $_POST['email'],
        $_POST['phone'],
        $_POST['capacity'],
        $_POST['verification_status'],
        $id
    );

    $stmt->execute();

    header("Location: index.php?success=updated");
    exit();
}
?>

<!-- FORM -->
<form method="POST">

    <input type="text" name="name" value="<?= htmlspecialchars($shelter['name']) ?>">

    <input type="email" name="email" value="<?= htmlspecialchars($shelter['email']) ?>">

    <input type="text" name="phone" value="<?= htmlspecialchars($shelter['phone']) ?>">

    <input type="number" name="capacity" value="<?= $shelter['capacity'] ?>">

    <select name="verification_status">
        <option value="Pending" <?= $shelter['verification_status'] == "Pending" ? "selected" : "" ?>>
            Pending
        </option>
        <option value="Verified" <?= $shelter['verification_status'] == "Verified" ? "selected" : "" ?>>
            Verified
        </option>
    </select>

    <button type="submit">Update</button>

</form>
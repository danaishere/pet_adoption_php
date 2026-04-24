<?php
require_once "../shared/db.php";
include("../includes/header.php");

$id = $_GET['id'];

/* ---------------- FETCH DATA ---------------- */
$stmt = $conn->prepare("SELECT * FROM shelters WHERE shelter_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();
$shelter = $result->fetch_assoc();

if (!$shelter) {
    die("<div class='alert alert-danger m-4'>Shelter not found</div>");
}

/* ---------------- UPDATE ---------------- */
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $sql = "UPDATE shelters SET
        name = ?, type = ?, email = ?, phone = ?,
        address = ?, city = ?, province = ?,
        capacity = ?, description = ?
        WHERE shelter_id = ?";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "sssssssisi",
        $_POST['name'],
        $_POST['type'],
        $_POST['email'],
        $_POST['phone'],
        $_POST['address'],
        $_POST['city'],
        $_POST['province'],
        $_POST['capacity'],
        $_POST['description'],
        $id
    );

    $stmt->execute();

    header("Location: index.php?success=updated");
    exit();
}
?>

<!-- TITLE -->
<h2 class="mb-4 text-center fw-bold mt-4">
   Edit Shelter
</h2>

<!-- FORM CARD -->
<div class="card shadow p-4">

<form method="POST" name="shelterForm" onsubmit="return validateForm();">

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Shelter Name</label>
        <input type="text" name="name" class="form-control"
               value="<?= htmlspecialchars($shelter['name']) ?>" required>
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">Type</label>
        <select name="type" class="form-select" required>
            <option value="">Select</option>
            <option <?= $shelter['type'] == "Shelter" ? "selected" : "" ?>>Shelter</option>
            <option <?= $shelter['type'] == "Rescue" ? "selected" : "" ?>>Rescue</option>
            <option <?= $shelter['type'] == "Foster Home" ? "selected" : "" ?>>Foster Home</option>
        </select>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control"
               value="<?= htmlspecialchars($shelter['email']) ?>" required>
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">Phone</label>
        <input type="text" name="phone" class="form-control"
               value="<?= htmlspecialchars($shelter['phone']) ?>" required>
    </div>
</div>

<div class="mb-3">
    <label class="form-label">Address</label>
    <input type="text" name="address" class="form-control"
           value="<?= htmlspecialchars($shelter['address']) ?>" required>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">City</label>
        <input type="text" name="city" class="form-control"
               value="<?= htmlspecialchars($shelter['city']) ?>" required>
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">Province</label>
        <input type="text" name="province" class="form-control"
               value="<?= htmlspecialchars($shelter['province']) ?>" required>
    </div>
</div>

<div class="mb-3">
    <label class="form-label">Capacity</label>
    <input type="number" name="capacity" class="form-control"
           value="<?= $shelter['capacity'] ?>" required>
</div>

<div class="mb-3">
    <label class="form-label">Description</label>
    <textarea name="description" class="form-control" rows="4"><?= htmlspecialchars($shelter['description']) ?></textarea>
</div>

<button type="submit" class="btn btn-success w-100">
    <i class="fa-solid fa-pen-to-square"></i> Update Shelter
</button>

</form>
</div>

<script>
function validateForm() {
    let capacity = document.forms["shelterForm"]["capacity"].value;
    if (capacity < 0) {
        alert("Capacity cannot be negative.");
        return false;
    }
}
</script>

<?php include("includes/footer.php"); ?>
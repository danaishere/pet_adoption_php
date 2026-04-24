<?php
require_once "../shared/db.php";
include("includes/header.php");
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim($_POST['name']);
    $type = $_POST['type'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $province = $_POST['province'];
    $capacity = $_POST['capacity'];
    $description = $_POST['description'];

if (!empty($name) && !empty($type) && !empty($email) && is_numeric($capacity) && $capacity >= 0){
        $sql = "INSERT INTO shelters 
        (name, type, email, phone, address, city, province, capacity, description)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);

        $stmt->bind_param(
            "sssssssiss",
            $name,
            $type,
            $email,
            $phone,
            $address,
            $city,
            $province,
            $capacity,
            $description
        );

      if ($stmt->execute()) {
    header("Location: index.php?success=added");
    exit();
} else {
    echo "<p style='color:red;'>Error: " . $stmt->error . "</p>";
}
    } else {
        echo "<p style='color:red;'>Please fill in all required fields and ensure capacity is a non-negative number.</p>";
    }
}
?>

<h2 class="mb-4 text-center fw-bold mt-4">
   Add New Shelter
</h2>

<div class="card shadow p-4">
<form method="POST" name="shelterForm" onsubmit="return validateForm();">

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Shelter Name</label>
        <input type="text" name="name" class="form-control" required>
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">Type</label>
        <select name="type" class="form-select" required>
            <option value="">Select</option>
            <option>Shelter</option>
            <option>Rescue</option>
            <option>Foster Home</option>
        </select>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" required>
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">Phone</label>
        <input type="text" name="phone" class="form-control" required>
    </div>
</div>

<div class="mb-3">
    <label class="form-label">Address</label>
    <input type="text" name="address" class="form-control" required>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">City</label>
        <input type="text" name="city" class="form-control" required>
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">Province</label>
        <input type="text" name="province" class="form-control" required>
    </div>
</div>

<div class="mb-3">
    <label class="form-label">Capacity</label>
    <input type="number" name="capacity" class="form-control" required>
</div>

<div class="mb-3">
    <label class="form-label">Description</label>
    <textarea name="description" class="form-control" rows="4"></textarea>
</div>

<button type="submit" class="btn btn-success">
    <i class="fa-solid fa-save"></i> Save Shelter
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
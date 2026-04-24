<?php
$pageTitle = 'Add New Pet';
$pageStyles = 'style.css';

require_once '../shared/header.php';
require_once '../shared/db.php';

$errors = [];
$success = '';

$shelters = mysqli_query($conn, "SELECT * FROM shelters");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $name = $_POST['name'];
    $species = $_POST['species'];
    $breed = $_POST['breed'];
    $ageYears = $_POST['age_years'];
    $ageMonths = $_POST['age_months'];
    $gender = $_POST['gender'];
    $color = $_POST['color'];
    $weightKg = $_POST['weight_kg'];
    $adoptionStatus = $_POST['adoption_status'];
    $description = $_POST['description'];
    $shelterId = $_POST['shelter_id'];

    // Validation
    if (empty($name)) $errors[] = 'Pet name is required';
    if (empty($species)) $errors[] = 'Species is required';
    if (empty($gender)) $errors[] = 'Gender is required';
    if (empty($shelterId)) $errors[] = 'Shelter is required';

    // Photo upload
    $photoPath = null;

    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {

        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

        if (!in_array($_FILES['photo']['type'], $allowedTypes)) {
            $errors[] = 'Only JPG, PNG, GIF, WEBP allowed';
        } elseif ($_FILES['photo']['size'] > 5 * 1024 * 1024) {
            $errors[] = 'Image must be less than 5MB';
        } else {

            $uploadDir = '../shared/uploads/pets/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $extension = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
            $fileName = strtolower(str_replace(' ', '_', $name)) . '_' . time() . '.' . $extension;
            $targetPath = $uploadDir . $fileName;

            if (move_uploaded_file($_FILES['photo']['tmp_name'], $targetPath)) {
                $photoPath = 'uploads/pets/' . $fileName;
            } else {
                $errors[] = 'Failed to upload photo';
            }
        }
    }

    // Insert
    if (empty($errors)) {

        $stmt = $conn->prepare("
            INSERT INTO pet_profiles 
            (name, species, breed, age_years, age_months, gender, color, weight_kg, photo, adoption_status, description, shelter_id)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->bind_param(
            'sssiissdsssi',
            $name,
            $species,
            $breed,
            $ageYears,
            $ageMonths,
            $gender,
            $color,
            $weightKg,
            $photoPath,
            $adoptionStatus,
            $description,
            $shelterId
        );

        if ($stmt->execute()) {
            header('Location: index.php?added=1');
            exit();
        } else {
            $errors[] = 'Something went wrong. Try again.';
        }
    }
}
?>

<h1>Add New Pet</h1>

<a href="index.php">Back to All Pets</a>
<br><br>

<?php if (!empty($errors)): ?>
<ul class="errors">
    <?php foreach ($errors as $error): ?>
        <li><?php echo $error; ?></li>
    <?php endforeach; ?>
</ul>
<?php endif; ?>

<form method="post" enctype="multipart/form-data">

<label>Pet Name:</label>
<input type="text" name="name">

<label>Species:</label>
<select name="species">
    <option value="">-- Select --</option>
    <option>Dog</option>
    <option>Cat</option>
    <option>Rabbit</option>
    <option>Bird</option>
    <option>Other</option>
</select>

<label>Breed:</label>
<input type="text" name="breed">

<label>Select Shelter:</label>
<select name="shelter_id" required>
    <option value="">-- Select Shelter --</option>
    <?php while ($row = mysqli_fetch_assoc($shelters)): ?>
        <option value="<?php echo $row['shelter_id']; ?>">
            <?php echo $row['name']; ?>
        </option>
    <?php endwhile; ?>
</select>

<label>Age (Years):</label>
<input type="number" name="age_years" value="0">

<label>Age (Months):</label>
<input type="number" name="age_months" value="0">

<label>Gender:</label>
<select name="gender">
    <option value="">-- Select --</option>
    <option>Male</option>
    <option>Female</option>
</select>

<label>Color:</label>
<input type="text" name="color">

<label>Weight (kg):</label>
<input type="number" step="0.01" name="weight_kg">

<label>Photo:</label>
<input type="file" name="photo">

<label>Adoption Status:</label>
<select name="adoption_status">
    <option>Available</option>
    <option>Pending</option>
    <option>Adopted</option>
</select>

<label>Description:</label>
<textarea name="description"></textarea>

<br><br>
<input type="submit" value="Add Pet">

</form>

<?php require_once '../shared/footer.php'; ?>
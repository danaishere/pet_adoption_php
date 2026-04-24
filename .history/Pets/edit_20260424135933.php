<?php
$pageStyles = 'style.css';
require_once '../shared/db.php';

$errors = [];
$success = '';

// Fetch all shelters
$sheltersQuery = $conn->query("SELECT * FROM shelters");

// Get pet by ID
if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    $stmt = $conn->prepare('SELECT * FROM pet_profiles WHERE pet_id = ?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $pet = $result->fetch_assoc();

    if (!$pet) {
        header('Location: index.php');
        exit();
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $id = (int)$_POST['pet_id'];
    $name = trim($_POST['name']);
    $species = $_POST['species'];
    $breed = $_POST['breed'];
    $ageYears = (int)$_POST['age_years'];
    $ageMonths = (int)$_POST['age_months'];
    $gender = $_POST['gender'];
    $color = $_POST['color'];
    $weightKg = (float)$_POST['weight_kg'];
    $adoptionStatus = $_POST['adoption_status'];
    $description = $_POST['description'];
    $shelterId = (int)$_POST['shelter_id'];

    // Validation
    if (empty($name)) $errors[] = 'Pet name is required';
    if (empty($species)) $errors[] = 'Species is required';
    if (empty($gender)) $errors[] = 'Gender is required';
    if (empty($shelterId)) $errors[] = 'Shelter selection is required';

    // Photo handling
    $photoPath = $_POST['existing_photo'];

    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {

        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

        if (!in_array($_FILES['photo']['type'], $allowedTypes)) {
            $errors[] = 'Only JPG, PNG, GIF, WEBP allowed';
        } elseif ($_FILES['photo']['size'] > 5 * 1024 * 1024) {
            $errors[] = 'Image must be under 5MB';
        } else {

            $uploadDir = '../shared/uploads/pets/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $extension = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
            $fileName = strtolower(str_replace(' ', '_', $name)) . '_' . time() . '.' . $extension;
            $targetPath = $uploadDir . $fileName;

            if (move_uploaded_file($_FILES['photo']['tmp_name'], $targetPath)) {

                if ($photoPath && file_exists('../shared/' . $photoPath)) {
                    unlink('../shared/' . $photoPath);
                }

                $photoPath = 'uploads/pets/' . $fileName;
            } else {
                $errors[] = 'Photo upload failed';
            }
        }
    }

    // Update if no errors
    if (empty($errors)) {

        $stmt = $conn->prepare("
            UPDATE pet_profiles 
            SET name=?, species=?, breed=?, age_years=?, age_months=?, 
                gender=?, color=?, weight_kg=?, photo=?, 
                adoption_status=?, description=?, shelter_id=? 
            WHERE pet_id=?
        ");

        $stmt->bind_param(
            'sssiissdsssii',
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
            $shelterId,
            $id
        );

        if ($stmt->execute()) {
            $success = 'Pet updated successfully!';

            // Refresh data
            $stmt = $conn->prepare('SELECT * FROM pet_profiles WHERE pet_id = ?');
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $pet = $result->fetch_assoc();
        } else {
            $errors[] = 'Update failed. Try again.';
        }
    }
}

$pageTitle = 'Edit ' . $pet['name'];
require_once '../shared/header.php';
?>

<h1>Edit Pet: <?php echo htmlspecialchars($pet['name']); ?></h1>

<a href="index.php">Back to All Pets</a> |
<a href="view.php?id=<?php echo $pet['pet_id']; ?>">View Profile</a>
<br><br>

<?php if (!empty($errors)): ?>
<ul class="errors">
    <?php foreach ($errors as $error): ?>
        <li><?php echo $error; ?></li>
    <?php endforeach; ?>
</ul>
<?php endif; ?>

<?php if ($success): ?>
<p class="success"><?php echo $success; ?></p>
<?php endif; ?>

<form method="post" enctype="multipart/form-data">

<input type="hidden" name="pet_id" value="<?php echo $pet['pet_id']; ?>">
<input type="hidden" name="existing_photo" value="<?php echo $pet['photo']; ?>">

<label>Pet Name:</label>
<input type="text" name="name" value="<?php echo htmlspecialchars($pet['name']); ?>">

<label>Species:</label>
<select name="species">
    <option value="">-- Select --</option>
    <?php
    $speciesOptions = ['Dog','Cat','Rabbit','Bird','Other'];
    foreach ($speciesOptions as $option) {
        $selected = ($pet['species'] == $option) ? 'selected' : '';
        echo "<option value='$option' $selected>$option</option>";
    }
    ?>
</select>

<label>Select Shelter:</label>
<select name="shelter_id" required>
    <option value="">-- Select Shelter --</option>
    <?php while ($shelter = $sheltersQuery->fetch_assoc()): ?>
        <option value="<?php echo $shelter['shelter_id']; ?>"
            <?php echo ($pet['shelter_id'] == $shelter['shelter_id']) ? 'selected' : ''; ?>>
            <?php echo htmlspecialchars($shelter['name']); ?>
        </option>
    <?php endwhile; ?>
</select>

<label>Breed:</label>
<input type="text" name="breed" value="<?php echo htmlspecialchars($pet['breed']); ?>">

<label>Age (Years):</label>
<input type="number" name="age_years" min="0" value="<?php echo $pet['age_years']; ?>">

<label>Age (Months):</label>
<input type="number" name="age_months" min="0" max="11" value="<?php echo $pet['age_months']; ?>">

<label>Gender:</label>
<select name="gender">
    <option value="">-- Select --</option>
    <option value="Male" <?php echo $pet['gender']=='Male'?'selected':''; ?>>Male</option>
    <option value="Female" <?php echo $pet['gender']=='Female'?'selected':''; ?>>Female</option>
</select>

<label>Color:</label>
<input type="text" name="color" value="<?php echo htmlspecialchars($pet['color']); ?>">

<label>Weight (kg):</label>
<input type="number" name="weight_kg" step="0.01" value="<?php echo $pet['weight_kg']; ?>">

<?php if ($pet['photo'] && file_exists('../shared/' . $pet['photo'])): ?>
<label>Current Photo:</label>
<img src="../shared/<?php echo $pet['photo']; ?>" width="150">
<?php endif; ?>

<label>Upload New Photo:</label>
<input type="file" name="photo">

<label>Adoption Status:</label>
<select name="adoption_status">
    <option value="Available" <?php echo $pet['adoption_status']=='Available'?'selected':''; ?>>Available</option>
    <option value="Pending" <?php echo $pet['adoption_status']=='Pending'?'selected':''; ?>>Pending</option>
    <option value="Adopted" <?php echo $pet['adoption_status']=='Adopted'?'selected':''; ?>>Adopted</option>
</select>

<label>Description:</label>
<textarea name="description"><?php echo htmlspecialchars($pet['description']); ?></textarea>

<br><br>
<input type="submit" value="Update Pet">

</form>

<?php require_once '../shared/footer.php'; ?>
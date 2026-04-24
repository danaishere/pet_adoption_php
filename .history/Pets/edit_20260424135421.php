<?php
$pageStyles = 'style.css';
require_once '../shared/db.php';

$errors = [];
$success = '';

$shelters = mysqli_query($conn, "SELECT * FROM shelters");

// Get the pet by ID from the URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

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

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $id = $_POST['pet_id'];
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

    // Validate the form fields
    if (empty($name)) $errors[] = 'Pet name is required';
    if (empty($species)) $errors[] = 'Species is required';
    if (empty($gender)) $errors[] = 'Gender is required';

    // Handle photo upload
    $photoPath = $_POST['existing_photo'];

    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $fileType = $_FILES['photo']['type'];
        $fileSize = $_FILES['photo']['size'];

        if (!in_array($fileType, $allowedTypes)) {
            $errors[] = 'Only JPG, PNG, GIF, and WEBP images are allowed';
        } elseif ($fileSize > 5 * 1024 * 1024) {
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
                // Delete old photo if it exists and is different
                if ($photoPath && file_exists('../shared/' . $photoPath)) {
                    unlink('../shared/' . $photoPath);
                }
                $photoPath = 'uploads/pets/' . $fileName;
            } else {
                $errors[] = 'Failed to upload photo';
            }
        }
    }

    if (empty($errors)) {
        $stmt = $conn->prepare('UPDATE pet_profiles SET name=?, species=?, breed=?, age_years=?, age_months=?, gender=?, color=?, weight_kg=?, photo=?, adoption_status=?, description=? WHERE pet_id=?');
        $stmt->bind_param('sssiissdsssi', $name, $species, $breed, $ageYears, $ageMonths, $gender, $color, $weightKg, $photoPath, $adoptionStatus, $description, $id);

        if ($stmt->execute()) {
            $success = 'Pet updated successfully!';

            // Refresh pet data
            $stmt = $conn->prepare('SELECT * FROM pet_profiles WHERE pet_id = ?');
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $pet = $result->fetch_assoc();
        } else {
            $errors[] = 'Something went wrong. Please try again.';
        }
    }
}

$pageTitle = 'Edit ' . $pet['name'];
require_once '../shared/header.php';
?>

<h1>Edit Pet: <?php echo $pet['name']; ?></h1>
<?php
$shelters = mysqli_query($conn, "SELECT * FROM shelters");
?>
<a href="index.php">Back to All Pets</a>
&nbsp;|&nbsp;
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

<form action="#" method="post" enctype="multipart/form-data">

    <input type="hidden" name="pet_id" value="<?php echo $pet['pet_id']; ?>">
    <input type="hidden" name="existing_photo" value="<?php echo $pet['photo']; ?>">

    <label>Pet Name:</label>
    <input type="text" name="name" value="<?php echo $pet['name']; ?>">

    <label>Species:</label>
    <select name="species">
        <option value="">-- Select --</option>
        <option value="Dog" <?php echo $pet['species'] == 'Dog' ? 'selected' : ''; ?>>Dog</option>
        <option value="Cat" <?php echo $pet['species'] == 'Cat' ? 'selected' : ''; ?>>Cat</option>
        <option value="Rabbit" <?php echo $pet['species'] == 'Rabbit' ? 'selected' : ''; ?>>Rabbit</option>
        <option value="Bird" <?php echo $pet['species'] == 'Bird' ? 'selected' : ''; ?>>Bird</option>
        <option value="Other" <?php echo $pet['species'] == 'Other' ? 'selected' : ''; ?>>Other</option>
    </select>
    <label>Select Shelter</label>
<option value="<?php echo $row['shelter_id']; ?>"
    <?php echo ($pet['shelter_id'] == $row['shelter_id']) ? 'selected' : ''; ?>>
    <?php echo $row['name']; ?>
</option>
    <label>Breed:</label>
    <input type="text" name="breed" value="<?php echo $pet['breed']; ?>">

    <div class="row">
        <div class="col-md-6">
            <label>Age (Years):</label>
            <input type="number" name="age_years" min="0" value="<?php echo $pet['age_years']; ?>">
        </div>
        <div class="col-md-6">
            <label>Age (Months):</label>
            <input type="number" name="age_months" min="0" max="11" value="<?php echo $pet['age_months']; ?>">
        </div>
    </div>

    <label>Gender:</label>
    <select name="gender">
        <option value="">-- Select --</option>
        <option value="Male" <?php echo $pet['gender'] == 'Male' ? 'selected' : ''; ?>>Male</option>
        <option value="Female" <?php echo $pet['gender'] == 'Female' ? 'selected' : ''; ?>>Female</option>
    </select>

    <label>Color:</label>
    <input type="text" name="color" value="<?php echo $pet['color']; ?>">

    <label>Weight (kg):</label>
    <input type="number" name="weight_kg" step="0.01" min="0" value="<?php echo $pet['weight_kg']; ?>">

    <!-- Current Photo -->
    <?php if ($pet['photo'] && file_exists('../shared/' . $pet['photo'])): ?>
        <label>Current Photo:</label>
        <img src="../shared/<?php echo $pet['photo']; ?>" alt="<?php echo $pet['name']; ?>" class="photoPreview">
    <?php endif; ?>

    <label>Upload New Photo (leave blank to keep current):</label>
    <input type="file" name="photo" accept="image/*">

    <label>Adoption Status:</label>
    <select name="adoption_status">
        <option value="Available" <?php echo $pet['adoption_status'] == 'Available' ? 'selected' : ''; ?>>Available</option>
        <option value="Pending" <?php echo $pet['adoption_status'] == 'Pending' ? 'selected' : ''; ?>>Pending</option>
        <option value="Adopted" <?php echo $pet['adoption_status'] == 'Adopted' ? 'selected' : ''; ?>>Adopted</option>
    </select>

    <label>Description:</label>
    <textarea name="description" rows="4"><?php echo $pet['description']; ?></textarea>

    <input type="submit" value="Update Pet">

</form>

<?php require_once '../shared/footer.php'; ?>

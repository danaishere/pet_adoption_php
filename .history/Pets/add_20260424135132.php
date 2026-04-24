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

    
    if (empty($name)) $errors[] = 'Pet name is required';
    if (empty($species)) $errors[] = 'Species is required';
    if (empty($gender)) $errors[] = 'Gender is required';

    
    $photoPath = null;
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
                $photoPath = 'uploads/pets/' . $fileName;
            } else {
                $errors[] = 'Failed to upload photo';
            }
        }
    }

    if (empty($errors)) {
        $stmt = $conn->prepare('INSERT INTO pet_profiles (name, species, breed, age_years, age_months, gender, color, weight_kg, photo, adoption_status, description) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
        $stmt->bind_param('sssiissdsss', $name, $species, $breed, $ageYears, $ageMonths, $gender, $color, $weightKg, $photoPath, $adoptionStatus, $description);

        if ($stmt->execute()) {
            header('Location: index.php?added=1');
            exit();
        } else {
            $errors[] = 'Something went wrong. Please try again.';
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

<form action="#" method="post" enctype="multipart/form-data">

    <label>Pet Name:</label>
    <input type="text" name="name" value="<?php echo isset($name) ? $name : ''; ?>">

    <label>Species:</label>
    <select name="species">
        <option value="">-- Select --</option>
        <option value="Dog" <?php echo (isset($species) && $species == 'Dog') ? 'selected' : ''; ?>>Dog</option>
        <option value="Cat" <?php echo (isset($species) && $species == 'Cat') ? 'selected' : ''; ?>>Cat</option>
        <option value="Rabbit" <?php echo (isset($species) && $species == 'Rabbit') ? 'selected' : ''; ?>>Rabbit</option>
        <option value="Bird" <?php echo (isset($species) && $species == 'Bird') ? 'selected' : ''; ?>>Bird</option>
        <option value="Other" <?php echo (isset($species) && $species == 'Other') ? 'selected' : ''; ?>>Other</option>
    </select>

    <label>Breed:</label>
    <input type="text" name="breed" value="<?php echo isset($breed) ? $breed : ''; ?>">

    <div class="row">
        <div class="col-md-6">
            <label>Age (Years):</label>
            <input type="number" name="age_years" min="0" value="<?php echo isset($ageYears) ? $ageYears : '0'; ?>">
        </div>
        <div class="col-md-6">
            <label>Age (Months):</label>
            <input type="number" name="age_months" min="0" max="11" value="<?php echo isset($ageMonths) ? $ageMonths : '0'; ?>">
        </div>
    </div>

    <label>Gender:</label>
    <select name="gender">
        <option value="">-- Select --</option>
        <option value="Male" <?php echo (isset($gender) && $gender == 'Male') ? 'selected' : ''; ?>>Male</option>
        <option value="Female" <?php echo (isset($gender) && $gender == 'Female') ? 'selected' : ''; ?>>Female</option>
    </select>

    <label>Color:</label>
    <input type="text" name="color" value="<?php echo isset($color) ? $color : ''; ?>">

    <label>Weight (kg):</label>
    <input type="number" name="weight_kg" step="0.01" min="0" value="<?php echo isset($weightKg) ? $weightKg : ''; ?>">

    <label>Photo:</label>
    <input type="file" name="photo" accept="image/*">

    <label>Adoption Status:</label>
    <select name="adoption_status">
        <option value="Available" <?php echo (isset($adoptionStatus) && $adoptionStatus == 'Available') ? 'selected' : ''; ?>>Available</option>
        <option value="Pending" <?php echo (isset($adoptionStatus) && $adoptionStatus == 'Pending') ? 'selected' : ''; ?>>Pending</option>
        <option value="Adopted" <?php echo (isset($adoptionStatus) && $adoptionStatus == 'Adopted') ? 'selected' : ''; ?>>Adopted</option>
    </select>

    <label>Description:</label>
    <textarea name="description" rows="4"><?php echo isset($description) ? $description : ''; ?></textarea>

    <input type="submit" value="Add Pet">

</form>

<?php require_once '../shared/footer.php'; ?>

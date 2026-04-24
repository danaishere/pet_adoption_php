<!-- Update  -->
<?php
require_once '../shared/db.php';

$errors = [];
$success = '';

// Get the adopter by ID from the URL and fetch their info
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $conn->prepare('SELECT * FROM adopters WHERE id = ?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $adopter = $result->fetch_assoc();
}

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $id = $_POST['id'];
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $housing_type = $_POST['housing_type'];
    $has_yard = isset($_POST['has_yard']) ? 1 : 0;
    $experience = $_POST['experience'];
    $pet_preference = $_POST['pet_preference'];

    // Validate the form fields
    if (empty($full_name)) $errors[] = 'Full name is required';
    if (empty($email)) $errors[] = 'Email is required';
    if (empty($housing_type)) $errors[] = 'Housing type is required';

    if (empty($errors)) {
        $stmt = $conn->prepare('UPDATE adopters SET full_name=?, email=?, phone=?, housing_type=?, has_yard=?, experience=?, pet_preference=? WHERE id=?');
        $stmt->bind_param('ssssissi', $full_name, $email, $phone, $housing_type, $has_yard, $experience, $pet_preference, $id);

        if ($stmt->execute()) {
            $success = 'Profile updated successfully!';
            
            $stmt = $conn->prepare('SELECT * FROM adopters WHERE id = ?');
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $adopter = $result->fetch_assoc();
        } else {
            $errors[] = 'Something went wrong. Please try again.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="../yassine/style.css">
</head>
<body>

    <h1>Edit Adopter Profile</h1>
    <!-- Link back to the profiles list -->
    <a href="profile.php">Back to Profiles</a>

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

    
    <form action="#" method="post">

        <input type="hidden" name="id" value="<?php echo $adopter['id']; ?>">

        <label>Full Name:</label>
        <input type="text" name="full_name" value="<?php echo $adopter['full_name']; ?>"> <br><br>

        <label>Email:</label>
        <input type="email" name="email" value="<?php echo $adopter['email']; ?>"> <br><br>

        <label>Phone:</label>
        <input type="text" name="phone" value="<?php echo $adopter['phone']; ?>"> <br><br>

        <label>Housing Type:</label>
        <select name="housing_type">
            <option value="house" <?php echo $adopter['housing_type'] == 'house' ? 'selected' : ''; ?>>House</option>
            <option value="apartment" <?php echo $adopter['housing_type'] == 'apartment' ? 'selected' : ''; ?>>Apartment</option>
            <option value="condo" <?php echo $adopter['housing_type'] == 'condo' ? 'selected' : ''; ?>>Condo</option>
        </select> <br><br>

        <label>Has Yard?</label>
        <input type="checkbox" name="has_yard" <?php echo $adopter['has_yard'] ? 'checked' : ''; ?>> <br><br>

        <label>Experience:</label>
        <select name="experience">
            <option value="none" <?php echo $adopter['experience'] == 'none' ? 'selected' : ''; ?>>None</option>
            <option value="some" <?php echo $adopter['experience'] == 'some' ? 'selected' : ''; ?>>Some</option>
            <option value="experienced" <?php echo $adopter['experience'] == 'experienced' ? 'selected' : ''; ?>>Experienced</option>
        </select> <br><br>

        <label>Pet Preference:</label>
        <input type="text" name="pet_preference" value="<?php echo $adopter['pet_preference']; ?>"> <br><br>

        <input type="submit" value="Update Profile">

    </form>

</body>
</html>
<?php
include "../shared/footer.php"; ?>
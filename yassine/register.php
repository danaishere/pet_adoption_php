<?php
require_once 'C:/Users/elamr/Desktop/pet_adoption_php/shared/db.php';

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Get form data
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $phone = $_POST['phone'];
    $housing_type = $_POST['housing_type'];
    $has_yard = isset($_POST['has_yard']) ? 1 : 0;
    $experience = $_POST['experience'];
    $pet_preference = $_POST['pet_preference'];

    // Validate
    if (empty($full_name)) $errors[] = 'Full name is required';
    if (empty($email)) $errors[] = 'Email is required';
    if (empty($password)) $errors[] = 'Password is required';
    if (empty($housing_type)) $errors[] = 'Housing type is required';

    if (empty($errors)) {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert into database
        $stmt = $conn->prepare('INSERT INTO adopters (full_name, email, password, phone, housing_type, has_yard, experience, pet_preference) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
        $stmt->bind_param('sssssiss', $full_name, $email, $hashed_password, $phone, $housing_type, $has_yard, $experience, $pet_preference);

        if ($stmt->execute()) {
            $success = 'Registration successful!';
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
    <title>Register - Pet Adoption</title>
</head>
<body>

    <h1>Adopter Registration</h1>

    <!-- Show errors -->
    <?php if (!empty($errors)): ?>
        <ul style="color:red;">
            <?php foreach ($errors as $error): ?>
                <li><?php echo $error; ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <!-- Show success -->
    <?php if ($success): ?>
        <p style="color:green;"><?php echo $success; ?></p>
    <?php endif; ?>

    <!-- Registration Form -->
    <form action="#" method="post">

        <label>Full Name:</label>
        <input type="text" name="full_name"> <br><br>

        <label>Email:</label>
        <input type="email" name="email"> <br><br>

        <label>Password:</label>
        <input type="password" name="password"> <br><br>

        <label>Phone:</label>
        <input type="text" name="phone"> <br><br>

        <label>Housing Type:</label>
        <select name="housing_type">
            <option value="">-- Select --</option>
            <option value="house">House</option>
            <option value="apartment">Apartment</option>
            <option value="condo">Condo</option>
        </select> <br><br>

        <label>Do you have a yard?</label>
        <input type="checkbox" name="has_yard"> <br><br>

        <label>Experience with pets:</label>
        <select name="experience">
            <option value="none">None</option>
            <option value="some">Some</option>
            <option value="experienced">Experienced</option>
        </select> <br><br>

        <label>Pet Preference:</label>
        <input type="text" name="pet_preference" placeholder="e.g. dog, cat"> <br><br>

        <input type="submit" value="Register">

    </form>

</body>
</html>
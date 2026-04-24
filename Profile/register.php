<!-- create  -->
<?php
require_once '../shared/db.php';
include "../shared/header.php";
// Empty arrays for errors and success message
$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
     // Get the data from the form
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $phone = $_POST['phone'];
    $housing_type = $_POST['housing_type'];
    $has_yard = isset($_POST['has_yard']) ? 1 : 0;
    $experience = $_POST['experience'];
    $pet_preference = $_POST['pet_preference'];
// Validate the form fields
    if (empty($full_name)) $errors[] = 'Full name is required';
    if (empty($email)) $errors[] = 'Email is required';
    if (empty($password)) $errors[] = 'Password is required';
    if (empty($housing_type)) $errors[] = 'Housing type is required';

    if (empty($errors)) {
           // Hash the password before saving
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

       
       $stmt = $conn->prepare('INSERT INTO adopters (full_name, email, password, phone, housing_type, has_yard, experience, pet_preference, role, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?, "adopter", 1)');
$stmt->bind_param('sssssiss', $full_name, $email, $hashed_password, $phone, $housing_type, $has_yard, $experience, $pet_preference);
// Show success or error message
      if ($stmt->execute()) {
    header('Location: /Adoption-Application/login.php?registered=1');
    exit();
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
        <link rel="stylesheet" href="../yassine/style.css">

</head>
<body>

    <h1>Adopter Registration</h1>
 <!-- Show error messages if any -->
   
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
<?php
include "../shared/footer.php"; ?>
<!-- Read -->
<?php
require_once '../shared/db.php';

// Get all active adopters from the database
$stmt = $conn->prepare('SELECT * FROM adopters WHERE is_active = 1');
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Adopter Profiles</title>
        <link rel="stylesheet" href="../yassine/style.css">

</head>
<body>

    <h1>Adopter Profiles</h1>
 <!-- Show success message if an account was just deactivated -->
    <?php if (isset($_GET['deleted'])): ?>
    <p class="success">Account deactivated successfully!</p>
<?php endif; ?>
<!-- Link to register a new adopter -->
    <a href="register.php">Register New Adopter</a>

    <br><br>

    <?php if ($result->num_rows == 0): ?>
        <p>No adopters found.</p>
    <?php else: ?>

        <table border="1" cellpadding="10">
            <tr>
                <th>ID</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Housing</th>
                <th>Has Yard</th>
                <th>Experience</th>
                <th>Pet Preference</th>
                <th>Actions</th>
            </tr>

            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['full_name']; ?></td>
                <td><?php echo $row['email']; ?></td>
                <td><?php echo $row['phone']; ?></td>
                <td><?php echo $row['housing_type']; ?></td>
                <td><?php echo $row['has_yard'] ? 'Yes' : 'No'; ?></td>
                <td><?php echo $row['experience']; ?></td>
                <td><?php echo $row['pet_preference']; ?></td>
                <td>
                    <a href="edit_profile.php?id=<?php echo $row['id']; ?>">Edit</a>
                    |
                    <a href="delete_account.php?id=<?php echo $row['id']; ?>">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>

        </table>

    <?php endif; ?>

</body>
</html>
<?php
include "../shared/footer.php"; ?>
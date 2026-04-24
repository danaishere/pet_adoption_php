<?php
// Connect to the database
require_once '../shared/db.php';
include "../shared/header.php";

// Get all active adopters from the database
$stmt = $conn->prepare('SELECT * FROM adopters WHERE is_active = 1');
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Adopter Profiles - Pet Adoption</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <h1>Adopter Profiles</h1>

    <!-- Navigation -->
    <nav>
        <a href="register.php">+ Register New Adopter</a>
    </nav>

    <!-- Show all adopters -->
    <?php if ($result->num_rows == 0): ?>
        <p>No adopters found.</p>
    <?php else: ?>

        <table>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Housing</th>
                <th>Experience</th>
                <th>Pet Preference</th>
                <th>Actions</th>
            </tr>

            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['full_name']; ?></td>
                <td><?php echo $row['email']; ?></td>
                <td><?php echo $row['housing_type']; ?></td>
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
include "../shared/footer.php";
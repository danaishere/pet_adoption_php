<?php
session_start();
require_once '../shared/db.php';
require_once '../shared/header.php';

// Get all active adopters from the database
$stmt = $conn->prepare('SELECT * FROM adopters WHERE is_active = 1 AND role != "admin"');
$stmt->execute();
$result = $stmt->get_result();
?>

<h1 class="mb-4">Adopter Profiles</h1>

<?php if (isset($_GET['deleted'])): ?>
    <div class="alert alert-success">Account deleted successfully!</div>
<?php endif; ?>

<a href="register.php" class="btn btn-primary-custom mb-3">+ Register New Adopter</a>

<?php if ($result->num_rows == 0): ?>
    <div class="alert alert-info">No adopters found.</div>
<?php else: ?>
    <table class="table table-bordered table-hover align-middle">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Housing</th>
                <th>Experience</th>
                <th>Pet Preference</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                <td><?php echo htmlspecialchars($row['email']); ?></td>
                <td><?php echo htmlspecialchars($row['housing_type']); ?></td>
                <td><?php echo htmlspecialchars($row['experience']); ?></td>
                <td><?php echo htmlspecialchars($row['pet_preference'] ?? '—'); ?></td>
                <td>
                    <a href="view.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-info">View</a>
                    <a href="edit_profile.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                    <a href="delete_account.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger"
                       onclick="return confirm('Are you sure you want to delete this account?')">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
<?php endif; ?>

<?php require_once '../shared/footer.php'; ?>
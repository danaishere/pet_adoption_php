<?php
session_start();
require_once '../shared/db.php';
require_once '../shared/header.php';

if (!isset($_GET['id'])) {
    header('Location: profile.php');
    exit();
}

$id = $_GET['id'];
$stmt = $conn->prepare('SELECT * FROM adopters WHERE id = ?');
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
$adopter = $result->fetch_assoc();

if (!$adopter) {
    header('Location: profile.php');
    exit();
}
?>

<a href="profile.php" class="btn btn-secondary-custom mb-4">Back to Adopter Profiles</a>

<div class="card p-4" style="max-width: 700px;">
    <h2 class="mb-4"><?php echo htmlspecialchars($adopter['full_name']); ?></h2>

    <table class="table table-bordered">
        <tr>
            <th>Email</th>
            <td><?php echo htmlspecialchars($adopter['email']); ?></td>
        </tr>
        <tr>
            <th>Phone</th>
            <td><?php echo htmlspecialchars($adopter['phone'] ?? '—'); ?></td>
        </tr>
        <tr>
            <th>Housing Type</th>
            <td><?php echo htmlspecialchars($adopter['housing_type']); ?></td>
        </tr>
        <tr>
            <th>Has Yard</th>
            <td><?php echo $adopter['has_yard'] ? 'Yes' : 'No'; ?></td>
        </tr>
        <tr>
            <th>Experience</th>
            <td><?php echo htmlspecialchars($adopter['experience']); ?></td>
        </tr>
        <tr>
            <th>Pet Preference</th>
            <td><?php echo htmlspecialchars($adopter['pet_preference'] ?? '—'); ?></td>
        </tr>
       <tr>
    <th>Registered On</th>
    <td><?php echo !empty($adopter['date_registered']) ? date('F j, Y', strtotime($adopter['date_registered'])) : '—'; ?></td>
</tr>
        <tr>
            <th>Status</th>
            <td><?php echo $adopter['is_active'] ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Inactive</span>'; ?></td>
        </tr>
    </table>

    <div class="mt-3">
        <a href="edit_profile.php?id=<?php echo $adopter['id']; ?>" class="btn btn-warning">Edit</a>
        <a href="delete_account.php?id=<?php echo $adopter['id']; ?>" class="btn btn-danger"
           onclick="return confirm('Are you sure you want to delete this account?')">Delete</a>
    </div>
</div>

<?php require_once '../shared/footer.php'; ?>
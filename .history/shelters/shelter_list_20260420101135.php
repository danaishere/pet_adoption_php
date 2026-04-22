<?php
require_once "../config/db.php";
 include("../includes/header.php"); 

$sql = "SELECT * FROM shelters ORDER BY created_at DESC";
$stmt = $conn->query($sql);
$shelters = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<h2>All Shelters</h2>

<?php if(isset($_GET['success'])): ?>
    <p style="color:green;">Operation successful!</p>
<?php endif; ?>

<table border="1">
<tr>
    <th>Name</th>
    <th>Type</th>
    <th>City</th>
    <th>Capacity</th>
    <th>Actions</th>
</tr>

<?php foreach($shelters as $shelter): ?>
<tr>
    <td><?= htmlspecialchars($shelter['name']) ?></td>
    <td><?= $shelter['type'] ?></td>
    <td><?= $shelter['city'] ?></td>
    <td><?= $shelter['current_occupancy'] ?> / <?= $shelter['capacity'] ?></td>
    <td>
        <a href="shelter_details.php?id=<?= $shelter['shelter_id'] ?>">View</a>
        <a href="edit_shelter.php?id=<?= $shelter['shelter_id'] ?>">Edit</a>
        <a href="delete_shelter.php?id=<?= $shelter['shelter_id'] ?>" 
           onclick="return confirm('Are you sure?')">Delete</a>
    </td>
</tr>
<?php endforeach; ?>

</table>
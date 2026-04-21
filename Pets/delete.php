<?php
require_once '../shared/db.php';

// Check if an ID was passed in the URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Get the pet's photo path before deleting
    $stmt = $conn->prepare('SELECT photo FROM pet_profiles WHERE pet_id = ?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $pet = $result->fetch_assoc();

    // Delete the pet from the database
    $stmt = $conn->prepare('DELETE FROM pet_profiles WHERE pet_id = ?');
    $stmt->bind_param('i', $id);
    $stmt->execute();

    // Delete the photo file if it exists
    if ($pet && $pet['photo'] && file_exists('../shared/' . $pet['photo'])) {
        unlink('../shared/' . $pet['photo']);
    }

    header('Location: index.php?deleted=1');
    exit();
} else {
    header('Location: index.php');
    exit();
}
?>

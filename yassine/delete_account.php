<?php
require_once '../shared/db.php';
// Check if an ID was passed in the URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];
// Delete the adopter from the database using a parameterized query
    $stmt = $conn->prepare('DELETE FROM adopters WHERE id = ?');
    $stmt->bind_param('i', $id);

    if ($stmt->execute()) {
        header('Location: profile.php?deleted=1');
        exit();
    } else {
        echo "Something went wrong. Please try again.";
    }
}
?>
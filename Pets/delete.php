<?php
require_once '../shared/db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    
    $stmt = $conn->prepare('SELECT photo FROM pet_profiles WHERE pet_id = ?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $pet = $result->fetch_assoc();

    
    $stmt = $conn->prepare('DELETE FROM applications WHERE pet_id = ?');
    $stmt->bind_param('i', $id);
    $stmt->execute();

   
    $stmt = $conn->prepare('DELETE FROM pet_profiles WHERE pet_id = ?');
    $stmt->bind_param('i', $id);
    $stmt->execute();

    
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
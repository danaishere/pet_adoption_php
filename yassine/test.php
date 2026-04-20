<?php
$conn = new mysqli("localhost", "root", "root", "pet_adoption", 3306);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    echo "Connected successfully!";
}
?>
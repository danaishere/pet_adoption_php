<?php
require_once "../config/db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = $_POST['name'];
    $type = $_POST['type'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $province = $_POST['province'];
    $capacity = $_POST['capacity'];
    $description = $_POST['description'];

    // Basic validation
    if (!empty($name) && !empty($email) && $capacity >= 0) {

        $sql = "INSERT INTO shelters 
        (name, type, email, phone, address, city, province, capacity, description)
        VALUES (:name, :type, :email, :phone, :address, :city, :province, :capacity, :description)";

        $stmt = $conn->prepare($sql);

        $stmt->execute([
            ':name' => $name,
            ':type' => $type,
            ':email' => $email,
            ':phone' => $phone,
            ':address' => $address,
            ':city' => $city,
            ':province' => $province,
            ':capacity' => $capacity,
            ':description' => $description
        ]);

        header("Location: index.php?success=added");
        exit();
    }
}
?>
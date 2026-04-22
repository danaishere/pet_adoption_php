$id = $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM shelters WHERE shelter_id = :id");
$stmt->execute([':id' => $id]);
$shelter = $stmt->fetch(PDO::FETCH_ASSOC);

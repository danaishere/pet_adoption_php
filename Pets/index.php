<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>

<?php
$pageTitle = 'All Pets';
$pageStyles = 'style.css';
require_once '../shared/header.php';
require_once '../shared/db.php';

// Handle filters
$whereClause = 'WHERE 1=1';
$params = [];
$types = '';

if (isset($_GET['species']) && $_GET['species'] !== '') {
    $whereClause .= ' AND species = ?';
    $params[] = $_GET['species'];
    $types .= 's';
}

if (isset($_GET['status']) && $_GET['status'] !== '') {
    $whereClause .= ' AND adoption_status = ?';
    $params[] = $_GET['status'];
    $types .= 's';
}

if (isset($_GET['search']) && $_GET['search'] !== '') {
    $whereClause .= ' AND (name LIKE ? OR breed LIKE ?)';
    $searchTerm = '%' . $_GET['search'] . '%';
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $types .= 'ss';
}

$sql = "SELECT * FROM pet_profiles $whereClause ORDER BY date_added DESC";
$stmt = $conn->prepare($sql);

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

// Get distinct species for filter dropdown
$speciesResult = $conn->query("SELECT DISTINCT species FROM pet_profiles ORDER BY species");

$isAdmin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
?>

<h1><?php echo $isAdmin ? 'All Pets' : 'Available Pets'; ?></h1>

<!-- Navigation -->
<?php if ($isAdmin): ?>
<nav class="mb-3">
    <a href="add.php" class="btn btn-primary-custom">+ Add New Pet</a>
</nav>
<?php endif; ?>

<!-- Show success messages -->
<?php if (isset($_GET['added'])): ?>
    <p class="success">Pet added successfully!</p>
<?php endif; ?>

<?php if (isset($_GET['updated'])): ?>
    <p class="success">Pet updated successfully!</p>
<?php endif; ?>

<?php if (isset($_GET['deleted'])): ?>
    <p class="success">Pet removed successfully!</p>
<?php endif; ?>

<!-- Filter Bar -->
<form action="" method="get" class="filterBar">
    <label>Species:</label>
    <select name="species">
        <option value="">All Species</option>
        <?php while ($speciesRow = $speciesResult->fetch_assoc()): ?>
            <option value="<?php echo $speciesRow['species']; ?>"
                <?php echo (isset($_GET['species']) && $_GET['species'] == $speciesRow['species']) ? 'selected' : ''; ?>>
                <?php echo $speciesRow['species']; ?>
            </option>
        <?php endwhile; ?>
    </select>

    <label>Status:</label>
    <select name="status">
        <option value="">All Statuses</option>
        <option value="Available" <?php echo (isset($_GET['status']) && $_GET['status'] == 'Available') ? 'selected' : ''; ?>>Available</option>
        <option value="Pending" <?php echo (isset($_GET['status']) && $_GET['status'] == 'Pending') ? 'selected' : ''; ?>>Pending</option>
        <option value="Adopted" <?php echo (isset($_GET['status']) && $_GET['status'] == 'Adopted') ? 'selected' : ''; ?>>Adopted</option>
    </select>

    <label>Search:</label>
    <input type="text" name="search" placeholder="Name or breed..." value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">

    <input type="submit" value="Search">
    <a href="index.php" class="btn btn-secondary-custom btn-sm">Reset</a>
</form>

<!-- Pet Cards Grid -->
<?php if ($result->num_rows == 0): ?>
    <p>No pets found.</p>
<?php else: ?>
    <div class="row">
        <?php while ($pet = $result->fetch_assoc()): ?>
            <div class="col-md-4 col-sm-6 mb-4">
                <div class="petCard">
                    <?php if ($pet['photo'] && file_exists('../shared/' . $pet['photo'])): ?>
                        <img src="../shared/<?php echo $pet['photo']; ?>" alt="<?php echo $pet['name']; ?>" class="petCardImage">
                    <?php else: ?>
                        <div class="noPhoto">No Photo Available</div>
                    <?php endif; ?>

                    <div class="petCardBody">
                        <?php
                            $statusClass = 'statusAvailable';
                            if ($pet['adoption_status'] == 'Pending') $statusClass = 'statusPending';
                            if ($pet['adoption_status'] == 'Adopted') $statusClass = 'statusAdopted';
                        ?>
                        <span class="statusBadge <?php echo $statusClass; ?>"><?php echo $pet['adoption_status']; ?></span>

                        <h3><?php echo $pet['name']; ?></h3>
                        <p><strong><?php echo $pet['species']; ?></strong> - <?php echo $pet['breed']; ?></p>
                        <p><?php echo $pet['gender']; ?> | <?php echo $pet['age_years']; ?>y <?php echo $pet['age_months']; ?>m | <?php echo $pet['weight_kg']; ?> kg</p>
                        <p><?php echo $pet['color']; ?></p>
                    </div>

                    <div class="petCardActions">
                        <a href="view.php?id=<?php echo $pet['pet_id']; ?>">View</a>
                        <?php if ($isAdmin): ?>
                            |
                            <a href="edit.php?id=<?php echo $pet['pet_id']; ?>">Edit</a>
                            |
                            <a href="delete.php?id=<?php echo $pet['pet_id']; ?>" onclick="return confirm('Are you sure you want to remove this pet?')">Delete</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
<?php endif; ?>

<?php require_once '../shared/footer.php'; ?>
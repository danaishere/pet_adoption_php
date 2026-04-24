<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo isset($pageTitle) ? $pageTitle . ' - Furever Home' : 'Furever Home'; ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="/style.css">
  <?php if (isset($pageStyles)): ?>
    <link rel="stylesheet" href="<?php echo $pageStyles; ?>">
  <?php endif; ?>
</head>

<body>

  <nav class="navbar navbar-expand-lg main-navbar">
    <div class="container">
      <a class="navbar-brand" href="/index.php">Furever Home</a>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarNav">

        <!-- Left links -->
        <ul class="navbar-nav me-auto">
          <li class="nav-item">
            <a class="nav-link" href="/pets/index.php">Manage Pets</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/Applications">Applications List</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/profile/index.php">Adopter Database</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/shelters/index.php">Shelters</a>
          </li>
        </ul>

        <!-- Right:-->
        <ul class="navbar-nav ms-auto">
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle profileToggle" href="#" id="profileDropdown" role="button"
              data-bs-toggle="dropdown" aria-expanded="false">
              Admin
            </a>
            <ul class="dropdown-menu dropdown-menu-end profileMenu" aria-labelledby="profileDropdown">
              <li><a class="dropdown-item" href="#">Settings</a></li>
              <li>
                <hr class="dropdown-divider profileDivider">
              </li>
              <li><a class="dropdown-item signOutLink" href="/profile/logout.php">Sign Out</a></li>
            </ul>
          </li>
        </ul>

      </div>
    </div>
  </nav>

  <div class="container main-content">
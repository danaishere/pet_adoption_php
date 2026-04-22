<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pet Adoption CMS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/shelter_style.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

   <style>
    body {
        background-color: #F0FDF4;
        font-family: 'Segoe UI', sans-serif;
    }

    h1, h2, h3, h4, h5 {
        color: #111827;
        font-weight: 600;
    }

    .navbar {
        background-color: #4ADE80 !important;
    }

    .navbar-brand {
        font-weight: bold;
        color: #111827 !important;
    }

    .btn-primary {
        background-color: #4ADE80;
        border: none;
    }

    .btn-primary:hover {
        background-color: #34D399;
    }

    .btn-success {
        background-color: #34D399;
        border: none;
    }

    .btn-success:hover {
        background-color: #4ADE80;
    }

    .card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        background-color: white;
    }

    .footer {
        background-color: #4ADE80;
        color: #111827;
    }

    .badge-verified {
        background-color: #34D399;
    }

    .badge-pending {
        background-color: orange;
    }

    .badge-inactive {
        background-color: gray;
    }
</style>
</head>

<body>

<nav class="navbar navbar-expand-lg navbar-dark m-0 p-2">
    <div class="container-fluid px-3">
        <a class="navbar-brand" href="../index.php">
            <i class="fa-solid fa-paw"></i> Furever Home
        </a>

        <div class="d-flex gap-2">
            <a href="index.php" class="btn ">
                <i class="fa-solid fa-building"></i> Shelters
            </a>

            <a href="add_shelter.php" >
                <i class="fa-solid fa-plus"></i> Add Shelter
            </a>
        </div>
    </div>
</nav>

<div class="container mt-4"></div>
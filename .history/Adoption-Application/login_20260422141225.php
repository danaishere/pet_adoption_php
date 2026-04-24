<?php
session_start();
include "../shared/db.php";


if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] === 'admin') {
        header("Location: admin/dashboard.php");
    } else {
        header("Location: applications/apply.php");
    }
    exit();
}

$error = "";

if (isset($_POST['login'])) {

    $email    = $_POST['email'];
    $password = $_POST['password'];

    $sql    = "SELECT * FROM adopters WHERE email='$email' AND is_active=1 LIMIT 1";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {

        $user = $result->fetch_assoc();

       
        if (password_verify($password, $user['password']) || $password === $user['password']) {

            // store in session
            $_SESSION['user_id']   = $user['id'];
            $_SESSION['user_name'] = $user['full_name'];
            $_SESSION['role']      = $user['role'];

            // redirect by role
            if ($user['role'] === 'admin') {
                header("Location: ../admin/dashboard.php");
            } else {
                header("Location: ../applications/apply.php");
            }
            exit();
        }
    }

    $error = "Invalid email or password. Please try again.";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - Pet Adoption</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow">

                <div class="card-header bg-dark text-white text-center py-3">
                    <h4 class="mb-0"> Pet Adoption Login</h4>
                </div>

                <div class="card-body p-4">

                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?= $error ?></div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Email Address</label>
                            <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Enter your password" required>
                        </div>
                        <button type="submit" name="login" class="btn btn-dark w-100">Login</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>

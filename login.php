<?php
session_start();
include 'db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $result = mysqli_query($conn, "SELECT * FROM users WHERE email='$username'");
    $user = mysqli_fetch_assoc($result);

    if ($user && md5($password) == $user['password']) {

        $_SESSION['loggedin'] = true;
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['email'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['name'] = $user['name'];

        if ($user['role'] == 'admin') {
            header("Location: admin/admin_dashboard.php");
        } else {
            $_SESSION['company_id'] = $user['company_id'];
            header("Location: employee/employee_dashboard.php");
        }
        exit();
    } else {
        $error = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BUSINESS CRM - Log In</title>

    <link rel="stylesheet" href="css/sampa_css/style.css">
    <link rel="stylesheet" href="css/sampa_css/bootstrap.min.css">
    <link rel="stylesheet" href="css/sampa_css/all.min.css">
</head>

<body>
    <?php if ($error): ?>
        <div class="toast-container position-fixed top-0 end-0 p-3">
            <div id="errorToast" class="toast show" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header bg-danger text-white">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <strong class="me-auto">Error</strong>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="card-box">
        <div class="title">BUSINESS CRM</div>
        <div class="subtitle">Please sign in to your account</div>

        <form id="loginForm" action="" method="post" novalidate>
            <div class="mb-3">
                <input type="email" id="email" class="form-control"
                    placeholder="Email address (e.g., admin@educare.com)" name="email" required>
                <div class="invalid-feedback">
                    Please enter a valid email address.
                </div>
            </div>

            <div class="mb-3">
                <div class="position-relative">
                    <input type="password" id="password" class="form-control pe-5" name="password" required minlength="6">

                    <div id="eye_icon">
                        <i class="fa-solid fa-eye position-absolute top-50 end-0 translate-middle-y me-3 toggle-eye"
                            onclick="togglePassword()"></i>
                    </div>

                    <div class="invalid-feedback">
                        Password must be at least 6 characters.
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100">Sign in</button>
        </form>

        <script src="js/ipsita_js/jquery-4.0.0.js"></script>
        <script src="js/sampa_js/bootstrap.min.js"></script>
        <script src="js/sampa_js/all.min.js"></script>
        <script src="js/sampa_js/script.js"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const errorToast = document.getElementById('errorToast');
                if (errorToast) {
                    setTimeout(function() {
                        const toast = new bootstrap.Toast(errorToast);
                        toast.hide();
                    }, 5000);
                }
            });
        </script>
</body>

</html>
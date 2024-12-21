<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include '../init.php';

    $user = new User();

    // Sanitize inputs
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    // Attempt login
    if ($user->login($email, $password)) {
        $_SESSION['user_email'] = $email;

        if (isset($_POST['remember_me'])) {
            setcookie('user_email', $email, time() + (7 * 24 * 60 * 60), '/');
        } else {
            setcookie('user_email', '', time() - 3600, '/');
        }

        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Invalid email or password.";
    }
}

if (isset($_COOKIE['user_email']) && !isset($_SESSION['user_email'])) {
    $_SESSION['user_email'] = $_COOKIE['user_email'];
    header("Location: dashboard.php");
    exit();
}
?>

<?php include '../templates/header.php'; ?>
<style>
    body {
        background: linear-gradient(135deg, #1e3c72, #2a5298);
        min-height: 100vh;
        margin: 0;
        font-family: 'Roboto', sans-serif;
        justify-self: center;
        
       
    }

    .login-container {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(15px);
        border-radius: 20px;
        box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.3);
        animation: fadeInUp 1s ease-out;
        max-width: 400px;
        width: 120%;
        padding : 40px;
        
        
    }
    .login-container h3 {
        margin-bottom: 20px;
        font-size: 1.8rem;
        color: #1e3c72;
        animation: fadeIn 1.5s ease-out;
    }

    .form-label {
        color: #1e3c72;
        font-weight: 500;
        animation: fadeInLeft 1s ease-out;
    }

    .form-control {
        border-radius: 25px;
        padding: 10px;
        margin-bottom: 15px;
        animation: fadeInRight 1s ease-out;
    }

    .form-control:focus {
        border-color: #2a5298;
        box-shadow: 0 0 10px rgba(42, 82, 152, 0.3);
    }

    .btn-primary {
        background: linear-gradient(135deg, #1e3c72, #2a5298);
        border: none;
        border-radius: 30px;
        padding: 12px 20px;
        font-size: 1.2rem;
        color: #fff;
        font-weight: bold;
        cursor: pointer;
        transition: background 0.3s ease, transform 0.3s ease;
        width: 100%;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #2a5298, #1e3c72);
        transform: scale(1.05);
    }

    .text-center a {
        color: #2a5298;
        text-decoration: none;
        font-weight: bold;
        transition: color 0.3s ease;
    }

    .text-center a:hover {
        color: #1e3c72;
    }

    .alert {
        color: #721c24;
        background-color: #f8d7da;
        border-color: #f5c6cb;
        border-radius: 10px;
        padding: 10px;
        margin-bottom: 15px;
    }

    @media screen and (max-width: 768px) {
        .login-container {
            padding: 30px;
            width: 95%;
        }

        .login-container h3 {
            font-size: 1.5rem;
        }
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeInLeft {
        from {
            opacity: 0;
            transform: translateX(-20px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    @keyframes fadeInRight {
        from {
            opacity: 0;
            transform: translateX(20px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
</style>

<div class="login-container">
    <h3>Login to Your Account</h3>
    <?php if (isset($error)) { echo "<div class='alert alert-danger'>$error</div>"; } ?>
    <form method="POST">
        <label for="email" class="form-label">Email Address</label>
        <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>

        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>

        <div class="form-check mb-3">
            <input type="checkbox" class="form-check-input" id="remember_me" name="remember_me">
            <label class="form-check-label" for="remember_me">Remember Me</label>
        </div>

        <button type="submit" class="btn btn-primary w-100">Login</button>
    </form>
    <div class="text-center mt-3">
        <a href="Register.php">Create an account</a> | <a href="forgot_password.php">Forgot password?</a>
    </div>
</div>

<?php include '../templates/footer.php'; ?>

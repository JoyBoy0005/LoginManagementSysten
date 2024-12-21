<?php
include '../init.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = new User();

    $firstname = htmlspecialchars(trim($_POST['firstname']));
    $lastname = htmlspecialchars(trim($_POST['lastname']));
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    if ($password === $confirmPassword) {
        if ($user->register($firstname, $lastname, $email, $password)) {
            header("Location: login.php?success=Registration successful.");
        } else {
            $error = "Error during registration.";
        }
    } else {
        $error = "Passwords do not match.";
    }
}
?>

<?php include '../templates/header.php'; ?>
<style>
    body {
        background: linear-gradient(135deg, #ff7e5f, #feb47b);
        min-height: 100vh;
        font-family: 'Roboto', sans-serif;
        
    }

    .register-container {  
        width: 100%;
        max-width: 500px;
        background: #ffffff;
        padding-left: 2%;
        padding-right: 2%;
        padding-top: 2%;
        padding-bottom: 2%;
        border-radius: 15px;
        box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.2);
        animation: fadeIn 0.5s ease-out;
        justify-self: center;
    }

    .register-container h3 {
        text-align: center;
        margin-bottom: 20px;
        font-weight: bold;
        color: #ff7e5f;
    }

    .form-label {
        color: #ff7e5f;
        font-weight: 500;
    }

    .form-control {
        border-radius: 10px;
        padding: 10px;
    }

    .form-control:focus {
        border-color: #feb47b;
        box-shadow: 0 0 10px rgba(255, 126, 95, 0.3);
    }

    .btn-primary {
        background: #ff7e5f;
        border: none;
        border-radius: 25px;
        padding: 12px;
        font-size: 1rem;
        transition: background 0.3s ease, transform 0.2s ease;
    }

    .btn-primary:hover {
        background: #feb47b;
        transform: scale(1.05);
    }

    .text-center a {
        color: #ff7e5f;
        text-decoration: none;
        font-weight: bold;
        transition: color 0.3s ease;
    }

    .text-center a:hover {
        color: #feb47b;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

<div class="register-container">
    <h3>Create an Account</h3>
    <?php if (isset($error)) { echo "<div class='alert alert-danger'>$error</div>"; } ?>
    <form method="POST">
        <div class="mb-3">
            <label for="firstname" class="form-label">First Name</label>
            <input type="text" class="form-control" id="firstname" name="firstname" placeholder="Enter your first name" required>
        </div>
        <div class="mb-3">
            <label for="lastname" class="form-label">Last Name</label>
            <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Enter your last name" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email Address</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
        </div>
        <div class="mb-3">
            <label for="confirmPassword" class="form-label">Confirm Password</label>
            <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" placeholder="Re-enter your password" required>
        </div>
        <div id="strengthMessage"></div>
        <button type="submit" class="btn btn-primary w-100">Register</button>
    </form>
    <div class="text-center mt-3">
        <a href="login.php">Already have an account? Login</a>
    </div>
</div>
<?php include '../templates/footer.php'; ?>
    

<script>
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('confirm_password');
    const strengthMessage = document.getElementById('strengthMessage');

    passwordInput.addEventListener('input', function() {
        const password = passwordInput.value;
        let strength = 'Weak';
        let color = 'red';

        if (password.length >= 8) {
            strength = 'Medium';
            color = 'orange';
            if (/[A-Z]/.test(password) && /[0-9]/.test(password) && /[!@#$%^&*]/.test(password)) {
                strength = 'Strong';
                color = 'green';
            }
        }

        strengthMessage.textContent = `Password strength: ${strength}`;
        strengthMessage.style.color = color;
    });

    confirmPasswordInput.addEventListener('input', function() {
        if (passwordInput.value !== confirmPasswordInput.value) {
            strengthMessage.textContent = 'Passwords do not match';
            strengthMessage.style.color = 'red';
        } else {
            strengthMessage.textContent = '';
        }
    });
</script>
<?php
session_start(); // Start the session for CSRF protection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include '../init.php';

    // CSRF Token validation
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("CSRF token validation failed.");
    }

    // Retrieve the token from the URL
    $token = isset($_GET['token']) ? trim($_GET['token']) : null;

    if (!empty($token)) {
        $newPassword = $_POST['password'];
        $confirmPassword = $_POST['confirm_password'];

        if ($newPassword === $confirmPassword) {
            $db = new Database();

            // Validate the token and expiry without hashing (adjust based on your storage logic)
            $user = $db->fetch("SELECT * FROM users WHERE reset_token = ? AND token_expiry > NOW()", [$token]);

            if ($user) {
                // Hash the new password
                $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

                // Update the password and clear the reset token and expiry
                $result = $db->query("UPDATE users SET password = ?, reset_token = NULL, token_expiry = NULL WHERE id = ?", [$hashedPassword, $user['id']]);

                if ($result) {
                    echo "<script>
                        setTimeout(function() {
                            window.location.href = 'login.php';
                        }, 3000);
                    </script>";
                    $message = "Password reset successfully! You will be redirected to the login page shortly.";
                } else {
                    $error = "An error occurred while resetting your password. Please try again.";
                }
            } else {
                $error = "Invalid or expired token.";
            }
        } else {
            $error = "Passwords do not match.";
        }
    } else {
        $error = "Invalid or missing token.";
    }
} else {
    // For GET requests, ensure the token is in the URL
    $token = isset($_GET['token']) ? trim($_GET['token']) : null;
    if (empty($token)) {
        $error = "Invalid or missing token.";
    }

    // Generate a CSRF token for the form
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>


<?php include '../templates/header.php'; ?>
<style>
    body {
        background: linear-gradient(135deg, #0f2027, #2c5364);
        min-height: 100vh;
        margin: 0;
        font-family: 'Roboto', sans-serif;
    }

    .reset-password-container {
        width: 100%;
        max-width: 450px;
        background: #ffffff;
        padding-left: 2%;
        padding-right: 2%;
        padding-top: 2%;
        padding-bottom: 2%;
        border-radius: 15px;
        box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.2);
        animation: fadeIn 0.5s ease-out;
        justify-self: center;
        margin: auto;
    }

    .reset-password-container h3 {
        margin-bottom: 20px;
        font-weight: bold;
        color: #0f2027;
    }

    .form-label {
        color: #0f2027;
        font-weight: 500;
    }

    .form-control {
        border-radius: 10px;
        padding: 10px;
        text-align: center;
    }

    .form-control:focus {
        border-color: #2c5364;
        box-shadow: 0 0 10px rgba(44, 83, 100, 0.3);
    }

    .btn-primary {
        background: #0f2027;
        border: none;
        border-radius: 25px;
        padding: 12px;
        font-size: 1rem;
        transition: background 0.3s ease, transform 0.2s ease;
    }

    .btn-primary:hover {
        background: #2c5364;
        transform: scale(1.05);
        color: #ffffff;
    }

    .text-center {
        margin-top: 10px;
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

    #strengthMessage {
        margin-top: 10px;
        font-weight: bold;
    }
</style>

<div class="reset-password-container">
    <h3>Reset Password</h3>
    <?php if (isset($error)) { echo "<div class='alert alert-danger'>$error</div>"; } ?>
    <?php if (isset($message)) { echo "<div class='alert alert-success'>$message</div>"; } ?>
    <form method="POST">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        <div class="mb-3">
            <label for="password" class="form-label">New Password</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Enter your new password" required>
        </div>
        <div class="mb-3">
            <label for="confirm_password" class="form-label">Confirm Password</label>
            <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Re-enter your new password" required>
        </div>
        <div id="strengthMessage"></div>
        <button type="submit" class="btn btn-primary w-100">Reset Password</button>
    </form>
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

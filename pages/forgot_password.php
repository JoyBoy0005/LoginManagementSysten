<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include '../init.php';

    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);

    if (!empty($email)) {
        $db = new Database();
        $user = $db->fetch("SELECT * FROM users WHERE email = ?", [$email]);

        if ($user) {
            // Generate a password reset token
            $resetToken = bin2hex(random_bytes(16));
            $tokenExpiry = date("Y-m-d H:i:s", strtotime("+1 hour")); // Token expires in 1 hour

            // Update the user record with the reset token and expiry
            $db->query("UPDATE users SET reset_token = ?, token_expiry = ? WHERE email = ?", [$resetToken, $tokenExpiry, $email]);

            // Securely send the reset link to the user's email
            $resetLink = "http://localhost/auth2.0/LoginSystemProject/pages/reset_password.php?token=$resetToken";

            $subject = "Password Reset Request";
            $message = "
                <html>
                <head>
                    <title>Password Reset Request</title>
                </head>
                <body>
                    <p>Hello,</p>
                    <p>You can reset your password by clicking the link below:</p>
                    <p><a href='$resetLink' style='color: #3a6073; text-decoration: underline;'>Reset Password</a></p>
                    <p>This link will expire in 1 hour.</p>
                    <p>This is just for demo purposes.</p>
                </body>
                </html>
            ";
            $headers = "From: no-reply@yourdomain.com\r\n";
            $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

            if (mail($email, $subject, $message, $headers)) {
                $message = "A password reset link has been sent to your email.";
            } else {
                $error = "Failed to send the email. Please try again.";
            }
        } else {
            $error = "Email not found.";
        }
    } else {
        $error = "Please enter a valid email address.";
    }
}
?>


<?php include '../templates/header.php'; ?>
<style>
    body {
        background: linear-gradient(135deg, #3a6073, #16222a);
        min-height: 100vh;
        font-family: 'Roboto', sans-serif;
        margin: 0;
    }

    .forgot-password-container {
        width: 100%;
        max-width: 450px;
        background: #ffffff;
        width: 100%;
        padding-left: 2%;
        padding-right: 2%;
        padding-top: 2%;
        padding-bottom: 2%;
        border-radius: 15px;
        box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.2);
        animation: fadeIn 0.5s ease-out;
        overflow-wrap: break-word; /* Ensure text does not overflow */
        word-wrap: break-word;
        word-break: break-word;
        margin: auto;
    }

    .forgot-password-container h3 {
        margin-bottom: 20px;
        font-weight: bold;
        color: #3a6073;
        text-align: center;
    }

    .form-label {
        color: #3a6073;
        font-weight: 500;
    }

    .form-control {
        border-radius: 10px;
        padding: 10px;
        text-align: center;
        max-width: 100%; /* Prevent input fields from exceeding container width */
    }

    .form-control:focus {
        border-color: #3a6073;
        box-shadow: 0 0 10px rgba(58, 96, 115, 0.3);
    }

    .btn-primary {
        background: #3a6073;
        border: none;
        border-radius: 25px;
        padding: 12px;
        font-size: 1rem;
        transition: background 0.3s ease, transform 0.2s ease;
        max-width: 100%; /* Ensure button stays within the form */
    }

    .btn-primary:hover {
        background: #16222a;
        transform: scale(1.05);
        color: #ffffff;
    }

    .alert {
        word-wrap: break-word; /* Prevent alert messages from overflowing */
        overflow-wrap: break-word;
        word-break: break-word;
        text-align: center;
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

<div class="forgot-password-container">
    <h3>Forgot Password</h3>
    <?php if (isset($error)) { echo "<div class='alert alert-danger'>$error</div>"; } ?>
    <?php if (isset($message)) { echo "<div class='alert alert-success'>$message</div>"; } ?>
    <form method="POST">
        <div class="mb-3">
            <label for="email" class="form-label">Email Address</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Send Reset Link</button>
    </form>
</div>
<?php include '../templates/footer.php'; ?>

<?php
include '../init.php';

// Generate a reset token
$resetToken = bin2hex(random_bytes(16));
$hashedToken = hash('sha256', $resetToken); // Hash the token for security
$tokenExpiry = date("Y-m-d H:i:s", strtotime("+1 hour")); // Token valid for 1 hour

// Update the database with the token
$db = new Database();
$db->query("UPDATE users SET reset_token = ?, token_expiry = ? WHERE email = ?", [$hashedToken, $tokenExpiry, 'dummy@example.com']);

// Print the reset link
$resetLink = "http://localhost/auth2.0/LoginSystemProject/pages/reset_password.php?token=$resetToken";
echo "Reset Link: $resetLink";

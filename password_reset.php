<?php
require_once 'email_config.php';

function sendPasswordResetEmail($userEmail, $resetToken) {
    $resetLink = "https://admin.olpp.org/reset_password.php?token=" . $resetToken;
    $subject = "Password Reset Request - OLPP";
    $body = "
    <h2>Password Reset Request</h2>
    <p>Dear user,</p>
    <p>We received a request to reset your password. If you didn't make this request, you can ignore this email.</p>
    <p>To reset your password, click on the following link:</p>
    <p><a href='$resetLink'>Reset Password</a></p>
    <p>This link will expire in 1 hour for security reasons.</p>
    <p>Thank you for using our service!</p>
    ";

    return sendEmail($userEmail, $subject, $body);
}
?>
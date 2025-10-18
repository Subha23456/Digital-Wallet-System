<?php
require_once 'database.php';  // Now including database.php
session_start();

$msg = '';  // Initialize feedback message

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    /* === 1.  Grab and sanitize form input === */
    $email       = trim($_POST['email']       ?? '');
    $password    = trim($_POST['password']    ?? '');
    $otp         = trim($_POST['otp']         ?? '');
    $fingerprint = trim($_POST['fingerprint'] ?? '');

    /* === 2.  Fetch user from the table === */
    $stmt = $pdo->prepare("SELECT * FROM securityuser WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    /* === 3.  Authentication chain === */
    if ($user && password_verify($password, $user['password'])) {

        if ($otp === $user['otp']) {

            if (hash('sha256', $fingerprint) === $user['biometric_hash']) {
                /** SUCCESS **/
                $_SESSION['uid']  = $user['id'];
                $_SESSION['role'] = $user['role'];

                // Log activity
                $activity = 'User logged in successfully';
                $log = $pdo->prepare("INSERT INTO activity_log (user_id, activity) VALUES (?, ?)");
                $log->execute([$_SESSION['uid'], $activity]);

                header('Location: dashboard.php');
                exit;
            }
            $msg = 'Biometric authentication failed.';
        } else {
            $msg = 'Invalid OTP code.';
        }

    } else {
        $msg = 'Invalid login credentials.';
    }

    /* === 4.  Log failed login attempts to fraud_logs === */
    $log = $pdo->prepare("INSERT INTO fraud_logs (user_id, activity, timestamp) VALUES (?, ?, NOW())");
    $log->execute([$user['id'] ?? null, $msg]);
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Secure Login</title>
<style>
    /* same CSS as before */
</style>
</head>
<body>
<div class="card">
    <h2>🔒 Secure Login</h2>
    <?php if($msg): ?>
        <div class="alert bad"><?= htmlspecialchars($msg) ?></div>
    <?php endif; ?>
    <form method="post">
        <label>Email</label>
        <input type="email" name="email" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <label>OTP (2-Factor Code)</label>
        <input type="text" name="otp" maxlength="6" required>

        <label>Fingerprint ID</label>
        <input type="text" name="fingerprint" required>

        <button class="btn" type="submit">Login Securely</button>
    </form>
</div>
</body>
</html>

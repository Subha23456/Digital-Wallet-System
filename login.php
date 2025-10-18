<?php
session_start();
include 'db_connect.php';

$email = $password = "";
$login_error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Get user data from database
    $stmt = $conn->prepare("SELECT id, email, password, balance FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($user_id, $user_email, $hashed_password, $balance);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            // Correct login
            $_SESSION['user_id'] = $user_id;
            $_SESSION['email'] = $user_email;
            $_SESSION['balance'] = $balance;

            header("Location: dashboard.html");
            exit();
        } else {
            $login_error = "Invalid password.";
        }
    } else {
        $login_error = "No account found with this email.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!-- Login Page HTML -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login | Digital Wallet</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: url("linking.png") no-repeat center center;
      background-size: cover;
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .container {
      background-color: rgba(255, 255, 255, 0.9);
      padding: 30px;
      border-radius: 8px;
      max-width: 400px;
      width: 100%;
      box-shadow: 0 0 10px rgba(0,0,0,0.2);
    }
    h2 {
      text-align: center;
      margin-bottom: 20px;
    }
    input, button {
      width: 100%;
      padding: 10px;
      margin-bottom: 15px;
      border-radius: 5px;
      border: 1px solid #ddd;
    }
    button {
      background: #28a745;
      color: #fff;
      border: none;
    }
    button:hover {
      background: #218838;
    }
    .error {
      color: red;
      text-align: center;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Login to Digital Wallet</h2>
    <?php if ($login_error): ?>
      <div class="error"><?php echo $login_error; ?></div>
    <?php endif; ?>
    <form method="POST" action="dashboard.html">
      <input type="email" name="email" placeholder="Email" required value="<?php echo htmlspecialchars($email); ?>">
      <input type="password" name="password" placeholder="Password" required>
      <button type="submit">Login</button>
    </form>
    <p style="text-align:center;">Don't have an account? <a href="create_wallet.php">Create Wallet</a></p>
  </div>
</body>
</html>

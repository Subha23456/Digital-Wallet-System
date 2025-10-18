<?php
include 'db_connect.php';
session_start();

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT * FROM admin_users WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $_SESSION["admin_logged_in"] = true;
        $_SESSION["admin_username"] = $username;
        header("Location: dashboard.php");
        exit();
    } else {
        $message = "❌ Invalid credentials.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Admin Login</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(to right, #74ebd5, #acb6e5);
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
    }
    .login-box {
      background: white;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 10px 20px rgba(0,0,0,0.15);
      width: 350px;
    }
    h2 {
      text-align: center;
      color: #007bff;
    }
    form input {
      width: 100%;
      padding: 10px;
      margin-top: 15px;
      border: 1px solid #ccc;
      border-radius: 6px;
    }
    button {
      width: 100%;
      padding: 12px;
      background: #007bff;
      color: white;
      border: none;
      margin-top: 20px;
      border-radius: 6px;
      font-weight: bold;
      cursor: pointer;
    }
    .message {
      margin-top: 15px;
      text-align: center;
      color: red;
    }
  </style>
</head>
<body>
  <div class="login-box">
    <h2>Admin Login</h2>
    <?php if (!empty($message)): ?>
      <div class="message"><?= $message ?></div>
    <?php endif; ?>
    <form method="POST" action="admindashboard.html">
      <input type="text" name="username" placeholder="Username" required />
      <input type="password" name="password" placeholder="Password" required />
      <button type="submit">Login</button>
    </form>
  </div>
</body>
</html>

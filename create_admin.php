<?php
session_start();

if (!isset($_SESSION["admin_logged_in"])) {
    header("Location: admin_login.php");
    exit();
}
$username = $_SESSION["admin_username"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      margin: 0;
      background: #f7f9fc;
    }
    .navbar {
      background-color: #007bff;
      color: white;
      padding: 20px;
      display: flex;
      justify-content: space-between;
    }
    .navbar h1 {
      margin: 0;
    }
    .logout-btn {
      padding: 8px 16px;
      background: white;
      color: #007bff;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      font-weight: bold;
    }
    .content {
      padding: 40px;
    }
    .card {
      background: white;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
  </style>
</head>
<body>
  <div class="navbar">
    <h1>Admin Dashboard</h1>
    <form method="post" action="logout.php">
      <button class="logout-btn">Logout</button>
    </form>
  </div>

  <div class="content">
    <div class="card">
      <h2>Welcome, <?= htmlspecialchars($username) ?> 👋</h2>
      <p>This is your dashboard. Add controls, transactions, and wallet analytics here.</p>
    </div>
  </div>
</body>
</html>

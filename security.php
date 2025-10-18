<?php
session_start();

// Security logic
$is_https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443;
$is_logged_in = isset($_SESSION['user_id']); // Your logic here
$secure = $is_https && $is_logged_in;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Security Check</title>
  <style>
    * {
      box-sizing: border-box;
    }

    body, html {
      margin: 0;
      padding: 0;
      height: 100%;
      font-family: Arial, sans-serif;
      background-color: #f0f9ff;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    #loader {
      position: fixed;
      top: 0;
      left: 0;
      width: 100vw;
      height: 100vh;
      background-color: white;
      display: flex;
      justify-content: center;
      align-items: center;
      z-index: 999;
    }

    #loader img {
      width: 900px;
      height: auto;
    }

    #result {
      display: none;
      text-align: center;
      padding: 40px;
      background: white;
      border-radius: 12px;
      box-shadow: 0 0 20px rgba(0,0,0,0.1);
      max-width: 400px;
    }

    #result h2 {
      color: #2e7d32;
    }

    #dashboardBtn {
      background-color: #4CAF50;
      color: white;
      border: none;
      padding: 12px 24px;
      font-size: 1rem;
      border-radius: 5px;
      cursor: pointer;
      margin-top: 20px;
    }

    #dashboardBtn:hover {
      background-color: #388e3c;
    }
  </style>
</head>
<body>

<!-- Fullscreen Loader -->
<div id="loader">
  <img src="security.gif" alt="Loading...">
</div>

<!-- Result Message -->
<div id="result">
  <?php if ($secure): ?>
    <h2>✅ Your Security is Strong!</h2>
    <p>You are connected securely and logged in.</p>
  <?php else: ?>
    <h2>⚠️ Security Warning</h2>
    <p>Please check your HTTPS connection or login status.</p>
  <?php endif; ?>

  <form action="dashboard.html">
    <button id="dashboardBtn" type="submit">Go to Dashboard</button>
  </form>
</div>

<script>
  // Wait 3 seconds, hide loader, show message
  window.onload = function() {
    setTimeout(() => {
      document.getElementById('loader').style.display = 'none';
      document.getElementById('result').style.display = 'block';
    }, 3000);
  };
</script>

</body>
</html>

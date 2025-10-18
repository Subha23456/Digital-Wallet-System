<?php
$payment_success = true; // You would check this from your payment logic
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Payment Successful</title>
  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: 'Segoe UI', sans-serif;
      background-color: #f0f9ff;
      height: 100vh;
      overflow: hidden;
    }

    /* Fullscreen Loader Overlay */
    #loaderOverlay {
      position: fixed;
      top: 0;
      left: 0;
      height: 100%;
      width: 100%;
      background-color: #fff;
      display: flex;
      justify-content: center;
      align-items: center;
      z-index: 9999;
    }

    #loaderOverlay img {
      width: 1000px;
      height: auto;
    }

    .container {
      display: none;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .content-box {
      text-align: center;
      padding: 40px;
      border-radius: 10px;
      background: white;
      box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
      max-width: 400px;
    }

    .success-text {
      font-size: 1.5rem;
      color: #2e7d32;
    }

    .go-dashboard {
      margin-top: 20px;
    }

    .go-dashboard button {
      background-color: #4CAF50;
      border: none;
      padding: 12px 24px;
      font-size: 1rem;
      color: white;
      border-radius: 5px;
      cursor: pointer;
    }

    .go-dashboard button:hover {
      background-color: #388e3c;
    }
  </style>
</head>
<body>

  <!-- Fullscreen Loader GIF -->
  <div id="loaderOverlay">
    <img src="payment.gif" alt="Loading...">
  </div>

  <!-- Success Content -->
  <div class="container" id="successContainer">
    <div class="content-box">
      <div class="success-text">✅ Payment Successful!</div>
      <div class="go-dashboard">
        <form action="dashboard.html" method="POST">
          <button type="submit">Go to Dashboard</button>
        </form>
      </div>
    </div>
  </div>

  <script>
    // Wait 2.5 seconds, hide loader, show success content
    setTimeout(() => {
      document.getElementById("loaderOverlay").style.display = "none";
      document.getElementById("successContainer").style.display = "flex";
    }, 2500);
  </script>

</body>
</html>

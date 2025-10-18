<?php
include 'db_connect.php';

$success = false;
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $bank_name = $_POST['bankName'];
    $account_number = $_POST['accountNumber'];
    $expiry_date = $_POST['expridate'];

    $stmt = $conn->prepare("INSERT INTO bank_accounts (bank_name, account_number, expiry_date) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $bank_name, $account_number, $expiry_date);

    if ($stmt->execute()) {
        $success = true;
    } else {
        $message = "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Link Bank Account</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-image: url("linking.png");
      background-size: cover;
      background-repeat: no-repeat;
      color: #333;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }

    .container {
      background: rgba(255, 255, 255, 0.9);
      padding: 30px;
      border-radius: 8px;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
      max-width: 400px;
      width: 100%;
      z-index: 1;
      display: none;
      text-align: center;
    }

    form {
      display: flex;
      flex-direction: column;
    }

    input, button {
      padding: 10px;
      margin-bottom: 15px;
      border: 1px solid #ddd;
      border-radius: 5px;
      font-size: 1rem;
    }

    button {
      background: #4CAF50;
      color: #fff;
      border: none;
      cursor: pointer;
    }

    button:hover {
      background: #45a049;
    }

    .message {
      color: green;
      font-weight: bold;
      margin-top: 15px;
    }

    /* Fullscreen loading overlay */
    #loadingOverlay {
      position: fixed;
      top: 0;
      left: 0;
      height: 100%;
      width: 100%;
      background-color: rgba(255, 255, 255, 0.95);
      display: flex;
      justify-content: center;
      align-items: center;
      z-index: 9999;
    }

    #loadingOverlay img {
      width: 1000px;
      height: auto;
    }

    #successContent {
      display: none;
    }

    .dashboard-button {
      margin-top: 20px;
      padding: 10px 20px;
      background-color: #4CAF50;
      color: white;
      border: none;
      border-radius: 5px;
      font-size: 1rem;
      cursor: pointer;
    }

    .dashboard-button:hover {
      background-color: #388e3c;
    }
  </style>
</head>
<body>

<?php if ($success): ?>
  <!-- Show loading first -->
  <div id="loadingOverlay">
    <img src="bank.gif" alt="Processing...">
  </div>

  <!-- Then show success message -->
  <div class="container" id="successContent">
    <h2>✅ Bank Account Linked Successfully!</h2>
    <form action="dashboard.html" method="POST">
      <button class="dashboard-button" type="submit">Go to Dashboard</button>
    </form>
  </div>

  <script>
    setTimeout(() => {
      // Hide loading and show success content
      document.getElementById('loadingOverlay').style.display = 'none';
      document.getElementById('successContent').style.display = 'block';
    }, 2500); // 2.5 seconds delay
  </script>

<?php else: ?>
  <!-- Show form if not submitted or if there was an error -->
  <div class="container" style="display: block;">
    <h2>Link Bank Account</h2>
    <form method="POST">
      <input type="text" name="bankName" placeholder="Enter bank name" required>
      <input type="text" name="accountNumber" placeholder="Enter account number" required>
      <input type="date" name="expridate" required>
      <button type="submit">Link Bank Account</button>
    </form>
    <?php if (!empty($message)): ?>
      <div class="message"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
  </div>
<?php endif; ?>

</body>
</html>

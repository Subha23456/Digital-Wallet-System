<?php
include 'db_connect.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $recipient = trim($_POST['recipient']);
    $amount = floatval($_POST['amount']);
    $method = $_POST['payment_method'];

    if ($recipient !== "" && $amount > 0 && !empty($method)) {
        $stmt = $conn->prepare("INSERT INTO payments (recipient, amount, method, payment_date) VALUES (?, ?, ?, NOW())");
        $stmt->bind_param("sds", $recipient, $amount, $method);

        if ($stmt->execute()) {
            $message = "✅ Payment to <strong>$recipient</strong> of <strong>₹" . number_format($amount, 2) . "</strong> was successful via <strong>" . ucfirst(str_replace('-', ' ', $method)) . "</strong>.";
        } else {
            $message = "❌ Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        $message = "❌ Please fill out all fields correctly.";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Payment Page</title>
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-image: url("digital.gif");
      background-size: cover;
      background-repeat: no-repeat;
      margin: 0;
      padding: 0;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .payment-container {
      width: 400px;
      background-color: rgba(255, 255, 255, 0.95);
      padding: 25px 30px;
      border-radius: 12px;
      box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
      backdrop-filter: blur(5px);
    }

    h1 {
      text-align: center;
      color: #007bff;
      margin-bottom: 25px;
    }

    form label {
      display: block;
      margin-bottom: 5px;
      font-weight: bold;
    }

    form input, form select, form button {
      width: 100%;
      padding: 10px;
      margin-bottom: 18px;
      border: 1px solid #ccc;
      border-radius: 6px;
      font-size: 1em;
    }

    form input:focus, form select:focus {
      border-color: #007bff;
      outline: none;
    }

    button {
      background-color: #007bff;
      color: white;
      border: none;
      cursor: pointer;
      font-weight: bold;
      transition: background-color 0.3s ease;
    }

    button:hover {
      background-color: #0056b3;
    }

    .message {
      text-align: center;
      margin-bottom: 20px;
      padding: 10px;
      border-radius: 6px;
      font-size: 0.95rem;
    }

    .message.success {
      background-color: #e6ffed;
      color: #2e7d32;
      border: 1px solid #b2dfdb;
    }

    .message.error {
      background-color: #ffe6e6;
      color: #c62828;
      border: 1px solid #ef9a9a;
    }
  </style>
</head>
<body>
  <div class="payment-container">
    <h1>Make a Payment</h1>

    <?php if (!empty($message)): ?>
      <div class="message <?= strpos($message, '✅') !== false ? 'success' : 'error' ?>">
        <?= $message ?>
      </div>
    <?php endif; ?>

    <form method="POST" action="payment_successful.php">
      <label for="recipient">Recipient Name</label>
      <input type="text" id="recipient" name="recipient" placeholder="Enter recipient's name" required>

      <label for="amount">Amount</label>
      <input type="number" id="amount" name="amount" placeholder="Enter payment amount" required step="0.01" min="0.01">

      <label for="payment_method">Payment Method</label>
      <select id="payment_method" name="payment_method" required>
        <option value="" disabled selected>Select payment method</option>
        <option value="credit-card">Credit Card</option>
        <option value="debit-card">Debit Card</option>
        <option value="wallet-balance">Wallet Balance</option>
      </select>

      <button type="submit">Pay Now</button>
    </form>
  </div>
</body>
</html>

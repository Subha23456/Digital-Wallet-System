<?php
include 'db_connect.php';

// Handle form submission and insert transaction
$message = "";
$transactions = [];
$user_id = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $transaction_type = $_POST['transaction_type'] ?? '';
    $amount = floatval($_POST['amount'] ?? 0);
    $user_id = intval($_POST['user_id'] ?? 0);

    if (!empty($transaction_type) && $amount > 0 && $user_id > 0) {
        // Get current balance
        $balance = 0;
        $stmt = $conn->prepare("SELECT balance_after_transaction FROM transactions WHERE user_id = ? ORDER BY id DESC LIMIT 1");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->bind_result($last_balance);
        if ($stmt->fetch()) {
            $balance = $last_balance;
        }
        $stmt->close();

        // Calculate new balance
        if ($transaction_type === "deposit") {
            $new_balance = $balance + $amount;
        } else {
            $message = "Unsupported transaction type.";
        }

        // Insert transaction
        if (!isset($message) || $message == "") {
            $stmt = $conn->prepare("INSERT INTO transactions (user_id, transaction_type, amount, balance_after_transaction, transaction_date) VALUES (?, ?, ?, ?, NOW())");
            $stmt->bind_param("isdd", $user_id, $transaction_type, $amount, $new_balance);
            if ($stmt->execute()) {
                $message = "Transaction successful. New balance: $" . number_format($new_balance, 2);
            } else {
                $message = "Error: " . $stmt->error;
            }
            $stmt->close();
        }
    } else {
        $message = "Please fill in all fields with valid data.";
    }
}

// Get transaction history
if (isset($user_id) && $user_id > 0) {
    $stmt = $conn->prepare("SELECT transaction_date, transaction_type, amount, balance_after_transaction FROM transactions WHERE user_id = ? ORDER BY transaction_date DESC");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $transactions = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Digital Wallet - Transaction Page</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-image: url("trasaction.webp");
      background-size: cover;
      background-repeat: no-repeat;
      color:#333;
      margin: 0;
      padding: 0;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .container {
      width: 100%;
      max-width: 900px;
      background-color: white;
      border-radius: 8px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      padding: 30px;
      margin-top: 20px;
    }

    h1 {
      text-align: center;
      color: #333;
    }

    .transaction-form-container, .transaction-history-container {
      margin-top: 30px;
    }

    .transaction-form-container h2 {
      color: #333;
      font-size: 1.5rem;
      margin-bottom: 10px;
    }

    .transaction-form-container label {
      font-size: 1rem;
      color: #555;
      margin-bottom: 5px;
      display: block;
    }

    input[type="number"], select, button {
      width: 100%;
      padding: 10px;
      font-size: 1rem;
      margin-bottom: 15px;
      border: 1px solid #ddd;
      border-radius: 4px;
    }

    button {
      background-color: #4CAF50;
      color: white;
      border: none;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    button:hover {
      background-color: #45a049;
    }

    .transaction-history-table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }

    .transaction-history-table th, .transaction-history-table td {
      padding: 10px;
      text-align: center;
      border: 1px solid #ddd;
    }

    .transaction-history-table th {
      background-color: #f4f7fc;
    }

    .transaction-history-table tr:nth-child(even) {
      background-color: #f9f9f9;
    }

    .message {
      padding: 10px;
      background-color: #e7f7e7;
      border: 1px solid #b2d8b2;
      color: #2e7d32;
      border-radius: 5px;
      margin-bottom: 20px;
      text-align: center;
    }

    .form-footer {
      text-align: center;
      color: #888;
      font-size: 0.9rem;
    }

    .form-footer a {
      color: #4CAF50;
      text-decoration: none;
    }
  </style>
</head>
<body>
<div class="container">
  <h1>Digital Wallet - Transaction Page</h1>

  <?php if (!empty($message)) echo "<div class='message'>$message</div>"; ?>

  <div class="transaction-form-container">
    <form method="post" action="transaction_successful.php">
      <h2>Make a Transaction</h2>

      <label for="transaction_type">Transaction Type:</label>
      <select name="transaction_type" id="transaction_type" required>
        <option value="deposit">Deposit</option>
      </select>

      <label for="amount">Amount:</label>
      <input type="number" name="amount" id="amount" required step="0.01" min="0.01">

      <label for="user_id">User ID:</label>
      <input type="number" name="user_id" id="user_id" required>

      <button type="submit">Submit Transaction</button>
    </form>
  </div>

  <hr>

  <div class="transaction-history-container">
    <h2>Transaction History</h2>
    <table class="transaction-history-table">
      <thead>
        <tr>
          <th>Date</th>
          <th>Transaction Type</th>
          <th>Amount</th>
          <th>Balance After Transaction</th>
        </tr>
      </thead>
      <tbody>
      <?php if (!empty($transactions)): ?>
        <?php foreach ($transactions as $t): ?>
          <tr>
            <td><?= htmlspecialchars($t['transaction_date']) ?></td>
            <td><?= ucfirst(htmlspecialchars($t['transaction_type'])) ?></td>
            <td>$<?= number_format($t['amount'], 2) ?></td>
            <td>$<?= number_format($t['balance_after_transaction'], 2) ?></td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr><td colspan="4">No transactions found.</td></tr>
      <?php endif; ?>
      </tbody>
    </table>
  </div>

  <div class="form-footer">
    <p>&copy; 2025 Digital Wallet. All Rights Reserved.</p>
  </div>
</div>
</body>
</html>

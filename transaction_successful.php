<?php
$new_balance = isset($_GET['balance']) ? floatval($_GET['balance']) : 0.00;
$user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deposit Successful - Digital Wallet</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url("reciveingMoney.jpg");
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            width: 100%;
            max-width: 800px;
            background-color: transparent;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 30px;
            text-align: center;
        }

        h1 {
            color: #4CAF50;
            font-size: 2rem;
            margin-bottom: 20px;
        }

        .message-container {
            background-color: #e8f5e9;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .message-container p {
            font-size: 1.2rem;
            color: #333;
        }

        .balance-container {
            background-color: #f4f7fc;
            padding: 20px;
            border-radius: 8px;
            margin-top: 20px;
        }

        .balance-container h3 {
            font-size: 1.5rem;
            color: #333;
        }

        .balance-amount {
            font-size: 2rem;
            font-weight: bold;
            color: #4CAF50;
            margin-top: 10px;
        }

        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 1rem;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 20px;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #45a049;
        }

        .form-footer {
            text-align: center;
            color: #888;
            font-size: 0.9rem;
            margin-top: 20px;
        }

        .form-footer a {
            color: #4CAF50;
            text-decoration: none;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Deposit Successful!</h1>

    <div class="message-container">
        <p>Your deposit was successfully processed. The amount has been added to your account balance.</p>
    </div>

    <div class="balance-container">
        <h3>Your Updated Balance:</h3>
        <div class="balance-amount">
            $<?php echo number_format($new_balance, 2); ?>
        </div>
    </div>

    <button onclick="window.location.href='transactions.php'">Make Another Deposit</button>

    <div class="form-footer">
        <p>&copy; 2025 Digital Wallet. All Rights Reserved.</p>
    </div>
</div>

</body>
</html>

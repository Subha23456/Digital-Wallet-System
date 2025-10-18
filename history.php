<?php
session_start();
include("db_connect.php");

// Simulating a logged-in user
$user_id = $_SESSION['user_id'] ?? 1; // replace 1 with actual login logic

$sql = "SELECT * FROM transactions WHERE user_id = ? ORDER BY date DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Transaction History</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h2>Transaction History</h2>
        <table>
            <thead>
                <tr>
                    <th>Txn ID</th>
                    <th>Type</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['transaction_id']) ?></td>
                    <td><?= htmlspecialchars($row['type']) ?></td>
                    <td>$<?= number_format($row['amount'], 2) ?></td>
                    <td><?= htmlspecialchars($row['status']) ?></td>
                    <td><?= htmlspecialchars($row['date']) ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

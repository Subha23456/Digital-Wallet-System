<?php
include 'db_connect.php';

$email = $password = $confirmPassword = "";
$error_message = $success_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    if ($password !== $confirmPassword) {
        $error_message = "Passwords do not match!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Invalid email format!";
    } else {
        // Hash password
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Check if email already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error_message = "An account with this email already exists.";
        } else {
            // Insert into users table
            $stmt = $conn->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
            $stmt->bind_param("ss", $email, $hashedPassword);
            if ($stmt->execute()) {
                $success_message = "Wallet created successfully! You can now <a href='dashboard.php'>login</a>.";
            } else {
                $error_message = "Something went wrong: " . $stmt->error;
            }
        }

        $stmt->close();
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Create Wallet</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-image: url("linking.png");
      background-size: cover;
      background-repeat: no-repeat;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }
    .container {
      background: transparent;
      padding: 30px;
      border-radius: 8px;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
      max-width: 400px;
      width: 100%;
    }
    h2 {
      text-align: center;
      margin-bottom: 20px;
    }
    input {
      width: 100%;
      padding: 10px;
      margin-bottom: 15px;
      border: 1px solid #ddd;
      border-radius: 5px;
    }
    button {
      width: 100%;
      padding: 10px;
      background: #007bff;
      color: #fff;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }
    button:hover {
      background: #0056b3;
    }
    .message {
      text-align: center;
      font-weight: bold;
      color: red;
    }
    .success {
      color: green;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Create Wallet</h2>

    <!-- Show Messages -->
    <?php if ($error_message): ?>
      <div class="message"><?php echo $error_message; ?></div>
    <?php elseif ($success_message): ?>
      <div class="message success"><?php echo $success_message; ?></div>
    <?php endif; ?>

    <form method="POST" action="dashboard.html">
      <input type="email" name="email" placeholder="Enter your email" required value="<?php echo htmlspecialchars($email); ?>">
      <input type="password" name="password" placeholder="Create password" required>
      <input type="password" name="confirmPassword" placeholder="Confirm password" required>
      <button type="submit">Create Wallet</button>
    </form>
  </div>
</body>
</html>

<?php
require_once 'vendor/autoload.php';  // Twilio SDK
include('db.php');  // Include the database connection

use Twilio\Rest\Client;

// Twilio credentials
$sid = 'your_account_sid';  
$auth_token = 'your_auth_token';  
$twilio_phone_number = 'your_twilio_phone_number';  

// Function to send SMS
function sendSmsNotification($to, $message) {
    global $sid, $auth_token, $twilio_phone_number;

    $client = new Client($sid, $auth_token);

    try {
        // Send SMS
        $client->messages->create(
            $to,
            [
                'from' => $twilio_phone_number,
                'body' => $message
            ]
        );
        echo "Message sent successfully!";
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Fetch all users from the database
function getUsers($conn) {
    $sql = "SELECT * FROM users";  // SQL query to fetch all users
    $result = $conn->query($sql);
    $users = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;  // Add each user to the users array
        }
    }

    return $users;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['user_id'], $_POST['message'])) {
    $user_id = $_POST['user_id'];
    $message = $_POST['message'];

    // Get the user's phone number from the database
    $stmt = $conn->prepare("SELECT phone_number FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);  // Bind the user_id parameter
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        // Send SMS to the selected user's phone number
        sendSmsNotification($user['phone_number'], $message);
    } else {
        echo "User not found!";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send SMS Notification</title>
</head>
<body>
    <h1>Send Notification to User</h1>
    
    <form method="POST">
        <label for="user_id">Select User:</label>
        <select id="user_id" name="user_id" required>
            <option value="">--Select User--</option>
            <?php
            // Fetch all users from the database and display in the dropdown
            $users = getUsers($conn);
            foreach ($users as $user) {
                echo "<option value='{$user['id']}'>{$user['name']} ({$user['phone_number']})</option>";
            }
            ?>
        </select><br><br>
        
        <label for="message">Message:</label>
        <textarea id="message" name="message" required></textarea><br><br>
        
        <button type="submit">Send Message</button>
    </form>
</body>
</html>

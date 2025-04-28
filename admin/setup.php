<?php
require_once '../config/database.php';

// Function to create user
function createUser($conn, $username, $password) {
    $sql = "SELECT id FROM users WHERE username = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows == 0) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
            if ($stmt = $conn->prepare($sql)) {
                $stmt->bind_param("ss", $username, $hashed_password);
                if ($stmt->execute()) {
                    return true;
                }
            }
        }
        $stmt->close();
    }
    return false;
}

// Create users
$users = [
    ['username' => 'jvirocel', 'password' => 'P@ssw0rd'],
    ['username' => 'janjan', 'password' => 'thatsmyname']
];

$created_users = [];
$existing_users = [];

foreach ($users as $user) {
    if (createUser($conn, $user['username'], $user['password'])) {
        $created_users[] = $user['username'];
    } else {
        $existing_users[] = $user['username'];
    }
}

// Display results
echo "<h2>Setup Results</h2>";

if (!empty($created_users)) {
    echo "<p>Successfully created accounts for:</p>";
    echo "<ul>";
    foreach ($created_users as $username) {
        echo "<li>Username: $username</li>";
    }
    echo "</ul>";
}

if (!empty($existing_users)) {
    echo "<p>These accounts already exist:</p>";
    echo "<ul>";
    foreach ($existing_users as $username) {
        echo "<li>Username: $username</li>";
    }
    echo "</ul>";
}

echo "<p>You can now <a href='login.php'>login</a> to your admin dashboard.</p>";
echo "<p>Please delete this file after setup is complete.</p>";

$conn->close();
?> 
<?php
session_start();
require_once '../config/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password'];
    
    $sql = "SELECT id, username, password FROM users WHERE username = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row['password'])) {
                $_SESSION['loggedin'] = true;
                $_SESSION['id'] = $row['id'];
                $_SESSION['username'] = $row['username'];
                header("location: dashboard.php");
                exit;
            } else {
                $login_err = "Invalid username or password.";
            }
        } else {
            $login_err = "Invalid username or password.";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        .login-container {
            max-width: 400px;
            margin: 100px auto;
            padding: 2rem;
            background-color: var(--white);
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .login-form {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        .login-form input {
            padding: 0.8rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
        }
        .login-form button {
            background-color: var(--primary-color);
            color: white;
            padding: 0.8rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
        }
        .login-form button:hover {
            background-color: var(--secondary-color);
        }
        .error-message {
            color: #dc3545;
            margin-bottom: 1rem;
        }
        .login-footer {
            margin-top: 1rem;
            text-align: center;
        }
        .back-home-btn {
            display: inline-block;
            padding: 0.5rem 1rem;
            background-color: var(--light-bg);
            color: var(--text-color);
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        .back-home-btn:hover {
            background-color: var(--primary-color);
            color: white;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Admin Login</h2>
        <?php 
        if (!empty($login_err)) {
            echo '<div class="error-message">' . $login_err . '</div>';
        }        
        ?>
        <form class="login-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        <div class="login-footer">
            <a href="../index.php" class="back-home-btn">Back to Home</a>
        </div>
    </div>
</body>
</html> 
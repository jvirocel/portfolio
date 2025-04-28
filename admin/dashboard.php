<?php
session_start();
require_once '../config/database.php';

// Check if user is logged in
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $section = $conn->real_escape_string($_POST['section']);
    $key = $conn->real_escape_string($_POST['key']);
    $value = $conn->real_escape_string($_POST['value']);
    
    $sql = "INSERT INTO content (section, content_key, content_value) 
            VALUES (?, ?, ?) 
            ON DUPLICATE KEY UPDATE content_value = VALUES(content_value)";
            
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("sss", $section, $key, $value);
        $stmt->execute();
        $stmt->close();
        $success_message = "Content updated successfully!";
    }
}

// Fetch current content
$sql = "SELECT * FROM content ORDER BY section, content_key";
$result = $conn->query($sql);
$content = [];
while($row = $result->fetch_assoc()) {
    $content[$row['section']][$row['content_key']] = $row['content_value'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portfolio Admin Dashboard</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .dashboard-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }
        .admin-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }
        .admin-nav {
            display: flex;
            gap: 1rem;
        }
        .section-editor {
            background-color: var(--white);
            padding: 1.5rem;
            border-radius: 10px;
            margin-bottom: 2rem;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .editor-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }
        .content-form {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        .content-form textarea {
            min-height: 150px;
            padding: 0.8rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
            resize: vertical;
        }
        .success-message {
            background-color: #d4edda;
            color: #155724;
            padding: 1rem;
            border-radius: 5px;
            margin-bottom: 1rem;
        }
        .logout-btn {
            background-color: #dc3545;
            color: white;
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }
        .logout-btn:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <div class="admin-header">
            <h1>Portfolio Admin Dashboard</h1>
            <div class="admin-nav">
                <a href="../index.php" target="_blank" class="submit-btn">View Site</a>
                <a href="logout.php" class="logout-btn">Logout</a>
            </div>
        </div>

        <?php 
        if (!empty($success_message)) {
            echo '<div class="success-message">' . $success_message . '</div>';
        }
        ?>

        <!-- About Section Editor -->
        <div class="section-editor">
            <h2>Edit About Section</h2>
            <form class="content-form" method="post">
                <input type="hidden" name="section" value="about">
                <input type="hidden" name="key" value="content">
                <textarea name="value" placeholder="About content..."><?php echo isset($content['about']['content']) ? htmlspecialchars($content['about']['content']) : ''; ?></textarea>
                <button type="submit" class="submit-btn">Update About Section</button>
            </form>
        </div>

        <!-- Experience Section Editor -->
        <div class="section-editor">
            <h2>Edit Experience</h2>
            <div class="editor-grid">
                <!-- MIS Experience -->
                <form class="content-form" method="post">
                    <input type="hidden" name="section" value="experience">
                    <input type="hidden" name="key" value="mis">
                    <h3>MIS Experience</h3>
                    <textarea name="value" placeholder="MIS experience details..."><?php echo isset($content['experience']['mis']) ? htmlspecialchars($content['experience']['mis']) : ''; ?></textarea>
                    <button type="submit" class="submit-btn">Update MIS Experience</button>
                </form>

                <!-- Statistician Experience -->
                <form class="content-form" method="post">
                    <input type="hidden" name="section" value="experience">
                    <input type="hidden" name="key" value="statistician">
                    <h3>Statistician Experience</h3>
                    <textarea name="value" placeholder="Statistician experience details..."><?php echo isset($content['experience']['statistician']) ? htmlspecialchars($content['experience']['statistician']) : ''; ?></textarea>
                    <button type="submit" class="submit-btn">Update Statistician Experience</button>
                </form>
            </div>
        </div>

        <!-- Education Section Editor -->
        <div class="section-editor">
            <h2>Edit Education</h2>
            <div class="editor-grid">
                <!-- Masters Education -->
                <form class="content-form" method="post">
                    <input type="hidden" name="section" value="education">
                    <input type="hidden" name="key" value="masters">
                    <h3>Master's Degree</h3>
                    <textarea name="value" placeholder="Master's degree details..."><?php echo isset($content['education']['masters']) ? htmlspecialchars($content['education']['masters']) : ''; ?></textarea>
                    <button type="submit" class="submit-btn">Update Master's Education</button>
                </form>

                <!-- Bachelor's Education -->
                <form class="content-form" method="post">
                    <input type="hidden" name="section" value="education">
                    <input type="hidden" name="key" value="bachelors">
                    <h3>Bachelor's Degree</h3>
                    <textarea name="value" placeholder="Bachelor's degree details..."><?php echo isset($content['education']['bachelors']) ? htmlspecialchars($content['education']['bachelors']) : ''; ?></textarea>
                    <button type="submit" class="submit-btn">Update Bachelor's Education</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html> 
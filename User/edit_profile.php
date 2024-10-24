<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}
include '../db.php';
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
if ($user = $result->fetch_assoc()) {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $new_username = htmlspecialchars($_POST['username']);
        $new_email = htmlspecialchars($_POST['email']);
        $new_password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;
        if ($new_password) {
            $update_stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, password = ? WHERE id = ?");
            $update_stmt->bind_param("sssi", $new_username, $new_email, $new_password, $user_id);
        } else {
            $update_stmt = $conn->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
            $update_stmt->bind_param("ssi", $new_username, $new_email, $user_id);
        }
        if ($update_stmt->execute()) {
            header("Location: profile.php");
            exit();
        } else {
            echo "Error: " . $conn->error;
        }
        $update_stmt->close();
    }
} else {
    echo "User not found.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f0f2f5;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            height: 100vh;
            padding: 20px;
            background: linear-gradient(135deg, #74ebd5, #ACB6E5);
        }
        .container {
            max-width: 600px;
            width: 100%;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }
        h1 {
            font-size: 28px;
            color: #333;
            margin-bottom: 20px;
            text-align: center;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            font-size: 16px;
            color: #555;
            margin-bottom: 5px;
        }
        input {
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            background-color: #f9f9f9;
            transition: border 0.3s ease, box-shadow 0.3s ease;
        }
        input:focus {
            border-color: #74ebd5;
            box-shadow: 0 0 10px rgba(116, 235, 213, 0.5);
            outline: none;
        }
        button {
            padding: 15px;
            background-color: #74ebd5;
            border: none;
            border-radius: 5px;
            color: white;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #ACB6E5;
        }
        a {
            color: #74ebd5;
            text-decoration: none;
            font-weight: 500;
            display: block;
            text-align: center;
            margin-top: 20px;
            transition: color 0.3s ease;
        }
        a:hover {
            color: #ACB6E5;
        }
        @media (max-width: 480px) {
            .container {
                padding: 15px;
            }
            input {
                padding: 12px;
            }
            button {
                padding: 12px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Edit Profile</h1>
        <form method="POST" action="edit_profile.php">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['name']); ?>" required>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            <label for="password">New Password (leave blank to keep current password):</label>
            <input type="password" id="password" name="password">
            <button type="submit">Save Changes</button>
        </form>
        <a href="profile.php">Back to Profile</a>
    </div>
</body>
</html>

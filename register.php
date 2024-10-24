<?php
include 'db.php'; 
session_start();
$success_message = ''; 
$error_message = ''; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST['name']); 
    $email = htmlspecialchars($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); 

    $stmt = $conn->prepare("SELECT email FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $error_message = "Email sudah terdaftar.";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $password);
        if ($stmt->execute()) {
            $success_message = "Registration successful.";
        } else {
            $error_message = "Error: " . $conn->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500&display=swap" rel="stylesheet">
    <title>Register</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #74ebd5, #ACB6E5);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .login-container {
            background-color: #fff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
            transition: transform 0.3s ease;
        }
        .login-container:hover {
            transform: scale(1.05);
        }
        .login-container h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
            font-weight: 500;
        }
        .login-container label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #555;
        }
        .login-container input[type="text"],
        .login-container input[type="email"],
        .login-container input[type="password"] {
            width: 100%;
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            background-color: #f9f9f9;
            transition: border 0.3s ease, box-shadow 0.3s ease;
        }
        .login-container input[type="text"]:focus,
        .login-container input[type="email"]:focus,
        .login-container input[type="password"]:focus {
            border-color: #74ebd5;
            box-shadow: 0 0 10px rgba(116, 235, 213, 0.5);
            outline: none;
        }
        .login-container button {
            width: 100%;
            padding: 15px;
            background-color: #74ebd5;
            border: none;
            border-radius: 5px;
            color: white;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .login-container button:hover {
            background-color: #ACB6E5;
        }
        .login-container p {
            text-align: center;
            margin-top: 20px;
        }
        .login-container p a {
            color: #74ebd5;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }
        .login-container p a:hover {
            color: #ACB6E5;
        }
        .success-message {
            color: green;
            text-align: center;
            margin-bottom: 20px;
            font-weight: 500;
        }
        .error-message {
            color: red;
            text-align: center;
            margin-bottom: 20px;
            font-weight: 500;
        }
        @media (max-width: 480px) {
            .login-container {
                padding: 20px;
            }
            .login-container input[type="text"],
            .login-container input[type="email"],
            .login-container input[type="password"] {
                padding: 12px;
            }
            .login-container button {
                padding: 12px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Register</h2>
        <?php if ($success_message): ?>
            <div class="success-message"><?php echo $success_message; ?></div>
        <?php elseif ($error_message): ?>
            <div class="error-message"><?php echo $error_message; ?></div>
        <?php endif; ?>
        <form method="POST" action="register.php">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <button type="submit">Register</button>
        </form>
        <p>Sudah punya akun? <a href="login.php">Login di sini</a></p>
    </div>
</body>
</html>

<?php
// Start session if needed
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LIBRARY MANAGEMENT - Login</title>
    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background: #f4f7f6;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #333;
        }

        .auth-container {
            background: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            width: 350px;
            text-align: center;
            position: relative;
        }

        .logo h1 {
            font-size: 28px;
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .logo span {
            font-size: 14px;
            color: #7f8c8d;
        }

        .input-group {
            margin-bottom: 20px;
            text-align: left;
        }

        .input-group label {
            font-size: 14px;
            color: #2c3e50;
            display: block;
            margin-bottom: 5px;
        }

        .input-group input,
        .input-group select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
            color: #2c3e50;
            transition: border-color 0.3s ease;
        }

        .input-group input:focus,
        .input-group select:focus {
            border-color: #3498db;
            outline: none;
        }

        button {
            background-color: #3498db;
            color: #ffffff;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            width: 100%;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #2980b9;
        }

        .auth-link {
            margin-top: 20px;
        }

        .auth-link a {
            color: #3498db;
            text-decoration: none;
        }

        .auth-link a:hover {
            text-decoration: underline;
        }

        /* Decorative elements */
        .study-icons {
            position: absolute;
            font-size: 30px;
            color: #3498db;
        }

        .icon-left {
            left: -25px;
            top: 30px;
        }

        .icon-right {
            right: -25px;
            top: 30px;
        }

    </style>
</head>
<body>
    <div class="auth-container">
        <div class="logo">
            <h1>LIBRARY MANAGEMENT</h1>
            <span>Open a book, Open your mind</span>
        </div>
        
        <form action="validate.php" method="POST">
            <div class="input-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required 
                       placeholder="Enter your username">
            </div>

            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required
                       placeholder="Enter your password">
            </div>

            <div class="input-group">
                <label for="user_type">User Type</label>
                <select id="user_type" name="user_type" required>
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                </select>
            </div>

            <button type="submit" name="login">Sign In</button>
        </form>

        <div class="auth-link">
            <p>Don't have an account? <a href="register.php">Register here</a></p>
        </div>

        <!-- Decorative elements -->
        <div class="study-icons icon-right">✏️</div>
        <div class="study-icons icon-left">📚</div>
    </div>
</body>
</html>

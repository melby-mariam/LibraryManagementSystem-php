<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>STUDY MATE - Register</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }

        .auth-container {
            width: 100%;
            max-width: 500px;
            margin: 0 auto;
            padding: 40px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .logo {
            margin-bottom: 20px;
        }

        .logo h1 {
            font-size: 32px;
            color: #4CAF50;
            margin: 0;
        }

        .logo span {
            font-size: 14px;
            color: #777;
        }

        .welcome-text {
            font-size: 18px;
            margin-bottom: 20px;
            color: #555;
        }

        .input-group {
            margin-bottom: 15px;
            text-align: left;
        }

        .input-group label {
            font-size: 16px;
            color: #333;
            margin-bottom: 5px;
            display: block;
        }

        .input-group input, .input-group select {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-top: 5px;
            background-color: #f9f9f9;
        }

        .password-requirements {
            font-size: 12px;
            color: #888;
            margin-top: 5px;
        }

        button {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #45a049;
        }

        .auth-link {
            margin-top: 20px;
            font-size: 14px;
            color: #555;
        }

        .auth-link a {
            color: #4CAF50;
            text-decoration: none;
        }

        .auth-link a:hover {
            text-decoration: underline;
        }

        .study-icons {
            font-size: 48px;
            color: #4CAF50;
            position: absolute;
            opacity: 0.1;
        }

        .icon-left {
            left: 10px;
            bottom: 10px;
        }

        .icon-right {
            right: 10px;
            top: 10px;
        }

        @media (max-width: 768px) {
            .auth-container {
                padding: 30px;
            }

            .logo h1 {
                font-size: 28px;
            }

            .welcome-text {
                font-size: 16px;
            }
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="logo">
            <h1>LIBRARY MANAGEMENT</h1>
            <span>Open a book, Open your mind</span>
        </div>
        
        <div class="welcome-text">
            Create your account and start your learning journey!
        </div>

        <form action="register_user.php" method="POST">
            <div class="input-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required 
                       placeholder="Choose your username" minlength="3" maxlength="20">
            </div>

            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required
                       placeholder="Create a strong password" minlength="8">
                <div class="password-requirements">
                    Password should be at least 8 characters long
                </div>
            </div>

            <div class="input-group">
                <label for="user_type">User Type</label>
                <select id="user_type" name="user_type" required>
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                </select>
            </div>

            <button type="submit" name="register">Create Account</button>
        </form>

        <div class="auth-link">
            <p>Already have an account? <a href="login.php">Login here</a></p>
        </div>

        <!-- Decorative elements -->
        <div class="study-icons icon-right">🎓</div>
        <div class="study-icons icon-left">📝</div>
    </div>
</body>
</html>

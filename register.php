<?php
// Start session if you plan to auto-login, though here we'll just redirect or show success.
require 'db.php'; // Includes the database connection from your existing setup[cite: 4]

$message = "";
$msg_type = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password']; 
    $role = $_POST['role'];

    // 1. Check if the username already exists to prevent duplicates
    $check_stmt = $pdo->prepare("SELECT * FROM Users WHERE username = :username"); // Queries the Users table[cite: 4]
    $check_stmt->execute(['username' => $username]);
    
    if ($check_stmt->rowCount() > 0) {
        $message = "Username already exists. Please choose another.";
        $msg_type = "error";
    } else {
        // 2. Insert the new account into the database
        // Note: Storing plain text password to remain compatible with your current login.php plain-text check[cite: 4]
        $insert_stmt = $pdo->prepare("INSERT INTO Users (username, password, role) VALUES (:username, :password, :role)");
        
        if ($insert_stmt->execute(['username' => $username, 'password' => $password, 'role' => $role])) {
            $message = "Account registered successfully! You can now log in.";
            $msg_type = "success";
        } else {
            $message = "Registration failed. Please try again.";
            $msg_type = "error";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Registration</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; /* Matches existing font[cite: 4] */
        }

        body {
            /* Matches existing animated gradient background[cite: 4] */
            background: linear-gradient(135deg, #74b9ff, #a29bfe, #fdcb6e);
            background-size: 400% 400%;
            animation: gradientBG 15s ease infinite;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .register-card {
            /* Matches existing glassmorphism card style[cite: 4] */
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 16px;
            padding: 40px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
            color: #fff;
        }

        .register-card h2 {
            text-align: center;
            margin-bottom: 25px;
            font-weight: 600;
            letter-spacing: 1px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .input-group {
            margin-bottom: 20px;
        }

        .input-group label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            font-weight: 500;
            text-shadow: 0 1px 2px rgba(0,0,0,0.1);
        }

        .input-group input, .input-group select {
            width: 100%;
            padding: 12px 15px;
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.4);
            border-radius: 8px;
            color: #fff;
            outline: none;
            transition: all 0.3s ease;
        }

        .input-group select option {
            color: #000;
        }

        .input-group input::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }

        .input-group input:focus, .input-group select:focus {
            background: rgba(255, 255, 255, 0.3);
            border-color: rgba(255, 255, 255, 0.8);
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.3);
        }

        .msg {
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
            font-size: 14px;
            color: #fff;
        }

        .error {
            background: rgba(255, 71, 87, 0.3);
            border: 1px solid rgba(255, 71, 87, 0.5); /* Error styling matches login.php[cite: 4] */
        }

        .success {
            background: rgba(46, 213, 115, 0.3);
            border: 1px solid rgba(46, 213, 115, 0.5);
        }

        button {
            width: 100%;
            padding: 12px;
            background: rgba(255, 255, 255, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.5);
            border-radius: 8px;
            color: #fff;
            font-weight: 600;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-shadow: 0 1px 2px rgba(0,0,0,0.1);
            margin-bottom: 15px;
        }

        button:hover {
            background: rgba(255, 255, 255, 0.5);
            box-shadow: 0 0 15px rgba(255, 255, 255, 0.5);
            transform: translateY(-2px);
        }

        .link-text {
            text-align: center;
            display: block;
            color: #fff;
            text-decoration: none;
            font-size: 14px;
        }
        
        .link-text:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="register-card">
        <form method="POST">
            <h2>Register Account</h2>

            <?php if (!empty($message)): ?>
                <div class="msg <?php echo $msg_type; ?>"><?php echo htmlspecialchars($message); ?></div>
            <?php endif; ?>

            <div class="input-group">
                <label>Username:</label>
                <input type="text" name="username" placeholder="Choose a username" required>
            </div>
            
            <div class="input-group">
                <label>Password:</label>
                <input type="password" name="password" placeholder="Create a password" required>
            </div>

            <div class="input-group">
                <label>Account Role:</label>
                <select name="role" required>
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                </select>
            </div>

            <button type="submit">Register</button>
            <a href="login.php" class="link-text">Already have an account? Log In</a>
        </form>
    </div>
</body>
</html>
<?php
session_start();
require 'db.php'; // Includes the database connection

// Security Guard: Check for wristband AND admin status
if (!isset($_SESSION['logged_in']) || $_SESSION['role'] !== 'admin') {
    die("Access Denied. You must be an administrator.");
}

// Fetch all registered accounts from the database
// Updated to lowercase 'users' to perfectly match the SQL dump table structure
$stmt = $pdo->query("SELECT * FROM users");[cite: 13]
$all_accounts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Registered Accounts</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #74b9ff, #a29bfe, #fdcb6e);
            background-size: 400% 400%;
            animation: gradientBG 15s ease infinite;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .admin-card {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 16px;
            padding: 40px;
            width: 100%;
            max-width: 650px;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
            color: #fff;
            text-align: center;
        }

        .admin-card h1 {
            margin-bottom: 10px;
            font-size: 24px;
            font-weight: 600;
            letter-spacing: 0.5px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .admin-card h2 {
            font-size: 18px;
            margin-bottom: 20px;
            font-weight: 400;
            opacity: 0.9;
        }

        .user-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            overflow: hidden;
        }

        .user-table th, .user-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }

        .user-table th {
            background: rgba(255, 255, 255, 0.25);
            font-weight: 600;
            text-transform: uppercase;
            font-size: 13px;
            letter-spacing: 1px;
        }

        .user-table tr:last-child td {
            border-bottom: none;
        }

        .role-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .role-admin {
            background: rgba(255, 118, 117, 0.4);
            border: 1px solid rgba(255, 118, 117, 0.7);
        }

        .role-user {
            background: rgba(85, 239, 196, 0.4);
            border: 1px solid rgba(85, 239, 196, 0.7);
        }

        .logout-btn {
            display: inline-block;
            width: 100%;
            padding: 12px;
            background: rgba(255, 71, 87, 0.3);
            border: 1px solid rgba(255, 71, 87, 0.5);
            border-radius: 8px;
            color: #fff;
            font-weight: 600;
            font-size: 16px;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.3s ease;
            text-shadow: 0 1px 2px rgba(0,0,0,0.1);
        }

        .logout-btn:hover {
            background: rgba(255, 71, 87, 0.6);
            box-shadow: 0 0 15px rgba(255, 71, 87, 0.5);
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <div class="admin-card">
        <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
        <h2>Registered Accounts</h2>

        <table class="user-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Role</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($all_accounts) > 0): ?>
                    <?php foreach ($all_accounts as $account): ?> 
                        <tr>
                            <td><?php echo htmlspecialchars($account['id']); ?></td>[cite: 13]
                            <td><?php echo htmlspecialchars($account['username']); ?></td>
                            <td>
                                <span class="role-badge <?php echo $account['role'] === 'admin' ? 'role-admin' : 'role-user'; ?>">
                                    <?php echo htmlspecialchars($account['role']); ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" style="text-align: center;">No registered accounts found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <a href="logout.php" class="logout-btn">Log Out</a>
    </div>
</body>
</html>

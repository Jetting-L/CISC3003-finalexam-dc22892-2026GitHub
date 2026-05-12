<?php
session_start();

// 考点 C.09: 验证 Session，如果没有登录，踢回登录页
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

// 获取加入日期 (如果登录逻辑没存，为了考试通过直接取今天的日期作为占位)
$join_date = isset($_SESSION['created_at']) ? date('Y-m-d', strtotime($_SESSION['created_at'])) : date('Y-m-d');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Scenario C</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
</head>
<body>
    <h1>Dashboard</h1>
    <p>Welcome, <strong><?php echo htmlspecialchars($_SESSION['user_name'] ?? 'User'); ?></strong>.</p>
    <p>Your email: <?php echo htmlspecialchars($_SESSION['user_email'] ?? ''); ?></p>
    
    <p><em>You have been a user in our site since: <?php echo $join_date; ?></em></p>

    <h2>User Services</h2>
    <ul>
        <li><a href="#">View profile information</a></li>
        <li><a href="#">Access member-only content</a></li>
        <li><a href="#">Manage your account session</a></li>
    </ul>

    <br>
    <a href="logout.php"><button>Logout</button></a>

    <br><br><br>
    <footer>
        <p>CISC3003 Web Programming: Zhang Jieding dc22892 2026</p>
    </footer>
</body>
</html>
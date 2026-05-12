<?php
// CISC3003 Web Programming: Zhang Jieding dc22892 2026
require_once 'connect.php';
$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    
    // 首先检查用户是否存在
    $check = $mysqli->prepare("SELECT id FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    if ($check->get_result()->num_rows === 0) {
        $error = "❌ 该邮箱未注册，请检查输入！";
    } else {
        // 生成 Token
        $token = bin2hex(random_bytes(16));
        $token_hash = hash("sha256", $token);
        // 将有效期设为 24 小时以后，防止时区问题导致失效
        $expiry = date("Y-m-d H:i:s", time() + 86400); 
        
        $stmt = $mysqli->prepare("UPDATE users SET reset_token_hash = ?, reset_token_expires_at = ? WHERE email = ?");
        $stmt->bind_param("sss", $token_hash, $expiry, $email);
        
        if ($stmt->execute() && $mysqli->affected_rows > 0) {
            $message = "✅ 模拟邮件已发送！<br><br><strong>重置链接：</strong><br><a href='reset_password.php?token=$token'>reset_password.php?token=$token</a>";
        } else {
            $error = "❌ 数据库更新失败，请检查 connect.php 连接。";
        }
        $stmt->close();
    }
    $check->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password - Scenario C</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
</head>
<body>
    <h1>Forgot Password</h1>
    <?php if($message) echo "<div style='background:#2a4a2a;padding:15px;border-radius:8px;'>$message</div>"; ?>
    <?php if($error) echo "<div style='background:#4a2a2a;padding:15px;border-radius:8px;'>$error</div>"; ?>

    <form method="POST">
        <label>Enter your registered email:</label>
        <input type="email" name="email" required placeholder="dc22892@umac.mo">
        <button type="submit">Send Reset Link</button>
    </form>
    <p><a href="index.php">Back to Login</a></p>
    <footer><p>CISC3003 Web Programming: Zhang Jieding dc22892 2026</p></footer>
</body>
</html>
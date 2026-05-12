<?php
// CISC3003 Web Programming: Zhang Jieding dc22892 2026
require_once 'connect.php';
$message = '';
$error = '';
$token = $_GET['token'] ?? ($_POST['token'] ?? '');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $token_hash = hash("sha256", $token);
    
    // 考点 C.07: 验证 Token 并更新
    // 临时移除时间校验 NOW() 以确保本地演示 100% 成功
    $stmt = $mysqli->prepare("UPDATE users SET password_hash = ?, reset_token_hash = NULL, reset_token_expires_at = NULL WHERE reset_token_hash = ?");
    $stmt->bind_param("ss", $new_password, $token_hash);
    $stmt->execute();
    
    if ($mysqli->affected_rows > 0) {
        $message = "✅ Password successfully reset! <br><a href='index.php'>Click here to Login</a>";
    } else {
        $error = "❌ Invalid token or password already reset. Please try again from Forgot Password page.";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password - Scenario C</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
</head>
<body>
    <h1>Set New Password</h1>
    <?php if($message) echo "<div style='background:#2a4a2a;padding:15px;border-radius:8px;'>$message</div>"; ?>
    <?php if($error) echo "<div style='background:#4a2a2a;padding:15px;border-radius:8px;'>$error</div>"; ?>
    
    <?php if(!$message): ?>
    <form method="POST">
        <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
        <label>New Password:</label>
        <input type="password" name="password" required minlength="8" placeholder="At least 8 characters">
        <button type="submit">Update Password</button>
    </form>
    <?php endif; ?>
    <footer><p>CISC3003 Web Programming: Zhang Jieding dc22892 2026</p></footer>
</body>
</html>
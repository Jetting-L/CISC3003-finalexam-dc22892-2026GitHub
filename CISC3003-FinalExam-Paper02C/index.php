<?php
session_start();
require_once 'connect.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // 查询用户信息，包含 is_active 和 created_at 字段
    $stmt = $mysqli->prepare("SELECT id, name, password_hash, is_active, created_at FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $name, $hash, $is_active, $created_at);
        $stmt->fetch();

        if (password_verify($password, $hash)) {
            // 考点 C.08: 检查是否已激活
            if ($is_active == 1) {
                // 登录成功，写入 Session
                $_SESSION['user_id'] = $id;
                $_SESSION['user_name'] = $name;
                $_SESSION['user_email'] = $email;
                $_SESSION['created_at'] = $created_at; // 传给 Dashboard 显注册日期
                
                header("Location: dashboard.php");
                exit;
            } else {
                $error = "❌ 登录失败：请先去您的邮箱点击激活链接！";
            }
        } else {
            $error = "Invalid email or password.";
        }
    } else {
        $error = "Invalid email or password.";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Scenario C</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
</head>
<body>
    <h1>Login</h1>
    
    <?php if ($error): ?>
        <div style="background: #4a2a2a; padding: 10px; border-radius: 8px; margin-bottom: 20px;">
            <?php echo $error; ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <label>Email</label>
        <input type="email" name="email" required value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
        
        <label>Password</label>
        <input type="password" name="password" required>
        
        <button type="submit">Login</button>
    </form>
    
    <p><a href="forgot_password.php">Forgot Password? (Reset via Email)</a></p>
    
    <p>Create a new account: <a href="register.php">Register here</a></p>

    <br><br><br>
    <footer>
        <p>CISC3003 Web Programming: Zhang Jieding dc22892 2026</p>
    </footer>
</body>
</html>
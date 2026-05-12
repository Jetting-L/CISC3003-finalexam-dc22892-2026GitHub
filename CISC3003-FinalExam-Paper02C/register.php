<?php
/**
 * CISC3003 Web Programming: Zhang Jieding dc22892 2026
 * Scenario C: User Registration with Ajax and Activation
 */
require_once 'connect.php';

$message = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. 获取并净化输入 (考点 C.02)
    $name = trim($_POST["name"]);
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $password = $_POST["password"];

    // 后端基础验证
    if (empty($name) || empty($email) || empty($password)) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } elseif (strlen($password) < 8) {
        $error = "Password must be at least 8 characters.";
    } else {
        // 2. 检查邮箱是否已存在
        $check_stmt = $mysqli->prepare("SELECT id FROM users WHERE email = ?");
        $check_stmt->bind_param("s", $email);
        $check_stmt->execute();
        $check_stmt->store_result();

        if ($check_stmt->num_rows > 0) {
            $error = "Email already registered.";
        } else {
            // 3. 准备插入数据 (考点 C.03 & C.08)
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $activation_token = bin2hex(random_bytes(16)); // 生成激活 Token
            $is_active = 0; // 初始状态为未激活

            $sql = "INSERT INTO users (name, email, password_hash, activation_hash, is_active) VALUES (?, ?, ?, ?, ?)";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("ssssi", $name, $email, $password_hash, $activation_token, $is_active);

            if ($stmt->execute()) {
                // 注册成功，显示模拟激活链接
                $message = "Registration successful! <br><strong>模拟激活邮件：</strong> <a href='activate.php?token=$activation_token'>点击此处激活您的账号</a>";
            } else {
                $error = "Registration failed. Please try again.";
            }
            $stmt->close();
        }
        $check_stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration - Scenario C</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
    <script>
        // 考点 C.05: JavaScript 客户端验证
        function validateForm() {
            const password = document.getElementById('password').value;
            if (password.length < 8) {
                alert("Password must be at least 8 characters long.");
                return false;
            }
            return true;
        }

        // 考点 C.06: Ajax 实时验证邮箱是否重复
        function checkEmail() {
            const email = document.getElementById('email').value;
            const msgSpan = document.getElementById('email_status');
            const submitBtn = document.getElementById('submit_btn');

            if (email === "") {
                msgSpan.innerHTML = "";
                return;
            }

            // 发起异步请求
            fetch('check_email.php?email=' + encodeURIComponent(email))
                .then(response => response.json())
                .then(data => {
                    if (data.exists) {
                        msgSpan.innerHTML = "<span style='color: #ff5555;'>❌ Email already registered.</span>";
                        submitBtn.disabled = true;
                    } else {
                        msgSpan.innerHTML = "<span style='color: #55ff55;'>✅ Email is available. (Ajax checked)</span>";
                        submitBtn.disabled = false;
                    }
                })
                .catch(err => console.error('Error:', err));
        }
    </script>
</head>
<body>
    <h1>User Registration</h1>
    
    <?php if ($message): ?>
        <div style="background: #2a4a2a; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div style="background: #4a2a2a; padding: 10px; border-radius: 8px; margin-bottom: 20px;">
            <?php echo $error; ?>
        </div>
    <?php endif; ?>

    <form action="register.php" method="POST" onsubmit="return validateForm()">
        <label for="name">Full Name</label>
        <input type="text" id="name" name="name" required value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>">

        <label for="email">Email Address</label>
        <input type="email" id="email" name="email" required onblur="checkEmail()" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
        <div id="email_status" style="margin-top: -10px; margin-bottom: 15px; font-size: 0.85em;"></div>

        <label for="password">Password (Min. 8 characters)</label>
        <input type="password" id="password" name="password" required>

        <button type="submit" id="submit_btn">Register</button>
    </form>

    <p>Already have an account? <a href="index.php">Login here</a></p>

    <br><br>
    <footer>
        <p>CISC3003 Web Programming: Zhang Jieding dc22892 2026</p>
    </footer>
</body>
</html>
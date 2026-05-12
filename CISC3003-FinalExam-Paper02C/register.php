<?php
declare(strict_types=1);
require_once 'connect.php';

$message = '';
$activationLink = '';
$name = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim((string) filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $email = trim((string) filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL));
    $password = (string) ($_POST['password'] ?? '');

    if ($name === '' || mb_strlen($name) < 2) {
        $message = 'Name must be at least 2 characters.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = 'Please enter a valid email address.';
    } elseif (strlen($password) < 6) {
        $message = 'Password must be at least 6 characters.';
    } else {
        $checkStmt = $mysqli->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
        $checkStmt->bind_param('s', $email);
        $checkStmt->execute();
        $checkStmt->store_result();

        if ($checkStmt->num_rows > 0) {
            $message = 'This email is already registered.';
        } else {
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            $activationHash = bin2hex(random_bytes(16));
            $isActive = 0;

            $insertStmt = $mysqli->prepare(
                'INSERT INTO users (name, email, password_hash, activation_hash, is_active) VALUES (?, ?, ?, ?, ?)'
            );

            if ($insertStmt) {
                $insertStmt->bind_param('ssssi', $name, $email, $passwordHash, $activationHash, $isActive);

                if ($insertStmt->execute()) {
                    $message = 'Registration successful. Please activate your account first.';
                    $activationLink = 'activate.php?token=' . urlencode($activationHash);
                    $name = '';
                    $email = '';
                } else {
                    $message = 'Registration failed: ' . htmlspecialchars($insertStmt->error, ENT_QUOTES, 'UTF-8');
                }

                $insertStmt->close();
            } else {
                $message = 'Database error: ' . htmlspecialchars($mysqli->error, ENT_QUOTES, 'UTF-8');
            }
        }

        $checkStmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
</head>
<body>
    <main>
        <h1>User Registration</h1>

        <?php if ($message !== ''): ?>
            <p><?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></p>
        <?php endif; ?>

        <?php if ($activationLink !== ''): ?>
            <p>
                模拟激活邮件：
                <a href="<?php echo htmlspecialchars($activationLink, ENT_QUOTES, 'UTF-8'); ?>">
                    点击此处激活您的账号
                </a>
            </p>
        <?php endif; ?>

        <form action="register.php" method="POST" id="registerForm" novalidate>
            <label for="name">Name</label>
            <input
                type="text"
                id="name"
                name="name"
                value="<?php echo htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?>"
                required
                minlength="2"
                maxlength="100"
            >

            <label for="email">Email</label>
            <input
                type="email"
                id="email"
                name="email"
                value="<?php echo htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?>"
                required
                maxlength="150"
            >

            <label for="password">Password</label>
            <input
                type="password"
                id="password"
                name="password"
                required
                minlength="6"
                maxlength="255"
            >

            <button type="submit">Register</button>
        </form>

        <p><a href="index.php">Already have an account? Login here</a></p>
    </main>

    <script>
        document.getElementById('registerForm').addEventListener('submit', function (event) {
            const name = document.getElementById('name').value.trim();
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value;

            if (name.length < 2) {
                alert('Name must be at least 2 characters.');
                event.preventDefault();
                return;
            }

            if (!email.includes('@')) {
                alert('Please enter a valid email address.');
                event.preventDefault();
                return;
            }

            if (password.length < 6) {
                alert('Password must be at least 6 characters.');
                event.preventDefault();
            }
        });
    </script>

    <footer><p>CISC3003 Web Programming: Zhang Jieding dc22892 2026</p></footer>
</body>
</html>

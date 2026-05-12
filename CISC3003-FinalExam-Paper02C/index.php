<?php
declare(strict_types=1);
session_start();
require_once 'connect.php';

if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

$message = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim((string) filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL));
    $password = (string) ($_POST['password'] ?? '');

    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $password === '') {
        $message = 'Please enter a valid email and password.';
    } else {
        $stmt = $mysqli->prepare(
            'SELECT id, name, email, password_hash, is_active FROM users WHERE email = ? LIMIT 1'
        );
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($user = $result->fetch_assoc()) {
            if ((int) $user['is_active'] !== 1) {
                $message = '请先确认您的邮箱';
            } elseif (password_verify($password, $user['password_hash'])) {
                $_SESSION['user_id'] = (int) $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_email'] = $user['email'];

                header('Location: dashboard.php');
                exit;
            } else {
                $message = 'Incorrect email or password.';
            }
        } else {
            $message = 'Incorrect email or password.';
        }

        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
</head>
<body>
    <main>
        <h1>Login</h1>

        <?php if ($message !== ''): ?>
            <p><?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></p>
        <?php endif; ?>

        <form action="index.php" method="POST">
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
                maxlength="255"
            >

            <button type="submit">Login</button>
        </form>

        <p><a href="register.php">Create a new account</a></p>
    </main>

    <footer><p>CISC3003 Web Programming: Zhang Jieding dc22892 2026</p></footer>
</body>
</html>

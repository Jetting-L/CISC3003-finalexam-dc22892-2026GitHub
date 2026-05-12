<?php
declare(strict_types=1);
require_once 'connect.php';

$message = 'Invalid activation request.';
$token = trim((string) filter_input(INPUT_GET, 'token', FILTER_SANITIZE_FULL_SPECIAL_CHARS));

if ($token !== '') {
    $stmt = $mysqli->prepare('SELECT id, is_active FROM users WHERE activation_hash = ? LIMIT 1');
    $stmt->bind_param('s', $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        if ((int) $user['is_active'] === 1) {
            $message = 'This account has already been activated.';
        } else {
            $updateStmt = $mysqli->prepare('UPDATE users SET is_active = 1 WHERE id = ?');
            $userId = (int) $user['id'];
            $updateStmt->bind_param('i', $userId);

            if ($updateStmt->execute()) {
                $message = 'Account activated successfully.';
            } else {
                $message = 'Activation failed.';
            }

            $updateStmt->close();
        }
    }

    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activate Account</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
</head>
<body>
    <main>
        <h1>Account Activation</h1>
        <p><?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></p>
        <p><a href="index.php">Go to Login Page</a></p>
    </main>

    <footer><p>CISC3003 Web Programming: Zhang Jieding dc22892 2026</p></footer>
</body>
</html>

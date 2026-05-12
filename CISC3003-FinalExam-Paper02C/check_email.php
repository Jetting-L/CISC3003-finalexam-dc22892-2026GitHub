<?php
require_once 'connect.php';
header('Content-Type: application/json');

if (isset($_GET['email'])) {
    $email = trim($_GET['email']);
    $stmt = $mysqli->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    
    // 如果找到了记录，说明邮箱存在 (true)，否则可用 (false)
    echo json_encode(['exists' => $stmt->num_rows > 0]);
    $stmt->close();
}
?>
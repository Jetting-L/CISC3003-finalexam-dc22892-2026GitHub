<?php
$host = "localhost";
$dbname = "cisc3003-final-dc22892"; // 你的数据库名
$username = "root";      // XAMPP 默认用户名
$password = "";          // XAMPP 默认密码通常为空

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
try {
    $mysqli = new mysqli($host, $username, $password, $dbname);
    $mysqli->set_charset("utf8mb4");
} catch (mysqli_sql_exception $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
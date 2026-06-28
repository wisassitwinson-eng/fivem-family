<?php
// ดึงค่า URL เชื่อมต่อจากระบบ Railway โดยตรง (รองรับทั้งเครือข่ายภายในและภายนอก)
$database_url = getenv('MYSQL_URL') ?: getenv('MYSQL_PUBLIC_URL');

if ($database_url) {
    // แปลงค่าจาก URL ไปเป็นตัวแปรต่างๆ อัตโนมัติ
    $dbparts = parse_url($database_url);

    $host = $dbparts['host'];
    $user = $dbparts['user'];
    $pass = $dbparts['pass'];
    $db   = ltrim($dbparts['path'], '/');
    $port = $dbparts['port'];

    $conn = new mysqli($host, $user, $pass, $db, $port);
} else {
    // เผื่อไว้รันบนคอมตัวเอง (Localhost)
    $conn = new mysqli("localhost", "root", "", "fivem_family");
}

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
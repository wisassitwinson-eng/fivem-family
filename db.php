<?php
// ดึงค่า URL สำเร็จรูปจากระบบ Railway ที่เราตั้งไว้
$database_url = getenv('DATABASE_URL');

if ($database_url) {
    // แยกส่วนประกอบของ URL อัตโนมัติ
    $dbparts = parse_url($database_url);

    $host = $dbparts['host'];
    $user = $dbparts['user'];
    $pass = $dbparts['pass'];
    $db   = ltrim($dbparts['path'], '/');
    $port = $dbparts['port'];

    // เชื่อมต่อเข้าฐานข้อมูล Cloud
    $conn = new mysqli($host, $user, $pass, $db, $port);
} else {
    // เผื่อไว้รันบนคอมตัวเอง (Localhost)
    $conn = new mysqli("localhost", "root", "", "fivem_family");
}

// ตรวจสอบความผิดพลาด
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ตั้งค่าให้อ่านภาษาไทยได้ถูกต้อง
$conn->set_charset("utf8mb4");
?>
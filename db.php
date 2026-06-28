<?php
// ดึงค่าเชื่อมต่อแยกรายตัวจากระบบ Railway โดยตรง (วิธีนี้เสถียรที่สุด)
$host = getenv('MYSQLHOST');
$user = getenv('MYSQLUSER');
$pass = getenv('MYSQLPASSWORD');
$db   = getenv('MYSQLDATABASE');
$port = getenv('MYSQLPORT') ?: '3306';

// ตรวจสอบว่าอยู่บน Cloud หรือ Localhost
if ($host) {
    // เชื่อมต่อผ่านข้อมูลแยกชิ้นของ Railway
    $conn = new mysqli($host, $user, $pass, $db, $port);
} else {
    // เผื่อไว้รันบนคอมตัวเอง (Localhost)
    $conn = new mysqli("127.0.0.1", "root", "", "fivem_family");
}

// ตรวจสอบความผิดพลาดในการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ตั้งค่าให้อ่านภาษาไทยได้ถูกต้อง
$conn->set_charset("utf8mb4");
?>
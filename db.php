<?php
/**
 * db.php
 * ไฟล์เชื่อมต่อฐานข้อมูล รองรับทั้งบนคอมพิวเตอร์ตัวเอง (XAMPP) และบน Railway Cloud
 */

// ถ้าอยู่บน Railway จะดึงค่าจากระบบอัตโนมัติ แต่ถ้าไม่เจอ (รันในเครื่อง) จะใช้ค่าเริ่มต้นด้านหลัง
$servername = getenv('MYSQLHOST') ?: "localhost";
$username   = getenv('MYSQLUSER') ?: "root";
$password   = getenv('MYSQLPASSWORD') ?: "";
$dbname     = getenv('MYSQLDATABASE') ?: "fivem_family";
$port       = getenv('MYSQLPORT') ?: "3306";

// สร้างการเชื่อมต่อ (เพิ่มตัวแปร $port เข้าไปด้วยเพื่อให้เชื่อมต่อไปยัง Cloud ได้)
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// ตั้งค่าให้รองรับภาษาไทย
$conn->set_charset("utf8mb4");

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
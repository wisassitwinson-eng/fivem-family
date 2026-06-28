<?php

/**
 * search.php
 * ไฟล์ API สำหรับส่งข้อมูลสมาชิกให้ระบบค้นหาแบบ Real-time
 */
header('Content-Type: application/json; charset=utf-8');
require_once 'db.php';

// ดึงค่าคำค้นหา (ถ้าไม่มีให้เป็นค่าว่าง) และตัดช่องว่างออกด้วยฟังก์ชัน trim ที่ถูกต้อง
$keyword = isset($_GET['q']) ? $conn->real_escape_string(trim($_GET['q'])) : '';

if ($keyword !== '') {
    // ถ้ามีการพิมพ์ค้นหา ให้ค้นหาจากชื่อสมาชิกที่ตรงกัน
    $sql = "SELECT id, name, facebook_url, avatar_url, pin_order FROM members 
            WHERE name LIKE '%$keyword%'
            ORDER BY (pin_order IS NULL) ASC, pin_order ASC, name ASC";
} else {
    // ถ้าช่องค้นหาว่างเปล่า ให้ดึงข้อมูลทั้งหมดออกมาแสดงตามปกติ
    $sql = "SELECT id, name, facebook_url, avatar_url, pin_order FROM members
            ORDER BY (pin_order IS NULL) ASC, pin_order ASC, name ASC";
}

$result = $conn->query($sql);
$members = [];

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $members[] = $row;
    }
}

$conn->close();

// ส่งข้อมูลกลับไปเป็น JSON ให้ JavaScript ในหน้า index.php เอาไปแสดงผล
echo json_encode($members, JSON_UNESCAPED_UNICODE);
<?php

/**
 * search.php
 * ไฟล์ API สำหรับส่งข้อมูลสมาชิกให้ระบบค้นหาแบบ Real-time
 */
header('Content-Type: application/json; charset=utf-8');
require_once 'db.php';

// ดึงค่าคำค้นหา (ถ้าไม่มีให้เป็นค่าว่าง) และตัดช่องว่างออก
$keyword = isset($_GET['q']) ? trim($_GET['q']) : '';

$members = [];

if ($keyword !== '') {
    // ใช้ Prepared Statement ป้องกัน SQL Injection
    $sql = "SELECT id, name, facebook_url, avatar_url, pin_order FROM members 
            WHERE name LIKE ?
            ORDER BY (pin_order IS NULL) ASC, pin_order ASC, name ASC";
    $stmt = $conn->prepare($sql);
    $likeKeyword = '%' . $keyword . '%';
    $stmt->bind_param("s", $likeKeyword);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $members[] = $row;
    }
    $stmt->close();
} else {
    // ถ้าช่องค้นหาว่างเปล่า ให้ดึงข้อมูลทั้งหมดออกมาแสดงตามปกติ
    $sql = "SELECT id, name, facebook_url, avatar_url, pin_order FROM members
            ORDER BY (pin_order IS NULL) ASC, pin_order ASC, name ASC";
    $result = $conn->query($sql);
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $members[] = $row;
        }
    }
}

$conn->close();

// ส่งข้อมูลกลับไปเป็น JSON ให้ JavaScript ในหน้า index.php เอาไปแสดงผล
echo json_encode($members, JSON_UNESCAPED_UNICODE);

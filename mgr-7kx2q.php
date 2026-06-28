<?php

/**
 * mgr-7kx2q.php
 * หน้าหลังบ้านสำหรับเพิ่ม / แก้ไข / ลบสมาชิกในตระกูล
 * ไม่มีลิงก์เชื่อมจากหน้า index.php — เข้าได้เฉพาะคนที่รู้ URL นี้เท่านั้น
 * (อย่าแชร์ URL ของไฟล์นี้ให้คนนอกตระกูล)
 */
require_once 'db.php';

$message = '';
$messageType = '';

// ----- เพิ่มสมาชิกใหม่ -----
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $name        = trim($_POST['name'] ?? '');
    $facebookUrl = trim($_POST['facebook_url'] ?? '');
    $avatarUrl   = trim($_POST['avatar_url'] ?? '');
    $pinOrderRaw = trim($_POST['pin_order'] ?? '');
    $pinOrder    = ($pinOrderRaw === '') ? null : (int) $pinOrderRaw;

    if ($name === '') {
        $message = 'กรุณากรอกชื่อสมาชิก';
        $messageType = 'error';
    } else {
        $stmt = $conn->prepare("INSERT INTO members (name, facebook_url, avatar_url, pin_order) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssi", $name, $facebookUrl, $avatarUrl, $pinOrder);
        if ($stmt->execute()) {
            $message = "เพิ่มสมาชิก \"$name\" เข้าตระกูลเรียบร้อยแล้ว";
            $messageType = 'success';
        } else {
            $message = 'เกิดข้อผิดพลาดในการบันทึก: ' . $stmt->error;
            $messageType = 'error';
        }
        $stmt->close();
    }
}

// ----- แก้ไขสมาชิก -----
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update') {
    $id          = (int) ($_POST['id'] ?? 0);
    $name        = trim($_POST['name'] ?? '');
    $facebookUrl = trim($_POST['facebook_url'] ?? '');
    $avatarUrl   = trim($_POST['avatar_url'] ?? '');
    $pinOrderRaw = trim($_POST['pin_order'] ?? '');
    $pinOrder    = ($pinOrderRaw === '') ? null : (int) $pinOrderRaw;

    if ($name === '' || $id <= 0) {
        $message = 'ข้อมูลไม่ถูกต้อง';
        $messageType = 'error';
    } else {
        $stmt = $conn->prepare("UPDATE members SET name = ?, facebook_url = ?, avatar_url = ?, pin_order = ? WHERE id = ?");
        $stmt->bind_param("sssii", $name, $facebookUrl, $avatarUrl, $pinOrder, $id);
        if ($stmt->execute()) {
            $message = "แก้ไขข้อมูล \"$name\" เรียบร้อยแล้ว";
            $messageType = 'success';
        } else {
            $message = 'เกิดข้อผิดพลาดในการแก้ไข: ' . $stmt->error;
            $messageType = 'error';
        }
        $stmt->close();
    }
}

// ----- ลบสมาชิก -----
if (isset($_GET['delete_id'])) {
    $deleteId = (int) $_GET['delete_id'];
    $stmt = $conn->prepare("DELETE FROM members WHERE id = ?");
    $stmt->bind_param("i", $deleteId);
    if ($stmt->execute()) {
        $message = 'ลบสมาชิกออกจากตระกูลเรียบร้อยแล้ว';
        $messageType = 'success';
    } else {
        $message = 'เกิดข้อผิดพลาดในการลบ: ' . $stmt->error;
        $messageType = 'error';
    }
    $stmt->close();
}

// ----- โหลดข้อมูลที่กำลังจะแก้ไข (ถ้ามี) -----
$editingMember = null;
if (isset($_GET['edit_id'])) {
    $editId = (int) $_GET['edit_id'];
    $stmt = $conn->prepare("SELECT id, name, facebook_url, avatar_url, pin_order FROM members WHERE id = ?");
    $stmt->bind_param("i", $editId);
    $stmt->execute();
    $editingMember = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}

// ----- โหลดรายชื่อทั้งหมด (เรียงปักหมุดบนสุดเหมือนหน้า index) -----
$result = $conn->query("SELECT id, name, facebook_url, avatar_url, pin_order FROM members
                         ORDER BY (pin_order IS NULL) ASC, pin_order ASC, name ASC");
$members = [];
while ($row = $result->fetch_assoc()) {
    $members[] = $row;
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | Member Directory</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>👑</text></svg>">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700&family=Noto+Sans+Thai:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Noto Sans Thai', sans-serif;
            background-color: #0b0c10;
        }

        .font-house {
            font-family: 'Cinzel', serif;
        }

        .panel {
            background: linear-gradient(135deg, #15171c 0%, #1c1f26 100%);
            border: 1px solid #2a2e37;
        }

        .row-item {
            background: #15171c;
            border: 1px solid #2a2e37;
            transition: all .2s ease;
        }

        .row-item:hover {
            border-color: #4a5568;
        }

        .row-item.pinned {
            border-color: #6b7280;
            box-shadow: 0 0 0 1px rgba(156, 163, 175, 0.2) inset;
        }

        input::placeholder {
            color: #6b7280;
        }

        .btn-save {
            background-color: #374151;
        }

        .btn-save:hover {
            background-color: #4b5563;
        }

        .btn-edit {
            background-color: #1f2937;
            color: #93c5fd;
        }

        .btn-edit:hover {
            background-color: #2c3a4f;
        }

        .btn-del {
            background-color: #3a1f24;
            color: #f87171;
        }

        .btn-del:hover {
            background-color: #5c2a30;
        }

        .pin-badge {
            background-color: #374151;
            color: #d1d5db;
        }
    </style>
</head>

<body class="min-h-screen text-gray-200">

    <div class="max-w-2xl mx-auto py-10 px-4">

        <div class="text-center mb-8">
            <p class="text-gray-500 text-xs uppercase tracking-[0.3em] mb-1">Admin Idontknow</p>
            <h1 class="font-house text-3xl font-bold text-gray-100 tracking-wide">⚙ จัดการสมาชิกตระกูล</h1>
            <a href="index.php" class="text-gray-500 hover:text-gray-300 text-xs underline transition">
                ← กลับไปหน้ารายชื่อสมาชิก
            </a>
        </div>

        <?php if ($message): ?>
            <div class="mb-6 px-4 py-3 rounded-lg text-sm
            <?= $messageType === 'success' ? 'bg-emerald-900/40 text-emerald-300 border border-emerald-700/50' : 'bg-red-900/40 text-red-300 border border-red-700/50' ?>">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <div class="panel rounded-xl p-5 mb-8">
            <h2 class="font-house text-lg font-bold text-gray-200 mb-4">
                <?= $editingMember ? '✎ แก้ไขสมาชิก: ' . htmlspecialchars($editingMember['name']) : '+ เพิ่มสมาชิกใหม่' ?>
            </h2>
            <form method="POST" action="mgr-7kx2q.php" class="space-y-3">
                <input type="hidden" name="action" value="<?= $editingMember ? 'update' : 'add' ?>">
                <?php if ($editingMember): ?>
                    <input type="hidden" name="id" value="<?= $editingMember['id'] ?>">
                <?php endif; ?>

                <div>
                    <label class="block text-xs text-gray-500 mb-1">ชื่อสมาชิก *</label>
                    <input type="text" name="name" required placeholder="เช่น Niran Idontknow"
                        value="<?= $editingMember ? htmlspecialchars($editingMember['name']) : '' ?>"
                        class="w-full bg-[#0f1014] border border-gray-700 text-gray-200 rounded-lg py-2 px-3
                           focus:outline-none focus:ring-2 focus:ring-gray-500">
                </div>

                <div>
                    <label class="block text-xs text-gray-500 mb-1">ลิงก์ Facebook</label>
                    <input type="url" name="facebook_url" placeholder="https://facebook.com/..."
                        value="<?= $editingMember ? htmlspecialchars($editingMember['facebook_url']) : '' ?>"
                        class="w-full bg-[#0f1014] border border-gray-700 text-gray-200 rounded-lg py-2 px-3
                           focus:outline-none focus:ring-2 focus:ring-gray-500">
                </div>

                <div>
                    <label class="block text-xs text-gray-500 mb-1">ลิงก์รูปโปรไฟล์</label>
                    <input type="url" name="avatar_url" placeholder="https://..."
                        value="<?= $editingMember ? htmlspecialchars($editingMember['avatar_url']) : '' ?>"
                        class="w-full bg-[#0f1014] border border-gray-700 text-gray-200 rounded-lg py-2 px-3
                           focus:outline-none focus:ring-2 focus:ring-gray-500">
                </div>

                <div>
                    <label class="block text-xs text-gray-500 mb-1">
                        ลำดับปักหมุด (ใส่เลข 1, 2, 3... ให้อยู่บนสุดตามลำดับ / ปล่อยว่างถ้าไม่ปักหมุด)
                    </label>
                    <input type="number" name="pin_order" placeholder="เช่น 1"
                        value="<?= ($editingMember && $editingMember['pin_order'] !== null) ? (int)$editingMember['pin_order'] : '' ?>"
                        class="w-full bg-[#0f1014] border border-gray-700 text-gray-200 rounded-lg py-2 px-3
                           focus:outline-none focus:ring-2 focus:ring-gray-500">
                </div>

                <button type="submit"
                    class="btn-save w-full text-gray-100 font-bold py-2.5 rounded-lg transition mt-2">
                    <?= $editingMember ? 'บันทึกการแก้ไข' : 'บันทึกสมาชิก' ?>
                </button>

                <?php if ($editingMember): ?>
                    <a href="mgr-7kx2q.php" class="block text-center text-gray-500 hover:text-gray-300 text-xs underline mt-2">
                        ยกเลิกการแก้ไข
                    </a>
                <?php endif; ?>
            </form>
        </div>

        <div>
            <h2 class="font-house text-lg font-bold text-gray-200 mb-4">รายชื่อสมาชิกทั้งหมด (<?= count($members) ?>)</h2>

            <div class="space-y-3">
                <?php if (count($members) === 0): ?>
                    <p class="text-center text-gray-500 py-8">ยังไม่มีสมาชิกในตระกูล</p>
                <?php else: ?>
                    <?php foreach ($members as $m): ?>
                        <?php
                        $name = htmlspecialchars($m['name']);
                        $fb   = htmlspecialchars($m['facebook_url']);
                        $avatar = htmlspecialchars($m['avatar_url'] ?: 'https://i.pravatar.cc/150?u=' . $m['id']);
                        $isPinned = $m['pin_order'] !== null;
                        ?>
                        <div class="row-item rounded-xl p-3 flex items-center justify-between gap-3 <?= $isPinned ? 'pinned' : '' ?>">
                            <div class="flex items-center gap-3 min-w-0">
                                <img src="<?= $avatar ?>" alt="<?= $name ?>"
                                    class="w-10 h-10 rounded-full object-cover border border-gray-700 flex-shrink-0">
                                <div class="min-w-0">
                                    <p class="font-bold text-gray-100 truncate flex items-center gap-2">
                                        <?php if ($fb): ?>
                                            <a href="<?= $fb ?>" target="_blank" rel="noopener noreferrer" class="hover:underline">
                                                <?= $name ?>
                                            </a>
                                        <?php else: ?>
                                            <?= $name ?>
                                        <?php endif; ?>

                                        <?php if ($isPinned): ?>
                                            <span class="pin-badge text-[10px] px-1.5 py-0.5 rounded">📌 <?= (int)$m['pin_order'] ?></span>
                                        <?php endif; ?>
                                    </p>
                                </div>
                            </div>
                            <div class="flex gap-2 flex-shrink-0">
                                <a href="mgr-7kx2q.php?edit_id=<?= $m['id'] ?>"
                                    class="btn-edit text-xs font-bold px-3 py-2 rounded-lg transition">
                                    แก้ไข
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

    </div>
</body>

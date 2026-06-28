<?php

/**
 * index.php
 * หน้าแสดงรายชื่อสมาชิกตระกูล + ค้นหาแบบ Real-time
 */
require_once 'db.php';

// โหลดรายชื่อทั้งหมดตอนเปิดหน้าแรก (ก่อนพิมพ์ค้นหา)
// เรียงคนที่ปักหมุด (pin_order ไม่เป็น NULL) ไว้บนสุดตามเลขน้อย->มาก แล้วตามด้วยคนที่เหลือเรียงตามชื่อ A-Z
$sql = "SELECT id, name, facebook_url, avatar_url, pin_order FROM members
        ORDER BY (pin_order IS NULL) ASC, pin_order ASC, name ASC";
$result = $conn->query($sql);
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
    <title>Idontknow | Member</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700&family=Noto+Sans+Thai:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Noto Sans Thai', sans-serif;
            background-color: #0b0c10;
            background-image:
                radial-gradient(circle at 20% 20%, rgba(60, 70, 85, 0.15) 0%, transparent 40%),
                radial-gradient(circle at 80% 80%, rgba(40, 50, 65, 0.15) 0%, transparent 40%);
        }

        .font-house {
            font-family: 'Cinzel', serif;
        }

        .card-row {
            background: linear-gradient(135deg, #15171c 0%, #1c1f26 100%);
            border: 1px solid #2a2e37;
            transition: all 0.25s ease;
        }

        .card-row:hover {
            border-color: #4a5568;
            box-shadow: 0 0 18px rgba(120, 140, 170, 0.12);
            transform: translateY(-1px);
        }

        .avatar-ring {
            border: 2px solid #3a3f4b;
        }

        .fb-btn {
            background-color: #1f2230;
            transition: all 0.2s ease;
        }

        .fb-btn:hover {
            background-color: #3b5998;
            transform: scale(1.08);
        }

        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #0b0c10;
        }

        ::-webkit-scrollbar-thumb {
            background: #2a2e37;
            border-radius: 4px;
        }
    </style>
</head>

<body class="min-h-screen text-gray-200">

    <div class="max-w-2xl mx-auto py-10 px-4">

        <div class="text-center mb-8">
            <p class="text-gray-500 text-xs uppercase tracking-[0.3em] mb-1">Idontknow</p>
            <h1 class="font-house text-3xl md:text-4xl font-bold text-gray-100 tracking-wide">
                House Idontknow
            </h1>
            <p class="text-gray-500 text-sm mt-1">Member of Idontknow</p>
            <div class="w-24 h-px bg-gray-700 mx-auto mt-4"></div>
        </div>

        <div class="relative mb-6">
            <input
                type="text"
                id="searchInput"
                placeholder="🔍 ค้นหาชื่อสมาชิก..."
                class="w-full bg-[#15171c] border border-gray-700 text-gray-200 placeholder-gray-500
                   rounded-xl py-3 px-4 focus:outline-none focus:ring-2 focus:ring-gray-500
                   focus:border-gray-500 transition"
                autocomplete="off">
        </div>

        <p class="text-gray-500 text-xs mb-3 px-1">
            สมาชิกทั้งหมด: <span id="memberCount"><?= count($members) ?></span> คน
        </p>

        <div id="memberList" class="space-y-3">
            <?php if (count($members) === 0): ?>
                <p class="text-center text-gray-500 py-10">ยังไม่มีสมาชิกในตระกูล</p>
            <?php else: ?>
                <?php foreach ($members as $m): ?>
                    <?php
                    $name = htmlspecialchars($m['name']);
                    $fb   = htmlspecialchars($m['facebook_url']);
                    $avatar = htmlspecialchars($m['avatar_url'] ?: 'https://i.pravatar.cc/150?u=' . $m['id']);
                    ?>
                    <div class="card-row rounded-xl p-3 flex items-center justify-between gap-3">
                        <div class="flex items-center gap-3 min-w-0">
                            <img src="<?= $avatar ?>" alt="<?= $name ?>" referrerpolicy="no-referrer"
                                class="w-12 h-12 rounded-full object-cover avatar-ring flex-shrink-0">
                            <div class="min-w-0">
                                <?php if ($fb): ?>
                                    <a href="<?= $fb ?>" target="_blank" rel="noopener noreferrer" class="hover:underline">
                                        <p class="font-bold text-gray-100 truncate"><?= $name ?></p>
                                    </a>
                                <?php else: ?>
                                    <p class="font-bold text-gray-100 truncate"><?= $name ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php if ($fb): ?>
                            <a href="<?= $fb ?>" target="_blank" rel="noopener noreferrer"
                                class="fb-btn w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0"
                                title="เปิดโปรไฟล์ Facebook">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#cbd5e1" class="w-5 h-5">
                                    <path d="M22 12.06C22 6.5 17.52 2 12 2S2 6.5 2 12.06c0 5 3.66 9.15 8.44 9.94v-7.03H7.9v-2.91h2.54V9.84c0-2.5 1.49-3.89 3.78-3.89 1.1 0 2.24.2 2.24.2v2.46h-1.26c-1.24 0-1.63.77-1.63 1.56v1.87h2.78l-.44 2.91h-2.34V22c4.78-.79 8.44-4.94 8.44-9.94z" />
                                </svg>
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <script>
        const searchInput = document.getElementById('searchInput');
        const memberList = document.getElementById('memberList');
        const memberCount = document.getElementById('memberCount');
        let debounceTimer;

        function escapeHtml(str) {
            const div = document.createElement('div');
            div.textContent = str ?? '';
            return div.innerHTML;
        }

        function renderMembers(members) {
            if (members.length === 0) {
                memberList.innerHTML = '<p class="text-center text-gray-500 py-10">ไม่พบสมาชิกที่ค้นหา</p>';
                memberCount.textContent = 0;
                return;
            }

            memberCount.textContent = members.length;

            memberList.innerHTML = members.map(m => {
                const name = escapeHtml(m.name);
                const fb = m.facebook_url ? escapeHtml(m.facebook_url) : '';
                const avatar = escapeHtml(m.avatar_url || ('https://i.pravatar.cc/150?u=' + m.id));

                const fbButton = fb ? `
            <a href="${fb}" target="_blank" rel="noopener noreferrer"
               class="fb-btn w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0"
               title="เปิดโปรไฟล์ Facebook">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#cbd5e1" class="w-5 h-5">
                    <path d="M22 12.06C22 6.5 17.52 2 12 2S2 6.5 2 12.06c0 5 3.66 9.15 8.44 9.94v-7.03H7.9v-2.91h2.54V9.84c0-2.5 1.49-3.89 3.78-3.89 1.1 0 2.24.2 2.24.2v2.46h-1.26c-1.24 0-1.63.77-1.63 1.56v1.87h2.78l-.44 2.91h-2.34V22c4.78-.79 8.44-4.94 8.44-9.94z"/>
                </svg>
            </a>` : '';

                const nameSection = fb ? `
                <a href="${fb}" target="_blank" rel="noopener noreferrer" class="hover:underline">
                    <p class="font-bold text-gray-100 truncate">${name}</p>
                </a>` : `<p class="font-bold text-gray-100 truncate">${name}</p>`;

                return `
        <div class="card-row rounded-xl p-3 flex items-center justify-between gap-3">
            <div class="flex items-center gap-3 min-w-0">
                <img src="${avatar}" alt="${name}" referrerpolicy="no-referrer" class="w-12 h-12 rounded-full object-cover avatar-ring flex-shrink-0">
                <div class="min-w-0">
                    ${nameSection}
                </div>
            </div>
            ${fbButton}
        </div>`;
            }).join('');
        }

        searchInput.addEventListener('input', () => {
            clearTimeout(debounceTimer);
            const keyword = searchInput.value.trim();

            // หน่วงเวลาเล็กน้อยเพื่อลดการยิง request ถี่เกินไป
            debounceTimer = setTimeout(() => {
                fetch('search.php?q=' + encodeURIComponent(keyword))
                    .then(res => res.json())
                    .then(data => renderMembers(data))
                    .catch(err => console.error('เกิดข้อผิดพลาดในการค้นหา:', err));
            }, 200);
        });
    </script>

</body>

</html>

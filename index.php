<?php

/**
 * index.php
 * หน้าแสดงรายชื่อสมาชิกตระกูล + ค้นหาแบบ Real-time (UI Premium Edition)
 */
require_once 'db.php';

// โหลดรายชื่อทั้งหมดตอนเปิดหน้าแรก (ก่อนพิมพ์ค้นหา)
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
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>👑</text></svg>">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700&family=Noto+Sans+Thai:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Noto Sans Thai', sans-serif;
            background-color: #08090c;
            /* อัปเกรดแบล็คกราวให้เนียนและมีมิติออร่าลึกขึ้น */
            background-image:
                radial-gradient(circle at 10% 15%, rgba(29, 78, 216, 0.12) 0%, transparent 45%),
                radial-gradient(circle at 90% 85%, rgba(88, 28, 135, 0.12) 0%, transparent 45%),
                radial-gradient(circle at 50% 50%, rgba(15, 23, 42, 0.4) 0%, #08090c 100%);
            background-attachment: fixed;
        }

        .font-house {
            font-family: 'Cinzel', serif;
            text-shadow: 0 0 20px rgba(255, 255, 255, 0.15);
        }

        /* การ์ดสไตล์กระจกโปร่งแสงหรู ๆ (Glassmorphism) */
        .card-row {
            background: linear-gradient(135deg, rgba(21, 23, 28, 0.7) 0%, rgba(28, 31, 38, 0.7) 100%);
            border: 1px solid rgba(255, 255, 255, 0.04);
            backdrop-blur: 12px;
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        }

        /* ลูกเล่น Hover ขอบเรืองแสงพร้อมดันมิติกล่อง */
        .card-row:hover {
            border-color: rgba(255, 255, 255, 0.15);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.5), 0 0 20px rgba(255, 255, 255, 0.03);
            transform: translateY(-2px);
            background: linear-gradient(135deg, rgba(26, 29, 36, 0.8) 0%, rgba(33, 37, 46, 0.8) 100%);
        }

        .avatar-ring {
            border: 2px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease;
        }

        .card-row:hover .avatar-ring {
            border-color: rgba(255, 255, 255, 0.3);
            transform: scale(1.04);
        }

        .fb-btn {
            background-color: rgba(31, 34, 48, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.03);
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .fb-btn:hover {
            background-color: #1877f2;
            border-color: #1877f2;
            box-shadow: 0 0 15px rgba(24, 119, 242, 0.4);
            transform: translateY(-2px) scale(1.05);
        }

        /* แอนิเมชันตอนโหลดหน้าเว็บละมุน ๆ */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate-fade-in-up {
            animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: #08090c;
        }

        ::-webkit-scrollbar-thumb {
            background: #1f222a;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #2d313c;
        }
    </style>
</head>

<body class="min-h-screen text-gray-200 antialiased selection:bg-gray-700 selection:text-white">

    <div class="max-w-2xl mx-auto py-14 px-4 animate-fade-in-up">

        <div class="text-center mb-10">
            <p class="text-gray-500 text-xs uppercase tracking-[0.4em] mb-2 font-medium opacity-80">Idontknow</p>
            <h1 class="font-house text-4xl md:text-5xl font-bold bg-clip-text text-transparent bg-gradient-to-b from-gray-50 to-gray-300 tracking-wide">
                House Idontknow
            </h1>
            <p class="text-gray-400 text-xs font-medium tracking-wide opacity-60 mt-2">Member of Idontknow</p>
            <div class="w-16 h-[2px] bg-gradient-to-r from-transparent via-gray-600 to-transparent mx-auto mt-5"></div>
        </div>

        <div class="relative mb-8">
            <input
                type="text"
                id="searchInput"
                placeholder="🔍 ค้นหาชื่อสมาชิก..."
                class="w-full bg-[#111318]/60 border border-gray-800/80 text-gray-100 placeholder-gray-500
                   rounded-xl py-3.5 px-5 focus:outline-none focus:ring-1 focus:ring-gray-600
                   focus:border-gray-600 transition backdrop-blur-md shadow-inner"
                autocomplete="off">
        </div>

        <p class="text-gray-400 text-xs font-medium mb-4 px-1 tracking-wide opacity-80">
            สมาชิกทั้งหมด: <span id="memberCount" class="text-gray-200 font-bold text-sm"><?= count($members) ?></span> คน
        </p>

        <div id="memberList" class="space-y-3">
            <?php if (count($members) === 0): ?>
                <p class="text-center text-gray-500 py-12 bg-[#111318]/30 rounded-xl border border-gray-900">ยังไม่มีสมาชิกในตระกูล</p>
            <?php else: ?>
                <?php foreach ($members as $m): ?>
                    <?php
                    $name = htmlspecialchars($m['name']);
                    $fb   = htmlspecialchars($m['facebook_url']);
                    $avatar = htmlspecialchars($m['avatar_url'] ?: 'https://i.pravatar.cc/150?u=' . $m['id']);
                    ?>
                    <div class="card-row rounded-xl p-3.5 flex items-center justify-between gap-4">
                        <div class="flex items-center gap-3.5 min-w-0">
                            <img src="<?= $avatar ?>" alt="<?= $name ?>" referrerpolicy="no-referrer"
                                class="w-12 h-12 rounded-full object-cover avatar-ring flex-shrink-0">
                            <div class="min-w-0">
                                <?php if ($fb): ?>
                                    <a href="<?= $fb ?>" target="_blank" rel="noopener noreferrer" class="hover:text-white transition-colors duration-200">
                                        <p class="font-bold text-[15px] text-gray-200 truncate tracking-wide"><?= $name ?></p>
                                    </a>
                                <?php else: ?>
                                    <p class="font-bold text-[15px] text-gray-200 truncate tracking-wide"><?= $name ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php if ($fb): ?>
                            <a href="<?= $fb ?>" target="_blank" rel="noopener noreferrer"
                                class="fb-btn w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0"
                                title="เปิดโปรไฟล์ Facebook">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-[18px] h-[18px] text-gray-400 group-hover:text-white">
                                    <path d="M22 12.06C22 6.5 17.52 2 12 2S2 6.5 2 12.06c0 5 3.66 9.15 8.44 9.94v-7.03H7.9v-2.91h2.54V9.84c0-2.5 1.49-3.89 3.78-3.89 1.1 0 2.24.2 2.24.2v2.46h-1.26c-1.24 0-1.63.77-1.63 1.56v1.87h2.78l-.44 2.91h-2.34V22c4.78-.79 8.44-4.94 8.44-9.94z" />
                                </svg>
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <div id="youtube-player" class="hidden"></div>
    <script src="https://www.youtube.com/iframe_api"></script>

    <div class="fixed bottom-6 left-6 z-50 flex items-center gap-3 bg-[#111318]/90 border border-gray-800/80 py-2.5 px-4 rounded-full shadow-2xl backdrop-blur-md group transition-all duration-300 hover:border-gray-700">
        <button id="music-toggle" class="text-gray-400 hover:text-white flex items-center gap-2 transition-colors duration-200" title="เปิด/ปิดเพลง">
            <span id="music-icon" class="text-sm transition-transform active:scale-90">🔈</span>
            <span class="text-xs font-semibold tracking-wider text-gray-400 select-none">BGM</span>
        </button>
        <input type="range" id="volume-slider" min="0" max="100" value="30" 
            class="w-0 opacity-0 group-hover:w-20 group-hover:opacity-100 h-1 bg-gray-800 rounded-lg appearance-none cursor-pointer accent-gray-400 transition-all duration-300" 
            title="ปรับระดับเสียง">
    </div>

    <script>
        let player;
        let isPlaying = false;

        function onYouTubeIframeAPIReady() {
            player = new YT.Player('youtube-player', {
                height: '0',
                width: '0',
                videoId: 'syUBwHazoIc', 
                playerVars: {
                    'autoplay': 0,      
                    'loop': 1,          
                    'playlist': 'syUBwHazoIc', 
                    'controls': 0
                },
                events: {
                    'onReady': onPlayerReady
                }
            });
        }

        function onPlayerReady(event) {
            const toggleBtn = document.getElementById('music-toggle');
            const icon = document.getElementById('music-icon');
            const volumeSlider = document.getElementById('volume-slider');
            
            event.target.setVolume(30);
            volumeSlider.value = 30;

            toggleBtn.addEventListener('click', () => {
                if (!isPlaying) {
                    player.playVideo();
                    updateIcon(volumeSlider.value);
                    isPlaying = true;
                } else {
                    player.pauseVideo();
                    icon.innerText = '🔈'; 
                    isPlaying = false;
                }
            });

            volumeSlider.addEventListener('input', (e) => {
                const vol = e.target.value;
                player.setVolume(vol);
                if (isPlaying) {
                    updateIcon(vol);
                }
            });

            function updateIcon(vol) {
                if (vol == 0) {
                    icon.innerText = '🔇';
                } else if (vol < 40) {
                    icon.innerText = '🔈';
                } else if (vol < 80) {
                    icon.innerText = '🔉';
                } else {
                    icon.innerText = '🔊';
                }
            }
        }
    </script>

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
                memberList.innerHTML = '<p class="text-center text-gray-500 py-12 bg-[#111318]/30 rounded-xl border border-gray-900">ไม่พบสมาชิกที่ค้นหา</p>';
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
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-[18px] h-[18px] text-gray-400">
                    <path d="M22 12.06C22 6.5 17.52 2 12 2S2 6.5 2 12.06c0 5 3.66 9.15 8.44 9.94v-7.03H7.9v-2.91h2.54V9.84c0-2.5 1.49-3.89 3.78-3.89 1.1 0 2.24.2 2.24.2v2.46h-1.26c-1.24 0-1.63.77-1.63 1.56v1.87h2.78l-.44 2.91h-2.34V22c4.78-.79 8.44-4.94 8.44-9.94z"/>
                </svg>
            </a>` : '';

                const nameSection = fb ? `
                <a href="${fb}" target="_blank" rel="noopener noreferrer" class="hover:text-white transition-colors duration-200">
                    <p class="font-bold text-[15px] text-gray-200 truncate tracking-wide">${name}</p>
                </a>` : `<p class="font-bold text-[15px] text-gray-200 truncate tracking-wide">${name}</p>`;

                return `
        <div class="card-row rounded-xl p-3.5 flex items-center justify-between gap-4">
            <div class="flex items-center gap-3.5 min-w-0">
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

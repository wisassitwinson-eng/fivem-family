<?php

/**
 * index.php
 * หน้าแสดงรายชื่อสมาชิกตระกูล + ค้นหาแบบ Real-time (ฉบับเรียบหรูคงเดิม + อัปเกรดดีเทล 3 ข้อ + BGM เล่นออโต้)
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
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>👑</text></svg>">
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
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .card-row:hover {
            border-color: #3f4452;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.4);
            transform: translateY(-2px);
        }

        .avatar-ring {
            border: 2px solid #3a3f4b;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
            transition: border-color 0.3s ease;
        }

        .card-row:hover .avatar-ring {
            border-color: #52596b;
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
            <div class="inline-block relative">
                <div class="absolute -inset-1 bg-gradient-to-r from-amber-500/20 to-blue-500/20 rounded-full blur-md -z-10"></div>
                <p class="text-gray-500 text-xs uppercase tracking-[0.3em] mb-1">Idontknow</p>
                <h1 class="font-house text-3xl md:text-4xl font-bold text-gray-100 tracking-wide drop-shadow-[0_2px_10px_rgba(255,255,255,0.1)]">
                    House Idontknow
                </h1>
            </div>
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
                    // 1. ปรับรูปเริ่มต้นให้ใช้บริการ UI Avatars สีทอง-ดาร์ก ดูเข้าธีมและมีเส้นขอบชัดเจน
                    $avatar = htmlspecialchars($m['avatar_url'] ?: 'https://ui-avatars.com/api/?name=' . urlencode($m['name']) . '&background=2a2e37&color=cbd5e1&size=150');
                    ?>
                    <div class="card-row rounded-xl p-3 flex items-center justify-between gap-3">
                        <div class="flex items-center gap-3 min-w-0">
                            <img src="<?= $avatar ?>" alt="<?= $name ?>" referrerpolicy="no-referrer"
                                class="w-12 h-12 rounded-full object-cover avatar-ring flex-shrink-0 bg-[#2a2e37]">
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

        <div class="text-center mt-12 mb-6">
            <p class="text-gray-600 text-[11px] tracking-wider uppercase">© 2026 House Idontknow. All rights reserved.</p>
        </div>
    </div>

    <div id="youtube-player" class="hidden"></div>
    <script src="https://www.youtube.com/iframe_api"></script>

    <div class="fixed bottom-5 left-5 z-50 flex items-center gap-2.5 sm:gap-3 bg-[#15171c]/95 border border-gray-700 py-2 px-3.5 sm:px-4 rounded-full shadow-2xl backdrop-blur-md group transition-all duration-500 ease-out hover:scale-105 hover:border-gray-500 max-w-[200px] hover:max-w-[360px] sm:hover:max-w-[400px] overflow-hidden">
        
        <button id="mute-toggle" class="text-gray-300 hover:text-white flex items-center shrink-0 transition-transform active:scale-90" title="เปิด/ปิดเสียง">
            <span id="music-icon" class="text-base">🔈</span>
        </button>

        <div class="flex items-center gap-2 overflow-hidden shrink-0">
            <img id="bgm-cover" 
                 src="https://i.ytimg.com/vi/QgaZeV4GZaU/maxresdefault.jpg" 
                 alt="Cover" 
                 class="w-8 h-8 rounded-full object-cover shrink-0 border border-gray-600 shadow-sm animate-[spin_10s_linear_infinite]">
            
            <div class="flex flex-col justify-center overflow-hidden w-20 sm:w-24">
                <span class="text-[9px] text-gray-400 font-medium leading-none tracking-wider uppercase">Now Playing</span>
                <span id="bgm-title" class="text-xs font-semibold text-gray-200 truncate leading-tight mt-0.5">สถานีปลายทาง - SARAN</span>
            </div>
        </div>

        <div class="flex items-center gap-1 shrink-0 bg-gray-800/80 px-0 group-hover:px-2 py-1 rounded-full border border-transparent group-hover:border-gray-700 max-w-0 group-hover:max-w-[100px] opacity-0 group-hover:opacity-100 transition-all duration-500 ease-out overflow-hidden">
            <button id="prev-btn" class="text-gray-400 hover:text-white transition-colors active:scale-90 shrink-0" title="เพลงก่อนหน้า">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M6 6h2v12H6zm3.5 6l8.5 6V6z"/></svg>
            </button>
            
            <button id="play-pause-btn" class="text-gray-200 hover:text-white transition-transform active:scale-90 w-5 h-5 flex items-center justify-center shrink-0" title="เล่น / หยุด">
                <svg id="play-icon" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 fill-current block" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                <svg id="pause-icon" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 fill-current hidden" viewBox="0 0 24 24"><path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/></svg>
            </button>

            <button id="next-btn" class="text-gray-400 hover:text-white transition-colors active:scale-90 shrink-0" title="เพลงถัดไป">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M6 18l8.5-6L6 6v12zM16 6v12h2V6h-2z"/></svg>
            </button>
        </div>

        <input type="range" id="volume-slider" min="0" max="100" value="30" 
            class="w-0 opacity-0 group-hover:w-12 sm:group-hover:w-16 group-hover:max-w-[60px] sm:group-hover:max-w-[80px] group-hover:opacity-100 group-hover:ml-0.5 h-1 bg-gray-700 rounded-lg appearance-none cursor-pointer accent-gray-400 transition-all duration-500 ease-out shrink-0" 
            title="ปรับระดับเสียง">
    </div>

    <script>
        let player;
        let isPlaying = false;
        let isMuted = false;
        let previousVolume = 30;

        const myPlaylist = [
            'QgaZeV4GZaU', // 0: SARAN x เถาวัลย์ - สถานีปลายทาง
            'syUBwHazoIc', // 1: Pondering - Flowers for u
            'YThCJNzrp3Q'  // 2: Pondering - ไม่ต้องกระซิบ
        ];

        const trackDetails = [
            { title: "สถานีปลายทาง - SARAN", id: "QgaZeV4GZaU" },
            { title: "Flowers for u - Pondering", id: "syUBwHazoIc" },
            { title: "ไม่ต้องกระซิบ - Pondering", id: "YThCJNzrp3Q" }
        ];

        let currentTrackIndex = 0;

        function onYouTubeIframeAPIReady() {
            player = new YT.Player('youtube-player', {
                height: '0',
                width: '0',
                videoId: myPlaylist[0], 
                playerVars: {
                    'autoplay': 1,      
                    'loop': 1,          
                    'playlist': myPlaylist.join(','), 
                    'controls': 0
                },
                events: {
                    'onReady': onPlayerReady,
                    'onStateChange': onPlayerStateChange
                }
            });
        }

        function onPlayerReady(event) {
            const muteBtn = document.getElementById('mute-toggle');
            const playPauseBtn = document.getElementById('play-pause-btn');
            const prevBtn = document.getElementById('prev-btn');
            const nextBtn = document.getElementById('next-btn');
            const icon = document.getElementById('music-icon');
            const volumeSlider = document.getElementById('volume-slider');
            
            player.mute();
            player.playVideo();
            volumeSlider.value = 30;

            const unlockAudio = () => {
                if (player && typeof player.unMute === 'function') {
                    player.unMute();
                    player.setVolume(30);
                    player.playVideo();
                    
                    if (!player.isMuted()) {
                        volumeSlider.value = 30;
                        updateIcon(30);
                        isPlaying = true;
                        window.removeEventListener('click', unlockAudio);
                        window.removeEventListener('scroll', unlockAudio);
                        window.removeEventListener('keydown', unlockAudio);
                        window.removeEventListener('touchstart', unlockAudio);
                        window.removeEventListener('pointerdown', unlockAudio);
                    }
                }
            };

            window.addEventListener('click', unlockAudio);
            window.addEventListener('scroll', unlockAudio);
            window.addEventListener('keydown', unlockAudio);
            window.addEventListener('touchstart', unlockAudio);
            window.addEventListener('pointerdown', unlockAudio);

            playPauseBtn.addEventListener('click', () => {
                if (!isPlaying) {
                    player.playVideo();
                } else {
                    player.pauseVideo();
                }
            });

            muteBtn.addEventListener('click', () => {
                if (!isMuted) {
                    previousVolume = volumeSlider.value > 0 ? volumeSlider.value : 30;
                    player.setVolume(0);
                    volumeSlider.value = 0;
                    icon.innerText = '🔇';
                    isMuted = true;
                } else {
                    player.setVolume(previousVolume);
                    volumeSlider.value = previousVolume;
                    updateIcon(previousVolume);
                    isMuted = false;
                }
            });

            nextBtn.addEventListener('click', () => {
                currentTrackIndex = (currentTrackIndex + 1) % myPlaylist.length;
                player.nextVideo();
                updateTrackInfo(currentTrackIndex);
            });

            prevBtn.addEventListener('click', () => {
                currentTrackIndex = (currentTrackIndex - 1 + myPlaylist.length) % myPlaylist.length;
                player.previousVideo();
                updateTrackInfo(currentTrackIndex);
            });

            volumeSlider.addEventListener('input', (e) => {
                const vol = e.target.value;
                player.setVolume(vol);
                if (vol > 0) isMuted = false;
                updateIcon(vol);
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

        function onPlayerStateChange(event) {
            const playIcon = document.getElementById('play-icon');
            const pauseIcon = document.getElementById('pause-icon');
            const cover = document.getElementById('bgm-cover');

            if (event.data === YT.PlayerState.PLAYING) {
                isPlaying = true;
                playIcon.classList.add('hidden');
                pauseIcon.classList.remove('hidden');
                pauseIcon.classList.add('block');
                cover.style.animationPlayState = 'running';
            } else {
                isPlaying = false;
                playIcon.classList.remove('hidden');
                playIcon.classList.add('block');
                pauseIcon.classList.add('hidden');
                cover.style.animationPlayState = 'paused';
            }
        }

        function updateTrackInfo(index) {
            const track = trackDetails[index];
            if (track) {
                document.getElementById('bgm-title').innerText = track.title;
                document.getElementById('bgm-cover').src = `https://i.ytimg.com/vi/${track.id}/maxresdefault.jpg`;
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
                memberList.innerHTML = '<p class="text-center text-gray-500 py-10">ไม่พบสมาชิกที่ค้นหา</p>';
                memberCount.textContent = 0;
                return;
            }

            memberCount.textContent = members.length;

            memberList.innerHTML = members.map(m => {
                const name = escapeHtml(m.name);
                const fb = m.facebook_url ? escapeHtml(m.facebook_url) : '';
                // 1. อัปเกรด JavaScript ให้ตอนพิมพ์ค้นหา ก็ดึงรูป Default สไตล์เดียวกันมาแสดงผล
                const avatar = escapeHtml(m.avatar_url || ('https://ui-avatars.com/api/?name=' . urlencode(m.name) . '&background=2a2e37&color=cbd5e1&size=150'));

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
                <img src="${avatar}" alt="${name}" referrerpolicy="no-referrer" class="w-12 h-12 rounded-full object-cover avatar-ring flex-shrink-0 bg-[#2a2e37]">
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

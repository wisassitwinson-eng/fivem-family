<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome | Idontknow</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>👑</text></svg>">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Noto+Sans+Thai:wght@300;400;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }

        body {
            font-family: 'Noto Sans Thai', sans-serif;
            background-color: #000;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        /* พื้นหลัง aura สีแดงเข้ม */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background:
                radial-gradient(ellipse 70% 50% at 50% 0%, rgba(180, 20, 20, 0.18) 0%, transparent 65%),
                radial-gradient(ellipse 60% 40% at 50% 100%, rgba(100, 10, 10, 0.12) 0%, transparent 60%),
                radial-gradient(ellipse 40% 60% at 10% 50%, rgba(80, 0, 0, 0.08) 0%, transparent 60%),
                radial-gradient(ellipse 40% 60% at 90% 50%, rgba(80, 0, 0, 0.08) 0%, transparent 60%);
            pointer-events: none;
            z-index: 0;
        }

        /* เส้นกริดบางๆ เพิ่มลุค */
        body::after {
            content: '';
            position: fixed;
            inset: 0;
            background-image:
                linear-gradient(rgba(255,255,255,0.015) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.015) 1px, transparent 1px);
            background-size: 60px 60px;
            pointer-events: none;
            z-index: 0;
        }

        .content-wrapper {
            position: relative;
            z-index: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 100%;
            padding: 2rem 1.5rem;
            gap: 2rem;
        }

        /* ---- TEXT ---- */
        .welcome-label {
            font-family: 'Noto Sans Thai', sans-serif;
            font-weight: 300;
            font-size: clamp(0.85rem, 2.5vw, 1.1rem);
            color: rgba(255,255,255,0.45);
            letter-spacing: 0.35em;
            text-transform: uppercase;
            margin-bottom: -0.5rem;
        }

        .main-title {
            font-family: 'Bebas Neue', sans-serif;
            font-size: clamp(3.5rem, 14vw, 7.5rem);
            color: #fff;
            letter-spacing: 0.04em;
            line-height: 0.9;
            text-align: center;
            text-shadow:
                0 0 40px rgba(200, 30, 30, 0.35),
                0 0 80px rgba(200, 30, 30, 0.15);
        }

        .sub-title {
            font-family: 'Bebas Neue', sans-serif;
            font-size: clamp(1.2rem, 5vw, 2.4rem);
            color: #c0392b;
            letter-spacing: 0.25em;
            text-transform: uppercase;
            margin-top: 0.2rem;
            text-shadow: 0 0 20px rgba(192, 57, 43, 0.6);
        }

        /* ---- DIVIDER ---- */
        .divider {
            width: clamp(60px, 20vw, 100px);
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(192,57,43,0.7), transparent);
        }

        /* ---- BUTTON ---- */
        .btn-view {
            position: relative;
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            padding: 0.85rem 2.5rem;
            font-family: 'Noto Sans Thai', sans-serif;
            font-weight: 700;
            font-size: clamp(0.85rem, 2.5vw, 1rem);
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: #fff;
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.18);
            border-radius: 9999px;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.3s ease;
            backdrop-filter: blur(8px);
            overflow: hidden;
        }

        .btn-view::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(192,57,43,0.15) 0%, transparent 60%);
            border-radius: 9999px;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .btn-view:hover {
            border-color: rgba(192, 57, 43, 0.7);
            box-shadow:
                0 0 20px rgba(192, 57, 43, 0.25),
                0 0 40px rgba(192, 57, 43, 0.1),
                inset 0 0 20px rgba(192, 57, 43, 0.05);
            transform: translateY(-2px);
            color: #fff;
        }

        .btn-view:hover::before {
            opacity: 1;
        }

        .btn-arrow {
            transition: transform 0.3s ease;
            font-size: 1.1em;
        }

        .btn-view:hover .btn-arrow {
            transform: translateX(4px);
        }

        /* ---- LOGO CARD ---- */
        .logo-card {
            position: relative;
            width: clamp(260px, 80vw, 420px);
            background: rgba(255,255,255,0.03);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 1.25rem;
            overflow: hidden;
            box-shadow:
                0 0 0 1px rgba(192,57,43,0.08),
                0 8px 32px rgba(0,0,0,0.6),
                0 0 60px rgba(192,57,43,0.06);
            transition: box-shadow 0.4s ease, transform 0.4s ease;
        }

        .logo-card:hover {
            box-shadow:
                0 0 0 1px rgba(192,57,43,0.2),
                0 12px 40px rgba(0,0,0,0.7),
                0 0 80px rgba(192,57,43,0.12);
            transform: translateY(-3px);
        }

        /* ป้ายมุมบนของการ์ด */
        .card-badge {
            position: absolute;
            top: 0.75rem;
            left: 0.75rem;
            font-size: 0.6rem;
            letter-spacing: 0.2em;
            color: rgba(255,255,255,0.3);
            text-transform: uppercase;
            font-family: 'Noto Sans Thai', sans-serif;
        }

        .logo-card img {
            width: 100%;
            height: auto;
            display: block;
            object-fit: contain;
            padding: 1.5rem 2rem;
            mix-blend-mode: normal;
        }

        /* ---- FADE IN ---- */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .fade-1 { animation: fadeUp 0.7s ease both; }
        .fade-2 { animation: fadeUp 0.7s 0.15s ease both; }
        .fade-3 { animation: fadeUp 0.7s 0.3s ease both; }
        .fade-4 { animation: fadeUp 0.7s 0.45s ease both; }
        .fade-5 { animation: fadeUp 0.7s 0.6s ease both; }

        /* reduced motion */
        @media (prefers-reduced-motion: reduce) {
            .fade-1, .fade-2, .fade-3, .fade-4, .fade-5 { animation: none; }
        }
    </style>
</head>
<body>
    <div class="content-wrapper">

        <!-- Welcome label -->
        <p class="welcome-label fade-1">Welcome to</p>

        <!-- Main title -->
        <div class="text-center fade-2" style="margin-top: -0.5rem;">
            <div class="main-title">IDONTKNOW</div>
            <div class="sub-title">ACADEMY</div>
        </div>

        <!-- Divider -->
        <div class="divider fade-3"></div>

        <!-- Button -->
        <a href="index.php" class="btn-view fade-4">
            View Member List
            <span class="btn-arrow">→</span>
        </a>

        <!-- Logo Card -->
        <div class="logo-card fade-5">
            <span class="card-badge">House of Idontknow</span>
            <img
                src="LOGO BG 1.png"
                alt="Idontknow Logo"
                onerror="this.src='LOGO BG 2.png'; this.onerror=null;"
            >
        </div>

    </div>
</body>
</html>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $article['title'] }} | Green Palm</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --gp-dark-green: #0F3D24;
            --gp-primary-green: #1F5F38;
            --gp-accent-gold: #FFD700;
            --gp-gold-dark: #D4AF37;
            --gp-light: #F4F9F6;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Poppins', sans-serif; background: var(--gp-light); color: #222; }

        .article-navbar {
            background: var(--gp-dark-green);
            padding: 18px 0;
        }
        .article-navbar .navbar-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .article-navbar a.back-link {
            color: var(--gp-accent-gold);
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .article-navbar a.back-link:hover { color: #ffe44d; }

        /* =========================================
           LOGO GREEN PALM (Animasi Kilap)
           ========================================= */
        @keyframes shineLeftToRightText {
            0% { background-position: 200% 0, 0 0; }
            100% { background-position: -200% 0, 0 0; }
        }
        @keyframes shineLeftToRightLogo {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }

        .article-navbar .brand-decoration {
            display: flex;
            align-items: center;
            text-decoration: none;
            cursor: default;
            user-select: none;
        }

        .article-navbar .logo-container {
            position: relative;
            width: 40px;
            height: 40px;
            filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.3));
            margin-right: 10px;
        }
        .article-navbar .logo-container img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            display: block;
        }
        .article-navbar .logo-container::after {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: linear-gradient(
                110deg,
                transparent 35%,
                rgba(255, 255, 255, 0.8) 45%,
                rgba(255, 255, 255, 1.0) 50%,
                rgba(255, 255, 255, 0.8) 55%,
                transparent 65%
            );
            background-size: 200% 100%;
            background-repeat: no-repeat;
            animation: shineLeftToRightLogo 3.5s infinite linear;

            -webkit-mask-image: url("{{ asset('images/logo.png') }}");
            -webkit-mask-size: contain;
            -webkit-mask-repeat: no-repeat;
            -webkit-mask-position: center;
            mask-image: url("{{ asset('images/logo.png') }}");
            mask-size: contain;
            mask-repeat: no-repeat;
            mask-position: center;
            pointer-events: none;
        }

        .article-navbar .brand-text-wrapper {
            font-weight: 800;
            letter-spacing: 1px;
            font-size: 18px;
            line-height: 1;
            display: flex;
            gap: 5px;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.3));
        }
        .article-navbar .shiny-text {
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            background-size: 200% 100%, 100% 100%;
            background-repeat: no-repeat;
            animation: shineLeftToRightText 3.5s infinite linear;
            animation-delay: 0.4s;
            text-decoration: none;
        }
        .article-navbar .text-green {
            background-image:
                linear-gradient(110deg, transparent 35%, rgba(255,255,255,0.9) 50%, transparent 65%),
                linear-gradient(180deg, #6ee7b7 0%, #68ae11 100%);
        }
        .article-navbar .text-palm {
            background-image:
                linear-gradient(110deg, transparent 35%, rgba(255,255,255,0.9) 50%, transparent 65%),
                linear-gradient(180deg, #fbbf24 0%, #dfa91b 100%);
        }

        @media (max-width: 576px) {
            .article-navbar .brand-text-wrapper { font-size: 15px; }
            .article-navbar .logo-container { width: 32px; height: 32px; }
        }

        /* =========================================
           ARTICLE CONTENT
           ========================================= */
        .article-hero {
            width: 100%;
            max-height: 420px;
            object-fit: cover;
            border-radius: 0 0 32px 32px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.15);
        }

        .article-wrapper {
            max-width: 800px;
            margin: -60px auto 0;
            background: white;
            border-radius: 24px;
            padding: 45px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.08);
            position: relative;
            z-index: 2;
        }

        .article-date {
            color: var(--gp-primary-green);
            font-weight: 600;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .article-title {
            font-size: 2.2rem;
            font-weight: 800;
            color: var(--gp-dark-green);
            margin: 15px 0 30px;
            line-height: 1.3;
        }

        .article-content {
            font-size: 17px;
            line-height: 1.9;
            color: #333;
            white-space: pre-line;
        }

        .related-section {
            max-width: 800px;
            margin: 60px auto 80px;
        }
        .related-card {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 6px 20px rgba(0,0,0,0.08);
            transition: transform 0.3s ease;
            height: 100%;
        }
        .related-card:hover { transform: translateY(-5px); }
        .related-card img { height: 140px; width: 100%; object-fit: cover; }
        .related-card .body { padding: 15px; }
        .related-card h6 { font-weight: 700; color: var(--gp-dark-green); font-size: 14px; line-height: 1.5; }
        .related-card a { color: var(--gp-primary-green); font-weight: 600; font-size: 13px; }

        @media (max-width: 768px) {
            .article-wrapper { margin: -40px 15px 0; padding: 25px; }
            .article-title { font-size: 1.6rem; }
        }
    </style>
</head>
<body>

    <nav class="article-navbar">
        <div class="container navbar-content">
            <a href="{{ url('/') }}#berita" class="back-link">&larr; Kembali ke Berita</a>

            <div class="brand-decoration">
                <div class="logo-container">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo">
                </div>
                <div class="brand-text-wrapper">
                    <span class="shiny-text text-green">GREEN</span>
                    <span class="shiny-text text-palm">PALM</span>
                </div>
            </div>
        </div>
    </nav>

    <img src="{{ asset($article['image']) }}" alt="{{ $article['title'] }}" class="article-hero">

    <div class="container">
        <div class="article-wrapper">
            <span class="article-date">
                {{ \Carbon\Carbon::parse($article['date'])->translatedFormat('d F Y') }}
            </span>
            <h1 class="article-title">{{ $article['title'] }}</h1>
            <div class="article-content">{{ $article['content'] }}</div>
        </div>

        @if($related->count())
        <div class="related-section">
            <h4 class="fw-bold mb-4" style="color: var(--gp-dark-green);">Berita Lainnya</h4>
            <div class="row g-3">
                @foreach($related as $slug => $item)
                <div class="col-md-4">
                    <div class="related-card">
                        <img src="{{ asset($item['image']) }}" alt="{{ $item['title'] }}">
                        <div class="body">
                            <h6>{{ $item['title'] }}</h6>
                            <a href="{{ route('berita.show', $slug) }}">Baca &rarr;</a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

</body>
</html>
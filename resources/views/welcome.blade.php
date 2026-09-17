<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Green Palm | Solusi Digital Perkebunan Sawit</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --gp-dark-green: #0F3D24;
            --gp-primary-green: #1F5F38;
            --gp-accent-gold: #FFD700;
            --gp-gold-dark: #D4AF37;
            --gp-light: #F4F9F6;
            --gp-text-dark: #13321f;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: "Poppins", sans-serif;
            background-color: var(--gp-dark-green);
            color: var(--gp-text-dark);
            overflow-x: hidden;
        }

        a { text-decoration: none; transition: 0.3s; }
        html { scroll-behavior: smooth; }

        /* =========================================
        1. ANIMASI KILAP (KIRI KE KANAN)
        ========================================= */
        
        /* Animasi untuk TEKS (2 Layer: Kilap Jalan, Warna Diam) */
        @keyframes shineLeftToRightText {
            0% {
                /* Kilap di KIRI JAUH (-200%), Warna diam di (0) */
                background-position: 200% 0, 0 0;
            }
            100% {
                /* Kilap di KANAN JAUH (200%), Warna diam di (0) */
                background-position: -200% 0, 0 0;
            }
        }

        /* Animasi untuk LOGO (1 Layer Masking) */
        @keyframes shineLeftToRightLogo {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }

        /* =========================================
        2. SETUP LOGO (MULAI DULUAN)
        ========================================= */
        .logo-container {
            position: relative;
            width: 50px;
            height: 50px;
            filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.3));
            transition: transform 0.3s ease;
            margin-right: 12px;
            z-index: 10;
        }

        .logo-container img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            display: block;
        }

        /* Layer Kilap Logo */
        .logo-container::after {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            
            /* Kilap Miring 110deg (Jatuh dari Kiri Atas ke Kanan Bawah) */
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
            
            /* Animasi 3.5 detik, Delay 0 detik (Mulai duluan) */
            animation: shineLeftToRightLogo 3.5s infinite linear;
            animation-delay: 0s; 
            
            /* Masking */
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

        /* =========================================
        3. SETUP TEKS (MUNCUL SETELAH LOGO)
        ========================================= */
        .brand-text-wrapper {
            font-weight: 800;
            letter-spacing: 1.5px;
            font-size: 24px;
            line-height: 1;
            display: flex;
            gap: 6px;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.3)); 
        }

        .shiny-text {
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;

            /* PENTING: Layer 1 (200%) untuk kilap, Layer 2 (100%) untuk warna dasar */
            background-size: 200% 100%, 100% 100%;
            background-repeat: no-repeat;
            
            /* Animasi Text */
            animation: shineLeftToRightText 3.5s infinite linear;
            /* Delay 0.4 detik agar menunggu kilap logo lewat dulu */
            animation-delay: 0.4s; 
        }

        /* TEXT GREEN */
        .text-green {
            background-image: 
                /* Layer 1 (Atas): Kilap Putih Miring 110deg */
                linear-gradient(110deg, transparent 35%, rgba(255,255,255,0.9) 50%, transparent 65%),
                /* Layer 2 (Bawah): Warna Hijau Solid (DIAM) */
                linear-gradient(180deg, #6ee7b7 0%, #68ae11 100%);
        }

        /* TEXT PALM */
        .text-palm {
            background-image: 
                /* Layer 1 (Atas): Kilap Putih Miring 110deg */
                linear-gradient(110deg, transparent 35%, rgba(255,255,255,0.9) 50%, transparent 65%),
                /* Layer 2 (Bawah): Warna Emas Solid (DIAM) */
                linear-gradient(180deg, #fbbf24 0%, #dfa91b 100%);
        }

        /* Hover Effect */
        .navbar-brand:hover .logo-container {
            transform: scale(1.1) rotate(5deg);
        }

        /* === BAGIAN LAIN TETAP SAMA === */
        .gp-navbar {
            background: rgba(15, 61, 36, 0.95);
            backdrop-filter: blur(15px);
            padding: 12px 0;
            transition: all 0.4s ease;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
        .gp-navbar.scrolled {
            padding: 8px 0;
            background: rgba(15, 61, 36, 0.98);
            box-shadow: 0 6px 25px rgba(0, 0, 0, 0.2);
        }
        .gp-navbar .nav-link {
            color: rgba(255, 255, 255, 0.9) !important;
            font-weight: 500;
            margin: 0 15px;
            position: relative;
            padding: 8px 0;
        }
        .gp-navbar .nav-link::after {
            content: ''; position: absolute; bottom: 0; left: 0; width: 0; height: 2px;
            background: var(--gp-accent-gold); transition: width 0.3s ease;
        }
        .gp-navbar .nav-link:hover::after, .gp-navbar .nav-link.active::after { width: 100%; }
        .gp-navbar .nav-link:hover, .gp-navbar .nav-link.active { color: var(--gp-accent-gold) !important; }

        .btn-login {
            background: linear-gradient(135deg, var(--gp-accent-gold) 0%, var(--gp-gold-dark) 100%);
            color: var(--gp-dark-green); border-radius: 50px; padding: 10px 30px; font-weight: 600;
            border: 2px solid transparent; box-shadow: 0 4px 15px rgba(255, 215, 0, 0.3); transition: all 0.3s ease;
        }
        .btn-login:hover {
            background: transparent; color: var(--gp-accent-gold); border-color: var(--gp-accent-gold);
            transform: translateY(-2px); box-shadow: 0 6px 20px rgba(255, 215, 0, 0.4);
        }

        /* Hero & Sections - Sama persis seperti sebelumnya */
        .hero { position: relative; height: 100vh; min-height: 700px; display: flex; align-items: center; color: white; overflow: hidden; }
        .hero-bg { position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: url("images/palm-welcome.png") center/cover no-repeat; z-index: 1; animation: zoomIn 20s ease-in-out infinite alternate; }
        @keyframes zoomIn { 0% { transform: scale(1); } 100% { transform: scale(1.1); } }
        .hero-overlay { position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(120deg, rgba(15,61,36,0.95) 0%, rgba(15,61,36,0.85) 40%, rgba(15,61,36,0.4) 100%); z-index: 2; }
        .hero-particles { position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: 2; overflow: hidden; }
        .hero-content { position: relative; z-index: 3; max-width: 750px; animation: fadeInUp 1s ease-out; }
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(40px); } to { opacity: 1; transform: translateY(0); } }
        .hero-title { font-size: 4rem; font-weight: 800; line-height: 1.2; margin-bottom: 25px; text-shadow: 2px 4px 8px rgba(0, 0, 0, 0.3); }
        .hero-subtitle { font-size: 1.3rem; font-weight: 400; margin-bottom: 35px; color: #f0f0f0; line-height: 1.7; }
        .btn-hero { background: linear-gradient(135deg, var(--gp-accent-gold) 0%, var(--gp-gold-dark) 100%); color: var(--gp-dark-green); padding: 15px 45px; font-weight: 700; border-radius: 50px; box-shadow: 0 10px 30px rgba(255, 215, 0, 0.4); border: none; transition: all 0.4s ease; display: inline-block; }
        .btn-hero:hover { transform: translateY(-5px); box-shadow: 0 15px 40px rgba(255, 215, 0, 0.5); background: linear-gradient(135deg, var(--gp-gold-dark) 0%, var(--gp-accent-gold) 100%); }

        .gp-section { padding: 100px 0; position: relative; }
        .section-title { font-size: 2.5rem; font-weight: 600; color: white; margin-top: 10px; margin-bottom: 15px; text-align: center; text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2); }
        .section-subtitle { 
            color: var(--gp-accent-gold); 
            
            /* UKURAN: 4rem (sekitar 64px), lebih besar dari judul di bawahnya */
            font-size: 4rem; 
            
            text-align: center; 
            
            /* KETEBALAN: Extra Bold agar mendominasi */
            font-weight: 900; 
            
            /* Jarak bawah diperkecil agar menyatu dengan kalimat di bawahnya */
            margin-bottom: 0px; 
            
            text-transform: uppercase; 
            
            /* Letter spacing dikurangi sedikit agar teks besar tidak terlalu renggang */
            letter-spacing: 1px; 
            
            line-height: 1.1;
        }     
        .svc-card { background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%); border-radius: 24px; padding: 35px 25px; text-align: center; height: 100%; transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); cursor: pointer; border: 3px solid transparent; position: relative; overflow: hidden; }
        .svc-card::before { content: ''; position: absolute; top: 0; left: -100%; width: 100%; height: 100%; background: linear-gradient(90deg, transparent, rgba(255, 215, 0, 0.1), transparent); transition: left 0.5s ease; }
        .svc-card:hover::before { left: 100%; }
        .svc-card:hover { transform: translateY(-15px) scale(1.02); box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15); }
        .svc-card.active { border-color: var(--gp-accent-gold); background: linear-gradient(135deg, #fffef7 0%, #fff9e6 100%); box-shadow: 0 15px 35px rgba(255, 215, 0, 0.3); transform: translateY(-10px); }
        .svc-icon-wrapper { width: 100px; height: 100px; background: linear-gradient(135deg, #f0f7f2 0%, #e8f5ec 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 25px; transition: all 0.4s ease; box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08); }
        .svc-card:hover .svc-icon-wrapper, .svc-card.active .svc-icon-wrapper { background: linear-gradient(135deg, var(--gp-accent-gold) 0%, var(--gp-gold-dark) 100%); transform: rotate(360deg) scale(1.1); box-shadow: 0 8px 20px rgba(255, 215, 0, 0.4); }
        .svc-icon { width: 55px; height: 55px; object-fit: contain; transition: transform 0.3s ease; }
        .svc-title { font-weight: 700; font-size: 1.15rem; color: var(--gp-primary-green); margin-top: 10px; }

        .partner-box { background: linear-gradient(135deg, #ffffff 0%, #fafbfc 100%); border-radius: 32px; padding: 50px; box-shadow: 0 25px 60px rgba(0, 0, 0, 0.12); border: 1px solid rgba(255, 215, 0, 0.1); transition: all 0.5s ease; }
        .fade-in { animation: fadeInScale 0.6s ease-in-out forwards; }
        @keyframes fadeInScale { from { opacity: 0; transform: scale(0.95); } to { opacity: 1; transform: scale(1); } }
        .feature-check-list { list-style: none; padding: 0; margin: 30px 0; }
        .feature-check-list li { position: relative; padding-left: 38px; margin-bottom: 18px; font-weight: 600; font-size: 17px; color: #2c2c2c; transition: all 0.3s ease; }
        .feature-check-list li:hover { color: var(--gp-primary-green); transform: translateX(5px); }
        .feature-check-list li::before { content: '✓'; position: absolute; left: 0; top: 0; font-size: 22px; color: var(--gp-primary-green); font-weight: 900; width: 28px; height: 28px; background: linear-gradient(135deg, #e8f5ec 0%, #d4edda 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; }
        .feature-img-full { width: 100%; height: auto; border-radius: 24px; box-shadow: 0 15px 40px rgba(0, 0, 0, 0.12); transition: all 0.4s ease; }
        .feature-img-full:hover { transform: translateY(-10px); box-shadow: 0 20px 50px rgba(0, 0, 0, 0.18); }

        .highlight-container { padding: 100px 0; position: relative; }
        .highlight-container::before { content: ''; position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: radial-gradient(circle at 30% 50%, rgba(255, 215, 0, 0.05) 0%, transparent 50%); pointer-events: none; }
        .highlight-title { font-size: 3rem; font-weight: 800; color: white; line-height: 1.3; }
        .text-gold { color: var(--gp-accent-gold); text-shadow: 0 0 20px rgba(255, 215, 0, 0.3); }
        .btn-outline-gold { border: 3px solid var(--gp-accent-gold); color: var(--gp-accent-gold); border-radius: 50px; padding: 12px 35px; font-weight: 700; transition: all 0.4s ease; box-shadow: 0 4px 15px rgba(255, 215, 0, 0.2); }
        .btn-outline-gold:hover { background: linear-gradient(135deg, var(--gp-accent-gold) 0%, var(--gp-gold-dark) 100%); color: var(--gp-dark-green); transform: translateY(-3px); box-shadow: 0 8px 25px rgba(255, 215, 0, 0.4); }

        .news-card { background: linear-gradient(135deg, #1a4a2e 0%, #15422a 100%); border-radius: 20px; overflow: hidden; border: 2px solid rgba(255, 255, 255, 0.08); height: 100%; display: flex; flex-direction: column; transition: all 0.4s ease; position: relative; }
        .news-card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 4px; background: linear-gradient(90deg, var(--gp-accent-gold), var(--gp-gold-dark)); transform: scaleX(0); transition: transform 0.4s ease; }
        .news-card:hover::before { transform: scaleX(1); }
        .news-card:hover { border-color: var(--gp-accent-gold); transform: translateY(-10px); box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3); }
        .news-img { height: 240px; width: 100%; object-fit: cover; transition: transform 0.5s ease; }
        .news-card:hover .news-img { transform: scale(1.08); }
        .news-body { padding: 25px; display: flex; flex-direction: column; flex-grow: 1; }
        .news-title { font-size: 1.2rem; font-weight: 700; color: white; margin-bottom: 15px; line-height: 1.5; }
        .news-desc { font-size: 0.95rem; color: #c5d9ce; margin-bottom: 20px; flex-grow: 1; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; }
        .news-link { color: var(--gp-accent-gold); font-weight: 600; font-size: 0.95rem; transition: all 0.3s ease; display: inline-block; }
        .news-link:hover { color: #ffe44d; transform: translateX(5px); }

        .exp-section { background: linear-gradient(135deg, var(--gp-light) 0%, #ffffff 100%); border-radius: 50px 50px 0 0; padding: 80px 0; box-shadow: 0 -10px 40px rgba(0, 0, 0, 0.1); }
        .exp-card { background: white; border-radius: 28px; padding: 45px; box-shadow: 0 15px 40px rgba(0, 0, 0, 0.08); border: 1px solid rgba(31, 95, 56, 0.1); }
        .form-control-custom { background: #f8f9fa; border: 2px solid #e8ecef; border-radius: 14px; padding: 16px; transition: all 0.3s ease; }
        .form-control-custom:focus { box-shadow: 0 0 0 4px rgba(31, 95, 56, 0.1); border-color: var(--gp-primary-green); background: white; }
        .testimoni-card { background: linear-gradient(135deg, #ffffff 0%, #f8fafb 100%); border-radius: 28px; padding: 45px; text-align: center; max-width: 850px; margin: 0 auto; box-shadow: 0 15px 40px rgba(0, 0, 0, 0.08); border: 2px solid rgba(31, 95, 56, 0.05); }
        .quote-icon { font-size: 50px; color: var(--gp-accent-gold); line-height: 1; text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1); }
        .contact-card { background: linear-gradient(135deg, #ffffff 0%, #f8fafb 100%); border-radius: 20px; padding: 35px 25px; text-align: center; height: 100%; transition: all 0.4s ease; border: 2px solid rgba(31, 95, 56, 0.08); }
        .contact-card:hover { transform: translateY(-8px); box-shadow: 0 15px 35px rgba(0, 0, 0, 0.12); border-color: var(--gp-accent-gold); }
        .contact-icon { width: 70px; height: 70px; margin-bottom: 20px; transition: transform 0.3s ease; }
        .contact-card:hover .contact-icon { transform: scale(1.1) rotate(5deg); }

        footer { background: linear-gradient(135deg, #0a2918 0%, #0d3320 100%); color: #ffffff; padding: 50px 0; border-top: none; font-size: 14px; }
        .footer-content-wrapper { display: flex; flex-direction: column; align-items: center; text-align: center; gap: 8px; }
        .footer-copyright { font-size: 16px; font-weight: 500; margin: 0; opacity: 1; margin-bottom: 5px; }
        .footer-subtitle { font-size: 14px; font-weight: 400; opacity: 0.8; margin: 0; }
        .footer-contact { font-size: 14px; font-weight: 400; opacity: 0.8; margin: 0 0 25px 0; }
        .footer-social-container { display: flex; justify-content: center; gap: 20px; }
        .footer-icon-link { display: flex; align-items: center; justify-content: center; width: 45px; height: 45px; border-radius: 50%; border: 1px solid rgba(255, 255, 255, 0.15); background: rgba(255, 255, 255, 0.05); transition: all 0.3s ease; }
        .footer-icon-img { width: 22px; height: 22px; object-fit: contain; transition: all 0.3s ease; filter: brightness(0) invert(1); opacity: 0.8; }
        .footer-icon-link:hover { background: var(--gp-accent-gold); border-color: var(--gp-accent-gold); transform: translateY(-5px); box-shadow: 0 5px 15px rgba(255, 215, 0, 0.3); }
        .footer-icon-link:hover .footer-icon-img { opacity: 1; filter: brightness(0) saturate(100%) invert(13%) sepia(35%) saturate(836%) hue-rotate(98deg) brightness(93%) contrast(92%); }

        @media (max-width: 992px) { .hero-title { font-size: 3rem; } .section-title { font-size: 2.5rem; } .highlight-title { font-size: 2.5rem; } }
        @media (max-width: 768px) { .hero { height: auto; min-height: 100vh; padding: 120px 0 60px; } .hero-title { font-size: 2.5rem; } .hero-subtitle { font-size: 1.1rem; } .hero-content { padding: 0 20px; text-align: center; } .section-title { font-size: 2rem; } .partner-box { padding: 30px; } .gp-section { padding: 60px 0; } .highlight-title { font-size: 2rem; } .exp-header-wrapper { flex-direction: column; align-items: center; text-align: center; } .exp-title-text { font-size: 1.8rem; margin-bottom: 15px; } .exp-mascot-img { width: 65px; margin-left: 0; } }

        .scroll-top { position: fixed; bottom: 30px; right: 30px; width: 50px; height: 50px; background: linear-gradient(135deg, var(--gp-accent-gold) 0%, var(--gp-gold-dark) 100%); color: var(--gp-dark-green); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 24px; box-shadow: 0 5px 20px rgba(255, 215, 0, 0.4); cursor: pointer; z-index: 1000; opacity: 0; visibility: hidden; transition: all 0.3s ease; }
        .scroll-top.show { opacity: 1; visibility: visible; }
        .scroll-top:hover { transform: translateY(-5px); box-shadow: 0 8px 25px rgba(255, 215, 0, 0.5); }
        .exp-header-wrapper { display: flex; align-items: flex-end; justify-content: center; margin-bottom: 1.5rem; }
        .exp-title-text { font-weight: 800; color: var(--gp-primary-green); margin-bottom: 0; font-size: 2.5rem; line-height: 1.2; }
        .exp-mascot-img { width: 80px; margin-left: 20px; filter: drop-shadow(0 4px 8px rgba(0,0,0,0.1)); transition: transform 0.3s ease; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg gp-navbar fixed-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center text-white" href="#">
            
            <div class="logo-container">
                <img src="{{ asset('images/logo.png') }}" alt="Logo">
            </div>

            <div class="brand-text-wrapper">
                <span class="shiny-text text-green">GREEN</span> 
                <span class="shiny-text text-palm">PALM</span>
            </div>

        </a>
        <button class="navbar-toggler shadow-none border-0 text-white" type="button" data-bs-toggle="collapse" data-bs-target="#navGP">
            <span class="navbar-toggler-icon" style="filter: invert(1);"></span>
        </button>

        <div class="collapse navbar-collapse" id="navGP">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-center">
                <li class="nav-item"><a class="nav-link active" href="#home">Beranda</a></li>
                <li class="nav-item"><a class="nav-link" href="#layanan">Layanan</a></li>
                <li class="nav-item"><a class="nav-link" href="#berita">Berita</a></li>
                <li class="nav-item"><a class="nav-link" href="#kontak">Kontak</a></li>
                <li class="nav-item ms-lg-3 mt-3 mt-lg-0">
                    <a href="{{ route('login') }}" class="btn btn-login shadow-sm">Masuk / Daftar</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<section class="hero" id="home">
    <div class="hero-bg"></div>
    <div class="hero-overlay"></div>
    <div class="hero-particles"></div>
    <div class="container">
        <div class="hero-content">
            <h1 class="hero-title">Solusi Cerdas untuk <span style="color: var(--gp-accent-gold);">Kesejahteraan</span> Petani Sawit.</h1>
            <p class="hero-subtitle">Platform digital terintegrasi yang membantu pencatatan, edukasi, dan pemantauan harga sawit secara real-time.</p>
            <a href="#layanan" class="btn btn-hero">Jelajahi Fitur</a>
        </div>
    </div>
</section>

<section class="gp-section" id="layanan">
    <div class="container position-relative">
        
        <div class="d-none d-lg-block position-absolute end-0 top-0" style="margin-top: -80px; margin-right: 50px; z-index: 5;">
            <img src="images/maskot-think.png" alt="Think" style="width: 140px; filter: drop-shadow(0 8px 15px rgba(0,0,0,0.3));">
        </div>

        <p class="section-subtitle">Layanan Kami</p>
        <h2 class="section-title">Apa yang Green Palm Tawarkan?</h2>
        
        <div class="row g-4 mt-4 mb-5">
            <div class="col-6 col-md-3">
                <div class="svc-card active" onclick="showFeature('catatan', this)">
                    <div class="svc-icon-wrapper">
                        <img src="images/icons/catatan.png" class="svc-icon" alt="Catatan">
                    </div>
                    <div class="svc-title">Pencatatan Kebun</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="svc-card" onclick="showFeature('harga', this)">
                    <div class="svc-icon-wrapper">
                        <img src="images/icons/harga.png" class="svc-icon" alt="Harga">
                    </div>
                    <div class="svc-title">Info Harga Sawit</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="svc-card" onclick="showFeature('penyakit', this)">
                    <div class="svc-icon-wrapper">
                        <img src="images/icons/penyakit.png" class="svc-icon" alt="Penyakit">
                    </div>
                    <div class="svc-title">Tanya Penyakit</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="svc-card" onclick="showFeature('edukasi', this)">
                    <div class="svc-icon-wrapper">
                        <img src="images/icons/edukasi.png" class="svc-icon" alt="Edukasi">
                    </div>
                    <div class="svc-title">Edukasi Tanam</div>
                </div>
            </div>
        </div>

        <div class="partner-box" id="feature-display-area">
            <div class="row align-items-center fade-in g-5">
                
                <div class="col-lg-6 mb-4 mb-lg-0 order-2 order-lg-1">
                    <h3 id="feat-title" style="font-weight: 800; color: var(--gp-primary-green); font-size: 2rem;">
                        Platform Sistem Pencatatan Kebun
                    </h3>
                    
                    <p id="feat-desc" class="mt-3" style="font-size: 17px; line-height: 1.8; color: #444444;">
                        Sistem Pencatatan Kebun menyediakan layanan berbasis aplikasi yang memungkinkan pengguna untuk mencatat dan mengelola aktivitas kebun secara efisien.
                    </p>
                    
                    <ul id="feat-list" class="feature-check-list">
                        <li>Pencatatan Kebun yang Mudah</li>
                        <li>Kemudahan Penggunaan</li>
                        <li>Insentif untuk Berpartisipasi</li>
                    </ul>

                    <p id="feat-footer" style="font-size:15px; color:#666666; margin-top: 25px; font-weight: 500; padding: 20px; background: #f8f9fa; border-radius: 12px; border-left: 4px solid var(--gp-accent-gold);">
                        Kami menghubungkan para petani dengan layanan pencatatan yang efisien dan dapat dipertanggungjawabkan.
                    </p>
                </div>

                <div class="col-lg-6 text-center order-1 order-lg-2 mb-4 mb-lg-0">
                    <img id="feat-img" src="images/catatan.png" alt="Ilustrasi Fitur" class="feature-img-full">
                </div>

            </div>
        </div>

    </div>
</section>

<section class="gp-section highlight-container">
    <div class="container">
        <div class="row align-items-center justify-content-center">
            <div class="col-lg-6 mb-5 mb-lg-0 text-center text-lg-start">
                <h2 class="highlight-title">
                    <span class="text-gold">Solusi Digital</span> Terdepan untuk Meningkatkan Efisiensi & Kesejahteraan.
                </h2>
                <div class="mt-4">
                    <a href="{{ route('about') }}" class="btn btn-outline-gold">Tentang Kami</a>
                </div>
            </div>
            <div class="col-lg-4 text-center">
                <img src="images/maskot-oke.png" alt="Maskot" style="width: 280px; filter: drop-shadow(0 0 30px rgba(255, 215, 0, 0.4)); animation: float 3s ease-in-out infinite;">
            </div>
        </div>
    </div>
</section>

<section class="gp-section" id="berita">
    <div class="container">
        <p class="section-subtitle">Wawasan Terkini</p>
        <h2 class="section-title">BERITA SAWIT</h2>
        <p class="text-center mb-5" style="color: rgba(255,255,255,0.7); font-size: 1.1rem;">Dapatkan informasi terbaru seputar industri sawit di Indonesia.</p>

        <div class="row g-4">
            <div class="col-md-6">
                <div class="news-card">
                    <img src="images/petani-muda.png" class="news-img" alt="Berita 1">
                    <div class="news-body">
                        <h5 class="news-title">GreenPalm Dukung Petani Muda Tingkatkan Kualitas Sawit</h5>
                        <p class="news-desc">Semangat baru tumbuh di kalangan generasi muda untuk mengelola perkebunan sawit dengan teknologi...</p>
                        <a href="{{ route('berita.show', 'petani-muda-tingkatkan-kualitas') }}" class="news-link">Baca Selengkapnya <span>&rarr;</span></a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="news-card">
                    <img src="images/perkebunan.png" class="news-img" alt="Berita 2">
                    <div class="news-body">
                        <h5 class="news-title">Harga Sawit Stabil, Petani Bengkalis Sumringah</h5>
                        <p class="news-desc">Dinas Perkebunan Bengkalis menegaskan bahwa harga jual tandan buah segar (TBS) kelapa sawit...</p>
                        <a href="{{ route('berita.show', 'harga-sawit-stabil-bengkalis') }}" class="news-link">Baca Selengkapnya <span>&rarr;</span></a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="news-card">
                    <img src="images/pasar-eropa.png" class="news-img" alt="Berita 3">
                    <div class="news-body">
                        <h5 class="news-title">Potensi Pasar Sawit Indonesia di Eropa</h5>
                        <p class="news-desc">Pemerintah menilai potensi pasar sawit Indonesia di Eropa semakin terbuka lebar dengan standar baru...</p>
                        <a href="{{ route('berita.show', 'potensi-pasar-eropa') }}" class="news-link">Baca Selengkapnya <span>&rarr;</span></a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="news-card">
                    <img src="images/industri-sawit.png" class="news-img" alt="Berita 4">
                    <div class="news-body">
                        <h5 class="news-title">Tantangan Regulasi Baru Industri Sawit</h5>
                        <p class="news-desc">Pelaku industri sawit mulai mendiskusikan dampak dari regulasi denda terbaru yang diterapkan...</p>
                        <a href="{{ route('berita.show', 'tantangan-regulasi-baru') }}" class="news-link">Baca Selengkapnya <span>&rarr;</span></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="exp-section">
    
    <section class="container mb-5" id="testimoni">
        <h2 class="text-center fw-bold mb-5" style="color: var(--gp-primary-green); font-size: 2.5rem;">Ulasan Pengguna</h2>
        
        <div id="testimonialCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner pb-5">
                
                @forelse($reviews as $review)
                    {{-- Loop data rating dari database --}}
                    <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                        <div class="testimoni-card">
                            <div class="quote-icon">&ldquo;</div>
                            
                            {{-- Menampilkan Bintang --}}
                            <div class="mb-3" style="color: var(--gp-accent-gold); font-size: 1.2rem; letter-spacing: 2px;">
                                {{-- Memanggil accessor getStarsAttribute atau manual --}}
                                {!! $review->stars ?? str_repeat('⭐', $review->rating) !!}
                            </div>

                            {{-- Menampilkan Komentar --}}
                            <p class="fs-5 fst-italic text-dark mb-4" style="line-height: 1.8;">
                                "{{ $review->comment }}"
                            </p>
                            
                            {{-- Info User & Waktu --}}
                            <h5 class="fw-bold" style="color: var(--gp-primary-green);">
                                {{ $review->username ?? 'Pengguna GreenPalm' }}
                            </h5>
                            <small class="text-muted">
                                {{ $review->created_at->diffForHumans() }}
                            </small>
                        </div>
                    </div>
                @empty
                    {{-- Tampilan Default jika belum ada data di database --}}
                    <div class="carousel-item active">
                        <div class="testimoni-card">
                            <div class="quote-icon">&ldquo;</div>
                            <p class="fs-5 fst-italic text-dark mb-4" style="line-height: 1.8;">
                                "Belum ada ulasan saat ini. Jadilah yang pertama memberikan rating!"
                            </p>
                            <h5 class="fw-bold" style="color: var(--gp-primary-green);">GreenPalm</h5>
                        </div>
                    </div>
                @endforelse

            </div>
            
            {{-- Tombol Navigasi (Hanya muncul jika ulasan lebih dari 1) --}}
            @if(isset($reviews) && $reviews->count() > 1)
            <div class="d-flex justify-content-center gap-3 mt-3">
                <button class="btn btn-outline-success rounded-circle" style="width: 45px; height: 45px; font-weight: bold;" type="button" data-bs-target="#testimonialCarousel" data-bs-slide="prev">&larr;</button>
                <button class="btn btn-outline-success rounded-circle" style="width: 45px; height: 45px; font-weight: bold;" type="button" data-bs-target="#testimonialCarousel" data-bs-slide="next">&rarr;</button>
            </div>
            @endif
        </div>
    </section>

    <section class="container pb-5" id="kontak">
        <div class="text-center mb-5">
            <span class="badge text-dark mb-3 px-4 py-2 rounded-pill" style="background: linear-gradient(135deg, var(--gp-accent-gold), var(--gp-gold-dark)); font-size: 0.9rem; font-weight: 600;">HUBUNGI KAMI</span>
            <h2 class="fw-bold" style="color: var(--gp-primary-green); font-size: 2.5rem;">Ada Pertanyaan?</h2>
        </div>
        <div class="row g-4 justify-content-center">
            <div class="col-md-4 col-lg-3">
                <div class="contact-card shadow-sm">
                    <img src="images/telepon.png" class="contact-icon" alt="Telp">
                    <h6 class="fw-bold text-muted mb-2">WhatsApp / Telepon</h6>
                    <p class="fw-bold text-dark mb-0">+62 821-7296-7361</p>
                </div>
            </div>
            <div class="col-md-4 col-lg-3">
                <div class="contact-card shadow-sm">
                    <img src="images/pesan.png" class="contact-icon" alt="Email">
                    <h6 class="fw-bold text-muted mb-2">Email Resmi</h6>
                    <p class="fw-bold text-dark mb-0">greenpalmicif26@gmail.com</p>
                </div>
            </div>
            <div class="col-md-4 col-lg-3">
                <div class="contact-card shadow-sm">
                    <img src="images/lokasi.png" class="contact-icon" alt="Lokasi">
                    <h6 class="fw-bold text-muted mb-2">Lokasi Kantor</h6>
                    <p class="fw-bold text-dark mb-0">Politeknik Negeri Bengkalis</p>
                </div>
            </div>
        </div>
    </section>
</div>

<footer>
    <div class="container footer-content-wrapper">
        
        <p class="footer-copyright">
            Copyright © 2025 GreenPalm, Ltd. All Rights Reserved.
        </p>

        <p class="footer-subtitle">
            Politeknik Negeri Bengkalis
        </p>

        <p class="footer-contact">
            Kontak Kami: greenpalmicif26@gmail.com
        </p>

        <div class="footer-social-container">
            <a href="https://wa.me/6282172967361" target="_blank" class="footer-icon-link" title="WhatsApp">
                <img src="images/icons/whatsapp.png" alt="WhatsApp" class="footer-icon-img">
            </a>
            
            <a href="https://www.instagram.com/greenpalm.official?igsh=MTM3ZGRjYmh4cTU1eg==" target="_blank" class="footer-icon-link" title="Instagram">
                <img src="images/icons/instagram.png" alt="Instagram" class="footer-icon-img">
            </a>

            <a href="https://www.tiktok.com/@greenpalm.official?_r=1&_t=ZS-92YsEnOsnPG" target="_blank" class="footer-icon-link" title="TikTok">
                <img src="images/icons/tiktok.png" alt="TikTok" class="footer-icon-img">
            </a>

            <a href="mailto:greenpalmicif26@gmail.com" class="footer-icon-link" title="Email">
                <img src="images/icons/email.png" alt="Email" class="footer-icon-img">
            </a>
        </div>

    </div>
</footer>

<div class="scroll-top" id="scrollTop" onclick="scrollToTop()">
    ↑
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    const featureData = {
        'catatan': {
            title: "Platform Sistem Pencatatan Kebun",
            desc: "Sistem Pencatatan Kebun menyediakan layanan berbasis aplikasi yang memungkinkan pengguna untuk mencatat dan mengelola aktivitas kebun secara efisien.",
            list: [
                "Pencatatan Kebun yang Mudah",
                "Kemudahan Penggunaan",
                "Insentif untuk Berpartisipasi"
            ],
            footer: "Kami menghubungkan para petani dengan layanan pencatatan yang efisien dan dapat dipertanggungjawabkan.",
            img: "images/catatan.png"
        },
        'harga': {
            title: "Platform Informasi Harga Sawit",
            desc: "Informasi Harga Sawit menyediakan layanan berbasis aplikasi yang memungkinkan pengguna untuk memantau perkembangan harga sawit secara real-time dan akurat.",
            list: [
                "Akses Informasi Harga Terbaru",
                "Transparansi Pasar Sawit",
                "Dukungan untuk Keputusan Penjualan yang Tepat"
            ],
            footer: "Kami membantu para petani dan pelaku industri sawit untuk memperoleh informasi harga yang terpercaya, efisien, dan mudah diakses kapan pun dibutuhkan.",
            img: "images/harga-sawit.png"
        },
        'penyakit': {
            title: "Platform Sistem Tanya Penyakit Sawit",
            desc: "Sistem Tanya Penyakit Sawit menyediakan layanan berbasis aplikasi yang memudahkan pengguna untuk mengenali, mendiagnosis, dan mencari solusi atas berbagai penyakit pada tanaman sawit.",
            list: [
                "Identifikasi Penyakit Sawit Secara Cepat",
                "Panduan Penanganan yang Tepat",
                "Konsultasi dengan Ahli atau Sistem Cerdas"
            ],
            footer: "Kami membantu para petani sawit dalam menjaga kesehatan tanaman dengan layanan tanya-jawab yang informatif, praktis, dan dapat dipercaya.",
            img: "images/penyakit-sawit.png"
        },
        'edukasi': {
            title: "Platform Sistem Edukasi Penanaman",
            desc: "Sistem Edukasi Penanaman menyediakan layanan berbasis aplikasi yang membantu pengguna mempelajari teknik penanaman sawit yang baik, efisien, dan berkelanjutan.",
            list: [
                "Materi Edukasi Lengkap dan Interaktif",
                "Panduan Langkah demi Langkah Penanaman",
                "Peningkatan Pengetahuan Petani Sawit"
            ],
            footer: "Kami mendukung peningkatan keterampilan petani melalui akses mudah ke sumber belajar yang praktis dan dapat diterapkan langsung di lapangan.",
            img: "images/video-edukasi.png"
        }
    };

    function showFeature(key, element) {
        document.querySelectorAll('.svc-card').forEach(card => card.classList.remove('active'));
        element.classList.add('active');

        const titleEl = document.getElementById('feat-title');
        const descEl = document.getElementById('feat-desc');
        const listEl = document.getElementById('feat-list');
        const footerEl = document.getElementById('feat-footer');
        const imgEl = document.getElementById('feat-img');
        const container = document.getElementById('feature-display-area');

        container.style.opacity = '0.4';
        container.style.transform = 'scale(0.98)';
        
        setTimeout(() => {
            const data = featureData[key];
            
            titleEl.innerText = data.title;
            descEl.innerText = data.desc;
            footerEl.innerText = data.footer;
            imgEl.src = data.img;

            listEl.innerHTML = "";
            data.list.forEach(item => {
                const li = document.createElement('li');
                li.innerText = item;
                listEl.appendChild(li);
            });

            container.style.opacity = '1';
            container.style.transform = 'scale(1)';
            
            const row = container.querySelector('.row');
            row.classList.remove('fade-in');
            void row.offsetWidth;
            row.classList.add('fade-in');

        }, 250);
    }

    // Navbar scroll effect
    window.addEventListener('scroll', function() {
        const navbar = document.querySelector('.gp-navbar');
        const scrollTop = document.getElementById('scrollTop');
        
        if (window.scrollY > 100) {
            navbar.classList.add('scrolled');
            scrollTop.classList.add('show');
        } else {
            navbar.classList.remove('scrolled');
            scrollTop.classList.remove('show');
        }
    });

    // Smooth scroll for navbar links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                const offset = 80;
                const targetPosition = target.offsetTop - offset;
                window.scrollTo({
                    top: targetPosition,
                    behavior: 'smooth'
                });
            }
        });
    });

    // Scroll to top function
    function scrollToTop() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    }

    // Active nav link on scroll
    window.addEventListener('scroll', function() {
        let current = '';
        const sections = document.querySelectorAll('section[id]');
        
        sections.forEach(section => {
            const sectionTop = section.offsetTop;
            const sectionHeight = section.clientHeight;
            if (scrollY >= (sectionTop - 150)) {
                current = section.getAttribute('id');
            }
        });

        document.querySelectorAll('.nav-link').forEach(link => {
            link.classList.remove('active');
            if (link.getAttribute('href') === `#${current}`) {
                link.classList.add('active');
            }
        });
    });

    // Floating animation for maskot
    const style = document.createElement('style');
    style.textContent = `
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
    `;
    document.head.appendChild(style);
</script>

</body>
</html>
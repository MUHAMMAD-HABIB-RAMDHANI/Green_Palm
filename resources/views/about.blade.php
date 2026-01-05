<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tentang Kami | Green Palm</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@0,600;0,700;1,600;1,700&display=swap" rel="stylesheet">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        /* === 1. VARIABEL GLOBAL === */
        :root {
            --gp-dark-green: #0F3D24;
            --gp-primary-green: #1F5F38;
            --gp-accent-gold: #FFD700;
            --gp-gold-dark: #D4AF37;
            --primary-green: #1a4a2e;
            --secondary-green: #2d6a4f;
            --accent-gold: #d4af37;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #fafafa;
            color: #444;
            overflow-x: hidden;
            padding-top: 80px;
        }

        /* =========================================
           2. STYLE HEADER / NAVBAR
           ========================================= */
        @keyframes shineLeftToRightText {
            0% { background-position: 200% 0, 0 0; }
            100% { background-position: -200% 0, 0 0; }
        }
        @keyframes shineLeftToRightLogo {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }

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

        .nav-back-btn {
            color: rgba(255, 255, 255, 0.9);
            text-decoration: none;
            font-weight: 500;
            font-size: 15px;
            display: flex; align-items: center; gap: 8px;
            padding: 8px 20px;
            border-radius: 50px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.05);
        }

        .nav-back-btn:hover {
            color: var(--gp-accent-gold);
            border-color: var(--gp-accent-gold);
            background: rgba(255, 255, 255, 0.1);
            transform: translateX(-3px);
        }

        .logo-container {
            position: relative; width: 45px; height: 45px;
            filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.3));
            transition: transform 0.3s ease; margin-right: 10px; z-index: 10;
        }
        .logo-container img { width: 100%; height: 100%; object-fit: contain; display: block; }
        .logo-container::after {
            content: ''; position: absolute; top: 0; left: 0; right: 0; bottom: 0;
            background: linear-gradient(110deg, transparent 35%, rgba(255, 255, 255, 0.8) 45%, rgba(255, 255, 255, 1.0) 50%, rgba(255, 255, 255, 0.8) 55%, transparent 65%);
            background-size: 200% 100%; background-repeat: no-repeat;
            animation: shineLeftToRightLogo 3.5s infinite linear;
            -webkit-mask-image: url("{{ asset('images/logo.png') }}"); 
            -webkit-mask-size: contain; -webkit-mask-repeat: no-repeat; -webkit-mask-position: center;
            mask-image: url("{{ asset('images/logo.png') }}");
            mask-size: contain; mask-repeat: no-repeat; mask-position: center;
            pointer-events: none;
        }

        .brand-text-wrapper {
            font-weight: 800; letter-spacing: 1.5px; font-size: 20px; line-height: 1;
            display: flex; gap: 5px; filter: drop-shadow(0 2px 4px rgba(0,0,0,0.3)); 
        }
        .shiny-text {
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
            background-clip: text; background-size: 200% 100%, 100% 100%;
            background-repeat: no-repeat; animation: shineLeftToRightText 3.5s infinite linear; animation-delay: 0.4s; 
        }
        .text-green { background-image: linear-gradient(110deg, transparent 35%, rgba(255,255,255,0.9) 50%, transparent 65%), linear-gradient(180deg, #6ee7b7 0%, #68ae11 100%); }
        .text-palm { background-image: linear-gradient(110deg, transparent 35%, rgba(255,255,255,0.9) 50%, transparent 65%), linear-gradient(180deg, #fbbf24 0%, #dfa91b 100%); }

        /* =========================================
           3. INTRO SECTION (LAYOUT BARU 2 KOLOM)
           ========================================= */
        .intro-section {
            background: linear-gradient(135deg, #f3f6f4 0%, #e8f1ec 100%);
            padding: 80px 20px 100px 20px;
            position: relative;
            overflow: hidden;
        }
        
        .intro-section::before {
            content: ''; position: absolute; top: 0; left: 0; right: 0; bottom: 0;
            background-image: radial-gradient(circle at 10% 20%, rgba(26, 74, 46, 0.03) 0%, transparent 40%);
            pointer-events: none;
        }

        /* === BAGIAN KIRI: JUDUL & TEKS === */
        .intro-text-wrapper {
            position: relative;
            z-index: 5;
            padding-right: 20px;
        }

        /* Judul Asli (Playfair Display Italic) */
        .intro-title {
            font-family: 'Playfair Display', serif;
            font-style: italic;
            font-weight: 700;
            font-size: clamp(2.2rem, 5vw, 3.5rem);
            color: var(--primary-green);
            margin-bottom: 40px;
            position: relative;
            text-align: left; /* Rata Kiri */
            line-height: 1.2;
        }
        
        .intro-title span { 
            color: var(--accent-gold); 
            position: relative; 
            display: inline-block; 
        }
        
        /* Garis Bawah Emas (Style Asli) */
        .intro-title span::after {
            content: ''; position: absolute; bottom: 5px; left: 0;
            width: 100%; height: 12px;
            background: rgba(212, 175, 55, 0.25);
            z-index: -1; transform: skewX(-12deg);
        }

        /* Maskot (Floating di atas Judul) */
        .mascot-head-decor {
            position: absolute;
            top: -65px; /* Naik di atas judul */
            right: 0px; /* Di ujung kanan text wrapper judul */
            width: 100px; /* Sesuaikan ukuran */
            z-index: 10;
            filter: drop-shadow(0 5px 10px rgba(0,0,0,0.15));
            animation: floatHead 3s ease-in-out infinite;
        }

        @keyframes floatHead {
            0%, 100% { transform: translateY(0) rotate(5deg); }
            50% { transform: translateY(-8px) rotate(10deg); }
        }

        /* Teks Paragraf */
        .intro-desc {
            font-size: 16px;
            line-height: 1.9;
            color: #444;
            text-align: justify; /* Rata Kanan Kiri */
            margin-bottom: 20px;
        }
        
        .intro-desc strong {
            color: var(--primary-green);
            font-weight: 600;
        }

        .intro-divider {
            border: none;
            border-top: 2px dashed rgba(26, 74, 46, 0.15);
            margin: 25px 0;
            width: 60%;
        }

        /* === BAGIAN KANAN: FOTO POLAROID === */
        .intro-photo-container {
            position: relative;
            padding: 10px;
            perspective: 1000px;
        }

        .photo-polaroid {
            background: white;
            padding: 15px;
            padding-bottom: 50px; /* Space bawah tebal ala polaroid */
            box-shadow: 0 20px 50px rgba(0,0,0,0.15);
            transform: rotate(2deg); /* Miring sedikit */
            border-radius: 4px;
            transition: all 0.5s cubic-bezier(0.25, 0.8, 0.25, 1);
            width: 100%;
            max-width: 550px; /* Batas lebar maksimal */
            margin: 0 auto;
            position: relative;
            z-index: 2;
        }

        .photo-polaroid:hover {
            transform: rotate(0deg) scale(1.02);
            box-shadow: 0 30px 70px rgba(0,0,0,0.25);
            z-index: 10;
        }

        .photo-polaroid img {
            width: 100%;
            height: auto;
            object-fit: cover;
            border: 1px solid #f0f0f0;
            display: block;
        }

        /* Hiasan Selotip */
        .tape-decor {
            position: absolute;
            top: -18px;
            left: 50%;
            transform: translateX(-50%) rotate(-1deg);
            width: 130px;
            height: 40px;
            background: rgba(255,255,255,0.4);
            border: 1px solid rgba(255,255,255,0.5);
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            backdrop-filter: blur(4px);
            z-index: 20;
        }

        /* =========================================
           4. VISI & MISI
           ========================================= */
        .vm-section { background: white; padding: 100px 20px; position: relative; }
        .vm-section::before {
            content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 300px;
            background: linear-gradient(180deg, rgba(26, 74, 46, 0.02) 0%, rgba(255,255,255,0) 100%);
            z-index: 0;
        }
        
        .vm-title {
            text-align: center; font-family: 'Playfair Display', serif; font-weight: 700;
            font-size: clamp(2rem, 4vw, 2.8rem); color: var(--primary-green);
            margin-bottom: 70px; position: relative;
        }
        .vm-title::after {
            content: ''; display: block; width: 80px; height: 4px;
            background: linear-gradient(90deg, transparent, var(--accent-gold), transparent);
            margin: 20px auto 0; border-radius: 2px;
        }

        .vm-card {
            background: white; border-radius: 24px; padding: 45px;
            box-shadow: 0 15px 50px rgba(0,0,0,0.08);
            border: 1px solid rgba(26, 74, 46, 0.05);
            position: relative; z-index: 2; height: 100%;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .vm-card:hover { transform: translateY(-8px); box-shadow: 0 20px 60px rgba(0,0,0,0.12); }
        
        .vm-heading {
            font-family: 'Playfair Display', serif; font-size: 2.2rem; font-weight: 700;
            color: var(--primary-green); margin-bottom: 20px; display: flex; align-items: center; gap: 15px;
        }
        .vm-heading::before {
            content: ''; width: 6px; height: 40px;
            background: linear-gradient(180deg, var(--accent-gold), var(--secondary-green));
            border-radius: 10px; display: block;
        }

        .vm-desc { font-size: 16px; line-height: 1.8; color: #555; margin: 0; text-align: justify; }
        .vm-list { list-style: none; padding: 0; margin: 0; }
        .vm-list li { position: relative; padding-left: 30px; margin-bottom: 12px; font-size: 16px; line-height: 1.6; color: #555; }
        .vm-list li::before {
            content: '\f00c'; font-family: 'Font Awesome 6 Free'; font-weight: 900;
            position: absolute; left: 0; top: 2px; color: var(--gp-accent-gold);
        }

        .mascot-img {
            max-width: 100%; width: 220px;
            filter: drop-shadow(0 15px 25px rgba(0,0,0,0.12));
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .mascot-container:hover .mascot-img { transform: translateY(-15px) rotate(3deg) scale(1.05); }

        /* =========================================
           5. FOOTER
           ========================================= */
        footer { 
            background: linear-gradient(135deg, #0a2918 0%, #0d3320 100%); 
            color: #ffffff; padding: 50px 0; font-size: 14px; position: relative; z-index: 100;
        }
        .footer-content-wrapper { display: flex; flex-direction: column; align-items: center; text-align: center; gap: 8px; }
        .footer-copyright { font-size: 16px; font-weight: 500; margin: 0; opacity: 1; margin-bottom: 5px; }
        .footer-subtitle { font-size: 14px; font-weight: 400; opacity: 0.8; margin: 0; }
        .footer-contact { font-size: 14px; font-weight: 400; opacity: 0.8; margin: 0 0 25px 0; }
        .footer-social-container { display: flex; justify-content: center; gap: 20px; }
        .footer-icon-link { display: flex; align-items: center; justify-content: center; width: 45px; height: 45px; border-radius: 50%; border: 1px solid rgba(255, 255, 255, 0.15); background: rgba(255, 255, 255, 0.05); transition: all 0.3s ease; text-decoration: none; }
        .footer-icon-img { width: 22px; height: 22px; object-fit: contain; transition: all 0.3s ease; filter: brightness(0) invert(1); opacity: 0.8; }
        .footer-icon-link:hover { background: var(--gp-accent-gold); border-color: var(--gp-accent-gold); transform: translateY(-5px); box-shadow: 0 5px 15px rgba(255, 215, 0, 0.3); }
        .footer-icon-link:hover .footer-icon-img { opacity: 1; filter: brightness(0) saturate(100%) invert(13%) sepia(35%) saturate(836%) hue-rotate(98deg) brightness(93%) contrast(92%); }

        /* =========================================
           6. RESPONSIVE
           ========================================= */
        @media (max-width: 991px) {
            .intro-section { padding-top: 50px; }
            /* Pada Mobile, Teks Center */
            .intro-text-wrapper { text-align: center; padding-right: 0; margin-bottom: 50px; }
            .intro-desc { text-align: center; }
            .intro-title { text-align: center; font-size: 2.5rem; }
            .intro-divider { margin: 20px auto; }
            
            /* Posisi Maskot di Mobile (Tengah) */
            .mascot-head-decor { right: 50%; transform: translateX(140px); top: -45px; }
            
            /* Foto di Mobile */
            .photo-polaroid { transform: rotate(-2deg); width: 90%; margin: 0 auto; }
            
            /* Visi Misi Mobile */
            .vm-section { padding: 70px 20px; }
            .vm-heading { justify-content: center; font-size: 1.8rem; }
            .vm-heading::before { display: none; }
            .vm-card { text-align: left; margin-bottom: 40px; padding: 35px; }
            .mascot-img { width: 180px; margin-bottom: 30px; }
            .mobile-reverse { flex-direction: column-reverse; }
        }
        @media (max-width: 576px) {
            .mascot-head-decor { width: 80px; transform: translateX(100px); top: -30px; }
            .photo-polaroid { padding: 10px; padding-bottom: 40px; }
            .vm-card { padding: 25px; } .vm-desc { font-size: 15px; }
        }
    </style>
</head>
<body>

    <nav class="gp-navbar fixed-top">
        <div class="container d-flex justify-content-between align-items-center">
            
            <a href="{{ url('/') }}" class="nav-back-btn">
                <i class="fas fa-arrow-left"></i> 
                <span>Kembali</span>
            </a>

            <a class="navbar-brand d-flex align-items-center text-white m-0" href="#">
                <div class="logo-container">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo">
                </div>

                <div class="brand-text-wrapper">
                    <span class="shiny-text text-green">GREEN</span> 
                    <span class="shiny-text text-palm">PALM</span>
                </div>
            </a>

        </div>
    </nav>

    <section class="intro-section">
        <div class="container" style="max-width: 1200px;">
            <div class="row align-items-center">
                
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <div class="intro-text-wrapper">
                        
                        <div class="position-relative d-inline-block w-100">
                            <h1 class="intro-title">
                                Kenalan Dulu, <br>
                                <span>Biar Makin Akrab</span>
                            </h1>
                            
                            <img src="{{ asset('images/visi.png') }}" alt="Maskot" class="mascot-head-decor">
                        </div>

                        <p class="intro-desc">
                            <strong style="font-size: 1.2em;">Green Palm</strong> adalah aplikasi agri-teknologi yang fokus 
                            membantu pengelolaan dan pemantauan perkebunan kelapa sawit secara lebih 
                            mudah dan efisien. Kami hadir untuk mendukung industri sawit Indonesia agar 
                            tumbuh lebih maju, berkelanjutan, dan ramah lingkungan.
                        </p>
                        
                        <hr class="intro-divider">
                        
                        <p class="intro-desc text-muted">
                            Dengan semangat kolaborasi dan inovasi, <strong>Green Palm</strong> terus berupaya 
                            memudahkan petani, pengelola, dan pengguna lainnya untuk berkembang bersama 
                            menuju masa depan perkebunan yang lebih hijau.
                        </p>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="intro-photo-container">
                        <div class="photo-polaroid">
                            <div class="tape-decor"></div>
                            <img src="{{ asset('images/tim.jpg') }}" alt="Tim Green Palm">
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <section class="vm-section">
        <div class="container" style="max-width: 1100px;">
            <h2 class="vm-title">Visi & Misi Kami</h2>

            <div class="row align-items-center mb-5 mobile-reverse">
                <div class="col-md-7 mb-4 mb-md-0">
                    <div class="vm-card">
                        <h3 class="vm-heading">Visi</h3>
                        <p class="vm-desc">
                            Menjadi platform digital Green Palm terdepan di Kabupaten Bengkalis yang mendukung pengelolaan kebun kelapa sawit secara modern, efisien, dan berbasis data, serta berkontribusi dalam meningkatkan kesejahteraan dan literasi digital petani sawit, khususnya petani milenial.
                        </p>
                    </div>
                </div>
                <div class="col-md-5 text-center mascot-container">
                    <img src="{{ asset('images/visi.png') }}" alt="Visi Maskot" class="mascot-img">
                </div>
            </div>

            <div class="row align-items-start">
                <div class="col-md-5 text-center mascot-container order-1 order-md-0 mb-4 mb-md-0">
                    <img src="{{ asset('images/misi.png') }}" alt="Misi Maskot" class="mascot-img">
                </div>
                <div class="col-md-7 order-0 order-md-1">
                    <div class="vm-card">
                        <h3 class="vm-heading justify-content-md-start">Misi</h3> 
                        <ul class="vm-list">
                            <li>Mengembangkan aplikasi Green Palm berbasis web dan mobile yang berkualitas, mudah digunakan, dan sesuai dengan kebutuhan petani sawit lokal.</li>
                            <li>Menyediakan sistem pencatatan kebun digital yang terstruktur untuk meningkatkan efisiensi, akurasi, dan transparansi pengelolaan kebun kelapa sawit.</li>
                            <li>Menyajikan informasi harga sawit harian yang akurat dan relevan dari PKS lokal di Kabupaten Bengkalis.</li>
                            <li>Menyediakan edukasi budidaya serta informasi penyakit tanaman sawit yang mudah dipahami oleh petani pemula maupun petani milenial.</li>
                            <li>Mendorong adopsi teknologi digital di sektor perkebunan sawit melalui antarmuka aplikasi yang sederhana dan pendampingan penggunaan kepada petani.</li>
                            <li>Menjalin kerja sama dengan koperasi, penyuluh pertanian, dan komunitas petani sawit untuk meningkatkan kualitas pengelolaan kebun di Kabupaten Bengkalis.</li>
                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </section>

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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.gp-navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });
    </script>
</body>
</html>
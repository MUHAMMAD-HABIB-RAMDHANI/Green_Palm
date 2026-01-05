<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Toke Dashboard') - Green Palm</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    {{-- Google Font Poppins --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        /* =============================================
           CSS VARIABLES
           ============================================= */
        :root {
            --primary-green: #2b7a0b;
            --dark-green: #1E4620;
            --light-green: #e6f1e3;
            --text-dark: #222;
            --text-light: #555;
            --bg-light: #f4f7f6;
            --white: #ffffff;
            --shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            --shadow-nav: 0 -2px 10px rgba(0, 0, 0, 0.1);
        }

        /* =============================================
           BASE STYLES
           ============================================= */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--bg-light);
            display: flex;
            height: 100vh;
            color: var(--text-dark);
        }

        /* =============================================
           SIDEBAR (Desktop)
           ============================================= */
        .sidebar {
            width: 260px;
            background: #1F4C2B;
            height: 100vh;
            position: sticky;
            top: 0;
            display: flex;
            flex-direction: column;
            padding: 25px 15px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
            transition: width 0.3s ease;
            flex-shrink: 0;
        }

        .sidebar-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .sidebar-logo {
            max-width: 150px;
            height: auto;
            display: block;
            margin: 0 auto;
            padding-bottom: 15px;
        }

        .sidebar-nav {
            list-style: none;
            flex-grow: 1;
            margin-top: 20px;
        }

        .sidebar-nav li {
            margin: 8px 0;
        }

        .sidebar-nav a {
            color: var(--light-green);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 18px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 500;
            transition: all 0.3s ease;
            position: relative; 
        }
        
        .sidebar-nav a .icon {
            font-size: 20px;
        }

        .sidebar-nav a:hover {
            background: rgba(255, 255, 255, 0.1);
            color: var(--white);
        }
        
        /* State Aktif */
        .sidebar-nav a.active {
            background: var(--light-green);
            color: var(--dark-green);
            font-weight: 600;
        }

        /* =============================================
           MOBILE HEADER
           ============================================= */
        .mobile-header {
            display: none; 
            background: #1F4C2B;
        }
        .mobile-logo {
            height: 45px;
            width: auto;
        }
        .mobile-username {
            font-size: 14px;
            font-weight: 600;
            padding: 8px 12px;
            border-radius: 10px;
            background: var(--light-green);
            color: var(--dark-green);
            text-align: center;
            margin-left: 15px;
        }

        /* =============================================
           MAIN CONTENT
           ============================================= */
        .main-content {
            flex-grow: 1;
            padding: 30px;
            overflow-y: auto;
            height: 100vh;
        }
        
        .main-header {
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: var(--primary-green);
            padding: 15px 20px;
            border-radius: 12px;
            box-shadow: var(--shadow);
        }
        
        .main-header h1 {
            font-size: 28px;
            font-weight: 700;
            color: var(--white);
            margin: 0;
        }

        .header-username-desktop {
            font-size: 16px;
            font-weight: 600;
            padding: 10px 15px;
            border-radius: 10px;
            background: var(--light-green);
            color: var(--dark-green);
            text-align: center;
        }

        /* =============================================
           TOAST NOTIFICATION (Global)
           ============================================= */
        .gp-toast-container {
            position: fixed;
            top: 80px; 
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            flex-direction: column;
            gap: 8px;
            z-index: 9999;
            pointer-events: none;
        }

        .gp-toast {
            min-width: 260px;
            max-width: 92vw;
            padding: 10px 14px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
            text-align: center;
            opacity: 0; /* Default hidden, JS will animate */
            transform: translateY(-14px);
            transition: transform 0.25s ease, opacity 0.25s ease;
            pointer-events: auto;
        }

        .gp-toast--success {
            background: #E9FFF0;
            border: 1px solid #BDEFCF;
            color: #0F6B3A;
        }

        .gp-toast--error {
            background: #FFECEC;
            border: 1px solid #FFC0C0;
            color: #8A1F11;
        }

        .gp-toast.is-show {
            opacity: 1;
            transform: translateY(0);
        }

        /* =============================================
           PAGE TRANSITIONS
           ============================================= */
        .page-transition {
            opacity: 0; /* Mulai hidden */
            transition: opacity 0.3s ease;
        }

        /* =============================================
           RESPONSIVE DESIGN
           ============================================= */
        @media (max-width: 768px) {
            .sidebar-logo {
                max-width: 120px;
            }
            
            body {
                flex-direction: column;
                height: auto;
            }

            .sidebar {
                width: 100%;
                height: 65px; 
                position: fixed;
                top: auto !important;
                bottom: 0 !important;
                left: 0;
                flex-direction: row;
                justify-content: space-around;
                align-items: center;
                padding: 0 10px;
                box-shadow: var(--shadow-nav);
                z-index: 1000;
                background: #1F4C2B;
            }
            
            .sidebar-header {
                display: none;
            }
            
            .sidebar-nav {
                display: flex;
                justify-content: space-around;
                width: 100%;
                margin-top: 0;
            }
            
            .sidebar-nav li {
                margin: 0;
            }
            
            .sidebar-nav a {
                flex-direction: column;
                justify-content: center;
                padding: 8px 5px;
                font-size: 11px;
                gap: 4px;
                height: 60px;
                background: transparent !important; /* Reset bg for mobile nav */
            }
            
            .sidebar-nav a .icon {
                font-size: 20px;
            }
            
            .sidebar-nav a.active {
                color: #ffd86c; 
            }
            
            .mobile-header {
                display: flex;
                align-items: center;
                justify-content: flex-start;
                padding: 10px 15px;
                width: 100%;
                position: fixed;
                top: 0;
                left: 0;
                z-index: 999;
                box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            }
            
            .mobile-header .mobile-username {
                flex-grow: 1;
                text-align: left;
                padding-left: 20px;
            }

            .main-content {
                width: 100%;
                height: auto;
                padding: 80px 20px 90px 20px; 
            }
            
            .main-header {
                display: none;
            }
            
            .gp-toast-container {
                top: 75px;
            }
        }
    </style>
</head>
<body>
    
    {{-- Header Khusus Mobile --}}
    <header class="mobile-header">
        <img src="{{ asset('images/logo.png') }}" alt="GreenPalm" class="mobile-logo">
        <div class="mobile-username">
            👋 Halo, {{ Auth::user()->username ?? 'Toke' }}
        </div>
    </header>

    {{-- Sidebar Navigasi --}}
    <nav class="sidebar">
        <div class="sidebar-header">
            <img src="{{ asset('images/logo.png') }}" alt="GreenPalm" class="sidebar-logo">
        </div>

        <ul class="sidebar-nav">
            {{-- 1. Beranda Toke --}}
            <li>
                <a href="{{ route('toke.beranda') }}"
                   class="{{ request()->routeIs('toke.beranda') ? 'active' : '' }}">
                    <span class="icon">🏠</span> 
                    <span>Beranda</span>
                </a>
            </li>

            {{-- 2. Kelola RAM --}}
            <li>
                <a href="{{ route('toke.edit-ram') }}" 
                   class="{{ request()->routeIs('toke.edit-ram') ? 'active' : '' }}">
                    <span class="icon">🏭</span> 
                    <span>Kelola RAM</span>
                </a>
            </li>

            {{-- 3. Profil Toke --}}
            <li>
                <a href="{{ route('toke.profil') }}" 
                   class="{{ request()->routeIs('toke.profil') ? 'active' : '' }}">
                    <span class="icon">👤</span> 
                    <span>Profil</span>
                </a>
            </li>
        </ul>
    </nav>

    {{-- Form Logout Tersembunyi (Disimpan untuk logout via halaman profil) --}}
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

    {{-- Konten Utama --}}
    <main class="main-content">
        <div id="pageContent" class="page-transition">
            @yield('content')
        </div>
    </main>

    {{-- Script Animasi & Navigasi --}}
    <script>
        // Animasi Fade-in saat halaman dimuat
        window.addEventListener("load", () => {
            const page = document.getElementById("pageContent");
            if (page) {
                page.style.opacity = "1";
            }

            // Toast Animation
            const toasts = document.querySelectorAll('.gp-toast');
            toasts.forEach((toast, index) => {
                setTimeout(() => {
                    toast.classList.add('is-show');
                }, 100 * index);

                // Auto hide after 3 seconds
                setTimeout(() => {
                    toast.classList.remove('is-show');
                    setTimeout(() => toast.remove(), 300);
                }, 3000 + (100 * index));
            });
        });

        // Efek transisi saat klik link sidebar
        document.querySelectorAll(".sidebar-nav a").forEach(link => {
            link.addEventListener("click", function (e) {
                // Jangan cegah default jika itu tombol logout dengan onclick
                if (this.getAttribute('onclick')) return;

                const href = this.getAttribute('href');
                if (href && href !== '#') {
                    e.preventDefault();
                    const page = document.getElementById("pageContent");
                    
                    if (page) page.style.opacity = "0";

                    setTimeout(() => {
                        window.location.href = href;
                    }, 200);
                }
            });
        });
    </script>
    
</body>
</html>
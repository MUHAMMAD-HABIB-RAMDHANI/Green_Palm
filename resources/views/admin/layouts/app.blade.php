<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - Green Palm Admin</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    {{-- Google Font Poppins --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        /* =============================================
           CSS VARIABLES
           ============================================= */
        :root {
            --admin-primary: #1e3a8a;
            --admin-secondary: #3b82f6;
            --admin-accent: #fbbf24;
            --admin-dark: #1e293b;
            --admin-light: #e0f2fe;
            --text-dark: #222;
            --text-light: #555;
            --bg-light: #f1f5f9;
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
           SIDEBAR (Desktop) - ADMIN STYLE
           ============================================= */
        .sidebar {
            width: 280px;
            background: linear-gradient(180deg, #1e3a8a 0%, #1e40af 100%);
            height: 100vh;
            position: sticky;
            top: 0;
            display: flex;
            flex-direction: column;
            padding: 25px 15px;
            box-shadow: 4px 0 20px rgba(0,0,0,0.1);
            transition: width 0.3s ease;
            flex-shrink: 0;
            overflow-y: auto;
        }

        .sidebar-header {
            text-align: center;
            margin-bottom: 25px;
            padding-bottom: 20px;
            border-bottom: 2px solid rgba(255, 255, 255, 0.2);
        }

        .sidebar-logo {
            max-width: 160px;
            height: auto;
            display: block;
            margin: 0 auto 10px;
            filter: brightness(0) invert(1);
        }

        .admin-badge {
            background: var(--admin-accent);
            color: var(--admin-dark);
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            display: inline-block;
        }

        .sidebar-nav {
            list-style: none;
            flex-grow: 1;
            margin-top: 10px;
        }

        .sidebar-nav li {
            margin: 8px 0;
        }

        .sidebar-nav a {
            color: rgba(255, 255, 255, 0.85);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 14px 18px;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 500;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .sidebar-nav a::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            width: 4px;
            height: 100%;
            background: var(--admin-accent);
            transform: scaleY(0);
            transition: transform 0.3s ease;
        }

        .sidebar-nav a .icon {
            font-size: 22px;
            width: 28px;
            text-align: center;
        }

        .sidebar-nav a:hover {
            background: rgba(255, 255, 255, 0.15);
            color: var(--white);
            transform: translateX(4px);
        }

        .sidebar-nav a:hover::before {
            transform: scaleY(1);
        }
        
        .sidebar-nav a.active {
            background: var(--admin-accent);
            color: var(--admin-dark);
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(251, 191, 36, 0.3);
        }

        .sidebar-nav a.active::before {
            transform: scaleY(1);
        }
        
        .logout-link {
            margin-top: auto;
            padding-top: 20px;
            border-top: 2px solid rgba(255, 255, 255, 0.2);
        }
        .logout-link a {
            color: #fecaca;
            background: rgba(239, 68, 68, 0.15);
        }
        .logout-link a:hover {
            background: #ef4444;
            color: white;
        }

        /* =============================================
           MOBILE HEADER - ADMIN
           ============================================= */
        .mobile-header {
            display: none; 
            background: linear-gradient(90deg, #1e3a8a 0%, #3b82f6 100%);
        }
        .mobile-logo {
            height: 40px;
            width: auto;
            filter: brightness(0) invert(1);
        }
        .mobile-username {
            font-size: 13px;
            font-weight: 600;
            padding: 8px 14px;
            border-radius: 10px;
            background: var(--admin-accent);
            color: var(--admin-dark);
            text-align: center;
            margin-left: 15px;
            flex-grow: 1;
        }

        /* =============================================
           MAIN CONTENT - ADMIN
           ============================================= */
        .main-content {
            flex-grow: 1;
            padding: 30px;
            overflow-y: auto;
            height: 100vh;
        }
        
        .main-header {
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
            padding: 20px 25px;
            border-radius: 16px;
            box-shadow: var(--shadow);
        }
        
        .main-header h1 {
            font-size: 28px;
            font-weight: 700;
            color: var(--white);
            margin: 0;
        }

        .header-username-desktop {
            font-size: 15px;
            font-weight: 600;
            padding: 10px 18px;
            border-radius: 10px;
            background: var(--admin-accent);
            color: var(--admin-dark);
            text-align: center;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* =============================================
           ADMIN CARDS
           ============================================= */
        .card {
            background: var(--white);
            border-radius: 16px;
            box-shadow: var(--shadow);
            padding: 24px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            border-left: 4px solid var(--admin-secondary);
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.12);
        }
        .card h3 {
            color: var(--admin-primary);
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 15px;
        }
        .card p {
            font-size: 15px;
            line-height: 1.7;
            color: var(--text-light);
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: linear-gradient(135deg, #fff 0%, #f8fafc 100%);
            border-radius: 16px;
            padding: 24px;
            box-shadow: var(--shadow);
            display: flex;
            align-items: center;
            gap: 20px;
            transition: all 0.3s ease;
            border-left: 4px solid var(--admin-secondary);
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 24px rgba(0,0,0,0.15);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--admin-secondary) 0%, var(--admin-primary) 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            color: white;
            flex-shrink: 0;
        }

        .stat-info h4 {
            font-size: 32px;
            font-weight: 700;
            color: var(--admin-dark);
            margin: 0 0 5px 0;
        }

        .stat-info p {
            font-size: 14px;
            color: var(--text-light);
            margin: 0;
            font-weight: 500;
        }

        button {
            padding: 12px 20px;
            background: var(--admin-secondary);
            color: white;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 600;
            font-size: 15px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }
        button:hover {
            background: var(--admin-primary);
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(30, 58, 138, 0.4);
        }

        /* =============================================
           TOAST NOTIFICATION
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
            min-width: 280px;
            max-width: 92vw;
            padding: 12px 16px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 600;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
            text-align: center;
            opacity: 0;
            transform: translateY(-14px);
            transition: transform 0.25s ease, opacity 0.25s ease;
            pointer-events: auto;
        }

        .gp-toast--success {
            background: #dbeafe;
            border: 2px solid #3b82f6;
            color: #1e3a8a;
        }

        .gp-toast--error {
            background: #fee2e2;
            border: 2px solid #ef4444;
            color: #991b1b;
        }

        .gp-toast.is-show {
            opacity: 1;
            transform: translateY(0);
        }

        .gp-toast.is-hide {
            opacity: 0;
            transform: translateY(-12px);
        }

        /* =============================================
           PAGE TRANSITION
           ============================================= */
        .page-transition {
            opacity: 1;
            transform: translateY(0);
            transition: opacity 0.3s ease, transform 0.3s ease;
        }

        /* =============================================
           RESPONSIVE DESIGN
           ============================================= */
        @media (max-width: 768px) {
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
            }
            
            .sidebar-nav a .icon {
                font-size: 20px;
            }
            
            .sidebar-nav a.active {
                background: var(--admin-accent);
                color: var(--admin-dark);
            }
            
            .logout-link a {
                border: 1px solid #fecaca;
                background: transparent;
            }

            .mobile-header {
                display: flex;
                align-items: center;
                justify-content: flex-start;
                padding: 12px 15px;
                width: 100%;
                position: fixed;
                top: 0;
                left: 0;
                z-index: 999;
                box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            }

            .main-content {
                width: 100%;
                height: auto;
                padding: 80px 15px 90px 15px; 
            }
            
            .main-header {
                display: none;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .gp-toast-container {
                top: 75px;
            }
        }

        /* =============================================
        NOTIFICATION BADGE
        ============================================= */
        .notification-badge {
            position: absolute;
            top: 8px;
            right: 12px;
            background: #ef4444;
            color: white;
            font-size: 11px;
            font-weight: 700;
            padding: 3px 7px;
            border-radius: 12px;
            min-width: 20px;
            text-align: center;
            box-shadow: 0 2px 8px rgba(239, 68, 68, 0.4);
            animation: pulse-badge 2s infinite;
        }

        @keyframes pulse-badge {
            0%, 100% {
                transform: scale(1);
                box-shadow: 0 2px 8px rgba(239, 68, 68, 0.4);
            }
            50% {
                transform: scale(1.1);
                box-shadow: 0 4px 12px rgba(239, 68, 68, 0.6);
            }
        }

        /* Mobile notification badge position */
        @media (max-width: 768px) {
            .notification-badge {
                top: 6px;
                right: 50%;
                transform: translateX(18px);
                font-size: 10px;
                padding: 2px 6px;
            }
        }
    </style>
</head>
<body>
    
    <!-- Header Mobile -->
    <header class="mobile-header">
        <img src="{{ asset('images/logo.png') }}" alt="GreenPalm Admin" class="mobile-logo">
        <div class="mobile-username">
            👑 Admin: {{ Auth::user()->username }}
        </div>
    </header>

    <!-- Sidebar -->
    <nav class="sidebar">
        <div class="sidebar-header">
            <img src="{{ asset('images/logo.png') }}" alt="GreenPalm Admin" class="sidebar-logo">
            <div class="admin-badge">👑 Admin Panel</div>
        </div>

        <ul class="sidebar-nav">
            <li>
                <a href="{{ route('admin.beranda') }}"
                   class="{{ request()->routeIs('admin.beranda') ? 'active' : '' }}">
                   <span class="icon">📊</span> Dashboard
                </a>
            </li>

            <li>
                <a href="{{ route('admin.users') }}"
                   class="{{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                   <span class="icon">👥</span> Kelola User
                </a>
            </li>

            <li>
                <a href="{{ route('admin.kebun') }}"
                   class="{{ request()->routeIs('admin.kebun*') ? 'active' : '' }}">
                   <span class="icon">🌴</span> Data Kebun
                </a>
            </li>

            <li>
                <a href="{{ route('admin.update-data') }}"
                   class="{{ request()->routeIs('admin.update-data*') || request()->routeIs('admin.edukasi*') || request()->routeIs('admin.harga-sawit*') || request()->routeIs('admin.penyakit*') || request()->routeIs('admin.hama*') ? 'active' : '' }}">
                   <span class="icon">📝</span> Update Data
                </a>
            </li>

            <li>
                <a href="{{ route('admin.bantuan.index') }}"
                class="{{ request()->routeIs('admin.bantuan*') ? 'active' : '' }}"
                style="position: relative;">
                <span class="icon">💬</span> Bantuan User
                
                @php
                    $unreadHelpCount = \App\Models\HelpRequest::where('status', 'pending')->count();
                @endphp
                
                @if($unreadHelpCount > 0)
                    <span class="notification-badge">{{ $unreadHelpCount }}</span>
                @endif
                </a>
            </li>

            <li class="logout-link">
                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                   <span class="icon">🚪</span> Logout
                </a>
            </li>
        </ul>
    </nav>

    <!-- Form Logout -->
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

    <!-- Konten Utama -->
    <main class="main-content">
        <div class="main-header">
            <h1>@yield('header-title', 'Admin Dashboard')</h1>
            <div class="header-username-desktop">
                <span>👑</span>
                <span>Admin: {{ Auth::user()->username }}</span>
            </div>
        </div>

        <div id="pageContent" class="page-transition">
            @yield('content')
        </div>
    </main>

    <script>
        // Page Transition Effect
        document.querySelectorAll(".sidebar-nav a").forEach(link => {
            link.addEventListener("click", function (e) {
                if (this.closest("li").classList.contains("logout-link")) {
                    return;
                }

                e.preventDefault();
                const url = this.href;
                const page = document.getElementById("pageContent");

                if (page) {
                    page.style.opacity = "0";
                    page.style.transform = "translateY(10px)";
                }

                setTimeout(() => {
                    window.location.href = url;
                }, 200);
            });
        });

        // Fade-in after load
        window.addEventListener("load", () => {
            const page = document.getElementById("pageContent");
            if (page) {
                page.style.opacity = "1";
                page.style.transform = "translateY(0)";
            }
        });

        // Toast Notification System
        @if(session('success'))
            showToast('{{ session('success') }}', 'success');
        @endif

        @if(session('error'))
            showToast('{{ session('error') }}', 'error');
        @endif

        function showToast(message, type = 'success') {
            let container = document.querySelector('.gp-toast-container');
            if (!container) {
                container = document.createElement('div');
                container.className = 'gp-toast-container';
                document.body.appendChild(container);
            }

            const toast = document.createElement('div');
            toast.className = `gp-toast gp-toast--${type}`;
            toast.textContent = message;
            container.appendChild(toast);

            setTimeout(() => toast.classList.add('is-show'), 10);
            setTimeout(() => {
                toast.classList.remove('is-show');
                toast.classList.add('is-hide');
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }
    </script>
    
</body>
</html>
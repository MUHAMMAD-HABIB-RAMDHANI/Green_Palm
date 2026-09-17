<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - Green Palm</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    {{-- Google Font Poppins --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        /* =============================================
           CSS VARIABLES
           ============================================= */
        :root {
            --primary-green: #2b7a0b;
            --dark-green: #1E4620;
            --sidebar-bg: #1F4C2B;          
            --sidebar-header-bg: #14361b;   
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
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Poppins', sans-serif; background: var(--bg-light); display: flex; height: 100vh; color: var(--text-dark); }

        /* =============================================
           SIDEBAR (Desktop)
           ============================================= */
        .sidebar { width: 260px; background: var(--sidebar-bg); height: 100vh; position: sticky; top: 0; display: flex; flex-direction: column; padding: 0; box-shadow: 0 4px 10px rgba(0,0,0,0.05); transition: width 0.3s ease; flex-shrink: 0; overflow: hidden; }
        
        .sidebar-header { background-color: var(--sidebar-header-bg); padding: 30px 20px; text-align: center; border-bottom: 1px solid rgba(255, 255, 255, 0.05); margin-bottom: 0; }
        .sidebar-logo { max-width: 140px; height: auto; display: block; margin: 0 auto; }
        
        .sidebar-nav-container { padding: 20px 15px; flex-grow: 1; display: flex; flex-direction: column; overflow-y: auto; }
        .sidebar-nav { list-style: none; margin-top: 0; }
        .sidebar-nav li { margin: 8px 0; }
        
        .sidebar-nav a { color: var(--light-green); text-decoration: none; display: flex; align-items: center; gap: 12px; padding: 14px 18px; border-radius: 10px; font-size: 15px; font-weight: 500; transition: all 0.25s ease; position: relative; border-left: 4px solid transparent; }
        
        .notification-badge { position: absolute; top: 12px; right: 12px; background: #ef4444; color: white; font-size: 10px; font-weight: 700; padding: 2px 6px; border-radius: 10px; min-width: 18px; text-align: center; box-shadow: 0 2px 5px rgba(0,0,0,0.2); }
        
        .sidebar-nav a .icon { font-size: 20px; opacity: 0.8; transition: 0.3s; }
        .sidebar-nav a:hover { background: rgba(255, 255, 255, 0.08); color: var(--white); }
        
        .sidebar-nav a.active { background: rgba(230, 241, 227, 0.15); color: #fff; font-weight: 600; border-left-color: #4ade80; }
        .sidebar-nav a.active .icon { opacity: 1; color: #4ade80; }

        /* =============================================
           BRAND TEXT DASHBOARD (DESKTOP & MOBILE)
           ============================================= */
        .brand-text-dashboard { font-weight: 800; letter-spacing: 1.5px; font-size: 20px; display: flex; justify-content: center; gap: 5px; margin-top: 15px; }
        .mobile-brand-container { display: flex; align-items: center; gap: 8px; }
        .mobile-brand-text { font-weight: 800; letter-spacing: 1px; font-size: 16px; display: flex; gap: 4px; }
        
        .text-green-static { background: linear-gradient(180deg, #6ee7b7 0%, #4ade80 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .text-palm-static { background: linear-gradient(180deg, #fbbf24 0%, #dfa91b 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }

        /* =============================================
           MOBILE HEADER & MAIN CONTENT
           ============================================= */
        /* Header Default (Logo & User) */
        .mobile-header { display: none; background: #1F4C2B; }
        .mobile-logo { height: 45px; width: auto; }
        .mobile-username { font-size: 14px; font-weight: 600; padding: 8px 12px; border-radius: 10px; background: var(--light-green); color: var(--dark-green); text-align: center; margin-left: 15px; }
        
        /* Header Custom (Untuk Menu seperti Profil, Edit, dll) */
        .mobile-header-custom { display: none; }

        /* Desktop Header */
        .main-header { margin-bottom: 25px; display: flex; align-items: center; justify-content: space-between; background: var(--primary-green); padding: 20px 25px; border-radius: 16px; box-shadow: 0 4px 20px rgba(43, 122, 11, 0.15); color: white; }
        .main-header h1 { font-size: 24px; font-weight: 700; margin: 0; color: white; display: flex; align-items: center; gap: 10px; }
        .header-username-desktop { font-size: 15px; font-weight: 600; padding: 8px 16px; border-radius: 20px; background: rgba(255, 255, 255, 0.2); backdrop-filter: blur(5px); color: white; display: flex; align-items: center; gap: 8px; }
        .badge-premium { background: linear-gradient(135deg, #FFD700 0%, #FDB931 100%); color: #8a6d3b; font-size: 11px; font-weight: 800; padding: 4px 10px; border-radius: 12px; margin-left: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        
        .main-content { flex-grow: 1; padding: 30px; overflow-y: auto; height: 100vh; }

        /* =============================================
           1. GLOBAL CONFIRM MODAL STYLE (Hijau/Netral)
           ============================================= */
        .global-modal-overlay {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0.5); z-index: 9999; display: none; 
            justify-content: center; align-items: center; backdrop-filter: blur(4px);
        }
        .global-modal-box {
            background: white; width: 90%; max-width: 380px; border-radius: 25px;
            padding: 30px; text-align: center; box-shadow: 0 20px 60px rgba(0,0,0,0.2);
            animation: globalPopUp 0.3s cubic-bezier(0.18, 0.89, 0.32, 1.28);
        }
        @keyframes globalPopUp { from { transform: scale(0.8); opacity: 0; } to { transform: scale(1); opacity: 1; } }
        .global-modal-icon {
            width: 70px; height: 70px; background: #fef2f2; color: #ef4444;
            border-radius: 50%; display: flex; align-items: center; justify-content: center;
            font-size: 32px; margin: 0 auto 20px; font-weight: bold;
        }
        .global-modal-title { font-size: 20px; font-weight: 700; color: #222; margin-bottom: 10px; font-family: 'Poppins', sans-serif; }
        .global-modal-desc { font-size: 14px; color: #666; margin-bottom: 25px; line-height: 1.5; font-family: 'Poppins', sans-serif; }
        .global-modal-actions { display: flex; gap: 12px; }
        .btn-global-cancel { flex: 1; padding: 12px; border-radius: 15px; border: 2px solid #ddd; background: white; color: #555; font-weight: 600; cursor: pointer; transition: 0.2s; font-family: 'Poppins', sans-serif; }
        .btn-global-cancel:hover { background: #f9f9f9; border-color: #ccc; }
        .btn-global-confirm { flex: 1; padding: 12px; border-radius: 15px; border: none; background: linear-gradient(135deg, #ef4444 0%, #b91c1c 100%); color: white; font-weight: 600; cursor: pointer; transition: 0.2s; box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3); font-family: 'Poppins', sans-serif; }
        .btn-global-confirm:hover { transform: translateY(-2px); }

        /* =============================================
           2. GLOBAL LOGOUT & DELETE MODAL STYLE (Merah/Danger)
           ============================================= */
        .logout-modal-box {
            background: white; border-radius: 20px; width: 90%; max-width: 400px;
            overflow: hidden; box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            animation: slideUp 0.3s ease;
        }
        
        .logout-header { background: #dc3545; padding: 20px; text-align: center; color: white; }
        .logout-header h2 { margin: 10px 0 0; font-size: 20px; font-weight: 700; }
        .logout-icon { font-size: 40px; }
        .logout-body { padding: 25px; text-align: center; font-size: 15px; color: #555; }
        .logout-footer { padding: 0 25px 25px; display: flex; gap: 10px; }
        
        .btn-logout-confirm {
            flex: 1; padding: 12px; border: none; border-radius: 15px; 
            font-weight: 600; cursor: pointer; transition: 0.2s;
            background: #dc3545; color: white; box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3);
        }
        .btn-logout-confirm:hover { background: #c82333; transform: translateY(-2px); }

        /* =============================================
           PAGE TRANSITIONS & RESPONSIVE
           ============================================= */
        .page-transition { opacity: 1; transform: translateY(0); transition: opacity 0.3s ease; }

        @media (max-width: 768px) {
            .sidebar-logo { max-width: 120px; }
            body { flex-direction: column; height: auto; }
            .sidebar { width: 100%; height: 70px; position: fixed; top: auto !important; bottom: 0 !important; left: 0; flex-direction: row; justify-content: center; padding: 0; box-shadow: var(--shadow-nav); z-index: 1000; background: var(--sidebar-bg); }
            .sidebar-header { display: none; }
            .sidebar-nav-container { padding: 0; flex-direction: row; width: 100%; overflow: visible; }
            .sidebar-nav { display: flex; justify-content: space-around; align-items: center; width: 100%; margin-top: 0; padding: 0 10px; }
            .sidebar-nav li { margin: 0; flex: 1; display: flex; justify-content: center; }
            .sidebar-nav a { flex-direction: column; justify-content: center; padding: 8px 0; font-size: 10px; gap: 4px; height: 100%; width: 100%; text-align: center; border-radius: 0; border-left: none; border-top: 3px solid transparent; }
            .notification-badge { top: 2px; right: 50%; transform: translateX(20px); }
            .sidebar-nav a .icon { font-size: 20px; }
            .sidebar-nav a.active { background: transparent; color: #4ade80; border-top-color: #4ade80; border-left-color: transparent; }
            
            /* Logic untuk Header Mobile Default */
            .mobile-header { display: flex; align-items: center; justify-content: space-between; padding: 10px 15px; width: 100%; position: fixed; top: 0; left: 0; z-index: 999; box-shadow: 0 2px 8px rgba(0,0,0,0.1); background: #1F4C2B; }
            .mobile-logo { height: 38px; width: auto; }
            .mobile-header .mobile-username { flex-grow: 0; margin-left: 0; padding: 6px 12px; font-size: 12px; max-width: 140px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; text-align: center; background: var(--light-green); color: var(--dark-green); border-radius: 10px; }
            
            /* Logic untuk Header Mobile Custom (Baru) */
            .mobile-header-custom {
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 15px 20px;
                width: 100%;
                position: fixed;
                top: 0;
                left: 0;
                z-index: 999;
                box-shadow: 0 2px 8px rgba(0,0,0,0.1);
                background: #ffffff;
                color: #222;
                height: 70px;
            }

            .main-content { width: 100%; height: auto; padding: 80px 20px 90px 20px; }
            .main-header { display: none !important; }
        }
    </style>
</head>
<body>
    
    {{-- LOGIC HEADER MOBILE --}}
    @hasSection('mobile-header')
        @yield('mobile-header')
    @else
        <header class="mobile-header">
            <div class="mobile-brand-container">
                <img src="{{ asset('images/logo.png') }}" alt="GreenPalm Logo" class="mobile-logo">
                <div class="mobile-brand-text">
                    <span class="text-green-static">GREEN</span>
                    <span class="text-palm-static">PALM</span>
                </div>
            </div>
            <div class="mobile-username" title="Halo, {{ Auth::user()->username ?? 'Petani' }}">
                👋 {{ Auth::user()->username ?? 'Petani' }}
            </div>
        </header>
    @endif

    {{-- Sidebar Navigation --}}
    <nav class="sidebar">
        <div class="sidebar-header">
            <img src="{{ asset('images/logo.png') }}" alt="GreenPalm Logo" class="sidebar-logo">
            <div class="brand-text-dashboard">
                <span class="text-green-static">GREEN</span>
                <span class="text-palm-static">PALM</span>
            </div>
        </div>

        <div class="sidebar-nav-container">
            <ul class="sidebar-nav">
                <li>
                    <a href="{{ route('dashboard.beranda') }}"
                       class="{{ request()->routeIs('dashboard.beranda') ? 'active' : '' }}">
                        <span class="icon">🏠</span> 
                        <span>Beranda</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('dashboard.notifikasi') }}" 
                       class="{{ request()->routeIs('dashboard.notifikasi') ? 'active' : '' }}">
                        <span class="icon">🔔</span> 
                        <span>Notifikasi</span>
                        
                        @php
                            $unreadCount = 0;
                            if(auth()->check() && class_exists('\App\Models\Notification')) {
                                $unreadCount = \App\Models\Notification::where('user_id', auth()->id())
                                                ->whereNull('read_at')
                                                ->count();
                            }
                        @endphp
                        
                        @if($unreadCount > 0)
                            <span class="notification-badge">{{ $unreadCount }}</span>
                        @endif
                    </a>
                </li>

                <li>
                    <a href="{{ route('dashboard.profil') }}"
                       class="{{ request()->routeIs('dashboard.profil') ? 'active' : '' }}">
                        <span class="icon">👤</span> 
                        <span>Profil</span>
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    {{-- Form Logout Global --}}
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

    {{-- Main Content --}}
    <main class="main-content">
        <div id="pageContent" class="page-transition">
            @yield('content')
        </div>
    </main>

    {{-- 1. GLOBAL MODAL (BATALKAN PROSES - HIJAU/NETRAL) --}}
    <div class="global-modal-overlay" id="globalConfirmModal">
        <div class="global-modal-box">
            <div class="global-modal-icon">!</div>
            <h3 class="global-modal-title">Batalkan Proses?</h3>
            <p class="global-modal-desc">
                Data yang belum disimpan akan hilang jika Anda kembali. Apakah Anda yakin?
            </p>
            <div class="global-modal-actions">
                <button class="btn-global-cancel" id="globalModalNo">Lanjut Mengisi</button>
                <button class="btn-global-confirm" id="globalModalYes">Ya, Kembali</button>
            </div>
        </div>
    </div>

    {{-- 2. GLOBAL LOGOUT MODAL (KONFIRMASI KELUAR - MERAH) --}}
    <div class="global-modal-overlay" id="globalLogoutModal">
        <div class="logout-modal-box">
            <div class="logout-header">
                <div class="logout-icon">🚪</div>
                <h2>Konfirmasi Keluar</h2>
            </div>
            <div class="logout-body">
                Apakah Anda yakin ingin keluar dari akun ini? Anda harus login ulang untuk mengakses data.
            </div>
            <div class="logout-footer">
                <button class="btn-global-cancel" id="cancelLogoutGlobal">Batal</button>
                <button class="btn-logout-confirm" id="confirmLogoutGlobal">Ya, Keluar</button>
            </div>
        </div>
    </div>

    {{-- 3. GLOBAL DELETE MODAL (KONFIRMASI HAPUS - MERAH) --}}
    <div class="global-modal-overlay" id="globalDeleteModal">
        <div class="logout-modal-box">
            <div class="logout-header">
                <div class="logout-icon">🗑️</div>
                <h2>Hapus Data?</h2>
            </div>
            <div class="logout-body">
                <p style="margin-bottom: 5px; font-weight: 600; color: #333;" id="deleteItemName"></p>
                <p>Apakah Anda yakin ingin menghapus data ini?</p>
            </div>
            <div class="logout-footer">
                <button class="btn-global-cancel" id="cancelDeleteGlobal">Batal</button>
                
                <form id="globalDeleteForm" action="" method="POST" style="flex: 1; display: flex;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-logout-confirm" style="width: 100%;">Ya, Hapus</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // AJAX Navigation & Page Transition
        document.querySelectorAll(".sidebar-nav a").forEach(link => {
            link.addEventListener("click", function (e) {
                // Ignore special links
                if (this.classList.contains('confirm-logout')) {
                    return;
                }

                e.preventDefault();
                const url = this.href;
                const page = document.getElementById("pageContent");

                if (page) page.style.opacity = "0";

                setTimeout(() => {
                    window.location.href = url;
                }, 200);
            });
        });

        window.addEventListener("load", () => {
            const page = document.getElementById("pageContent");
            if (page) page.style.opacity = "1";
        });

        // ============================================
        // GLOBAL CONFIRMATION LOGIC
        // ============================================
        document.addEventListener('DOMContentLoaded', function() {
            
            // --- 1. DATA LOSS CONFIRMATION (Class: .confirm-exit) ---
            const confirmModal = document.getElementById('globalConfirmModal');
            const confirmYes = document.getElementById('globalModalYes');
            const confirmNo = document.getElementById('globalModalNo');
            let confirmTargetUrl = '';

            document.body.addEventListener('click', function(e) {
                const trigger = e.target.closest('.confirm-exit');
                if (trigger) {
                    e.preventDefault();
                    confirmTargetUrl = trigger.getAttribute('href');
                    confirmModal.style.display = 'flex';
                }
            });

            if(confirmYes) {
                confirmYes.addEventListener('click', function() {
                    if(confirmTargetUrl) window.location.href = confirmTargetUrl;
                });
            }

            if(confirmNo) {
                confirmNo.addEventListener('click', function() {
                    confirmModal.style.display = 'none';
                });
            }

            // --- 2. LOGOUT CONFIRMATION (Class: .confirm-logout) ---
            const logoutModal = document.getElementById('globalLogoutModal');
            const logoutYes = document.getElementById('confirmLogoutGlobal');
            const logoutNo = document.getElementById('cancelLogoutGlobal');
            const logoutForm = document.getElementById('logout-form');

            document.body.addEventListener('click', function(e) {
                const trigger = e.target.closest('.confirm-logout');
                if (trigger) {
                    e.preventDefault();
                    logoutModal.style.display = 'flex';
                }
            });

            if(logoutYes) {
                logoutYes.addEventListener('click', function() {
                    logoutForm.submit();
                });
            }

            if(logoutNo) {
                logoutNo.addEventListener('click', function() {
                    logoutModal.style.display = 'none';
                });
            }

            // --- 3. DELETE CONFIRMATION (Class: .confirm-delete) ---
            const deleteModal = document.getElementById('globalDeleteModal');
            const deleteForm = document.getElementById('globalDeleteForm');
            const deleteItemName = document.getElementById('deleteItemName');
            const cancelDelete = document.getElementById('cancelDeleteGlobal');

            document.body.addEventListener('click', function(e) {
                const trigger = e.target.closest('.confirm-delete');
                if (trigger) {
                    e.preventDefault();
                    
                    // Ambil URL dan Nama Item dari atribut tombol
                    const actionUrl = trigger.getAttribute('data-action');
                    const itemName = trigger.getAttribute('data-name') || "Item ini";

                    // Update Form
                    deleteForm.action = actionUrl;
                    deleteItemName.textContent = `"${itemName}"`;

                    deleteModal.style.display = 'flex';
                }
            });

            if(cancelDelete) {
                cancelDelete.addEventListener('click', function() {
                    deleteModal.style.display = 'none';
                });
            }

            // --- CLOSE MODALS ON OUTSIDE CLICK ---
            window.addEventListener('click', function(e) {
                if (e.target === confirmModal) confirmModal.style.display = 'none';
                if (e.target === logoutModal) logoutModal.style.display = 'none';
                if (e.target === deleteModal) deleteModal.style.display = 'none';
            });
        });
    </script>
    
</body>
</html>
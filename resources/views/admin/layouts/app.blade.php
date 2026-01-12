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
           CSS VARIABLES (UPDATED TO GREEN THEME)
           ============================================= */
        :root {
            --primary-green: #2b7a0b;
            --dark-green: #1E4620;
            --sidebar-bg: #1F4C2B;          
            --sidebar-header-bg: #14361b;   
            --light-green: #e6f1e3;
            --admin-accent: #fbbf24; 
            --text-dark: #222;
            --text-light: #555;
            --bg-light: #f4f7f6;
            --white: #ffffff;
            --shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            --shadow-nav: 0 -2px 10px rgba(0, 0, 0, 0.1);
            --danger-red: #ef4444;
        }

        /* =============================================
           BASE STYLES
           ============================================= */
        * { box-sizing: border-box; margin: 0; padding: 0; }
        
        body { 
            font-family: 'Poppins', sans-serif; 
            background: var(--bg-light); 
            display: flex; 
            height: 100vh; 
            color: var(--text-dark);
            overflow: hidden; 
        }

        /* =============================================
           SIDEBAR (Desktop)
           ============================================= */
        .sidebar {
            width: 280px;
            background: var(--sidebar-bg);
            height: 100vh;
            position: relative; 
            display: flex;
            flex-direction: column;
            padding: 25px 15px;
            box-shadow: 4px 0 20px rgba(0,0,0,0.05);
            flex-shrink: 0;
            overflow-y: auto; 
            z-index: 50;
        }

        .sidebar::-webkit-scrollbar { width: 5px; }
        .sidebar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.2); border-radius: 5px; }
        .sidebar::-webkit-scrollbar-track { background: transparent; }

        .sidebar-header { text-align: center; margin-bottom: 25px; padding-bottom: 20px; border-bottom: 1px solid rgba(255, 255, 255, 0.1); }
        .sidebar-logo { max-width: 150px; height: auto; display: block; margin: 0 auto 10px; }
        .admin-badge { background: var(--admin-accent); color: var(--dark-green); padding: 6px 16px; border-radius: 20px; font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; display: inline-block; box-shadow: 0 2px 8px rgba(0,0,0,0.2); }

        .sidebar-nav { list-style: none; flex-grow: 1; margin-top: 10px; }
        .sidebar-nav li { margin: 8px 0; }
        .sidebar-nav a { color: var(--light-green); text-decoration: none; display: flex; align-items: center; gap: 14px; padding: 14px 18px; border-radius: 10px; font-size: 15px; font-weight: 500; transition: all 0.3s ease; position: relative; overflow: hidden; border-left: 4px solid transparent; }
        .sidebar-nav a .icon { font-size: 22px; width: 28px; text-align: center; opacity: 0.8; transition: 0.3s; }
        .sidebar-nav a:hover { background: rgba(255, 255, 255, 0.08); color: var(--white); }
        .sidebar-nav a:hover .icon { opacity: 1; }
        .sidebar-nav a.active { background: rgba(230, 241, 227, 0.15); color: #fff; font-weight: 600; border-left-color: #4ade80; }
        .sidebar-nav a.active .icon { color: #4ade80; opacity: 1; }
        
        .logout-link { margin-top: auto; padding-top: 20px; border-top: 1px solid rgba(255, 255, 255, 0.1); }
        .logout-link a { color: #fecaca; background: rgba(239, 68, 68, 0.1); }
        .logout-link a:hover { background: #ef4444; color: white; }

        /* =============================================
           MOBILE HEADER
           ============================================= */
        .mobile-header { display: none; background: var(--sidebar-bg); }
        .mobile-logo { height: 40px; width: auto; }
        .mobile-username { font-size: 13px; font-weight: 600; padding: 8px 14px; border-radius: 10px; background: var(--light-green); color: var(--dark-green); text-align: center; margin-left: 15px; flex-grow: 1; }

        /* =============================================
           MAIN CONTENT
           ============================================= */
        .main-content { 
            flex-grow: 1; 
            padding: 30px; 
            overflow-y: auto; 
            height: 100vh; 
        }
        
        .main-header { margin-bottom: 25px; display: flex; align-items: center; justify-content: space-between; background: var(--primary-green); padding: 20px 25px; border-radius: 16px; box-shadow: 0 4px 20px rgba(43, 122, 11, 0.15); color: var(--white); }
        .main-header h1 { font-size: 28px; font-weight: 700; color: var(--white); margin: 0; }
        .header-username-desktop { font-size: 15px; font-weight: 600; padding: 10px 18px; border-radius: 10px; background: rgba(255, 255, 255, 0.2); backdrop-filter: blur(5px); color: var(--white); text-align: center; display: flex; align-items: center; gap: 8px; }

        /* =============================================
           ADMIN CARDS
           ============================================= */
        .card { background: var(--white); border-radius: 16px; box-shadow: var(--shadow); padding: 24px; transition: transform 0.2s ease, box-shadow 0.2s ease; border-left: 4px solid var(--primary-green); }
        .card:hover { transform: translateY(-5px); box-shadow: 0 8px 20px rgba(0,0,0,0.12); }
        .card h3 { color: var(--dark-green); font-size: 20px; font-weight: 600; margin-bottom: 15px; }
        .card p { font-size: 15px; line-height: 1.7; color: var(--text-light); }

        /* =============================================
           MODAL & TOAST
           ============================================= */
        .modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.6); backdrop-filter: blur(4px); z-index: 9999; display: flex; align-items: center; justify-content: center; opacity: 0; visibility: hidden; transition: all 0.3s ease; }
        .modal-overlay.active { opacity: 1; visibility: visible; }
        .modal-box { background: white; width: 90%; max-width: 450px; padding: 30px; border-radius: 20px; text-align: center; box-shadow: 0 20px 60px rgba(0,0,0,0.3); transform: translateY(20px); transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275); border-top: 6px solid var(--danger-red); }
        .modal-overlay.active .modal-box { transform: translateY(0); }
        .modal-icon { font-size: 50px; color: var(--danger-red); margin-bottom: 15px; animation: shake 0.5s ease-in-out; }
        @keyframes shake { 0% { transform: rotate(0deg); } 25% { transform: rotate(-10deg); } 75% { transform: rotate(10deg); } 100% { transform: rotate(0deg); } }
        .modal-title { font-size: 22px; font-weight: 700; color: var(--text-dark); margin-bottom: 10px; }
        .modal-text { font-size: 15px; color: var(--text-light); margin-bottom: 25px; line-height: 1.5; }
        .modal-text strong { color: var(--danger-red); }
        .modal-actions { display: flex; gap: 15px; justify-content: center; }
        .btn-modal { padding: 12px 24px; border-radius: 10px; font-weight: 600; font-size: 14px; border: none; cursor: pointer; transition: all 0.2s; flex: 1; }
        .btn-cancel { background: #f1f5f9; color: #64748b; }
        .btn-cancel:hover { background: #e2e8f0; }
        .btn-confirm { background: var(--danger-red); color: white; box-shadow: 0 4px 15px rgba(239, 68, 68, 0.4); }
        .btn-confirm:hover { background: #dc2626; transform: translateY(-2px); }

        .gp-toast-container { position: fixed; top: 80px; left: 50%; transform: translateX(-50%); display: flex; flex-direction: column; gap: 8px; z-index: 9999; pointer-events: none; }
        .gp-toast { min-width: 280px; max-width: 92vw; padding: 12px 16px; border-radius: 12px; font-size: 14px; font-weight: 600; box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15); text-align: center; opacity: 0; transform: translateY(-14px); transition: transform 0.25s ease, opacity 0.25s ease; pointer-events: auto; }
        .gp-toast--success { background: #dcfce7; border: 2px solid #22c55e; color: #14532d; }
        .gp-toast--error { background: #fee2e2; border: 2px solid #ef4444; color: #991b1b; }
        .gp-toast.is-show { opacity: 1; transform: translateY(0); }
        .gp-toast.is-hide { opacity: 0; transform: translateY(-12px); }

        .page-transition { opacity: 1; transform: translateY(0); transition: opacity 0.3s ease, transform 0.3s ease; }

        /* =============================================
           RESPONSIVE DESIGN
           ============================================= */
        @media (max-width: 768px) {
            body { flex-direction: column; height: auto; overflow: auto; }
            .sidebar { width: 100%; height: 70px; position: fixed; top: auto !important; bottom: 0 !important; left: 0; flex-direction: row; justify-content: center; align-items: center; padding: 0; box-shadow: var(--shadow-nav); z-index: 1000; overflow-y: hidden; }
            .sidebar-header { display: none; }
            .sidebar-nav { display: flex; justify-content: space-around; width: 100%; margin-top: 0; padding: 0 10px; }
            .sidebar-nav li { margin: 0; flex: 1; display: flex; justify-content: center; }
            .sidebar-nav a { flex-direction: column; justify-content: center; padding: 8px 0; font-size: 11px; gap: 4px; height: 100%; width: 100%; border-left: none; border-top: 3px solid transparent; border-radius: 0; }
            .sidebar-nav a .icon { font-size: 20px; }
            .sidebar-nav a.active { background: transparent; color: #4ade80; box-shadow: none; border-top-color: #4ade80; border-left-color: transparent; }
            .logout-link a { border: none; background: transparent; }
            .mobile-header { display: flex; align-items: center; justify-content: flex-start; padding: 12px 15px; width: 100%; position: fixed; top: 0; left: 0; z-index: 999; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
            .main-content { width: 100%; height: auto; overflow-y: visible; padding: 80px 15px 90px 15px; }
            .main-header { display: none; }
            .gp-toast-container { top: 75px; }
        }
        
        .notification-badge { position: absolute; top: 8px; right: 12px; background: #ef4444; color: white; font-size: 11px; font-weight: 700; padding: 3px 7px; border-radius: 12px; min-width: 20px; text-align: center; box-shadow: 0 2px 8px rgba(239, 68, 68, 0.4); animation: pulse-badge 2s infinite; }
        @keyframes pulse-badge { 0%, 100% { transform: scale(1); box-shadow: 0 2px 8px rgba(239, 68, 68, 0.4); } 50% { transform: scale(1.1); box-shadow: 0 4px 12px rgba(239, 68, 68, 0.6); } }
        @media (max-width: 768px) { .notification-badge { top: 5px; right: 50%; transform: translateX(15px); font-size: 10px; padding: 2px 6px; } }
    </style>
</head>
<body>
    
    <header class="mobile-header">
        <img src="{{ asset('images/logo.png') }}" alt="GreenPalm Admin" class="mobile-logo">
        <div class="mobile-username">
            👑 Admin: {{ Auth::user()->username }}
        </div>
    </header>

    <nav class="sidebar">
        <div class="sidebar-header">
            <img src="{{ asset('images/logo.png') }}" alt="GreenPalm Admin" class="sidebar-logo">
            <div class="admin-badge">👑 Admin Panel</div>
        </div>

        <ul class="sidebar-nav">
            <li><a href="{{ route('admin.beranda') }}" class="{{ request()->routeIs('admin.beranda') ? 'active' : '' }}"><span class="icon">📊</span> Dashboard</a></li>
            <li><a href="{{ route('admin.users') }}" class="{{ request()->routeIs('admin.users*') ? 'active' : '' }}"><span class="icon">👥</span> Kelola User</a></li>
            <li><a href="{{ route('admin.kebun') }}" class="{{ request()->routeIs('admin.kebun*') ? 'active' : '' }}"><span class="icon">🌴</span> Data Kebun</a></li>
            <li>
                <a href="{{ route('admin.update-data') }}" class="{{ request()->routeIs('admin.update-data*') || request()->routeIs('admin.edukasi*') || request()->routeIs('admin.harga-sawit*') || request()->routeIs('admin.penyakit*') || request()->routeIs('admin.hama*') || request()->routeIs('admin.kabar-sawit*') ? 'active' : '' }}">
                    <span class="icon">📝</span> Update Data
                </a>
            </li>
            <li>
                <a href="{{ route('admin.bantuan.index') }}" class="{{ request()->routeIs('admin.bantuan*') ? 'active' : '' }}" style="position: relative;">
                    <span class="icon">💬</span> Bantuan User
                    @php $unreadHelpCount = \App\Models\HelpRequest::where('status', 'pending')->count(); @endphp
                    @if($unreadHelpCount > 0) <span class="notification-badge">{{ $unreadHelpCount }}</span> @endif
                </a>
            </li>
            <li class="logout-link">
                <a href="#" onclick="event.preventDefault(); openLogoutModal();">
                   <span class="icon">🚪</span> Logout
                </a>
            </li>
        </ul>
    </nav>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;"> @csrf </form>

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

    {{-- ================================================== --}}
    {{-- GLOBAL DELETE CONFIRMATION MODAL --}}
    {{-- ================================================== --}}
    <div id="globalDeleteModal" class="modal-overlay">
        <div class="modal-box">
            <div class="modal-icon">⚠️</div>
            <h3 class="modal-title">Konfirmasi Hapus</h3>
            <p class="modal-text">
                Apakah Anda yakin ingin menghapus data: <br>
                <strong id="deleteItemName">Item Name</strong>?
                <br><span style="font-size: 13px; color: #999;">Tindakan ini tidak dapat dibatalkan.</span>
            </p>
            
            <div class="modal-actions">
                <button type="button" class="btn-modal btn-cancel" onclick="closeDeleteModal()">Batal</button>
                <form id="globalDeleteForm" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-modal btn-confirm">Ya, Hapus!</button>
                </form>
            </div>
        </div>
    </div>

    {{-- ================================================== --}}
    {{-- LOGOUT CONFIRMATION MODAL --}}
    {{-- ================================================== --}}
    <div id="logoutModal" class="modal-overlay">
        <div class="modal-box">
            {{-- Ikon Pintu / Logout (Merah agar konsisten dengan aksi keluar) --}}
            <div class="modal-icon" style="color: var(--danger-red);">👋</div>
            
            <h3 class="modal-title">Konfirmasi Logout</h3>
            <p class="modal-text">
                Apakah Anda yakin ingin keluar dari sistem?
                <br><span style="font-size: 13px; color: #999;">Sesi Anda akan diakhiri.</span>
            </p>
            
            <div class="modal-actions">
                <button type="button" class="btn-modal btn-cancel" onclick="closeLogoutModal()">Batal</button>
                
                {{-- PERUBAHAN: Menggunakan class btn-confirm agar tombol menjadi merah --}}
                <button type="button" class="btn-modal btn-confirm" onclick="confirmLogout()">
                    Ya, Keluar
                </button>
            </div>
        </div>
    </div>

    <script>
        // Page Transition
        document.querySelectorAll(".sidebar-nav a").forEach(link => {
            link.addEventListener("click", function (e) {
                if (this.closest("li").classList.contains("logout-link")) return;
                e.preventDefault();
                const url = this.href;
                const page = document.getElementById("pageContent");
                if (page) { page.style.opacity = "0"; page.style.transform = "translateY(10px)"; }
                setTimeout(() => { window.location.href = url; }, 200);
            });
        });

        window.addEventListener("load", () => {
            const page = document.getElementById("pageContent");
            if (page) { page.style.opacity = "1"; page.style.transform = "translateY(0)"; }
        });

        // Toast Notification
        @if(session('success')) showToast('{{ session('success') }}', 'success'); @endif
        @if(session('error')) showToast('{{ session('error') }}', 'error'); @endif

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

        /* =============================================
           LOGIC MODAL DELETE GLOBAL
           ============================================= */
        function attachDeleteHandlers() {
            const deleteButtons = document.querySelectorAll('.confirm-delete');
            const modal = document.getElementById('globalDeleteModal');
            const form = document.getElementById('globalDeleteForm');
            const nameSpan = document.getElementById('deleteItemName');

            deleteButtons.forEach(button => {
                button.removeEventListener('click', openModalHandler);
                button.addEventListener('click', openModalHandler);
            });

            function openModalHandler(e) {
                e.preventDefault();
                const actionUrl = this.getAttribute('data-action');
                const itemName = this.getAttribute('data-name');
                form.action = actionUrl;
                nameSpan.textContent = itemName;
                modal.classList.add('active');
            }
        }

        function closeDeleteModal() {
            document.getElementById('globalDeleteModal').classList.remove('active');
        }

        document.getElementById('globalDeleteModal').addEventListener('click', function(e) {
            if (e.target === this) closeDeleteModal();
        });

        /* =============================================
           LOGIC MODAL LOGOUT
           ============================================= */
        function openLogoutModal() {
            document.getElementById('logoutModal').classList.add('active');
        }

        function closeLogoutModal() {
            document.getElementById('logoutModal').classList.remove('active');
        }

        function confirmLogout() {
            document.getElementById('logout-form').submit();
        }

        document.getElementById('logoutModal').addEventListener('click', function(e) {
            if (e.target === this) closeLogoutModal();
        });

        // Jalankan saat halaman dimuat
        document.addEventListener('DOMContentLoaded', attachDeleteHandlers);

    </script>
    
</body>
</html>
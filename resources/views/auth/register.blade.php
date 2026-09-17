<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Register | Green Palm</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        /* --- CSS DASAR --- */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html, body { height: 100%; width: 100%; font-family: "Poppins", sans-serif; overflow-x: hidden; overflow-y: auto; }
        body { background: linear-gradient(135deg, #0a3d24 0%, #0f5f38 25%, #1a7a4a 50%, #2d9f63 75%, #3fb865 100%); position: relative; }
        
        /* Dekorasi Background */
        body::before { content: ''; position: fixed; width: 800px; height: 800px; background: radial-gradient(circle, rgba(63, 180, 101, 0.2) 0%, transparent 70%); border-radius: 50%; top: -300px; right: -300px; z-index: 0; animation: float 10s ease-in-out infinite; }
        body::after { content: ''; position: fixed; width: 600px; height: 600px; background: radial-gradient(circle, rgba(214, 170, 77, 0.15) 0%, transparent 70%); border-radius: 50%; bottom: -200px; left: -200px; z-index: 0; animation: float 8s ease-in-out infinite reverse; }
        
        /* Particles */
        .particles { position: fixed; width: 100%; height: 100%; top: 0; left: 0; z-index: 0; pointer-events: none; }
        .particle { position: absolute; width: 4px; height: 4px; background: rgba(255, 255, 255, 0.3); border-radius: 50%; animation: rise 15s infinite ease-in; }
        .particle:nth-child(1) { left: 10%; animation-delay: 0s; } .particle:nth-child(2) { left: 25%; animation-delay: 2s; } .particle:nth-child(3) { left: 40%; animation-delay: 4s; } .particle:nth-child(4) { left: 55%; animation-delay: 1s; } .particle:nth-child(5) { left: 70%; animation-delay: 3s; } .particle:nth-child(6) { left: 85%; animation-delay: 5s; }

        /* Keyframes */
        @keyframes rise { 0% { bottom: -10%; opacity: 0; } 10% { opacity: 0.5; } 90% { opacity: 0.5; } 100% { bottom: 110%; opacity: 0; } }
        @keyframes float { 0%, 100% { transform: translateY(0) rotate(0deg) scale(1); } 50% { transform: translateY(-30px) rotate(5deg) scale(1.08); } }
        @keyframes slideInLeft { from { transform: translateX(-100px); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
        @keyframes slideInRight { from { transform: translateX(100px) scale(0.95); opacity: 0; } to { transform: translateX(0) scale(1); opacity: 1; } }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes shine { 0% { background-position: -200% center; } 100% { background-position: 200% center; } }
        
        /* Keyframe Baru untuk Teks Mengkilap (Ditambahkan) */
        @keyframes textShine { 
            0% { background-position: 150% center; } 
            100% { background-position: -250% center; } 
        }

        .layout { display: flex; min-height: 100vh; width: 100vw; position: relative; z-index: 1; }
        .left-side { width: 55vw; height: 100vh; position: relative; opacity: 0; animation: slideInLeft 1.2s cubic-bezier(0.34, 1.56, 0.64, 1) forwards; overflow: hidden; display: flex; flex-direction: column; justify-content: center; align-items: center; }
        .left-bg { position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; transition: transform 0.7s cubic-bezier(0.34, 1.56, 0.64, 1); z-index: 0; filter: brightness(0.95); }
        .left-side:hover .left-bg { transform: scale(1.08); filter: brightness(1); }
        .left-dark { position: absolute; inset: 0; background: linear-gradient(165deg, rgba(10, 77, 46, 0.85) 0%, rgba(15, 95, 56, 0.75) 30%, rgba(0, 0, 0, 0.7) 60%, rgba(0, 0, 0, 0.9) 100%); z-index: 1; }

        /* --- LOGO SHINE LOGIC (Masking & Updated Style) --- */
        .logo-wrap { position: relative; z-index: 10; text-align: center; color: white; animation: fadeIn 1.5s cubic-bezier(0.34, 1.56, 0.64, 1) 0.4s both; margin-bottom: 50px; }
        
        .logo-masked-container { 
            position: relative; 
            width: 260px; 
            margin: 0 auto 20px auto; 
            filter: drop-shadow(0 12px 24px rgba(0, 0, 0, 0.5)); 
            transition: transform 0.3s ease; 
        }
        
        .logo-masked-container img { width: 100%; height: auto; display: block; }
        
        /* Update Efek Masking & Animasi */
        .logo-masked-container::after { 
            content: ''; 
            position: absolute; 
            top: 0; left: 0; right: 0; bottom: 0; 
            background: linear-gradient(120deg, transparent 30%, rgba(255, 255, 255, 0.6) 50%, transparent 70%); 
            background-size: 200% 100%; 
            background-repeat: no-repeat; 
            animation: logoShine 3s infinite linear; 
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
        
        @keyframes logoShine { 0% { background-position: 150% 0; } 100% { background-position: -250% 0; } }
        
        .logo-wrap:hover .logo-masked-container { transform: scale(1.05); }

        /* Text Styles (UPDATED dengan Efek Kilap) */
        .logo-wrap .logo-text { font-size: 38px; font-weight: 800; letter-spacing: 8px; margin-top: 12px; text-shadow: 0 6px 20px rgba(0, 0, 0, 0.7); }
        
        .text-green { 
            background: linear-gradient(110deg, #22c55e 0%, #4ade80 30%, #ffffff 50%, #4ade80 70%, #22c55e 100%);
            background-size: 200% auto;
            -webkit-background-clip: text; 
            -webkit-text-fill-color: transparent; 
            background-clip: text; 
            filter: drop-shadow(0 0 20px rgba(74, 222, 128, 0.5)); 
            animation: textShine 3s linear infinite;
            animation-delay: 0.8s; 
        }
        
        .text-palm { 
            background: linear-gradient(110deg, #d97706 0%, #f59e0b 30%, #ffffff 50%, #f59e0b 70%, #d97706 100%);
            background-size: 200% auto;
            -webkit-background-clip: text; 
            -webkit-text-fill-color: transparent; 
            background-clip: text; 
            filter: drop-shadow(0 0 20px rgba(251, 191, 36, 0.5)); 
            animation: textShine 3s linear infinite;
            animation-delay: 0.8s;
        }

        /* Taglines */
        .taglines { position: relative; z-index: 10; text-align: center; animation: fadeIn 1.7s cubic-bezier(0.34, 1.56, 0.64, 1) 0.7s both; display: flex; flex-direction: column; gap: 16px; }
        .taglines .t1, .taglines .t2, .taglines .t3 { display: inline-block; padding: 16px 32px; font-weight: 700; border-radius: 16px; backdrop-filter: blur(20px); box-shadow: 0 8px 24px rgba(0, 0, 0, 0.4); transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1); position: relative; overflow: hidden; }
        .taglines .t1::before, .taglines .t2::before, .taglines .t3::before { content: ''; position: absolute; top: 0; left: -100%; width: 100%; height: 100%; background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent); transition: left 0.6s ease; }
        .taglines .t1:hover, .taglines .t2:hover, .taglines .t3:hover { transform: translateX(12px) scale(1.03); box-shadow: 0 12px 32px rgba(0, 0, 0, 0.5); }
        .taglines .t1:hover::before, .taglines .t2:hover::before, .taglines .t3:hover::before { left: 100%; }
        .t1 { background: linear-gradient(135deg, #134826 0%, #1a6338 50%, #22805e 100%); color: white; font-size: 18px; border: 1px solid rgba(255, 255, 255, 0.1); }
        .t2 { background: linear-gradient(135deg, #1f723d 0%, #2a8f51 50%, #35ab65 100%); color: white; font-size: 18px; border: 1px solid rgba(255, 255, 255, 0.1); }
        .t3 { background: linear-gradient(135deg, #d6aa4d 0%, #e8c068 50%, #f0cf75 100%); color: #1a1a1a; font-size: 18px; border: 1px solid rgba(255, 255, 255, 0.2); font-weight: 800; }

        /* RIGHT SIDE & CARD STYLE (Compact for Register) */
        .right-side { width: 45vw; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px; }
        
        .auth-card { 
            width: min(500px, 100%); 
            background: rgba(255, 255, 255, 0.98); 
            backdrop-filter: blur(30px); 
            padding: 35px 40px 30px; 
            border-radius: 30px; 
            text-align: center; 
            box-shadow: 0 30px 80px rgba(0, 0, 0, 0.4), 0 0 0 1px rgba(255, 255, 255, 0.2), inset 0 1px 0 rgba(255, 255, 255, 0.9); 
            opacity: 0; 
            animation: slideInRight 1.2s cubic-bezier(0.34, 1.56, 0.64, 1) 0.3s forwards; 
            border: 1px solid rgba(255, 255, 255, 0.6); 
            position: relative; 
            overflow: hidden; 
        }
        .auth-card::before { content: ''; position: absolute; top: -50%; left: -50%; width: 200%; height: 200%; background: linear-gradient(45deg, transparent 40%, rgba(45, 159, 99, 0.03) 50%, transparent 60%); animation: shine 6s infinite; }

        .auth-title { font-size: 32px; background: linear-gradient(135deg, #0a3d24 0%, #0f5f38 30%, #1a7a4a 60%, #2d9f63 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; font-weight: 900; margin-bottom: 4px; letter-spacing: -1px; position: relative; z-index: 1; }
        .auth-subtitle { font-size: 14px; color: #6b7280; margin-bottom: 20px; line-height: 1.4; font-weight: 400; position: relative; z-index: 1; }
        
        .label { display: block; font-size: 13px; font-weight: 600; color: #1f2937; text-align: left; margin-bottom: 6px; letter-spacing: 0.3px; position: relative; z-index: 1; }
        .input-group { margin-bottom: 14px; position: relative; z-index: 1; }
        
        .input { width: 100%; border-radius: 14px; padding: 12px 18px; border: 2px solid #e5e7eb; background: #f9fafb; font-size: 14px; transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1); font-family: "Poppins", sans-serif; }
        .input:focus { outline: none; border-color: #2d9f63; background: white; box-shadow: 0 0 0 4px rgba(45, 159, 99, 0.12), 0 8px 20px rgba(45, 159, 99, 0.15); transform: translateY(-1px); }
        .input:hover { border-color: #cbd5e1; background: white; }
        .input:invalid:not(:placeholder-shown) { border-color: #f87171; }
        
        .password-wrap { position: relative; }
        .password-wrap .input { padding-right: 45px; }
        .eye { width: 20px; position: absolute; right: 16px; top: 50%; transform: translateY(-50%); opacity: 0.5; cursor: pointer; transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1); filter: grayscale(1); }
        .eye:hover { opacity: 1; transform: translateY(-50%) scale(1.15) rotate(5deg); filter: grayscale(0); }

        /* --- Password Requirement Hint & Checklist --- */
        .password-hint {
            text-align: left;
            font-size: 11.5px;
            color: #6b7280;
            margin-top: 6px;
            margin-bottom: 2px;
        }

        .password-checklist {
            list-style: none;
            text-align: left;
            margin-top: 8px;
            margin-bottom: 4px;
            padding: 10px 12px;
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            display: none;
        }
        .password-checklist.show { display: block; }
        .password-checklist li {
            font-size: 12px;
            color: #9ca3af;
            padding: 2px 0;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: color 0.2s ease;
        }
        .password-checklist li .check-icon {
            width: 14px;
            display: inline-block;
            text-align: center;
        }
        .password-checklist li.valid {
            color: #166534;
            font-weight: 600;
        }
        .password-checklist li.valid .check-icon::before { content: '✓'; }
        .password-checklist li:not(.valid) .check-icon::before { content: '○'; }

        .btn-auth { width: 100%; padding: 14px; border-radius: 14px; font-size: 16px; font-weight: 700; background: linear-gradient(135deg, #0a3d24 0%, #0f5f38 25%, #1a7a4a 50%, #2d9f63 75%, #3fb865 100%); background-size: 300% 100%; color: white; border: none; cursor: pointer; transition: all 0.5s cubic-bezier(0.34, 1.56, 0.64, 1); box-shadow: 0 10px 30px rgba(15, 95, 56, 0.4); letter-spacing: 1px; position: relative; overflow: hidden; z-index: 1; text-transform: uppercase; margin-top: 8px; }
        .btn-auth:before { content: ''; position: absolute; top: 0; left: -100%; width: 100%; height: 100%; background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent); transition: left 0.6s ease; }
        .btn-auth:hover { background-position: 100% 0; box-shadow: 0 15px 40px rgba(15, 95, 56, 0.5); transform: translateY(-2px) scale(1.02); }
        .btn-auth:hover:before { left: 100%; }
        .btn-auth:active { transform: translateY(-1px) scale(1.01); box-shadow: 0 8px 20px rgba(15, 95, 56, 0.4); }

        .auth-footer { margin-top: 18px; font-size: 13.5px; color: #6b7280; position: relative; z-index: 1; }
        .auth-footer a { background: linear-gradient(135deg, #d6aa4d, #e8c068); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; font-weight: 700; text-decoration: none; transition: all 0.3s ease; position: relative; }
        .auth-footer a:after { content: ''; position: absolute; width: 0; height: 2px; bottom: -3px; left: 0; background: linear-gradient(90deg, #d6aa4d, #e8c068); transition: width 0.3s cubic-bezier(0.34, 1.56, 0.64, 1); }
        .auth-footer a:hover { filter: brightness(1.2); }
        .auth-footer a:hover:after { width: 100%; }

        /* Error/Success Alert Styling */
        .alert { padding: 10px 14px; border-radius: 10px; font-size: 12px; margin-bottom: 15px; text-align: left; position: relative; z-index: 1; }
        .alert-success { background: rgba(74, 222, 128, 0.2); color: #166534; border: 1px solid #4ade80; }
        .alert-danger { background: rgba(248, 113, 113, 0.2); color: #991b1b; border: 1px solid #f87171; }
        .error-text { color: #dc2626; font-size: 11.5px; text-align: left; margin-top: 3px; font-weight: 500; }

        /* RESPONSIVE */
        @media (max-width: 992px) {
            .layout { flex-direction: column; }
            .left-side { width: 100vw; height: 35vh; }
            .right-side { width: 100vw; min-height: 65vh; padding: 20px 16px; align-items: flex-start; }
            .auth-card { margin: 0 auto 24px; width: min(520px, 96vw); padding: 35px 30px 30px; }
            .logo-masked-container { width: 180px; }
            .logo-wrap .logo-text { font-size: 24px; letter-spacing: 5px; }
            .logo-wrap { margin-bottom: 20px; }
            .t1, .t2, .t3 { font-size: 13px; padding: 10px 20px; }
            .auth-title { font-size: 28px; }
        }

        @media (max-width: 600px) {
            .left-side { height: 30vh; }
            .logo-masked-container { width: 130px; }
            .logo-wrap .logo-text { font-size: 20px; letter-spacing: 3px; }
            .taglines { gap: 8px; }
            .taglines .t1, .taglines .t2, .taglines .t3 { padding: 8px 14px; border-radius: 10px; }
            .t1, .t2, .t3 { font-size: 11px; }
            .auth-card { padding: 30px 20px 24px; border-radius: 24px; width: 94vw; }
            .auth-title { font-size: 26px; }
            .auth-subtitle { font-size: 12px; margin-bottom: 20px; }
            .label { font-size: 12px; }
            .input { font-size: 13px; padding: 10px 14px; border-radius: 12px; }
            .btn-auth { font-size: 15px; padding: 12px; border-radius: 12px; }
            .auth-footer { font-size: 12px; }
        }
    </style>
</head>
<body>

<div class="particles">
    <div class="particle"></div><div class="particle"></div><div class="particle"></div>
    <div class="particle"></div><div class="particle"></div><div class="particle"></div>
</div>

<div class="layout">

    {{-- ========== LEFT SIDE ========== --}}
    <div class="left-side">
        <img src="{{ asset('images/palm.jpg') }}" class="left-bg" alt="Green Palm">
        <div class="left-dark"></div>

        <div class="logo-wrap">
            <div class="logo-masked-container">
                <img src="{{ asset('images/logo.png') }}" alt="Logo">
            </div>

            <div class="logo-text">
                <span class="text-green">GREEN</span> 
                <span class="text-palm">PALM</span>
            </div>
        </div>

        <div class="taglines">
            <div class="t1">GREEN PALM</div>
            <div class="t2">MENGHADIRKAN FITUR YANG</div>
            <div class="t3">MEMBANTU KESEJAHTERAAN BAGI MASYARAKAT</div>
        </div>
    </div>

    {{-- ========== RIGHT SIDE ========== --}}
    <div class="right-side">
        <div class="auth-card">

            <div class="auth-title">Register</div>
            <div class="auth-subtitle">Create your new account</div>

            {{-- Session alert --}}
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            {{-- Error validasi --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0" style="padding-left: 1rem; margin: 0;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- autocomplete="off" pada form + nama field acak samar mencegah browser auto-fill saat halaman dibuka --}}
            <form method="POST" action="{{ route('register') }}" autocomplete="off" id="registerForm">
                @csrf

                {{-- Nama pengguna --}}
                <div class="input-group">
                    <label for="username" class="label">Nama Pengguna</label>
                    <input id="username"
                           type="text"
                           name="username"
                           value="{{ old('username') }}"
                           required
                           class="input"
                           placeholder="Masukkan username anda"
                           autocomplete="off"
                           autocapitalize="off"
                           spellcheck="false">
                    @error('username')
                    <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Email --}}
                <div class="input-group">
                    <label for="email" class="label">Email</label>
                    <input id="email"
                           type="email"
                           name="email"
                           value="{{ old('email') }}"
                           required
                           class="input"
                           placeholder="nama@email.com"
                           autocomplete="off">
                    @error('email')
                    <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="input-group">
                    <label for="password" class="label">Password</label>
                    <div class="password-wrap">
                        <input id="password"
                               type="password"
                               name="password"
                               required
                               class="input"
                               placeholder="••••••••"
                               autocomplete="new-password"
                               minlength="8"
                               pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[^\s]{8,}$"
                               title="Minimal 8 karakter, kombinasi huruf besar, huruf kecil, dan angka, tanpa spasi">
                        <img src="{{ asset('images/icons/eye.png') }}"
                             alt="Toggle"
                             class="eye"
                             onclick="togglePassword('password', this)"
                             onerror="this.style.display='none'">
                    </div>
                    <div class="password-hint">
                        Minimal 8 karakter, wajib ada huruf besar, huruf kecil, dan angka. Tidak boleh mengandung spasi.
                    </div>

                    {{-- Checklist real-time --}}
                    <ul class="password-checklist" id="passwordChecklist">
                        <li id="ruleLength"><span class="check-icon"></span> Minimal 8 karakter</li>
                        <li id="ruleUpper"><span class="check-icon"></span> Mengandung huruf besar (A-Z)</li>
                        <li id="ruleLower"><span class="check-icon"></span> Mengandung huruf kecil (a-z)</li>
                        <li id="ruleNumber"><span class="check-icon"></span> Mengandung angka (0-9)</li>
                        <li id="ruleNoSpace"><span class="check-icon"></span> Tidak mengandung spasi</li>
                    </ul>

                    @error('password')
                    <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Konfirmasi password --}}
                <div class="input-group">
                    <label for="password_confirmation" class="label">Konfirmasi Password</label>
                    <div class="password-wrap">
                        <input id="password_confirmation"
                               type="password"
                               name="password_confirmation"
                               required
                               class="input"
                               placeholder="••••••••"
                               autocomplete="new-password"
                               minlength="8">
                        <img src="{{ asset('images/icons/eye.png') }}"
                             alt="Toggle"
                             class="eye"
                             onclick="togglePassword('password_confirmation', this)"
                             onerror="this.style.display='none'">
                    </div>
                    <div class="error-text" id="confirmMismatch" style="display:none;">Konfirmasi password tidak sama.</div>
                </div>

                <button type="submit" class="btn-auth">
                    Daftar
                </button>
            </form>

            <div class="auth-footer">
                Sudah punya akun?
                <a href="{{ route('login') }}">Masuk Sekarang</a>
            </div>
        </div>
    </div>
</div>

<script>
    function togglePassword(inputId, imgEl) {
        const input = document.getElementById(inputId);
        if (!input || !imgEl) return;

        const isHidden = input.type === 'password';
        input.type = isHidden ? 'text' : 'password';
    }

    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('registerForm');
        const usernameInput = document.getElementById('username');
        const emailInput = document.getElementById('email');
        const passwordInput = document.getElementById('password');
        const confirmInput = document.getElementById('password_confirmation');
        const checklist = document.getElementById('passwordChecklist');
        const confirmMismatch = document.getElementById('confirmMismatch');

        const ruleLength = document.getElementById('ruleLength');
        const ruleUpper = document.getElementById('ruleUpper');
        const ruleLower = document.getElementById('ruleLower');
        const ruleNumber = document.getElementById('ruleNumber');
        const ruleNoSpace = document.getElementById('ruleNoSpace');

        // -----------------------------------------------------
        // 1. Cegah browser auto-fill mengisi form saat halaman dibuka.
        //    Beberapa browser tetap mengisi walau autocomplete="off",
        //    jadi kita paksa kosongkan begitu halaman selesai dimuat.
        // -----------------------------------------------------
        window.addEventListener('pageshow', function () {
            // Hanya kosongkan jika TIDAK sedang menampilkan old() akibat validasi gagal
            const hasOldValue = usernameInput.value !== '' || emailInput.value !== '';
            const hasValidationError = document.querySelector('.error-text') !== null;

            if (!hasValidationError) {
                usernameInput.value = '';
                emailInput.value = '';
            }
            passwordInput.value = '';
            confirmInput.value = '';
        });

        // -----------------------------------------------------
        // 2. Checklist real-time saat mengetik password
        // -----------------------------------------------------
        passwordInput.addEventListener('focus', function () {
            checklist.classList.add('show');
        });

        passwordInput.addEventListener('input', function () {
            const val = passwordInput.value;

            const hasLength = val.length >= 8;
            const hasUpper = /[A-Z]/.test(val);
            const hasLower = /[a-z]/.test(val);
            const hasNumber = /\d/.test(val);
            const hasNoSpace = !/\s/.test(val) && val.length > 0;

            toggleRule(ruleLength, hasLength);
            toggleRule(ruleUpper, hasUpper);
            toggleRule(ruleLower, hasLower);
            toggleRule(ruleNumber, hasNumber);
            toggleRule(ruleNoSpace, hasNoSpace);
        });

        function toggleRule(el, valid) {
            if (valid) {
                el.classList.add('valid');
            } else {
                el.classList.remove('valid');
            }
        }

        // -----------------------------------------------------
        // 3. Validasi kecocokan konfirmasi password (client-side)
        // -----------------------------------------------------
        function checkMatch() {
            if (confirmInput.value.length === 0) {
                confirmMismatch.style.display = 'none';
                confirmInput.setCustomValidity('');
                return;
            }
            if (passwordInput.value !== confirmInput.value) {
                confirmMismatch.style.display = 'block';
                confirmInput.setCustomValidity('Password tidak sama');
            } else {
                confirmMismatch.style.display = 'none';
                confirmInput.setCustomValidity('');
            }
        }

        passwordInput.addEventListener('input', checkMatch);
        confirmInput.addEventListener('input', checkMatch);

        // -----------------------------------------------------
        // 4. Cegah user mengetik spasi langsung di kolom password
        // -----------------------------------------------------
        [passwordInput, confirmInput].forEach(function (el) {
            el.addEventListener('keydown', function (e) {
                if (e.key === ' ') {
                    e.preventDefault();
                }
            });
            el.addEventListener('paste', function (e) {
                const pasted = (e.clipboardData || window.clipboardData).getData('text');
                if (/\s/.test(pasted)) {
                    e.preventDefault();
                }
            });
        });
    });
</script>
</body>
</html>
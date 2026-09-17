<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login | Green Palm</title>
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
        
        /* Keyframe Baru untuk Teks Mengkilap */
        @keyframes textShine { 
            0% { background-position: 150% center; } 
            100% { background-position: -250% center; } 
        }

        .layout { display: flex; min-height: 100vh; width: 100vw; position: relative; z-index: 1; }
        .left-side { width: 55vw; height: 100vh; position: relative; opacity: 0; animation: slideInLeft 1.2s cubic-bezier(0.34, 1.56, 0.64, 1) forwards; overflow: hidden; display: flex; flex-direction: column; justify-content: center; align-items: center; }
        .left-bg { position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; transition: transform 0.7s cubic-bezier(0.34, 1.56, 0.64, 1); z-index: 0; filter: brightness(0.95); }
        .left-side:hover .left-bg { transform: scale(1.08); filter: brightness(1); }
        .left-dark { position: absolute; inset: 0; background: linear-gradient(165deg, rgba(10, 77, 46, 0.85) 0%, rgba(15, 95, 56, 0.75) 30%, rgba(0, 0, 0, 0.7) 60%, rgba(0, 0, 0, 0.9) 100%); z-index: 1; }

        /* --- LOGO SHINE LOGIC (Masking Version) --- */
        .logo-wrap {
            position: relative;
            z-index: 10;
            text-align: center;
            color: white;
            animation: fadeIn 1.5s cubic-bezier(0.34, 1.56, 0.64, 1) 0.4s both;
            margin-bottom: 50px;
        }

        /* Container Pembungkus Logo */
        .logo-masked-container {
            position: relative;
            width: 260px; /* Atur lebar logo disini */
            margin: 0 auto 20px auto;
            filter: drop-shadow(0 12px 24px rgba(0, 0, 0, 0.5));
            transition: transform 0.3s ease;
        }

        /* Gambar Logo Asli */
        .logo-masked-container img {
            width: 100%;
            height: auto;
            display: block;
        }

        /* Efek Kilap (Masking) pada Logo */
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

        @keyframes logoShine {
            0% { background-position: 150% 0; }
            100% { background-position: -250% 0; } 
        }

        .logo-wrap:hover .logo-masked-container { transform: scale(1.05); }

        /* --- TEXT STYLES (UPDATED: SHINE DELAY) --- */
        .logo-wrap .logo-text { font-size: 38px; font-weight: 800; letter-spacing: 8px; margin-top: 12px; text-shadow: 0 6px 20px rgba(0, 0, 0, 0.7); }

        .text-green { 
            background: linear-gradient(110deg, #22c55e 0%, #4ade80 30%, #ffffff 50%, #4ade80 70%, #22c55e 100%);
            background-size: 200% auto;
            -webkit-background-clip: text; 
            -webkit-text-fill-color: transparent; 
            background-clip: text; 
            filter: drop-shadow(0 0 20px rgba(74, 222, 128, 0.5)); 
            
            /* Animasi dengan Delay 0.8s agar muncul saat kilap logo di tengah */
            animation: textShine 4s linear infinite;
            animation-delay: 0.8s; 
        }

        .text-palm { 
            background: linear-gradient(110deg, #d97706 0%, #f59e0b 30%, #ffffff 50%, #f59e0b 70%, #d97706 100%);
            background-size: 200% auto;
            -webkit-background-clip: text; 
            -webkit-text-fill-color: transparent; 
            background-clip: text; 
            filter: drop-shadow(0 0 20px rgba(251, 191, 36, 0.5)); 
            
            /* Animasi dengan Delay 0.8s */
            animation: textShine 4s linear infinite;
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

        /* RIGHT SIDE & FORM */
        .right-side { width: 45vw; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 40px; }
        .login-card { width: min(540px, 100%); background: rgba(255, 255, 255, 0.98); backdrop-filter: blur(30px); padding: 60px 55px 50px; border-radius: 36px; text-align: center; box-shadow: 0 30px 80px rgba(0, 0, 0, 0.4), 0 0 0 1px rgba(255, 255, 255, 0.2), inset 0 1px 0 rgba(255, 255, 255, 0.9); opacity: 0; animation: slideInRight 1.2s cubic-bezier(0.34, 1.56, 0.64, 1) 0.3s forwards; border: 1px solid rgba(255, 255, 255, 0.6); position: relative; overflow: hidden; }
        .login-card::before { content: ''; position: absolute; top: -50%; left: -50%; width: 200%; height: 200%; background: linear-gradient(45deg, transparent 40%, rgba(45, 159, 99, 0.03) 50%, transparent 60%); animation: shine 6s infinite; }
        .login-title { font-size: 42px; background: linear-gradient(135deg, #0a3d24 0%, #0f5f38 30%, #1a7a4a 60%, #2d9f63 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; font-weight: 900; margin-bottom: 12px; letter-spacing: -1px; position: relative; z-index: 1; }
        .login-subtitle { font-size: 15.5px; color: #6b7280; margin-bottom: 40px; line-height: 1.6; font-weight: 400; position: relative; z-index: 1; }
        .label { display: block; font-size: 14px; font-weight: 600; color: #1f2937; text-align: left; margin-bottom: 10px; letter-spacing: 0.3px; position: relative; z-index: 1; }
        .input-group { margin-bottom: 24px; position: relative; z-index: 1; }
        .input { width: 100%; border-radius: 16px; padding: 16px 22px; border: 2px solid #e5e7eb; background: #f9fafb; font-size: 15px; transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1); font-family: "Poppins", sans-serif; }
        .input:focus { outline: none; border-color: #2d9f63; background: white; box-shadow: 0 0 0 5px rgba(45, 159, 99, 0.12), 0 8px 20px rgba(45, 159, 99, 0.15); transform: translateY(-2px); }
        .input:hover { border-color: #cbd5e1; background: white; }
        .password-wrap { position: relative; }
        .password-wrap .input { padding-right: 55px; }
        .eye { width: 24px; position: absolute; right: 20px; top: 50%; transform: translateY(-50%); opacity: 0.5; cursor: pointer; transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1); filter: grayscale(1); }
        .eye:hover { opacity: 1; transform: translateY(-50%) scale(1.15) rotate(5deg); filter: grayscale(0); }
        
        .forgot { text-align: right; margin-top: 12px; margin-bottom: 32px; position: relative; z-index: 1; }
        .forgot a { font-size: 13.5px; color: #6b7280; text-decoration: none; font-weight: 600; transition: all 0.3s ease; position: relative; }
        .forgot a:after { content: ''; position: absolute; width: 0; height: 2px; bottom: -3px; left: 0; background: linear-gradient(90deg, #2d9f63, #1a7a4a); transition: width 0.3s cubic-bezier(0.34, 1.56, 0.64, 1); }
        .forgot a:hover { color: #2d9f63; }
        .forgot a:hover:after { width: 100%; }
        
        .btn-login { width: 100%; padding: 18px; border-radius: 16px; font-size: 17px; font-weight: 700; background: linear-gradient(135deg, #0a3d24 0%, #0f5f38 25%, #1a7a4a 50%, #2d9f63 75%, #3fb865 100%); background-size: 300% 100%; color: white; border: none; cursor: pointer; transition: all 0.5s cubic-bezier(0.34, 1.56, 0.64, 1); box-shadow: 0 10px 30px rgba(15, 95, 56, 0.4); letter-spacing: 1px; position: relative; overflow: hidden; z-index: 1; text-transform: uppercase; }
        .btn-login:before { content: ''; position: absolute; top: 0; left: -100%; width: 100%; height: 100%; background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent); transition: left 0.6s ease; }
        .btn-login:hover { background-position: 100% 0; box-shadow: 0 15px 40px rgba(15, 95, 56, 0.5); transform: translateY(-3px) scale(1.02); }
        .btn-login:hover:before { left: 100%; }
        .btn-login:active { transform: translateY(-1px) scale(1.01); box-shadow: 0 8px 20px rgba(15, 95, 56, 0.4); }
        
        .register-text { margin-top: 28px; font-size: 14.5px; color: #6b7280; position: relative; z-index: 1; }
        .register-text a { background: linear-gradient(135deg, #d6aa4d, #e8c068); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; font-weight: 700; text-decoration: none; transition: all 0.3s ease; position: relative; }
        .register-text a:after { content: ''; position: absolute; width: 0; height: 2px; bottom: -3px; left: 0; background: linear-gradient(90deg, #d6aa4d, #e8c068); transition: width 0.3s cubic-bezier(0.34, 1.56, 0.64, 1); }
        .register-text a:hover { filter: brightness(1.2); }
        .register-text a:hover:after { width: 100%; }

        .error-message {
    color: #ef4444; /* Warna merah */
    font-size: 12px;
    font-weight: 600;
    text-align: left;
    margin-top: 5px;
    display: block;
    animation: fadeIn 0.3s ease;
}

.input.is-invalid {
    border: 2px solid #ff4d4d !important;
    background-color: #fff5f5 !important;
    box-shadow: 0 0 0 4px rgba(255, 77, 77, 0.1) !important;
}
.input.is-invalid {
    animation: shake 0.2s ease-in-out 0s 2;
}
/* Animasi getar sedikit saat error (Opsional tapi keren) */
@keyframes shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-5px); }
    75% { transform: translateX(5px); }
}

        /* RESPONSIVE */
        @media (max-width: 992px) {
            .layout { flex-direction: column; }
            .left-side { width: 100vw; height: 40vh; }
            .right-side { width: 100vw; min-height: 60vh; padding: 24px 16px; }
            .login-card { margin: 12px auto 24px; width: min(520px, 96vw); padding: 50px 45px 40px; }
            .logo-masked-container { width: 200px; }
            .logo-wrap .logo-text { font-size: 28px; letter-spacing: 6px; }
            .logo-wrap { margin-bottom: 30px; }
            .t1, .t2, .t3 { font-size: 15px; padding: 14px 28px; }
            .login-title { font-size: 36px; }
        }

        @media (max-width: 600px) {
            .left-side { height: 35vh; }
            .logo-masked-container { width: 150px; }
            .logo-wrap .logo-text { font-size: 22px; letter-spacing: 4px; }
            .taglines { gap: 10px; }
            .taglines .t1, .taglines .t2, .taglines .t3 { padding: 10px 18px; border-radius: 12px; }
            .t1, .t2, .t3 { font-size: 13px; }
            .login-card { padding: 40px 28px 32px; border-radius: 28px; width: 94vw; }
            .login-title { font-size: 32px; }
            .login-subtitle { font-size: 14px; margin-bottom: 32px; }
            .label { font-size: 13px; }
            .input { font-size: 14px; padding: 14px 18px; border-radius: 14px; }
            .btn-login { font-size: 16px; padding: 16px; border-radius: 14px; }
            .register-text { font-size: 13.5px; }
        }
    </style>
</head>

<body>

<div class="particles">
    <div class="particle"></div><div class="particle"></div><div class="particle"></div>
    <div class="particle"></div><div class="particle"></div><div class="particle"></div>
</div>

<div class="layout">
    <div class="left-side">
        <img src="{{ asset('images/palm-welcome.png') }}" class="left-bg" alt="Palm plantation">
        <div class="left-dark"></div>

        <div class="logo-wrap">
            <div class="logo-masked-container">
                <img src="{{ asset('images/logo.png') }}" alt="Green Palm Logo">
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

    <div class="right-side">
        <div class="login-card">
            <div class="login-title">Welcome</div>
            <div class="login-subtitle">Please login or sign up to continue<br>using our app</div>
            
            <form method="POST" action="{{ route('login') }}">
    @csrf 
    
    <div class="input-group">
    <label class="label">Email</label>
    <input type="email" 
           name="email" 
           value="{{ old('email') }}" 
           class="input @error('email') is-invalid @enderror" 
           placeholder="nama@email.com" 
           required>
    @error('email')
        <span class="error-message" style="color: #ff4d4d; font-size: 13px; display: block; text-align: left; margin-top: 5px;">
            {{ $message }}
        </span>
    @enderror
</div>

<div class="input-group">
    <label class="label">Password</label>
    <div class="password-wrap">
        <input type="password" 
               id="password" 
               name="password" 
               class="input @error('password') is-invalid @enderror" 
               placeholder="••••••••" 
               required>
        <img src="{{ asset('images/icons/eye.png') }}" class="eye" onclick="togglePassword()" alt="Toggle">
    </div>
    @error('password')
        <span class="error-message" style="color: #ff4d4d; font-size: 13px; display: block; text-align: left; margin-top: 5px;">
            {{ $message }}
        </span>
    @enderror
</div>

    <div class="forgot">
        <a href="{{ route('password.forgot') }}">Lupa Password?</a>
    </div>

    <button class="btn-login" type="submit">Masuk</button>
    
    @if ($errors->has('loginError'))
        <div class="error-message" style="text-align: center; margin-top: 15px;">
            {{ $errors->first('loginError') }}
        </div>
    @endif

    <div class="register-text">
        Belum punya akun? <a href="{{ route('register') }}">Daftar Sekarang</a>
    </div>
</form>
        </div>
    </div>
</div>

<script>
    function togglePassword() {
        const input = document.getElementById("password");
        input.type = input.type === "password" ? "text" : "password";
    }
</script>

</body>
</html>
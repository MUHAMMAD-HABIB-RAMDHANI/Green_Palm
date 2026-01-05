@extends('toke.toke')

@section('title', 'Profil Toke')

@section('content')

<style>
/* Menggunakan style yang sama dengan profil user biasa, minus stats */
.profile-grid {
    display: grid;
    grid-template-columns: 380px 1fr;
    gap: 25px;
    max-width: 1200px;
    margin: 0 auto;
}

.profile-info-card {
    background: linear-gradient(135deg, #1e4620 0%, #2b7a0b 100%);
    border-radius: 20px;
    box-shadow: 0 8px 24px rgba(43, 122, 11, 0.25);
    padding: 40px 30px;
    text-align: center;
    position: relative;
    overflow: hidden;
}

.profile-info-card::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
    animation: pulse 8s ease-in-out infinite;
}

@keyframes pulse {
    0%, 100% { transform: scale(1); opacity: 0.5; }
    50% { transform: scale(1.1); opacity: 0.3; }
}

.profile-picture-wrapper {
    position: relative;
    width: 160px;
    height: 160px;
    margin: 0 auto 25px;
    z-index: 1;
}

.profile-avatar {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    object-fit: cover;
    border: 5px solid rgba(255, 255, 255, 0.3);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
    background: white;
}

.profile-picture-wrapper::after {
    content: '';
    position: absolute;
    inset: -8px;
    border-radius: 50%;
    border: 3px solid transparent;
    border-top-color: rgba(255, 255, 255, 0.5);
    border-right-color: rgba(255, 255, 255, 0.5);
    animation: rotate 4s linear infinite;
}

@keyframes rotate {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

.edit-pic-btn {
    position: absolute;
    bottom: 8px;
    right: 8px;
    width: 44px;
    height: 44px;
    background: #d6aa4d;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    font-size: 18px;
    border: 4px solid #1e4620;
    cursor: pointer;
    transition: all 0.3s ease;
    z-index: 2;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.edit-pic-btn:hover {
    transform: scale(1.15) rotate(15deg);
    background: #e0b95d;
    box-shadow: 0 6px 16px rgba(214, 170, 77, 0.4);
}

.profile-name {
    font-size: 26px;
    font-weight: 700;
    color: white;
    margin: 0 0 8px 0;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    z-index: 1;
    position: relative;
}

.profile-email {
    font-size: 15px;
    color: rgba(255, 255, 255, 0.85);
    margin: 0;
    word-break: break-all;
    z-index: 1;
    position: relative;
}

.profile-actions {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.info-box {
    background: linear-gradient(135deg, #fff9e6 0%, #ffe9b3 100%);
    border-radius: 16px;
    padding: 24px 28px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    box-shadow: 0 4px 12px rgba(214, 170, 77, 0.15);
    border-left: 5px solid #d6aa4d;
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
}

.info-box::before {
    content: '🏭';
    position: absolute;
    right: -10px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 80px;
    opacity: 0.1;
}

.info-box:hover {
    transform: translateX(5px);
    box-shadow: 0 6px 16px rgba(214, 170, 77, 0.25);
}

.info-box-text {
    z-index: 1;
}

.info-box-text h5 {
    font-size: 18px;
    font-weight: 700;
    margin: 0 0 6px 0;
    color: #856404;
}

.info-box-text p {
    font-size: 15px;
    margin: 0;
    color: #856404;
    font-weight: 500;
}

.info-box-close {
    text-decoration: none;
    font-size: 28px;
    font-weight: 300;
    color: #d6aa4d;
    transition: all 0.3s ease;
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    background: rgba(214, 170, 77, 0.1);
}

.info-box-close:hover {
    background: rgba(214, 170, 77, 0.2);
    color: #b88c3d;
    transform: rotate(90deg);
}

.action-list-card {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
}

.action-list-card:hover {
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
}

.action-item {
    display: flex;
    align-items: center;
    padding: 20px 25px;
    text-decoration: none;
    color: #222;
    border-bottom: 1px solid #f0f0f0;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.action-item::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    width: 4px;
    height: 100%;
    background: var(--primary-green);
    transform: scaleY(0);
    transition: transform 0.3s ease;
}

.action-item:hover::before {
    transform: scaleY(1);
}

.action-item:last-child {
    border-bottom: none;
}

.action-item:hover {
    background: linear-gradient(90deg, rgba(230, 241, 227, 0.5) 0%, transparent 100%);
    padding-left: 30px;
}

.action-item .icon {
    width: 48px;
    height: 48px;
    background: linear-gradient(135deg, #e6f1e3 0%, #d4e7d0 100%);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    margin-right: 18px;
    flex-shrink: 0;
    transition: all 0.3s ease;
}

.action-item:hover .icon {
    background: linear-gradient(135deg, #2b7a0b 0%, #1e4620 100%);
    transform: scale(1.1) rotate(5deg);
    box-shadow: 0 4px 12px rgba(43, 122, 11, 0.3);
}

.action-item .text {
    flex-grow: 1;
    font-size: 16px;
    font-weight: 600;
    color: #222;
    transition: color 0.3s ease;
}

.action-item:hover .text {
    color: var(--primary-green);
}

.action-item .chevron {
    font-size: 24px;
    font-weight: 700;
    color: #ccc;
    transition: all 0.3s ease;
}

.action-item:hover .chevron {
    color: var(--primary-green);
    transform: translateX(5px);
}

/* Logout Special Styling */
.action-item:last-child {
    background: linear-gradient(135deg, #fff5f5 0%, #ffe0e0 50%, white 100%);
}

.action-item:last-child .icon {
    background: linear-gradient(135deg, #ffe0e0 0%, #ffb3b3 100%);
}

.action-item:last-child:hover {
    background: linear-gradient(90deg, rgba(255, 230, 230, 0.8) 0%, rgba(255, 240, 240, 0.5) 100%);
}

.action-item:last-child:hover .icon {
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
}

.action-item:last-child:hover .text {
    color: #dc3545;
}

.action-item:last-child:hover .chevron {
    color: #dc3545;
}

/* Logout Modal */
.logout-modal-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.6);
    backdrop-filter: blur(4px);
    z-index: 10000;
    animation: fadeIn 0.3s ease;
}

.logout-modal-overlay.show {
    display: flex;
    align-items: center;
    justify-content: center;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.logout-modal {
    background: white;
    border-radius: 20px;
    padding: 0;
    max-width: 420px;
    width: 90%;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    animation: slideUp 0.3s ease;
    overflow: hidden;
}

@keyframes slideUp {
    from { 
        opacity: 0;
        transform: translateY(30px) scale(0.95);
    }
    to { 
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

.logout-modal-header {
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
    padding: 30px;
    text-align: center;
    position: relative;
    overflow: hidden;
}

.logout-modal-icon {
    width: 80px;
    height: 80px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 15px;
    font-size: 40px;
    position: relative;
    z-index: 1;
}

.logout-modal-title {
    font-size: 24px;
    font-weight: 700;
    color: white;
    margin: 0;
    position: relative;
    z-index: 1;
}

.logout-modal-body {
    padding: 30px;
    text-align: center;
}

.logout-modal-message {
    font-size: 16px;
    color: #555;
    line-height: 1.6;
    margin: 0;
}

.logout-modal-footer {
    padding: 0 30px 30px;
    display: flex;
    gap: 12px;
}

.logout-btn {
    flex: 1;
    padding: 14px 24px;
    border: none;
    border-radius: 12px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    font-family: 'Poppins', sans-serif;
}

.logout-btn-cancel {
    background: #f0f0f0;
    color: #555;
}

.logout-btn-cancel:hover {
    background: #e0e0e0;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.logout-btn-confirm {
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
    color: white;
    box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3);
}

.logout-btn-confirm:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(220, 53, 69, 0.4);
}

@media (max-width: 992px) {
    .profile-grid {
        grid-template-columns: 1fr;
        gap: 20px;
    }
}

@media (max-width: 768px) {
    .profile-picture-wrapper {
        width: 140px;
        height: 140px;
    }
}
</style>

<header class="main-header">
    <h1>👤 Profil Toke</h1>
    <div class="header-username-desktop">
        👋 Halo, {{ $user->username }}
    </div>
</header>

<div class="profile-grid">

    <div class="profile-info-card">
        <div class="profile-picture-wrapper">
            <img
                id="profile-avatar"
                src="{{ $user->profile_picture 
                        ? asset('storage/' . $user->profile_picture) 
                        : 'https://placehold.co/160x160/e6f1e3/1E4620?text=' . strtoupper(substr($user->username, 0, 1)) }}"
                alt="Foto Profil"
                class="profile-avatar">

            <label for="avatar-file" class="edit-pic-btn" title="Ganti foto">📝</label>
        </div>

        <h3 class="profile-name">{{ $user->username }}</h3>
        <p class="profile-email">{{ $user->email }}</p>
    </div>

    <div class="profile-actions">

        <div class="info-box">
            <div class="info-box-text">
                <h5>🏭 Toke Sawit</h5>
                <p>Kelola informasi RAM Anda dengan mudah</p>
            </div>
            <a href="#" class="info-box-close" onclick="this.parentElement.style.display='none'; return false;">&times;</a>
        </div>

        <div class="action-list-card">
            {{-- LINK DIPERBAIKI: Mengarah ke route toke.profile.edit --}}
            <a href="{{ route('toke.profile.edit') }}" class="action-item">
                <span class="icon">📝</span>
                <span class="text">Edit Profil</span>
                <span class="chevron">&gt;</span>
            </a>
            <a href="#" class="action-item"
               onclick="alert('Fitur help center akan segera hadir!'); return false;">
                <span class="icon">❓</span>
                <span class="text">Help Center</span>
                <span class="chevron">&gt;</span>
            </a>
            <a href="#" class="action-item"
               onclick="alert('Terima kasih! Fitur rating akan segera hadir.'); return false;">
                <span class="icon">⭐</span>
                <span class="text">Beri Penilaian untuk Green Palm</span>
                <span class="chevron">&gt;</span>
            </a>
            <a href="#" class="action-item" id="logout-trigger">
                <span class="icon">🚪</span>
                <span class="text">Logout</span>
                <span class="chevron">&gt;</span>
            </a>
        </div>

    </div>
</div>

{{-- Logout Modal --}}
<div class="logout-modal-overlay" id="logoutModal">
    <div class="logout-modal">
        <div class="logout-modal-header">
            <div class="logout-modal-icon">🚪</div>
            <h2 class="logout-modal-title">Konfirmasi Logout</h2>
        </div>
        <div class="logout-modal-body">
            <p class="logout-modal-message">
                Apakah Anda yakin ingin keluar dari akun Anda?
            </p>
        </div>
        <div class="logout-modal-footer">
            <button type="button" class="logout-btn logout-btn-cancel" id="cancelLogout">
                Batal
            </button>
            <button type="button" class="logout-btn logout-btn-confirm" id="confirmLogout">
                Ya, Keluar
            </button>
        </div>
    </div>
</div>

{{-- Form upload tersembunyi (LINK DIPERBAIKI: Mengarah ke toke.profile.photo) --}}
<form id="upload-avatar-form"
      action="{{ route('toke.profile.photo') }}"
      method="POST"
      enctype="multipart/form-data"
      style="display:none">
    @csrf
    <input id="avatar-file" type="file" name="profile_picture" accept="image/*">
</form>

{{-- Form Logout --}}
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>

{{-- Toast Notifications --}}
@php
    $s = session('success');
    $e = session('error');
    if ($s) $s = trim(str_replace(['✓','✔','×','✖'], '', $s));
    if ($e) $e = trim(str_replace(['✓','✔','×','✖'], '', $e));
@endphp

<div id="toast-container" class="gp-toast-container">
    @if($s)
        <div class="gp-toast gp-toast--success">{{ $s }}</div>
    @endif
    @if($e)
        <div class="gp-toast gp-toast--error">{{ $e }}</div>
    @endif
    @if($errors->any())
        <div class="gp-toast gp-toast--error">
            @if($errors->count() === 1)
                {{ $errors->first() }}
            @else
                Terjadi {{ $errors->count() }} kesalahan. Periksa input Anda.
            @endif
        </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Upload Handler
    const fileInput = document.getElementById('avatar-file');
    const uploadForm = document.getElementById('upload-avatar-form');

    if (fileInput && uploadForm) {
        fileInput.addEventListener('change', function () {
            if (!fileInput.files || fileInput.files.length === 0) return;

            const file = fileInput.files[0];
            const max2MB = 2 * 1024 * 1024;
            const allowed = ['image/jpeg','image/png','image/jpg'];

            if (!allowed.includes(file.type)) {
                alert('Format foto harus JPG atau PNG');
                fileInput.value = '';
                return;
            }
            if (file.size > max2MB) {
                alert('Ukuran foto maksimal 2MB');
                fileInput.value = '';
                return;
            }
            uploadForm.submit();
        });
    }

    // Toast Handler
    const container = document.getElementById('toast-container');
    if (container) {
        container.querySelectorAll('.gp-toast').forEach((el, idx) => {
            requestAnimationFrame(() => el.classList.add('is-show'));
            setTimeout(() => {
                el.classList.remove('is-show');
                el.classList.add('is-hide');
                setTimeout(() => el.remove(), 250);
            }, 2000 + idx * 150);
        });
    }

    // Logout Modal
    const logoutTrigger = document.getElementById('logout-trigger');
    const logoutModal = document.getElementById('logoutModal');
    const cancelLogout = document.getElementById('cancelLogout');
    const confirmLogout = document.getElementById('confirmLogout');
    const logoutForm = document.getElementById('logout-form');

    if (logoutTrigger && logoutModal) {
        logoutTrigger.addEventListener('click', function(e) {
            e.preventDefault();
            logoutModal.classList.add('show');
            document.body.style.overflow = 'hidden';
        });

        if (cancelLogout) {
            cancelLogout.addEventListener('click', function() {
                logoutModal.classList.remove('show');
                document.body.style.overflow = '';
            });
        }

        logoutModal.addEventListener('click', function(e) {
            if (e.target === logoutModal) {
                logoutModal.classList.remove('show');
                document.body.style.overflow = '';
            }
        });

        if (confirmLogout) {
            confirmLogout.addEventListener('click', function() {
                if (logoutForm) {
                    logoutForm.submit();
                }
            });
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && logoutModal.classList.contains('show')) {
                logoutModal.classList.remove('show');
                document.body.style.overflow = '';
            }
        });
    }
});
</script>

@endsection
@extends('admin.layouts.app')

@section('title', 'Kelola Users')
@section('header-title', 'Kelola Pengguna')

@section('content')
<div style="max-width: 1400px; margin: 0 auto;">
    
    {{-- Header Section (Green Gradient) --}}
    <div class="card" style="margin-bottom: 25px; background: linear-gradient(135deg, #1E4620 0%, #2b7a0b 100%); border: none;">
        <div style="display: flex; align-items: center; justify-content: space-between;">
            <div>
                <h2 style="color: white; font-size: 24px; margin: 0 0 8px 0; font-weight: 700;">
                    👥 Manajemen Pengguna
                </h2>
                <p style="color: rgba(255,255,255,0.9); margin: 0; font-size: 15px;">
                    Total: <strong>{{ $users->total() }}</strong> pengguna terdaftar
                </p>
            </div>
        </div>
    </div>

    {{-- Users Table --}}
    <div class="card">
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 2px solid #e2e8f0;">
                        {{-- Table Headers (Dark Green Text) --}}
                        <th style="padding: 15px; text-align: left; font-weight: 600; color: #1E4620;">ID</th>
                        <th style="padding: 15px; text-align: left; font-weight: 600; color: #1E4620;">Username</th>
                        <th style="padding: 15px; text-align: left; font-weight: 600; color: #1E4620;">Email</th>
                        <th style="padding: 15px; text-align: left; font-weight: 600; color: #1E4620;">Terdaftar</th>
                        <th style="padding: 15px; text-align: center; font-weight: 600; color: #1E4620;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    {{-- Row Hover Effect (Very Light Green) --}}
                    <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.2s ease;" 
                        onmouseover="this.style.background='#f0fdf4'" 
                        onmouseout="this.style.background='white'">
                        
                        <td style="padding: 15px;">
                            {{-- ID Badge (Light Green BG, Dark Green Text) --}}
                            <span style="background: #dcfce7; color: #14532d; padding: 4px 10px; border-radius: 6px; font-weight: 600; font-size: 13px;">
                                #{{ $user->id }}
                            </span>
                        </td>
                        
                        <td style="padding: 15px;">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                @if($user->email === 'admin@gmail.com')
                                    <span style="font-size: 20px;">👑</span>
                                @else
                                    <span style="font-size: 20px;">👤</span>
                                @endif
                                <strong>{{ $user->username }}</strong>
                            </div>
                        </td>
                        
                        <td style="padding: 15px; color: #64748b;">{{ $user->email }}</td>
                        
                        <td style="padding: 15px; color: #64748b; font-size: 14px;">
                            {{ $user->created_at->format('d M Y') }}
                        </td>
                        
                        <td style="padding: 15px; text-align: center;">
                            {{-- Detail Button (Primary Green to Dark Green Hover) --}}
                            <a href="{{ route('admin.users.detail', $user->id) }}" 
                               style="display: inline-block; padding: 8px 16px; background: #2b7a0b; color: white; text-decoration: none; border-radius: 8px; font-size: 14px; font-weight: 600; transition: all 0.3s ease;"
                               onmouseover="this.style.background='#1E4620'"
                               onmouseout="this.style.background='#2b7a0b'">
                                📊 Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="padding: 40px; text-align: center; color: #94a3b8;">
                            <div style="font-size: 48px; margin-bottom: 15px;">👥</div>
                            <p style="margin: 0; font-size: 16px;">Belum ada pengguna terdaftar</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($users->hasPages())
        <div style="margin-top: 25px; padding-top: 20px; border-top: 2px solid #f1f5f9;">
            {{ $users->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
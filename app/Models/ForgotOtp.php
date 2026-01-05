<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ForgotOtp extends Model
{
    protected $table = 'forgot_otps';

    // Tabel tidak punya kolom updated_at
    public $timestamps = false;

    protected $fillable = [
        'email',
        'otp',
        'created_at',
        'expires_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'expires_at' => 'datetime',
    ];
}

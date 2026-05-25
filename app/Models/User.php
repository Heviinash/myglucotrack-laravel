<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'fullname',
        'email',
        'password',
        'tenant_id',
        'role',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function sessionLogs()
    {
        return $this->hasMany(SessionLog::class);
    }

    // For God panel: count patients/records via shared tenant
    public function patientsVia()
    {
        return $this->hasMany(Patient::class, 'tenant_id', 'tenant_id');
    }

    public function recordsVia()
    {
        return $this->hasMany(BloodSugarLevel::class, 'tenant_id', 'tenant_id');
    }

    public function isGod(): bool   { return $this->role === 'System God'; }
    public function isAdmin(): bool { return $this->role === 'Admin'; }
    public function isUser(): bool  { return $this->role === 'User'; }
    public function isActive(): bool { return $this->status === 'Active'; }
}

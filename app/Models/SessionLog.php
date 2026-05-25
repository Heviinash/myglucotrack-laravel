<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Scopes\TenantScope;

class SessionLog extends Model
{
    protected $fillable = [
        'user_id',
        'tenant_id',
        'device_info',
        'ip_address',
        'login_time',
        'logout_time',
        'still_active'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    protected static function booted()
    {
        static::addGlobalScope(new TenantScope);
    }

}
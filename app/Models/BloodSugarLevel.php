<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Scopes\TenantScope;

class BloodSugarLevel extends Model
{
    protected $fillable = [
        'patient_id',
        'blood_sugar_level',
        'before_after',
        'measurement_time',
        'measurement_date',
        'measurement_by',
        'notes',
        'tenant_id'
    ];


    public function getStatusAttribute()
    {
        if ($this->blood_sugar_level < 5.6) {
            return 'Normal';
        }

        if ($this->blood_sugar_level < 7) {
            return 'Pre-diabetic';
        }

        return 'High';
    }


    public function getStatusColorAttribute()
    {
        if ($this->blood_sugar_level < 5.6) {
            return 'green';
        }

        if ($this->blood_sugar_level < 7) {
            return 'yellow';
        }

        return 'red';
    }



    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    protected static function booted()
    {
        static::addGlobalScope(new TenantScope);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Scopes\TenantScope;
use Carbon\Carbon;

class Patient extends Model
{
    protected $fillable = [
        'patient_name',
        'ic_number',
        'dob',
        'age',
        'gender',
        'phone',
        'address',
        'tenant_id',
    ];

    protected $casts = [
        'dob' => 'date',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function bloodSugarLevels()
    {
        return $this->hasMany(BloodSugarLevel::class);
    }

    protected static function booted()
    {
        static::addGlobalScope(new TenantScope);
    }

    // Auto-calculate age from IC (first 6 digits = YYMMDD)
    public static function ageFromIc(string $ic): int
    {
        $year  = (int) substr($ic, 0, 2);
        $month = (int) substr($ic, 2, 2);
        $day   = (int) substr($ic, 4, 2);

        // Determine century: if year > current 2-digit year, it's 1900s
        $currentYear2 = (int) date('y');
        $fullYear = $year <= $currentYear2 ? 2000 + $year : 1900 + $year;

        try {
            $dob = Carbon::createFromDate($fullYear, $month, $day);
            return $dob->age;
        } catch (\Exception $e) {
            return 0;
        }
    }

    // Auto-calculate DOB from IC
    public static function dobFromIc(string $ic): ?string
    {
        $year  = (int) substr($ic, 0, 2);
        $month = (int) substr($ic, 2, 2);
        $day   = (int) substr($ic, 4, 2);

        $currentYear2 = (int) date('y');
        $fullYear = $year <= $currentYear2 ? 2000 + $year : 1900 + $year;

        try {
            return Carbon::createFromDate($fullYear, $month, $day)->toDateString();
        } catch (\Exception $e) {
            return null;
        }
    }
}

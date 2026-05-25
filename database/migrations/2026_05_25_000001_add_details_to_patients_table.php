<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->string('ic_number', 12)->nullable()->after('patient_name');
            $table->date('dob')->nullable()->after('ic_number');
            $table->string('gender')->nullable()->after('dob');
            $table->string('phone', 20)->nullable()->after('gender');
            $table->text('address')->nullable()->after('phone');
            // age is now auto-calculated, keep column but make nullable
        });
    }

    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropColumn(['ic_number','dob','gender','phone','address']);
        });
    }
};

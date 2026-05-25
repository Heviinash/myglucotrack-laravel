<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('blood_sugar_levels', function (Blueprint $table) {

            $table->id();

            // Patient Relation
            $table->foreignId('patient_id')
                  ->constrained()
                  ->onDelete('cascade');

            // Blood Sugar Data
            $table->decimal('blood_sugar_level', 8, 2);

            $table->string('before_after');

            $table->time('measurement_time');

            $table->date('measurement_date');

            $table->string('measurement_by');

            $table->text('notes')->nullable();

            // Tenant Relation
            $table->foreignId('tenant_id')
                  ->constrained()
                  ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blood_sugar_levels');
    }
};
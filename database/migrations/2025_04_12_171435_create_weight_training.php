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
        Schema::create('workouts', function (Blueprint $table) {
            $table->id();
            $table->string('exercise_category'); // "Weight Training", "Cardio"
            $table->string('exercise_type');    // "for biceps", "for chest", "for shoulders"
            $table->string('exercise_name');   // For example barble curl
            $table->string('main_muscles');    // biceps
            $table->string('equipment_req');   // ez barbell
            $table->text('execution_guide');   // guide
            $table->string('exercise_photo')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workouts');
    }
};

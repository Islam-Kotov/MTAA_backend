<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('weekly_plan_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('weekly_plan_id')->constrained('weekly_plans')->onDelete('cascade');
            $table->foreignId('workout_id')->constrained('workouts')->onDelete('cascade');
            $table->integer('sets');
            $table->integer('repetitions');
            $table->timestamps();

            $table->unique(['weekly_plan_id', 'workout_id']); 
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('weekly_plan_items');
    }
};

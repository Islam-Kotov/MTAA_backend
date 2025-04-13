<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('predefined_workouts', function (Blueprint $table) {
            $table->id();
            $table->enum('level', ['beginner', 'advanced']); // Beginner or Advanced
            $table->string('title'); //"Upper Body"
            $table->string('image')->nullable(); // "upper_body.png"
            $table->string('duration')->nullable(); // "~40 minutes"
            $table->string('calories')->nullable(); // "450 kcal"
            $table->unsignedTinyInteger('exercise_count')->default(0); // 8
            $table->string('focus')->nullable(); // "Arms, shoulders, chest, and back"
            $table->string('sets_reps')->nullable(); // "3-4 sets of 12-15 reps"
            $table->string('rest')->nullable(); // "30-40 seconds"
            $table->text('benefits')->nullable(); // benefits text.
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('predefined_workouts');
    }
};

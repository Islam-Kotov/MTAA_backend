<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('predefined_workout_exercises', function (Blueprint $table) {
            $table->id();
            $table->foreignId('predefined_workout_id')->constrained()->onDelete('cascade');

            $table->string('title'); // "Wall Push-Ups"
            $table->string('image')->nullable(); // image
            $table->string('reps_sets')->nullable(); // "3x15"
            $table->text('guide')->nullable(); // execution guide

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('predefined_workout_exercises');
    }
};

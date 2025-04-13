<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_workout_plan', function (Blueprint $table) {
            $table->unsignedInteger('repetitions')->after('workout_id');
            $table->unsignedInteger('sets')->after('repetitions');
        });
    }
    
    public function down(): void
    {
        Schema::table('user_workout_plan', function (Blueprint $table) {
            $table->dropColumn(['repetitions', 'sets']);
        });
    }
    
};

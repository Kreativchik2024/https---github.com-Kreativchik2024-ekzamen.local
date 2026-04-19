<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fixtures', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sportmonks_id')->unique();
            $table->foreignId('league_id')->constrained('leagues');
            $table->foreignId('home_team_id')->constrained('teams');
            $table->foreignId('away_team_id')->constrained('teams');
            $table->dateTime('starting_at');
            $table->string('status')->default('scheduled');
            $table->unsignedTinyInteger('home_score')->nullable();
            $table->unsignedTinyInteger('away_score')->nullable();
            $table->json('statistics')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('starting_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fixtures');
    }
};
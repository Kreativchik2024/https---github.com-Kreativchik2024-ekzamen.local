<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('value_bets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fixture_id')->constrained('fixtures')->onDelete('cascade');
            $table->foreignId('prediction_id')->constrained('predictions');
            $table->foreignId('odd_id')->constrained('odds');
            $table->string('bet_type');
            $table->decimal('expected_value', 8, 4);
            $table->decimal('edge_percent', 5, 2);
            $table->string('status')->default('pending');
            $table->timestamps();

            $table->index('status');
            $table->index('expected_value');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('value_bets');
    }
};
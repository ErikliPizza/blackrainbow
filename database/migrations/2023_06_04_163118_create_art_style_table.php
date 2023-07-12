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
        Schema::create('art_style', function (Blueprint $table) {
            $table->foreignId('art_id')->constrained('arts')->onDelete('cascade');
            $table->foreignId('style_id')->constrained('styles')->onDelete('cascade');
            $table->primary(['art_id', 'style_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('art_style');
    }
};

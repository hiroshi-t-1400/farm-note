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
        Schema::create('crop_seasons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('crop_id')->constrained();
            $table->foreignId('field_id')->constrained();
            $table->string('variety')->nullable();
            $table->string('supplier')->nullable();
            $table->double('planted_area')->nullable();
            $table->integer('plant_count')->nullable();
            $table->integer('total_yield')->nullable();
            $table->integer('year');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crop_seasons');
    }
};

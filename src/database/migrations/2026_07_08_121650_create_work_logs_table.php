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
        Schema::create('work_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('crop_season_id')->constrained()->onDelete('cascade');
            $table->foreignId('created_by')->constrained(
                table: 'users', column: 'id'
            )->onDelete('cascade');
            $table->foreignId('performed_by')->constrained(
                table: 'users', column: 'id'
            )->onDelete('cascade');
            // $table->integer('crop_season_id');
            // $table->foreign('crop_season_id')->references('id')->on('crop_seasons');
            // $table->integer('created_by');
            // $table->foreign('created_by')->references('id')->on('users');
            // $table->integer('performed_by');
            // $table->foreign('performed_by')->references('id')->on('users');
            $table->timestamp('work_date');
            $table->string('status');
            $table->string('title');
            $table->string('content')->nullable();
            $table->foreignId('updated_by')->constrained(
                table: 'users', column: 'id'
            )->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_logs');
    }
};

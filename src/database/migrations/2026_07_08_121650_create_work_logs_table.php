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
            $table->foreignId('crop_season_id')->constrained();
            $table->foreignId('created_by')
                // ->constrained(table: 'users', column: 'id');
                ->constrained('users');
            // $table->foreignId('performed_by')
            //     ->constrained(table: 'users', column: 'id');
            $table->timestamp('work_date');
            $table->string('status');
            $table->string('title');
            $table->string('content')->nullable();
            $table->foreignId('updated_by')->nullable()
                // ->constrained(table: 'users', column: 'id');
                ->constrained('users');
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

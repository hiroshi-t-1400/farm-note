<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\Console\Helper\TableCell;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('material_work_log', function (Blueprint $table) {
            $table->id();
            // $table->foreignId('work_log_id')->constrained();
            $table->foreignId('work_log_id')
                    ->constrained(table: 'work_logs', column: 'id');

            // $table->foreignId('material_id')->constrained();
            $table->foreignId('material_id')
                    ->constrained(table: 'materials', column: 'id');
            $table->string('quantity');
            $table->string('dilution_rate')->nullable();
            $table->string('material_amount')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('material_work_log');
    }
};

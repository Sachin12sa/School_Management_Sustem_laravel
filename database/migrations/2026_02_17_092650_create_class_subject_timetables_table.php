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
        Schema::create('class_subject_timetables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_id')
                  ->constrained('classes')
                  ->nullable()
                  ->onDelete('cascade');
             $table->foreignId('subject_id')
                ->constrained('subjects')
                ->nullable()
                ->onDelete('cascade');
             $table->foreignId('week_id')
                ->constrained('weeks')
                ->onDelete('cascade')->nullable(); 
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->integer('room_number')->nullable();
           
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_subject_timetables');
    }
};

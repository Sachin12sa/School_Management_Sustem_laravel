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
        Schema::create('marks_registers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users') ->nullabel();
            $table->foreignId('exam_id')->constrained('exam_schedules')->nullabel();
            $table->foreignId('class_id')->constrained('classes.id')->nullabel();
            $table->foreignId('subject_id')->constrained('exam_schedules')->nullabel();
            $table->string('class_work')->default(0);
            $table->string('home_work')->default(0);
            $table->string('test_work')->default(0);
            $table->string('exam')->default(0);
            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */ 
    public function down(): void
    {
        Schema::dropIfExists('marks_registers');
    }
};

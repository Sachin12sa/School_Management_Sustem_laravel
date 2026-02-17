<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assign_class_teachers', function (Blueprint $table) {

            $table->id();

            $table->foreignId('class_id')
                  ->constrained('classes')
                  ->onDelete('cascade');

            // If teacher is stored in users table with user_type = 2
            $table->foreignId('teacher_id')
                  ->constrained('users')
                  ->onDelete('cascade');

            $table->tinyInteger('status')
                  ->default(0)
                ->comment('0:Active, 1:Inactive');

            $table->foreignId('created_by')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();

            $table->tinyInteger('is_delete')
                  ->default(0)
                  ->comment('0:No, 1:Yes');

            $table->timestamps();

            // Prevent duplicate class teacher
            $table->unique(['class_id', 'teacher_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assign_class_teachers');
    }
};

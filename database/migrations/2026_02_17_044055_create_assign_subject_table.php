<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assign_subjects', function (Blueprint $table) {
            $table->id();

            $table->foreignId('class_id')
                ->constrained('classes')
                ->onDelete('cascade');

            $table->foreignId('subject_id')
                ->constrained('subjects')
                ->onDelete('cascade');

            $table->tinyInteger('subject_type')
                ->default(0)
                ->comment('0:Theory, 1:Practical');

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
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assign_subjects');
    }
};

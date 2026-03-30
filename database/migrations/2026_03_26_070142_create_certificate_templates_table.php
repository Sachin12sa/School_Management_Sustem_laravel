<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('certificate_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200);
            $table->enum('applicable_user', ['student', 'employee']);
            $table->string('page_layout', 50)->default('A4 Landscape');
            $table->enum('photo_style', ['square', 'circle', 'none'])->default('square');
            $table->unsignedInteger('photo_size')->default(100);      // px
            $table->unsignedInteger('top_space')->default(0);         // px
            $table->unsignedInteger('bottom_space')->default(0);
            $table->unsignedInteger('right_space')->default(0);
            $table->unsignedInteger('left_space')->default(0);
            $table->string('signature_image')->nullable();
            $table->string('logo_image')->nullable();
            $table->string('background_image')->nullable();
            $table->longText('content');                              // HTML from rich editor
            $table->tinyInteger('status')->default(1);
            $table->tinyInteger('is_delete')->default(0);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certificate_templates');
    }
};
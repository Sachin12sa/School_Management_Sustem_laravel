<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('id_card_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('applicable_user', ['student', 'teacher', 'admin', 'accountant', 'librarian']);
            $table->decimal('layout_width',  6, 2)->default(85.60);  // mm
            $table->decimal('layout_height', 6, 2)->default(54.00);  // mm
            $table->enum('photo_style', ['circle', 'square', 'rounded'])->default('circle');
            $table->integer('photo_size')->default(80);              // px
            $table->integer('top_space')->default(10);
            $table->integer('bottom_space')->default(10);
            $table->integer('left_space')->default(10);
            $table->integer('right_space')->default(10);
            $table->string('signature_image')->nullable();
            $table->string('logo_image')->nullable();
            $table->string('background_image')->nullable();
            $table->string('accent_color')->default('#1a56a0');      // primary colour
            $table->string('text_color')->default('#ffffff');        // header text
            $table->text('extra_content')->nullable();               // HTML editor content
            $table->integer('is_delete')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('id_card_templates');
    }
};
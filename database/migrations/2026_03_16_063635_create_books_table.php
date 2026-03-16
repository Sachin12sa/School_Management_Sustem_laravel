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
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('author');
            $table->string('isbn')->nullable()->unique();
            $table->string('publisher')->nullable();
            $table->string('edition')->nullable();
            $table->year('publish_year')->nullable();
            $table->string('category')->nullable();       // e.g. Science, Math, Fiction
            $table->string('rack_number')->nullable();    // physical location
            $table->unsignedInteger('quantity')->default(1);     // total copies
            $table->unsignedInteger('available')->default(1);    // copies not issued
            $table->text('description')->nullable();
            $table->string('cover_image')->nullable();
            $table->tinyInteger('status')->default(1);   // 1=Active, 0=Inactive
            $table->tinyInteger('is_delete')->default(0);
            $table->unsignedBigInteger('created_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};

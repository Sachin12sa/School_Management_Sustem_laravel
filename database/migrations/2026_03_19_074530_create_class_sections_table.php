<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('class_sections', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('class_id');
            $table->string('name', 50);          // e.g. "A", "B", "Science", "Morning"
            $table->tinyInteger('status')->default(0)->comment('0=Active, 1=Inactive');
            $table->tinyInteger('is_delete')->default(0);
            $table->unsignedBigInteger('created_by');
            $table->timestamps();

            $table->foreign('class_id')->references('id')->on('classes')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');

            // A class cannot have two sections with the same name
            $table->unique(['class_id', 'name']);
        });

        // Add section_id to users table so students can be assigned to a section
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('section_id')->nullable()->after('class_id');
            $table->foreign('section_id')->references('id')->on('class_sections')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['section_id']);
            $table->dropColumn('section_id');
        });

        Schema::dropIfExists('class_sections');
    }
};

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
        Schema::create('homework', function (Blueprint $table) {
            $table->id();
            $table->integer('class_id')->nullable();
            $table->integer('subject_id')->nullable();
            
            // Dates
            $table->date('homework_date')->nullable();
            $table->date('submission_date')->nullable();
            
            // Content
            $table->string('document_file', 255)->nullable();
            $table->longText('description')->nullable();
            
            // Tracking & Status
            $table->integer('created_by')->nullable();
            $table->tinyInteger('is_delete')->default(0)->comment('0: not deleted, 1: deleted');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('homework');
    }
};
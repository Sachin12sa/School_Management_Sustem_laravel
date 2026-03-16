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
        Schema::create('book_issues', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('book_id');
            $table->unsignedBigInteger('member_id');       // student or teacher user id
            $table->date('issue_date');
            $table->date('due_date');
            $table->date('return_date')->nullable();
            $table->unsignedInteger('fine_per_day')->default(0);  // Rs per day overdue
            $table->decimal('fine_amount', 8, 2)->default(0);     // calculated fine
            $table->enum('status', ['issued', 'returned', 'overdue'])->default('issued');
            $table->text('note')->nullable();
            $table->tinyInteger('is_delete')->default(0);
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('returned_by')->nullable();
            $table->timestamps();

            $table->foreign('book_id')->references('id')->on('books')->onDelete('cascade');
            $table->foreign('member_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('book_issues');
    }
};

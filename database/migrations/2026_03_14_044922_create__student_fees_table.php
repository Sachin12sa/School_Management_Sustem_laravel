<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_fees', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');       // users.id (user_type=3)
            $table->unsignedBigInteger('fee_type_id');      // fee_types.id
            $table->decimal('amount', 10, 2);               // actual charged amount (may differ from fee_type default)
            $table->string('due_date');                     // e.g. "2025-07-31"
            $table->decimal('paid_amount', 10, 2)->default(0);
            $table->enum('status', ['pending', 'partial', 'paid'])->default('pending');
            $table->string('payment_date')->nullable();
            $table->string('payment_method')->nullable();   // cash, bank, online
            $table->string('transaction_id')->nullable();
            $table->text('remarks')->nullable();
            $table->tinyInteger('is_delete')->default(0);
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('collected_by')->nullable(); // accountant id
            $table->timestamps();

            $table->foreign('student_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('fee_type_id')->references('id')->on('fee_types')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_fees');
    }
};
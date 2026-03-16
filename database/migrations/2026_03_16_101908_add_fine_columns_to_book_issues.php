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
        Schema::table('book_issues', function (Blueprint $table) {
             $table->enum('fine_status', ['none', 'unpaid', 'paid', 'waived'])
                  ->default('none')
                  ->after('fine_amount');
            $table->string('fine_payment_method')->nullable()->after('fine_status'); // cash, bank, online
            $table->date('fine_paid_at')->nullable()->after('fine_payment_method');
            $table->unsignedBigInteger('fine_collected_by')->nullable()->after('fine_paid_at');
            $table->text('fine_note')->nullable()->after('fine_collected_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('book_issues', function (Blueprint $table) {
            $table->dropColumn([
                'fine_status', 'fine_payment_method',
                'fine_paid_at', 'fine_collected_by', 'fine_note',
            ]);
        });
    }
};

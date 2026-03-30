<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('book_issues', function (Blueprint $table) {
            if (!Schema::hasColumn('book_issues', 'book_condition')) {
                $table->string('book_condition', 20)->default('good')->after('fine_note');
            }
            if (!Schema::hasColumn('book_issues', 'damage_charge')) {
                $table->decimal('damage_charge', 10, 2)->default(0)->after('book_condition');
            }
            if (!Schema::hasColumn('book_issues', 'damage_note')) {
                $table->text('damage_note')->nullable()->after('damage_charge');
            }
        });

        // Change fine_status from ENUM to VARCHAR so DEFAULT 'none' works reliably
        DB::statement("
            ALTER TABLE book_issues
            MODIFY COLUMN fine_status VARCHAR(10) NOT NULL DEFAULT 'none'
        ");

        // Fix any NULL fine_status on existing rows
        DB::table('book_issues')
            ->where('status', 'returned')->where('fine_amount', '>', 0)
            ->where(function ($q) { $q->whereNull('fine_status')->orWhere('fine_status', ''); })
            ->update(['fine_status' => 'unpaid']);

        DB::table('book_issues')
            ->where(function ($q) { $q->whereNull('fine_status')->orWhere('fine_status', ''); })
            ->update(['fine_status' => 'none']);

        // Backfill condition for existing rows
        DB::statement("UPDATE book_issues SET book_condition = 'good', damage_charge = 0 WHERE book_condition IS NULL OR book_condition = ''");
    }

    public function down(): void
    {
        Schema::table('book_issues', function (Blueprint $table) {
            foreach (['book_condition', 'damage_charge', 'damage_note'] as $col) {
                if (Schema::hasColumn('book_issues', $col)) $table->dropColumn($col);
            }
        });
    }
};
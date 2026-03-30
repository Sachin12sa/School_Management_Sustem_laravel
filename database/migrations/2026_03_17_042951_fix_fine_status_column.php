<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // ── Step 1: Change fine_status from ENUM to VARCHAR ───────────
        // ENUM cannot have a default changed via Doctrine's ->change()
        // We alter the column directly with raw SQL to avoid that issue
        DB::statement("
            ALTER TABLE book_issues
            MODIFY COLUMN fine_status VARCHAR(10) NOT NULL DEFAULT 'none'
        ");

        // ── Step 2: Fix all existing NULL or empty fine_status rows ───
        // Returned with a fine → unpaid (needs collection)
        DB::table('book_issues')
            ->where('status', 'returned')
            ->where('fine_amount', '>', 0)
            ->where(function ($q) {
                $q->whereNull('fine_status')->orWhere('fine_status', '');
            })
            ->update(['fine_status' => 'unpaid']);

        // Returned with zero fine → none
        DB::table('book_issues')
            ->where('status', 'returned')
            ->where(function ($q) {
                $q->where('fine_amount', 0)->orWhereNull('fine_amount');
            })
            ->where(function ($q) {
                $q->whereNull('fine_status')->orWhere('fine_status', '');
            })
            ->update(['fine_status' => 'none']);

        // Overdue / issued → none (fine not yet locked)
        DB::table('book_issues')
            ->whereIn('status', ['issued', 'overdue'])
            ->where(function ($q) {
                $q->whereNull('fine_status')->orWhere('fine_status', '');
            })
            ->update(['fine_status' => 'none']);
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE book_issues
            MODIFY COLUMN fine_status ENUM('none','unpaid','paid','waived') NOT NULL DEFAULT 'none'
        ");
    }
};
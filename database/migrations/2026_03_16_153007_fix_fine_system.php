<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

// ─────────────────────────────────────────────────────────────────
//  This migration does TWO things:
//  1. Adds fine columns if they don't exist yet
//     (safe to run even if you already ran the previous migration)
//  2. Fixes all NULL fine_status values in existing rows
// ─────────────────────────────────────────────────────────────────
return new class extends Migration
{
    public function up(): void
    {
        // ── Step 1: Add columns only if they don't exist ──────────
        Schema::table('book_issues', function (Blueprint $table) {
            if (!Schema::hasColumn('book_issues', 'fine_status')) {
                $table->string('fine_status', 10)->default('none')->after('fine_amount');
            }
            if (!Schema::hasColumn('book_issues', 'fine_payment_method')) {
                $table->string('fine_payment_method')->nullable()->after('fine_status');
            }
            if (!Schema::hasColumn('book_issues', 'fine_paid_at')) {
                $table->date('fine_paid_at')->nullable()->after('fine_payment_method');
            }
            if (!Schema::hasColumn('book_issues', 'fine_collected_by')) {
                $table->unsignedBigInteger('fine_collected_by')->nullable()->after('fine_paid_at');
            }
            if (!Schema::hasColumn('book_issues', 'fine_note')) {
                $table->text('fine_note')->nullable()->after('fine_collected_by');
            }
        });

        // ── Step 2: Fix ALL existing NULL fine_status rows ────────
        // MySQL ENUM/VARCHAR defaults only apply to new inserts,
        // not existing rows — so we fix them with explicit UPDATEs.

        // Returned with a fine → unpaid
        DB::table('book_issues')
            ->where('status', 'returned')
            ->where('fine_amount', '>', 0)
            ->whereNull('fine_status')
            ->update(['fine_status' => 'unpaid']);

        // Returned with no fine → none
        DB::table('book_issues')
            ->where('status', 'returned')
            ->where(function ($q) {
                $q->where('fine_amount', 0)->orWhereNull('fine_amount');
            })
            ->whereNull('fine_status')
            ->update(['fine_status' => 'none']);

        // Overdue → none (accruing, not yet settled)
        DB::table('book_issues')
            ->where('status', 'overdue')
            ->whereNull('fine_status')
            ->update(['fine_status' => 'none']);

        // Issued → none
        DB::table('book_issues')
            ->where('status', 'issued')
            ->whereNull('fine_status')
            ->update(['fine_status' => 'none']);

        // Catch-all: any remaining NULLs
        DB::table('book_issues')
            ->whereNull('fine_status')
            ->update(['fine_status' => 'none']);
    }

    public function down(): void
    {
        // Only drop if we added them — safe rollback
        Schema::table('book_issues', function (Blueprint $table) {
            $cols = ['fine_status', 'fine_payment_method', 'fine_paid_at',
                     'fine_collected_by', 'fine_note'];
            $existing = array_filter($cols, fn($c) => Schema::hasColumn('book_issues', $c));
            if ($existing) {
                $table->dropColumn(array_values($existing));
            }
        });
    }
};
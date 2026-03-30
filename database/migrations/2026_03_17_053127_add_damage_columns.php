<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // ── Add damage/condition columns (safe if already exist) ───
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

        // ── Fix fine_status: VARCHAR DEFAULT 'none' is more reliable than ENUM ──
        DB::statement("ALTER TABLE book_issues MODIFY COLUMN fine_status VARCHAR(10) NOT NULL DEFAULT 'none'");

        // ── Backfill: set good condition on existing rows ──────────
        DB::statement("UPDATE book_issues SET book_condition = 'good'  WHERE book_condition IS NULL OR book_condition = ''");
        DB::statement("UPDATE book_issues SET damage_charge  = 0       WHERE damage_charge  IS NULL");

        // ── Backfill: fix NULL fine_status on existing rows ────────
        DB::statement("UPDATE book_issues SET fine_status = 'unpaid' WHERE status = 'returned' AND fine_amount > 0 AND (fine_status IS NULL OR fine_status = '')");
        DB::statement("UPDATE book_issues SET fine_status = 'none'   WHERE fine_status IS NULL OR fine_status = ''");
    }

    public function down(): void
    {
        Schema::table('book_issues', function (Blueprint $table) {
            foreach (['damage_note', 'damage_charge', 'book_condition'] as $col) {
                if (Schema::hasColumn('book_issues', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
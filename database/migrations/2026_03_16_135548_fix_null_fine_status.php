<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
       
        DB::table('book_issues')
            ->where('status', 'returned')
            ->where('fine_amount', '<', 0)
            ->whereNull('fine_status')
            ->update(['fine_status' => 'unpaid']);

        // Returned books with no fine → none
        DB::table('book_issues')
            ->where('status', 'returned')
            ->where(function ($q) {
                $q->where('fine_amount', 0)->orWhereNull('fine_amount');
            })
            ->whereNull('fine_status')
            ->update(['fine_status' => 'none']);

        // Overdue/issued books → none (fine not calculated yet)
        DB::table('book_issues')
            ->whereIn('status', ['issued', 'overdue'])
            ->whereNull('fine_status')
            ->update(['fine_status' => 'none']);

        // Now set a proper default so this never happens again
        Schema::table('book_issues', function (Blueprint $table) {
            $table->string('fine_status')->default('none')->change();
        });
    }

    public function down(): void {}
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // ── STEP 1: Clean up any encoded emails from the old approach ────────
        // Fixes emails like kalyan+2083@gmail.com → kalyan@gmail.com
        $encoded = DB::table('users')
            ->where('email', 'like', '%+20%')   // matches +2081, +2082, +2083 etc.
            ->where('user_type', 3)
            ->get();

        foreach ($encoded as $user) {
            // Extract original email: kalyan+2083@gmail.com → kalyan@gmail.com
            $email  = $user->email;
            $domain = substr($email, strpos($email, '@'));           // @gmail.com
            $local  = substr($email, 0, strpos($email, '@'));        // kalyan+2083
            $clean  = substr($local, 0, strpos($local, '+')) . $domain; // kalyan@gmail.com

            DB::table('users')
                ->where('id', $user->id)
                ->update(['email' => $clean]);
        }

        // ── STEP 2: Drop the old single-column unique index on email ─────────
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique('users_email_unique');
        });

        // ── STEP 3: Add composite unique on (email + session_id) ─────────────
        // This allows:
        //   kalyan@gmail.com | session_id=1 (2081) ✅
        //   kalyan@gmail.com | session_id=2 (2082) ✅  ← same email, different year
        //   kalyan@gmail.com | session_id=1 (2081) ❌  ← duplicate in same year
        //
        // NULL session_id (admins, teachers, parents) are always allowed through
        // because MySQL treats NULL != NULL in unique indexes.
        Schema::table('users', function (Blueprint $table) {
            $table->unique(['email', 'session_id'], 'users_email_session_unique');
        });
    }

    public function down(): void
    {
        // Reverse: drop composite, restore single unique
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique('users_email_session_unique');
            $table->unique('email', 'users_email_unique');
        });
    }
};
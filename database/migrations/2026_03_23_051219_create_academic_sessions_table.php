<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        /*──────────────────────────────────────────────────────────────────────
         | 1. academic_sessions
         |    Stores each academic year e.g. "2081", "2082"
         ──────────────────────────────────────────────────────────────────────*/
        Schema::create('academic_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('name', 20);                          // e.g. "2081", "2081-82"
            $table->string('label', 100)->nullable();            // e.g. "Academic Year 2081 B.S."
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->tinyInteger('is_current')->default(0);       // 1 = active working session
            $table->tinyInteger('status')->default(0);           // 0=active, 1=archived
            $table->tinyInteger('is_delete')->default(0);
            $table->unsignedBigInteger('created_by');
            $table->timestamps();

            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->unique('name');
        });

        /*──────────────────────────────────────────────────────────────────────
         | 2. Add session_id to users table
         |    Every student row belongs to a session.
         |    Existing rows get NULL (will be assigned to a seeded session).
         ──────────────────────────────────────────────────────────────────────*/
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('session_id')->nullable()->after('section_id');
            $table->enum('promotion_status', ['pending', 'promoted', 'failed', 'graduated'])
                  ->default('pending')->after('session_id');
            $table->foreign('session_id')->references('id')->on('academic_sessions')->onDelete('set null');
        });

        /*──────────────────────────────────────────────────────────────────────
         | 3. promotion_rules
         |    Admin defines: class_id → next_class_id for a given promotion batch.
         |    "final_class" flag means students graduate instead of promoting.
         ──────────────────────────────────────────────────────────────────────*/
        Schema::create('promotion_rules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('from_session_id');
            $table->unsignedBigInteger('to_session_id');
            $table->unsignedBigInteger('from_class_id');
            $table->unsignedBigInteger('to_class_id')->nullable();   // NULL = graduate
            $table->tinyInteger('is_final_class')->default(0);       // 1 = graduating class
            $table->unsignedBigInteger('created_by');
            $table->timestamps();

            $table->foreign('from_session_id')->references('id')->on('academic_sessions')->onDelete('cascade');
            $table->foreign('to_session_id')->references('id')->on('academic_sessions')->onDelete('cascade');
            $table->foreign('from_class_id')->references('id')->on('classes')->onDelete('cascade');
            $table->foreign('to_class_id')->references('id')->on('classes')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');

            // One rule per class per promotion batch
            $table->unique(['from_session_id', 'to_session_id', 'from_class_id'], 'unique_promotion_rule');
        });

        /*──────────────────────────────────────────────────────────────────────
         | 4. student_promotions
         |    Audit trail — one row per student per promotion run.
         |    The new user row id is stored so history is fully traceable.
         ──────────────────────────────────────────────────────────────────────*/
        Schema::create('student_promotions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('from_session_id');
            $table->unsignedBigInteger('to_session_id');
            $table->unsignedBigInteger('student_id');              // original student user id
            $table->unsignedBigInteger('new_student_id')->nullable(); // new user row for new session
            $table->unsignedBigInteger('from_class_id');
            $table->unsignedBigInteger('to_class_id')->nullable();
            $table->unsignedBigInteger('from_section_id')->nullable();
            $table->unsignedBigInteger('to_section_id')->nullable();
            $table->enum('result', ['promoted', 'failed', 'graduated']);
            $table->text('remarks')->nullable();
            $table->tinyInteger('is_confirmed')->default(0);       // admin confirmed
            $table->unsignedBigInteger('promoted_by');
            $table->timestamps();

            $table->foreign('from_session_id')->references('id')->on('academic_sessions')->onDelete('cascade');
            $table->foreign('to_session_id')->references('id')->on('academic_sessions')->onDelete('cascade');
            $table->foreign('student_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('promoted_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_promotions');
        Schema::dropIfExists('promotion_rules');

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['session_id']);
            $table->dropColumn(['session_id', 'promotion_status']);
        });

        Schema::dropIfExists('academic_sessions');
    }
};
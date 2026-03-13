<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Table: teacher_attendances
     *
     * attendance_type:
     *   1 = Present
     *   2 = Absent
     *   3 = Late
     *   4 = Half Day
     */
    public function up(): void
    {
        Schema::create('teacher_attendances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('teacher_id');
            $table->date('attendance_date');
            $table->tinyInteger('attendance_type');   // 1=Present 2=Absent 3=Late 4=Half Day
            $table->unsignedBigInteger('created_by'); // admin user id
            $table->timestamps();

            $table->foreign('teacher_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');

            // prevent duplicate record per teacher per day
            $table->unique(['teacher_id', 'attendance_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teacher_attendances');
    }
};
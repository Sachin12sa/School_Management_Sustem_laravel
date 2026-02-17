<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::rename('assign_subjects', 'class_subjects');
    }

    public function down(): void
    {
        Schema::rename('class_subjects', 'assign_subjects');
    }
};

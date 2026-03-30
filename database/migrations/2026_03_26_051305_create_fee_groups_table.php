<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── fee_groups ─────────────────────────────────────────────────────
        Schema::create('fee_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->text('description')->nullable();
            $table->tinyInteger('status')->default(1);       // 1=active, 0=inactive
            $table->tinyInteger('is_delete')->default(0);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
        });

        // ── fee_group_items ────────────────────────────────────────────────
        // Each row = one fee_type inside a group, with its own due_date & amount
        Schema::create('fee_group_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('fee_group_id');
            $table->unsignedBigInteger('fee_type_id');
            $table->date('due_date');
            $table->decimal('amount', 10, 2)->default(0);
            $table->timestamps();

            $table->foreign('fee_group_id')->references('id')->on('fee_groups')->onDelete('cascade');
            $table->foreign('fee_type_id')->references('id')->on('fee_types')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fee_group_items');
        Schema::dropIfExists('fee_groups');
    }
};
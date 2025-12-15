<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // For SQLite, we need to recreate the table to modify foreign key constraints
        Schema::table('schedules', function (Blueprint $table) {
            $table->unsignedBigInteger('subject_id')->nullable()->after('class_model_id')->change(); // Make it nullable
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->unsignedBigInteger('subject_id')->nullable(false)->change(); // Make it non-nullable
        });
    }
};

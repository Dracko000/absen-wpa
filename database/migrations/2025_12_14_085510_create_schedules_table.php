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
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('class_model_id');
            $table->unsignedBigInteger('subject_id');
            $table->date('date');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('status')->default('active'); // active, cancelled, completed
            $table->timestamps();

            // Add foreign key constraint for class_model_id (class_models table should exist)
            $table->foreign('class_model_id')->references('id')->on('class_models')->onDelete('cascade');
            // Foreign key for subject_id will be added later after subjects table is created
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};

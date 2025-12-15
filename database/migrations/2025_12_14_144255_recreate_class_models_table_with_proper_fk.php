<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Get all data from the current class_models table
        $oldData = DB::select('SELECT * FROM class_models');

        // Drop the current table
        Schema::dropIfExists('class_models');

        // Create the table again with the correct foreign key relationship
        Schema::create('class_models', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('teacher_id');
            $table->timestamps();

            // Add the proper foreign key constraint
            $table->foreign('teacher_id')->references('id')->on('users')->onDelete('cascade');
        });

        // Restore the data
        foreach ($oldData as $row) {
            DB::table('class_models')->insert([
                'id' => $row->id,
                'name' => $row->name,
                'description' => $row->description,
                'teacher_id' => $row->teacher_id,
                'created_at' => $row->created_at,
                'updated_at' => $row->updated_at,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Get all data from the current class_models table
        $oldData = DB::select('SELECT * FROM class_models');

        // Drop the current table
        Schema::dropIfExists('class_models');

        // Recreate with the original structure (this will use foreignId with constrained again)
        Schema::create('class_models', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('teacher_id');
            $table->timestamps();

            // Add the foreign key constraint correctly this time
            $table->foreign('teacher_id')->references('id')->on('users')->onDelete('cascade');
        });

        // Restore the data
        foreach ($oldData as $row) {
            DB::table('class_models')->insert([
                'id' => $row->id,
                'name' => $row->name,
                'description' => $row->description,
                'teacher_id' => $row->teacher_id,
                'created_at' => $row->created_at,
                'updated_at' => $row->updated_at,
            ]);
        }
    }
};
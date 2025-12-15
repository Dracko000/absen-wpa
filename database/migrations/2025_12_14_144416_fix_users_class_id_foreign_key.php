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
        // This migration has been superseded by updated approach for PostgreSQL compatibility.
        // Any necessary foreign key relationships are set in the original migrations.
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Nothing to rollback.
    }
};
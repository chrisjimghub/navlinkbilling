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
        Schema::table('planned_applications', function (Blueprint $table) {
            // Add the foreign key column
            $table->unsignedBigInteger('location_id')->after('planned_application_type_id');

            // Define the foreign key constraint
            $table->foreign('location_id')->references('id')->on('locations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('planned_applications', function (Blueprint $table) {
            // Drop the foreign key constraint
            $table->dropForeign(['location_id']);

            // Drop the column
            $table->dropColumn('location_id');
        });
    }
};

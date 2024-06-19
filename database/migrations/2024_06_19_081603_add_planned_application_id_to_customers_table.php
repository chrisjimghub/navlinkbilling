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
        Schema::table('customers', function (Blueprint $table) {
            $table->unsignedBigInteger('planned_application_id')->after('planned_application_type_id');

            // Add the foreign key constraint to the planned_application_id column
            $table->foreign('planned_application_id')->references('id')->on('planned_applications')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropForeign(['planned_application_id']);
            $table->dropColumn('planned_application_id');
        });
    }
};

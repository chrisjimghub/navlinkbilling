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
        Schema::table('billings', function (Blueprint $table) {
            //
            $table->unsignedBigInteger('billing_status_id')->nullable()->after('approved_by_signature'); // Adjust 'after' to position the column as needed

            // Assuming the billing_statuses table exists and has an id column
            $table->foreign('billing_status_id')->references('id')->on('billing_statuses')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('billings', function (Blueprint $table) {
            //
            $table->dropForeign(['billing_status_id']);
            $table->dropColumn('billing_status_id');
        });
    }
};

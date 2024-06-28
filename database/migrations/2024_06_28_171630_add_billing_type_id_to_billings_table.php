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
            // Add the billing_type_id column after account_id
            $table->unsignedBigInteger('billing_type_id')->nullable()->after('account_id');

            // Set the foreign key constraint
            $table->foreign('billing_type_id')->references('id')->on('billing_types')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('billings', function (Blueprint $table) {
            //
        });
    }
};

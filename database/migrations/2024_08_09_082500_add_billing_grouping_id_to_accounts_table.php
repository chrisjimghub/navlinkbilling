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
        Schema::table('accounts', function (Blueprint $table) {
            //
            $table->unsignedBigInteger('billing_grouping_id')->nullable();

            $table->foreign('billing_grouping_id')
                  ->references('id')
                  ->on('billing_groupings')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('accounts', function (Blueprint $table) {
            //
            $table->dropForeign(['billing_grouping_id']);
            $table->dropColumn('billing_grouping_id');
        });
    }
};

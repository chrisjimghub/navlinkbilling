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
        Schema::table('account_statuses', function (Blueprint $table) {
            //
            $table->string('badge_css')->default('badge badge-default')->after('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('account_statuses', function (Blueprint $table) {
            //
            $table->dropColumn('badge_css');
        });
    }
};

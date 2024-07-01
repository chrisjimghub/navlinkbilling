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
        Schema::create('account_service_interruptions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('account_id')->constrained('accounts')->onDelete('cascade');

            $table->timestamp('date_start');
            $table->timestamp('date_end');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('account_service_interruptions');
    }
};

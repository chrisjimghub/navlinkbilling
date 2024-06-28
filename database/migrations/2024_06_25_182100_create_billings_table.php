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
        Schema::create('billings', function (Blueprint $table) {
            $table->id();

            // Account name / customer name
            // Customer address
            $table->foreignId('account_id')->constrained('accounts')->onDelete('cascade');

            // Billing date
            $table->date('date_start')->nullable();
            $table->date('date_end')->nullable();
            $table->date('date_cut_off')->nullable();

            $table->string('prepared_by')->nullable();
            $table->string('prepared_by_signature')->nullable();

            $table->string('approved_by')->nullable();
            $table->string('approved_by_signature')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('billings');
    }
};

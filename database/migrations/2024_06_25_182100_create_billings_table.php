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
            $table->date('date_start');
            $table->date('date_end');
            $table->date('date_cut_off');

            $table->string('prepared_by');
            $table->string('prepared_by_signature');

            $table->string('approved_by');
            $table->string('approved_by_signature');

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

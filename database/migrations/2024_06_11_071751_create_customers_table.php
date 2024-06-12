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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('date_of_birth');
            $table->string('contact_number');
            $table->string('email');
            $table->string('bill_recipients');
            $table->string('address');
            $table->string('block_street');
            $table->foreignId('barangay_id')->nullable()->index();
            $table->string('city_or_municipality');
            $table->foreignId('planned_application_type_id')->nullable()->index();
            $table->foreignId('subscription_id')->nullable()->index();
            $table->double('notes'); // TODO:: double?, notes = kwarta? abi nakug notes = description
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
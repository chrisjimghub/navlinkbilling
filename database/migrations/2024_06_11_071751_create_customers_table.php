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
            $table->string('first_name');
            $table->string('last_name');
            $table->string('date_of_birth');
            $table->string('contact_number');
            $table->string('email');
            $table->string('social_media')->nullable();
            $table->string('bill_recipients');
            $table->string('block_street_purok')->nullable();
            $table->string('barangay')->nullable();
            $table->string('city_municipality')->nullable();

            $table->foreignId('planned_application_type_id')->nullable()->constrained('planned_application_types')->onDelete('cascade');
            $table->foreignId('subscription_id')->constrained('subscriptions')->onDelete('cascade');


            $table->string('notes')->nullable(); 

            $table->string('signature')->nullable();

            $table->foreignId('user_id')->nullable()->index();

            $table->softDeletes(); // soft deletes
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

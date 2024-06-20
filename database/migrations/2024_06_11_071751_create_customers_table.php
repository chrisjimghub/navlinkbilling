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
            $table->date('date_of_birth')->nullable();
            $table->string('contact_number')->nullable();
            $table->string('email')->nullable();
            $table->string('block_street')->nullable();
            $table->string('barangay')->nullable();
            $table->string('city_or_municipality')->nullable();
            $table->string('social_media')->nullable();
            $table->string('notes')->nullable(); 
            $table->string('signature')->nullable();

            // $table->foreignId('user_id')->nullable()->index();
            // $table->foreignId('planned_application_type_id')->nullable()->constrained('planned_application_types')->onDelete('cascade');
            // $table->foreignId('subscription_id')->constrained('subscriptions')->onDelete('cascade');

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

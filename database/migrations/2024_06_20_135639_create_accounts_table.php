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
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();

            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->foreignId('planned_application_id')->constrained('planned_applications')->onDelete('cascade');
            $table->foreignId('subscription_id')->constrained('subscriptions')->onDelete('cascade');
            
            $table->date('installed_date')->nullable();
            $table->string('installed_address')->nullable();
            
            $table->string('notes')->nullable();

            $table->foreignId('account_status_id')->constrained('account_statuses')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};

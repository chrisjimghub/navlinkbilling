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
            $table->foreignId('planned_application_type_id')->constrained('planned_application_types')->onDelete('cascade');
            $table->foreignId('planned_application_id')->constrained('planned_applications')->onDelete('cascade');
            $table->foreignId('subscription_id')->constrained('subscriptions')->onDelete('cascade');
            $table->foreignId('otc_id')->constrained('otcs')->onDelete('cascade');
            $table->foreignId('contract_period_id')->constrained('contract_periods')->onDelete('cascade');
            
            $table->date('installed_date')->nullable();
            $table->string('installed_address')->nullable();
            
            $table->foreignId('account_statuses_id')->constrained('account_statuses')->onDelete('cascade');

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

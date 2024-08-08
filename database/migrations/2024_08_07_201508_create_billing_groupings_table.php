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
        Schema::create('billing_groupings', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->integer('day_start');
            $table->integer('day_end');
            $table->integer('day_cut_off');
            $table->integer('bill_generate_days_before_end_of_billing_period')->nullable();
            $table->integer('bill_notification_days_after_the_bill_created')->nullable();
            $table->integer('bill_cut_off_notification_days_before_cut_off_date')->nullable();
            $table->foreignId('billing_period_id')->constrained('billing_periods');
            $table->boolean('auto_generate_bill')->default(false);
            $table->boolean('auto_send_bill_notification')->default(false);
            $table->boolean('auto_send_cut_off_notification')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('billing_groupings');
    }
};

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

            /* 
                name // string unique
                day_start // integer
                day_end // int
                day_cut_off // int
                billing_period_id FK
                bill_generate_days_before_end_of_billing_period
                bill_notification_days_after_the_bill_created
                bill_cut_off_notification_days_before_cut_off_date

            */

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

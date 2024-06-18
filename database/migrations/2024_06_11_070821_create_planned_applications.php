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
        Schema::create('planned_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('planned_application_type_id')->constrained('planned_application_types')->onDelete('cascade');
            $table->integer('mbps');
            $table->decimal('price');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('planned_applications');
    }
};

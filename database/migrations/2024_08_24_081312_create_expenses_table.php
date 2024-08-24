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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('description')->nullable();
            $table->unsignedBigInteger('expense_category_id')->nullable(); 
            $table->unsignedBigInteger('user_id')->nullable();
            $table->decimal('amount', 8, 2); 
            $table->timestamps();
            $table->softDeletes();

            // Foreign key constraints
            $table->foreign('expense_category_id')
                  ->references('id')
                  ->on('expense_categories')
                  ->onDelete('cascade');

            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('expenses', function (Blueprint $table) {
            // Drop foreign keys first
            $table->dropForeign(['expense_category_id']);
            $table->dropForeign(['user_id']);
        });

        Schema::dropIfExists('expenses');
    }
};

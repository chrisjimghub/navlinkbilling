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
        Schema::create('olts', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('model');
            $table->ipAddress('ip_address');
            $table->foreignId('community_read_id')->constrained('community_strings')->onDelete('cascade');
            $table->foreignId('community_write_id')->constrained('community_strings')->onDelete('cascade');
            $table->string('base_oid');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('olts');
    }
};

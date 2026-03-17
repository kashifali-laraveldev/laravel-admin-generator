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
        Schema::create('demo_testers', function (Blueprint $table) {
            $table->id();
            $table->string('tester_name');
            $table->string('tester_email');
            $table->string('tester_image');
            $table->boolean('tester_is_verified');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('demo_testers');
    }
};

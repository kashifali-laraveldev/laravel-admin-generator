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
        Schema::dropIfExists('employees');
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->string('password');
            $table->string('profile_image');
            $table->string('id_card_image');
            $table->integer('age');
            $table->float('height');
            $table->string('city')->nullable();
            $table->date('joining_date');
            $table->datetime('joining_date_time');
            $table->boolean('is_married')->default(false);
            $table->enum('status', [1,2,3,4]);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};

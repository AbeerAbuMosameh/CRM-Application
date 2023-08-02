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
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->string('image')->nullable();
            $table->string('fname');
            $table->string('lname');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('job')->nullable();
            $table->string('education');
            $table->string('address');
            $table->string('city')->nullable();
            $table->enum('language', ['English', 'Arabic','French'])->nullable()->default('english');
            $table->string('cv')->nullable();
            $table->date('dob')->nullable();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->boolean('is_approved')->default(false);
            $table->softDeletes();
            $table->timestamps();
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};

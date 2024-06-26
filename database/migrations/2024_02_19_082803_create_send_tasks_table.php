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
        Schema::create('send_tasks', function (Blueprint $table) {
            $table->id();
            $table->string('task_name');
            $table->enum('category_name', ['Official', 'Personal']);
            $table->string('description');
            $table->string('title')->nullable();
            $table->string('high');
            $table->dateTime('original_task');
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('partner_id')->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->dateTime('send_time');
            $table->boolean('accept')->default(false);
            $table->boolean('decline')->default(false);
            $table->integer('last_task_id')->nullable();
            $table->dateTime('end_task_time')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('send_tasks');
    }
};

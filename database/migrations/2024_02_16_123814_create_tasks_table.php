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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('task_name');
            $table->string('description');
            $table->foreignId('user_id')->constrained('users');
            $table->enum('category_name', ['Official', 'Personal']);
            $table->dateTime('start_task');
            $table->dateTime('end_task')->nullable();
            $table->dateTime('original_task');
            $table->string('high');
            $table->boolean('active')->default(true);
            $table->enum('status', ['enable', 'disable'])->default('enable');
            $table->integer('real_task')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};

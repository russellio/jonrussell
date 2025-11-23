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
        Schema::create('tech_stack_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('skill_id')->nullable()->constrained()->onDelete('set null');
            $table->string('name');
            $table->integer('percent')->nullable();
            $table->string('icon_type')->default('fa'); // 'fa' or 'si'
            $table->string('icon_name');
            $table->boolean('active')->default(false);
            $table->integer('order')->default(0);
            $table->timestamps();

            $table->index('order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tech_stack_items');
    }
};

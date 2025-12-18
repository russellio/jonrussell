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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title');
            $table->text('byline')->nullable();
            $table->text('description')->nullable();
            $table->string('primary_image_src')->nullable();
            $table->string('primary_image_title')->nullable();
            $table->string('primary_image_alt')->nullable();
            $table->string('bg_image')->nullable();
            $table->string('bg_position_x')->nullable();
            $table->string('bg_position_y')->nullable();
            $table->foreignId('company_id')->nullable()->constrained()->onDelete('set null');
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};

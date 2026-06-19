<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tech_stack_items', function (Blueprint $table) {
            $table->string('icon_type')->nullable()->default(null)->change();
            $table->string('icon_name')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('tech_stack_items', function (Blueprint $table) {
            $table->string('icon_type')->nullable(false)->default('fa')->change();
            $table->string('icon_name')->nullable(false)->change();
        });
    }
};

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
        Schema::table('companies', function (Blueprint $table) {
            $table->string('logo_src')->nullable()->after('name');
            $table->string('logo_alt')->nullable()->after('logo_src');
            $table->boolean('logo_display_name')->default(false)->after('logo_alt');
            $table->string('link')->nullable()->after('logo_display_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn(['logo_src', 'logo_alt', 'logo_display_name', 'link']);
        });
    }
};

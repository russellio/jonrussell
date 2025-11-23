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
        Schema::table('tech_stack_items', function (Blueprint $table) {
            // Remove the old icon_name and icon_type columns
            // These have been migrated to the icons table via icon_id
            if (Schema::hasColumn('tech_stack_items', 'icon_name')) {
                $table->dropColumn('icon_name');
            }
            if (Schema::hasColumn('tech_stack_items', 'icon_type')) {
                $table->dropColumn('icon_type');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tech_stack_items', function (Blueprint $table) {
            // Restore the icon_name and icon_type columns
            $table->string('icon_type')->default('fa')->after('percent');
            $table->string('icon_name')->after('icon_type');
        });
    }
};

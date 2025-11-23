<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Extract unique icon combinations from tech_stack_items
        $uniqueIcons = DB::table('tech_stack_items')
            ->select('icon_type', 'icon_name')
            ->distinct()
            ->whereNotNull('icon_type')
            ->whereNotNull('icon_name')
            ->get();

        // Create Icon records for each unique combination using DB facade
        $iconMap = [];
        $now = now();
        
        foreach ($uniqueIcons as $iconData) {
            // Check if icon already exists
            $iconId = DB::table('icons')
                ->where('icon_type', $iconData->icon_type)
                ->where('icon_name', $iconData->icon_name)
                ->value('id');
            
            // Create if it doesn't exist
            if (!$iconId) {
                $iconId = DB::table('icons')->insertGetId([
                    'icon_type' => $iconData->icon_type,
                    'icon_name' => $iconData->icon_name,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
            
            $iconMap[$iconData->icon_type . '|' . $iconData->icon_name] = $iconId;
        }

        // Update tech_stack_items to reference the new icons
        // Group updates by icon_id for efficiency
        $updatesByIcon = [];
        $techStackItems = DB::table('tech_stack_items')->get();
        
        foreach ($techStackItems as $item) {
            if ($item->icon_type && $item->icon_name) {
                $key = $item->icon_type . '|' . $item->icon_name;
                if (isset($iconMap[$key])) {
                    $iconId = $iconMap[$key];
                    if (!isset($updatesByIcon[$iconId])) {
                        $updatesByIcon[$iconId] = [];
                    }
                    $updatesByIcon[$iconId][] = $item->id;
                }
            }
        }

        // Perform bulk updates
        foreach ($updatesByIcon as $iconId => $itemIds) {
            DB::table('tech_stack_items')
                ->whereIn('id', $itemIds)
                ->update(['icon_id' => $iconId]);
        }

        // Make icon_id NOT NULL after migration (for new records)
        // Note: We keep it nullable for now to allow existing records without icons
        // You can make it required later if needed
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Restore icon_type and icon_name from icons table
        $techStackItems = DB::table('tech_stack_items')
            ->whereNotNull('icon_id')
            ->join('icons', 'tech_stack_items.icon_id', '=', 'icons.id')
            ->select('tech_stack_items.id', 'icons.icon_type', 'icons.icon_name')
            ->get();

        foreach ($techStackItems as $item) {
            DB::table('tech_stack_items')
                ->where('id', $item->id)
                ->update([
                    'icon_type' => $item->icon_type,
                    'icon_name' => $item->icon_name,
                ]);
        }

        // Clear icon_id references
        DB::table('tech_stack_items')->update(['icon_id' => null]);
    }
};

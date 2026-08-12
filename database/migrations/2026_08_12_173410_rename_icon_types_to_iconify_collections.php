<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('icons')->where(['icon_type' => 'fa', 'icon_name' => 'laravel'])->update(['icon_type' => 'simple-icons', 'icon_name' => 'laravel']);
        DB::table('icons')->where(['icon_type' => 'fa', 'icon_name' => 'php'])->update(['icon_type' => 'simple-icons', 'icon_name' => 'php']);
        DB::table('icons')->where(['icon_type' => 'fa', 'icon_name' => 'vuejs'])->update(['icon_type' => 'simple-icons', 'icon_name' => 'vuedotjs']);
        DB::table('icons')->where(['icon_type' => 'fa', 'icon_name' => 'js'])->update(['icon_type' => 'simple-icons', 'icon_name' => 'javascript']);
        DB::table('icons')->where(['icon_type' => 'fa', 'icon_name' => 'html5'])->update(['icon_type' => 'simple-icons', 'icon_name' => 'html5']);
        DB::table('icons')->where(['icon_type' => 'fa', 'icon_name' => 'css3'])->update(['icon_type' => 'simple-icons', 'icon_name' => 'css3']);
        DB::table('icons')->where(['icon_type' => 'fa', 'icon_name' => 'code'])->update(['icon_type' => 'lucide', 'icon_name' => 'code']);
        DB::table('icons')->where(['icon_type' => 'fa', 'icon_name' => 'vial'])->update(['icon_type' => 'lucide', 'icon_name' => 'flask-conical']);
        DB::table('icons')->where(['icon_type' => 'fa', 'icon_name' => 'project-diagram'])->update(['icon_type' => 'lucide', 'icon_name' => 'workflow']);
        DB::table('icons')->where(['icon_type' => 'fa', 'icon_name' => 'sitemap'])->update(['icon_type' => 'lucide', 'icon_name' => 'layers']);
        DB::table('icons')->where(['icon_type' => 'si', 'icon_name' => 'ReactIcon'])->update(['icon_type' => 'simple-icons', 'icon_name' => 'react']);
        DB::table('icons')->where(['icon_type' => 'si', 'icon_name' => 'TypeScriptIcon'])->update(['icon_type' => 'simple-icons', 'icon_name' => 'typescript']);
        DB::table('icons')->where(['icon_type' => 'si', 'icon_name' => 'PythonIcon'])->update(['icon_type' => 'simple-icons', 'icon_name' => 'python']);
        DB::table('icons')->where(['icon_type' => 'si', 'icon_name' => 'MySqlIcon'])->update(['icon_type' => 'simple-icons', 'icon_name' => 'mysql']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('icons')->where(['icon_type' => 'simple-icons', 'icon_name' => 'laravel'])->update(['icon_type' => 'fa', 'icon_name' => 'laravel']);
        DB::table('icons')->where(['icon_type' => 'simple-icons', 'icon_name' => 'php'])->update(['icon_type' => 'fa', 'icon_name' => 'php']);
        DB::table('icons')->where(['icon_type' => 'simple-icons', 'icon_name' => 'vuedotjs'])->update(['icon_type' => 'fa', 'icon_name' => 'vuejs']);
        DB::table('icons')->where(['icon_type' => 'simple-icons', 'icon_name' => 'javascript'])->update(['icon_type' => 'fa', 'icon_name' => 'js']);
        DB::table('icons')->where(['icon_type' => 'simple-icons', 'icon_name' => 'html5'])->update(['icon_type' => 'fa', 'icon_name' => 'html5']);
        DB::table('icons')->where(['icon_type' => 'simple-icons', 'icon_name' => 'css3'])->update(['icon_type' => 'fa', 'icon_name' => 'css3']);
        DB::table('icons')->where(['icon_type' => 'lucide', 'icon_name' => 'code'])->update(['icon_type' => 'fa', 'icon_name' => 'code']);
        DB::table('icons')->where(['icon_type' => 'lucide', 'icon_name' => 'flask-conical'])->update(['icon_type' => 'fa', 'icon_name' => 'vial']);
        DB::table('icons')->where(['icon_type' => 'lucide', 'icon_name' => 'workflow'])->update(['icon_type' => 'fa', 'icon_name' => 'project-diagram']);
        DB::table('icons')->where(['icon_type' => 'lucide', 'icon_name' => 'layers'])->update(['icon_type' => 'fa', 'icon_name' => 'sitemap']);
        DB::table('icons')->where(['icon_type' => 'simple-icons', 'icon_name' => 'react'])->update(['icon_type' => 'si', 'icon_name' => 'ReactIcon']);
        DB::table('icons')->where(['icon_type' => 'simple-icons', 'icon_name' => 'typescript'])->update(['icon_type' => 'si', 'icon_name' => 'TypeScriptIcon']);
        DB::table('icons')->where(['icon_type' => 'simple-icons', 'icon_name' => 'python'])->update(['icon_type' => 'si', 'icon_name' => 'PythonIcon']);
        DB::table('icons')->where(['icon_type' => 'simple-icons', 'icon_name' => 'mysql'])->update(['icon_type' => 'si', 'icon_name' => 'MySqlIcon']);
    }
};

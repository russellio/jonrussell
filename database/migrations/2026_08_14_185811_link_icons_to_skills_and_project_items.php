<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Icons to get-or-create, keyed by a short reference used in the name maps below.
     * All verified present in the bundled @iconify-json/simple-icons and @iconify-json/lucide collections.
     *
     * @var array<string, array{0: string, 1: string}>
     */
    private array $icons = [
        // Reused: already present in the icons table (linked to tech_stack_items / other rows)
        'php' => ['simple-icons', 'php'],
        'javascript' => ['simple-icons', 'javascript'],
        'typescript' => ['simple-icons', 'typescript'],
        'mysql' => ['simple-icons', 'mysql'],
        'laravel' => ['simple-icons', 'laravel'],
        'vuedotjs' => ['simple-icons', 'vuedotjs'],
        'react' => ['simple-icons', 'react'],
        'expo' => ['simple-icons', 'expo'],
        'html5' => ['simple-icons', 'html5'],
        'css3' => ['simple-icons', 'css3'],
        'github' => ['simple-icons', 'github'],
        'composer' => ['simple-icons', 'composer'],
        'sentry' => ['simple-icons', 'sentry'],
        'lucide-code' => ['lucide', 'code'],
        'lucide-flask-conical' => ['lucide', 'flask-conical'],
        'lucide-layers' => ['lucide', 'layers'],
        // New
        'pinia' => ['simple-icons', 'pinia'],
        'jquery' => ['simple-icons', 'jquery'],
        'webpack' => ['simple-icons', 'webpack'],
        'vite' => ['simple-icons', 'vite'],
        'bootstrap' => ['simple-icons', 'bootstrap'],
        'tailwindcss' => ['simple-icons', 'tailwindcss'],
        'json' => ['simple-icons', 'json'],
        'xml' => ['simple-icons', 'xml'],
        'git' => ['simple-icons', 'git'],
        'jira' => ['simple-icons', 'jira'],
        'linux' => ['simple-icons', 'linux'],
        'docker' => ['simple-icons', 'docker'],
        'jenkins' => ['simple-icons', 'jenkins'],
        'azurepipelines' => ['simple-icons', 'azurepipelines'],
        'scrumalliance' => ['simple-icons', 'scrumalliance'],
        'phpstorm' => ['simple-icons', 'phpstorm'],
        'visualstudiocode' => ['simple-icons', 'visualstudiocode'],
        'cursor' => ['simple-icons', 'cursor'],
        'claude' => ['simple-icons', 'claude'],
        'jsonwebtokens' => ['simple-icons', 'jsonwebtokens'],
        'redis' => ['simple-icons', 'redis'],
        'microsoftsqlserver' => ['simple-icons', 'microsoftsqlserver'],
        'npm' => ['simple-icons', 'npm'],
        'yarn' => ['simple-icons', 'yarn'],
        'postman' => ['simple-icons', 'postman'],
        'pivotaltracker' => ['simple-icons', 'pivotaltracker'],
        'bitbucket' => ['simple-icons', 'bitbucket'],
        'lumen' => ['simple-icons', 'lumen'],
    ];

    /** @var array<string, string> */
    private array $skillIcons = [
        'PHP 8' => 'php',
        'JavaScript' => 'javascript',
        'TypeScript' => 'typescript',
        'MySQL 8' => 'mysql',
        'Node.js' => 'javascript',
        'Laravel' => 'laravel',
        'Vue 3' => 'vuedotjs',
        'Vue 2 / Vuex' => 'vuedotjs',
        'Pinia' => 'pinia',
        'React' => 'react',
        'React Native' => 'react',
        'Expo' => 'expo',
        'jQuery' => 'jquery',
        'webpack' => 'webpack',
        'Vite' => 'vite',
        'HTML5' => 'html5',
        'CSS3' => 'css3',
        'SCSS' => 'css3',
        'Bootstrap' => 'bootstrap',
        'Tailwind CSS' => 'tailwindcss',
        'JSON' => 'json',
        'XML' => 'xml',
        'Git' => 'git',
        'Jira' => 'jira',
        'REST APIs' => 'lucide-code',
        'OOP' => 'lucide-layers',
        'MVC' => 'lucide-layers',
        'Linux' => 'linux',
        'Docker' => 'docker',
        'Jenkins' => 'jenkins',
        'Azure Pipelines' => 'azurepipelines',
        'Github' => 'github',
        'Bitbucket' => 'bitbucket',
        'PEST / PHPUnit' => 'lucide-flask-conical',
        'Certified ScrumMaster (2017-2023)' => 'scrumalliance',
        'PhpStorm' => 'phpstorm',
        'VS Code' => 'visualstudiocode',
        'Cursor' => 'cursor',
        'Sentry' => 'sentry',
        'Claude Code' => 'claude',
    ];

    /** @var array<string, string> */
    private array $technologyIcons = [
        'Pinia' => 'pinia',
        'REST APIs' => 'lucide-code',
        'Lumen' => 'lumen',
        'JWT' => 'jsonwebtokens',
        'jQuery' => 'jquery',
        'Bootstrap' => 'bootstrap',
        'Redis' => 'redis',
        'MSSQL' => 'microsoftsqlserver',
    ];

    /** @var array<string, string> */
    private array $toolIcons = [
        'Vite' => 'vite',
        'Bitbucket' => 'bitbucket',
        'Docker' => 'docker',
        'PEST' => 'php',
        'Git' => 'git',
        'NPM' => 'npm',
        'Yarn' => 'yarn',
        'Composer' => 'composer',
        'Azure Pipelines' => 'azurepipelines',
        'Postman' => 'postman',
        'Github' => 'github',
        'webpack' => 'webpack',
        'Jira' => 'jira',
        'Jenkins' => 'jenkins',
        'Pivotal' => 'pivotaltracker',
    ];

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Content seeding, not schema — RefreshDatabase re-runs every migration against an
        // ephemeral test DB, and existing tests create their own Icon rows with arbitrary
        // (icon_type, icon_name) combinations that would collide with this migration's fixed set.
        if (app()->environment('testing')) {
            return;
        }

        $iconIds = $this->resolveIconIds();

        $this->applyLinks('skills', $this->skillIcons, $iconIds);
        $this->applyLinks('project_technologies', $this->technologyIcons, $iconIds);
        $this->applyLinks('project_tools', $this->toolIcons, $iconIds);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Icon links only ever fill previously-null icon_id values; not meaningfully reversible.
    }

    /**
     * @return array<string, int>
     */
    private function resolveIconIds(): array
    {
        $now = now();
        $iconIds = [];

        foreach ($this->icons as $key => [$type, $name]) {
            $id = DB::table('icons')->where('icon_type', $type)->where('icon_name', $name)->value('id');

            if (! $id) {
                $id = DB::table('icons')->insertGetId([
                    'icon_type' => $type,
                    'icon_name' => $name,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }

            $iconIds[$key] = $id;
        }

        return $iconIds;
    }

    /**
     * @param  array<string, string>  $nameToIconKey
     * @param  array<string, int>  $iconIds
     */
    private function applyLinks(string $table, array $nameToIconKey, array $iconIds): void
    {
        foreach ($nameToIconKey as $name => $iconKey) {
            DB::table($table)
                ->where('name', $name)
                ->whereNull('icon_id')
                ->update(['icon_id' => $iconIds[$iconKey]]);
        }
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Canonical skill catalogue: skill_type name => [skill name => order].
     * Local/dev is authoritative; this reconciles other environments (e.g. prod) to match,
     * including the exact `order` values (which have intentional gaps from prior deletes).
     *
     * @var array<string, array<string, int>>
     */
    private array $canonical = [
        'Software Engineering' => [
            'PHP 8' => 0, 'JavaScript' => 1, 'TypeScript' => 2, 'SQL' => 3, 'MySQL 8' => 4,
            'Node.js' => 5, 'Laravel' => 6, 'Vue 3' => 7, 'Vue 2 / Vuex' => 8, 'Pinia' => 9,
            'React' => 10, 'React Native' => 11, 'Expo' => 12, 'jQuery' => 13, 'webpack' => 14,
            'Vite' => 15, 'HTML5' => 17, 'CSS3' => 18, 'SCSS' => 19, 'Bootstrap' => 20,
            'Tailwind CSS' => 21, 'JSON' => 22, 'XML' => 23, 'Git' => 24, 'Jira' => 25,
            'REST APIs' => 26, 'Microservices' => 27, 'OOP' => 28, 'MVC' => 29, 'SDLC' => 30,
            'SaaS' => 31, 'SEO' => 32, 'a11y' => 33,
        ],
        'Architecture & DevOps' => [
            'AWS' => 0, 'Linux' => 1, 'Docker' => 2, 'CI/CD automation' => 3, 'Jenkins' => 4,
            'Azure Pipelines' => 5, 'Github' => 6, 'Bitbucket' => 7,
        ],
        'Quality & Collaboration' => [
            'Unit testing' => 0, 'PEST / PHPUnit' => 2, 'Code reviews' => 3,
            'Pair programming' => 4, 'API optimization' => 5,
        ],
        'Leadership & Team Building' => [
            'Team mentoring' => 0, 'Innovative process improvement' => 1,
            'Experienced Manager, Project Manager' => 2, 'Certified ScrumMaster (2017-2023)' => 3,
        ],
        'Tools & Environment' => [
            'PhpStorm' => 0, 'VS Code' => 1, 'Cursor' => 2, 'Sentry' => 5, 'Claude Code' => 6,
        ],
    ];

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Content reconciliation, not schema — RefreshDatabase re-runs every migration against
        // an ephemeral test DB, and existing tests assume a skill/skill_type-free baseline there.
        if (app()->environment('testing')) {
            return;
        }

        $now = now();

        DB::table('skill_types')->updateOrInsert(
            ['slug' => 'tools'],
            ['name' => 'Tools & Environment', 'order' => 5, 'created_at' => $now, 'updated_at' => $now]
        );

        $typeIds = DB::table('skill_types')->pluck('id', 'name');

        foreach ($this->canonical as $typeName => $skills) {
            $typeId = $typeIds[$typeName] ?? null;

            if (! $typeId) {
                continue;
            }

            $existingNames = DB::table('skills')->where('skill_type_id', $typeId)->pluck('name')->all();

            foreach ($skills as $name => $order) {
                if (! in_array($name, $existingNames, true)) {
                    DB::table('skills')->insert([
                        'skill_type_id' => $typeId,
                        'name' => $name,
                        'order' => $order,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                }
            }
        }

        DB::table('skills')->where('name', 'Vuex')->update(['name' => 'Vue 2 / Vuex']);
        DB::table('skills')->where('name', 'Certified ScrumMaster (CSM)')->update(['name' => 'Certified ScrumMaster (2017-2023)']);

        $this->mergePestAndPhpUnit();
        $this->deleteIfUnreferenced('Plays well with others');
        $this->renumberOrder();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Content reconciliation (creates/renames/merges by name) is not meaningfully
        // reversible; re-running up() forward is always safe.
    }

    private function mergePestAndPhpUnit(): void
    {
        $pest = DB::table('skills')->where('name', 'PEST')->first();
        $phpunit = DB::table('skills')->where('name', 'PHPUnit')->first();

        if ($pest && $phpunit) {
            $survivor = $this->referenceCount($phpunit->id) >= $this->referenceCount($pest->id) ? $phpunit : $pest;
            $loser = $survivor->id === $phpunit->id ? $pest : $phpunit;

            DB::table('tech_stack_items')->where('skill_id', $loser->id)->update(['skill_id' => $survivor->id]);

            foreach (DB::table('position_skill')->where('skill_id', $loser->id)->get() as $pivot) {
                $survivorAlreadyLinked = DB::table('position_skill')
                    ->where('position_id', $pivot->position_id)
                    ->where('skill_id', $survivor->id)
                    ->exists();

                if ($survivorAlreadyLinked) {
                    DB::table('position_skill')->where('id', $pivot->id)->delete();
                } else {
                    DB::table('position_skill')->where('id', $pivot->id)->update(['skill_id' => $survivor->id]);
                }
            }

            DB::table('skills')->where('id', $survivor->id)->update(['name' => 'PEST / PHPUnit']);
            DB::table('skills')->where('id', $loser->id)->delete();

            return;
        }

        $lone = $pest ?? $phpunit;

        if ($lone && ! DB::table('skills')->where('name', 'PEST / PHPUnit')->exists()) {
            DB::table('skills')->where('id', $lone->id)->update(['name' => 'PEST / PHPUnit']);
        }
    }

    private function referenceCount(int $skillId): int
    {
        return DB::table('position_skill')->where('skill_id', $skillId)->count()
            + DB::table('tech_stack_items')->where('skill_id', $skillId)->count();
    }

    private function deleteIfUnreferenced(string $name): void
    {
        $skill = DB::table('skills')->where('name', $name)->first();

        if ($skill && $this->referenceCount($skill->id) === 0) {
            DB::table('skills')->where('id', $skill->id)->delete();
        }
    }

    private function renumberOrder(): void
    {
        $typeIds = DB::table('skill_types')->pluck('id', 'name');

        foreach ($this->canonical as $typeName => $skills) {
            $typeId = $typeIds[$typeName] ?? null;

            if (! $typeId) {
                continue;
            }

            foreach ($skills as $name => $order) {
                DB::table('skills')
                    ->where('skill_type_id', $typeId)
                    ->where('name', $name)
                    ->update(['order' => $order]);
            }
        }
    }
};

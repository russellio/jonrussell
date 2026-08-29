<?php

namespace App\Http\Controllers;

use App\Queries\PostsQuery;
use App\Queries\ProjectsQuery;
use App\Queries\SkillsQuery;
use App\Queries\TechStackQuery;
use App\Queries\TimelineQuery;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function __invoke(Request $request): Response
    {
        return Inertia::render('Home', [
            'scrollTo' => $request->route('section'),
            'meta' => $this->sectionMeta($request->route('section')),
            'techStack' => Inertia::once(fn () => (new TechStackQuery)->get()),
            'skillTypes' => Inertia::once(fn () => (new SkillsQuery)->get()),
            'positions' => Inertia::once(fn () => (new TimelineQuery)->get()),
            'projects' => Inertia::once(fn () => (new ProjectsQuery)->get()),
            'posts' => Inertia::once(fn () => (new PostsQuery)->get()),
        ]);
    }

    /**
     * @return array{title: string, description: string, canonical: string}
     */
    private function sectionMeta(?string $section): array
    {
        $base = rtrim(config('app.url'), '/');

        return match ($section) {
            'about' => [
                'title' => 'About — Jon Russell',
                'description' => 'Senior software engineer with 13+ years building full-stack web and mobile applications. PHP, Laravel, Vue.js, TypeScript, and more.',
                'canonical' => "{$base}/about",
            ],
            'tech-stack' => [
                'title' => 'Tech Stack — Jon Russell',
                'description' => 'The tools and technologies I use day-to-day: PHP, Laravel, Vue.js, TypeScript, MySQL, Docker, and more.',
                'canonical' => "{$base}/tech-stack",
            ],
            'skills' => [
                'title' => 'Skills — Jon Russell',
                'description' => 'Technical and professional skills spanning backend, frontend, mobile, and infrastructure engineering.',
                'canonical' => "{$base}/skills",
            ],
            'experience' => [
                'title' => 'Experience — Jon Russell',
                'description' => 'My software engineering career history — 13+ years of roles from 2009 to the present.',
                'canonical' => "{$base}/experience",
            ],
            'projects' => [
                'title' => 'Projects — Jon Russell',
                'description' => 'Selected software projects I\'ve built — from side projects to production applications.',
                'canonical' => "{$base}/projects",
            ],
            'posts' => [
                'title' => 'Writing — Jon Russell',
                'description' => 'Articles and notes on software engineering, tools, and things I\'ve built.',
                'canonical' => "{$base}/posts",
            ],
            'contact' => [
                'title' => 'Contact — Jon Russell',
                'description' => 'Get in touch — open for freelance and contract software engineering work.',
                'canonical' => "{$base}/contact",
            ],
            default => [
                'title' => 'Jon Russell — Full Stack Software Engineer',
                'description' => 'Senior full-stack software engineer with 13+ years of experience. Specializing in PHP, Laravel, Vue.js, and TypeScript. Available for freelance and contract work.',
                'canonical' => $base,
            ],
        };
    }
}

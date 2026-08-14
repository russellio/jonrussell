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
            'techStack' => Inertia::once(fn () => (new TechStackQuery)->get()),
            'skillTypes' => Inertia::once(fn () => (new SkillsQuery)->get()),
            'positions' => Inertia::once(fn () => (new TimelineQuery)->get()),
            'projects' => Inertia::once(fn () => (new ProjectsQuery)->get()),
            'posts' => Inertia::once(fn () => (new PostsQuery)->get()),
        ]);
    }
}

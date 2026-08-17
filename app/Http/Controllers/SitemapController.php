<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function __invoke(): Response
    {
        $staticRoutes = ['home', 'about', 'tech-stack', 'skills', 'experience', 'projects', 'posts', 'contact'];

        $posts = Post::published()
            ->whereNotNull('body')
            ->where('body', '!=', '')
            ->orderByDesc('published_at')
            ->get(['slug', 'updated_at']);

        $xml = view('sitemap', compact('staticRoutes', 'posts'))->render();

        return response($xml)->header('Content-Type', 'application/xml');
    }
}

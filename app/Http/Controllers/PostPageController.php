<?php

namespace App\Http\Controllers;

use App\Queries\PostQuery;
use Illuminate\Http\Response as HttpResponse;
use Inertia\Inertia;
use Inertia\Response;

class PostPageController extends Controller
{
    public function __invoke(string $slug): Response
    {
        $post = (new PostQuery($slug))->get();

        abort_if($post === false, HttpResponse::HTTP_NOT_FOUND);

        return Inertia::render('Post', [
            'post' => $post,
            'canonical' => rtrim(config('app.url'), '/').'/posts/'.$slug,
        ]);
    }
}

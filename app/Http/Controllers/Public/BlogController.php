<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index()
    {
        $blogs   = Blog::published()->latest('published_at')->paginate(9);
        $popular = Blog::published()->orderBy('views', 'desc')->take(5)->get();
        return view('public.blog.index', compact('blogs', 'popular'));
    }

    public function show(string $slug)
    {
        $blog    = Blog::where('slug', $slug)->published()->firstOrFail();
        $blog->increment('views');
        $related = Blog::published()->where('id', '!=', $blog->id)->latest('published_at')->take(3)->get();
        return view('public.blog.show', compact('blog', 'related'));
    }
}

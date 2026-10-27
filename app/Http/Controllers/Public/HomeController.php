<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\Category;
use App\Models\Company;
use App\Models\Blog;
use App\Models\Location;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $featuredJobs = Job::with(['company', 'category', 'location'])
            ->active()->featured()->latest()->take(6)->get();

        $recentJobs = Job::with(['company', 'category', 'location'])
            ->active()->latest()->take(8)->get();

        $categories = Category::active()
            ->withCount(['jobs' => fn($q) => $q->active()])
            ->orderBy('order')->take(12)->get();

        $featuredCompanies = Company::active()->verified()->featured()
            ->withCount(['jobs' => fn($q) => $q->active()])
            ->take(8)->get();

        $recentBlogs = Blog::published()->latest('published_at')->take(3)->get();

        $locations = Location::active()->orderBy('order')->take(10)->get();

        $stats = [
            'jobs'      => Job::active()->count(),
            'companies' => Company::active()->verified()->count(),
            'seekers'   => \App\Models\User::where('role', 'job_seeker')->count(),
            'placed'    => \App\Models\Application::where('status', 'hired')->count(),
        ];

        return view('public.home', compact(
            'featuredJobs', 'recentJobs', 'categories',
            'featuredCompanies', 'recentBlogs', 'locations', 'stats'
        ));
    }

    public function setLocale(string $locale)
    {
        if (!in_array($locale, ['en', 'ur'])) abort(404);
        session(['locale' => $locale]);
        if (auth()->check()) {
            auth()->user()->update(['locale' => $locale]);
        }
        return back();
    }

    public function setTheme(string $theme)
    {
        if (!in_array($theme, ['light', 'dark'])) abort(404);
        session(['theme' => $theme]);
        if (auth()->check()) {
            auth()->user()->update(['theme' => $theme]);
        }
        return response()->json(['success' => true]);
    }
}

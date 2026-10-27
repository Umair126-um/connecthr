<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\Category;
use App\Models\Location;
use App\Models\Skill;
use Illuminate\Http\Request;

class JobController extends Controller
{
    public function index(Request $request)
    {
        $query = Job::with(['company', 'category', 'location', 'skills'])
            ->active();

        // Search
        if ($request->filled('q')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', "%{$request->q}%")
                  ->orWhere('description', 'like', "%{$request->q}%")
                  ->orWhereHas('company', fn($c) => $c->where('name', 'like', "%{$request->q}%"));
            });
        }

        if ($request->filled('category'))    $query->where('category_id', $request->category);
        if ($request->filled('location'))    $query->where('location_id', $request->location);
        if ($request->filled('job_type'))    $query->where('job_type', $request->job_type);
        if ($request->filled('experience'))  $query->where('experience_level', $request->experience);
        if ($request->filled('city'))        $query->where('city', 'like', "%{$request->city}%");
        if ($request->boolean('is_remote'))  $query->where('is_remote', true);
        if ($request->boolean('is_urgent'))  $query->where('is_urgent', true);
        if ($request->boolean('is_featured'))$query->where('is_featured', true);

        if ($request->filled('salary_min'))  $query->where('salary_min', '>=', $request->salary_min);
        if ($request->filled('salary_max'))  $query->where('salary_max', '<=', $request->salary_max);

        if ($request->filled('skill')) {
            $query->whereHas('skills', fn($q) => $q->where('skills.id', $request->skill));
        }

        // Sort
        $sort = $request->get('sort', 'latest');
        match($sort) {
            'oldest'  => $query->oldest(),
            'salary'  => $query->orderBy('salary_max', 'desc'),
            'views'   => $query->orderBy('views', 'desc'),
            default   => $query->latest(),
        };

        $jobs       = $query->paginate(setting('jobs_per_page', 12))->withQueryString();
        $categories = Category::active()->withCount(['jobs' => fn($q) => $q->active()])->orderBy('order')->get();
        $locations  = Location::active()->orderBy('order')->get();
        $skills     = Skill::orderBy('name')->get();

        return view('public.jobs.index', compact('jobs', 'categories', 'locations', 'skills'));
    }

    public function show(string $slug)
    {
        $job = Job::with(['company', 'category', 'location', 'skills', 'user'])
            ->where('slug', $slug)
            ->where('status', 'approved')
            ->firstOrFail();

        $job->incrementViews();

        $similarJobs = Job::with(['company', 'location'])
            ->active()
            ->where('id', '!=', $job->id)
            ->where(fn($q) => $q->where('category_id', $job->category_id)
                                ->orWhere('location_id', $job->location_id))
            ->take(4)->get();

        $hasApplied = auth()->check() && auth()->user()->hasAppliedToJob($job->id);
        $hasSaved   = auth()->check() && auth()->user()->hasSavedJob($job->id);

        // Track page view
        \App\Models\PageView::create([
            'url'        => request()->url(),
            'page_type'  => 'job',
            'job_id'     => $job->id,
            'ip_address' => request()->ip(),
            'user_id'    => auth()->id(),
            'visited_at' => now()->toDateString(),
        ]);

        return view('public.jobs.show', compact('job', 'similarJobs', 'hasApplied', 'hasSaved'));
    }

    public function byCategory(string $slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        $jobs = Job::with(['company', 'location'])
            ->active()->where('category_id', $category->id)
            ->latest()->paginate(12);

        return view('public.jobs.by-category', compact('category', 'jobs'));
    }

    public function search(Request $request)
    {
        return $this->index($request);
    }
}

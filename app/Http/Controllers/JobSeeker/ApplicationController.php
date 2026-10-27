<?php

namespace App\Http\Controllers\JobSeeker;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Job;
use App\Mail\ApplicationReceived;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class ApplicationController extends Controller
{
    public function index()
    {
        $applications = Application::with(['job.company', 'job.location', 'job.category'])
            ->where('user_id', auth()->id())
            ->latest()->paginate(10);

        return view('dashboard.applications.index', compact('applications'));
    }

    public function show(int $id)
    {
        $application = Application::with(['job.company', 'job.skills'])
            ->where('user_id', auth()->id())
            ->findOrFail($id);

        return view('dashboard.applications.show', compact('application'));
    }

    public function apply(Request $request, int $jobId)
    {
        $job  = Job::active()->findOrFail($jobId);
        $user = auth()->user();

        // Check already applied
        if ($user->hasAppliedToJob($jobId)) {
            return back()->with('error', 'You have already applied for this job.');
        }

        $request->validate([
            'cover_letter' => 'nullable|string|max:2000',
            'cv'           => 'nullable|mimes:pdf,doc,docx|max:5120',
        ]);

        $cvPath = null;
        if ($request->hasFile('cv')) {
            $cvPath = $request->file('cv')->store('cvs', 'public');
        } elseif ($user->profile?->cv_file) {
            $cvPath = $user->profile->cv_file; // use profile CV
        }

        $application = Application::create([
            'job_id'       => $job->id,
            'user_id'      => $user->id,
            'company_id'   => $job->company_id,
            'cv_file'      => $cvPath,
            'cover_letter' => $request->cover_letter,
            'status'       => 'applied',
        ]);

        // Email to employer
        try {
            Mail::to($job->user->email)->send(new ApplicationReceived($application));
        } catch (\Exception $e) {
            // Don't fail if email fails
        }

        return back()->with('success', 'Application submitted successfully!');
    }

    public function withdraw(int $id)
    {
        $app = Application::where('user_id', auth()->id())
            ->where('status', 'applied')
            ->findOrFail($id);

        $app->delete();
        return back()->with('success', 'Application withdrawn.');
    }
}


class SavedJobController extends Controller
{
    public function index()
    {
        $savedJobs = auth()->user()->savedJobs()
            ->with(['company', 'location', 'category'])
            ->latest('saved_jobs.created_at')
            ->paginate(12);

        return view('dashboard.saved', compact('savedJobs'));
    }

    public function toggle(int $jobId)
    {
        $user = auth()->user();
        $job  = Job::findOrFail($jobId);

        if ($user->hasSavedJob($jobId)) {
            $user->savedJobs()->detach($jobId);
            $saved = false;
        } else {
            $user->savedJobs()->attach($jobId);
            $saved = true;
        }

        if (request()->ajax()) {
            return response()->json(['saved' => $saved]);
        }

        return back()->with('success', $saved ? 'Job saved!' : 'Job removed from saved.');
    }
}


class AlertController extends Controller
{
    public function index()
    {
        $alerts = \App\Models\JobAlert::where('user_id', auth()->id())->with(['category', 'location'])->get();
        $categories = \App\Models\Category::active()->get();
        $locations  = \App\Models\Location::active()->get();
        return view('dashboard.alerts', compact('alerts', 'categories', 'locations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'keyword'     => 'nullable|string|max:100',
            'category_id' => 'nullable|exists:categories,id',
            'location_id' => 'nullable|exists:locations,id',
            'job_type'    => 'nullable|string',
            'frequency'   => 'required|in:daily,weekly,instant',
        ]);

        \App\Models\JobAlert::create(array_merge($request->all(), [
            'user_id' => auth()->id(),
            'email'   => auth()->user()->email,
        ]));

        return back()->with('success', 'Job alert created.');
    }

    public function subscribe(Request $request)
    {
        $request->validate(['email' => 'required|email', 'keyword' => 'nullable|string|max:100']);
        \App\Models\JobAlert::firstOrCreate(
            ['email' => $request->email, 'keyword' => $request->keyword],
            ['frequency' => 'weekly', 'is_active' => true]
        );
        return back()->with('success', 'You are now subscribed to job alerts.');
    }

    public function unsubscribe(Request $request)
    {
        \App\Models\JobAlert::where('email', $request->email)->update(['is_active' => false]);
        return back()->with('success', 'You have been unsubscribed from job alerts.');
    }

    public function destroy(int $id)
    {
        \App\Models\JobAlert::where('user_id', auth()->id())->findOrFail($id)->delete();
        return back()->with('success', 'Alert deleted.');
    }
}
